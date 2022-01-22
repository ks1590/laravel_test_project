<?php

namespace App\Http\Controllers;

use App\Facades\Chatwork;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Shop;
use Carbon\Traits\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SaleController extends Controller
{
    /**
     * Display Resource Routing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!empty($request['start-date']) && !empty($request['end-date'])) {
            $start_date = date('Y-m-d', strtotime($request['start-date']));
            $end_date = date('Y-m-d', strtotime($request['end-date']));
            if ($start_date > $end_date) {
                return redirect()->to(route('sale.index'))->with('danger', '選択した日付でエラーが発生しました。日程をご確認の上、再度お試しください。');
            }
        } else {
            $start_date = date('Y-m-d', strtotime('-1 week'));
            $end_date = date('Y-m-d', strtotime('now'));
        }

        if (!Auth::user()->is_admin) {
            $shop_id = Auth::user()->shop_id;
        } else {
            // Todo: Multishop ID filtering?
            if (!empty($request['shop_id'])) {
                if (Shop::where('id',$request['shop_id'])->exists()) {
                    $shop_id = $request['shop_id'];
                }
            }
        }

        if (!empty($shop_id)) {
            $sales = Sale::whereBetween('date',[$start_date, $end_date])->where('shop_id',$shop_id)->orderByDesc('date')->paginate(10);
        } else {
            $sales = Sale::whereBetween('date',[$start_date, $end_date])->orderByDesc('date')->paginate(10);
        }

        $netAmount = $sales->map(function($sale) { return $sale->totalAmount(); })->sum();

        return view('sale.index', ['sales' => $sales, 'shops' => Shop::all(), 'start_date' => $start_date, 'end_date' => $end_date, 'netAmount' => $netAmount]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('sale.create', ['shops' => Shop::all(), 'items' => Item::orderBy('category_id', 'asc')->orderBy('display_name', 'asc')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required','date'],
            'shop_id' => ['required','int'],
            'transaction_count' => ['required','int'],
            'sku' => ['required','array'],
            'quantity' => ['required','array'],
            'amount' => ['required','array']
        ]);

        $request->flash();

        if (Sale::where(['shop_id' => $request->input('shop_id'), 'date' => $request->input('date')])->exists()) {
            return redirect()->back()->with('danger', Shop::find($request->input('shop_id'))->shop_name.'、「日付：'.$request->input('date').'」の売上データが既に存在します。');
        }

        try {
            DB::beginTransaction();
            $sale = new Sale;
            $sale->date = $request->input('date');
            $sale->shop_id = $request->input('shop_id');
            $sale->transaction_count = $request->input('transaction_count');
            $sale->save();

            for ($i = 0; $i < count($request->input('sku')); $i++ ) {
                if (!$request->input('sku')[$i]) {
                    throw new \Exception('SKUが必要です。');
                }
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'sku' => $request->input('sku')[$i],
                    'quantity' => $request->input('quantity')[$i],
                    'amount' => $request->input('amount')[$i],
                ]);
            }

            DB::commit();

            $chatworktitle = 'Bubo Sales Tracker [Store New Sale Created]';
            $chatworkbody = "Bubo Sales Trackerに新しい売上が保存されました。"
                . "\n[shop_name] " . $sale->shop->shop_name
                . "\n[sales_date] " . $sale->date
                . "\n[total_quantity] " . $sale->totalQuantity()
                . "\n[total_amount] " . $sale->totalAmount();
            Chatwork::postToLogRoom($chatworktitle, $chatworkbody);

            return redirect()->to(route('sale.index'))->with('success', $sale->shop->shop_name . 'の売上を入力しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Sale $sale
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Sale $sale)
    {
        if (!Auth::user()->is_admin) {
            if ($sale->shop_id !== Auth::user()->shop_id) {
                return redirect()->to(route('sale.index'))->with('warning', 'アクセスできません。');
            }
        }

        return view('sale.show', ['sale' => $sale]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Sale $sale
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Sale $sale)
    {
        if ($sale->is_processing || $sale->is_sumaregi_committed) {
            return redirect()->back()->with('danger', $sale->shop->shop_name.'の'.$sale->date.'のデータはすでに提出済みです。この売上データを削除することはできません。');
        }

        if (!Auth::user()->is_admin) {
            if ($sale->shop_id !== Auth::user()->shop_id) {
                return redirect()->to(route('sale.index'))->with('warning', 'アクセスできません。');
            }
        }

        return view('sale.edit', ['sale' => $sale, 'shops' => Shop::all(), 'items' => Item::orderBy('category_id', 'asc')->orderBy('display_name', 'asc')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Sale $sale)
    {
        if ($sale->is_processing || $sale->is_sumaregi_committed) {
            return redirect()->back()->with('danger', $sale->shop->shop_name.'の'.$sale->date.'のデータはすでに提出済みです。この売上データを更新することはできません。');
        }

        $request->validate([
            'date' => ['required','date'],
            'shop_id' => ['required','int'],
            'transaction_count' => ['required','int'],
            'sku' => ['required','array'],
            'quantity' => ['required','array'],
            'amount' => ['required','array']
        ]);

        if (Sale::where(['shop_id' => $request->input('shop_id'), 'date' => $request->input('date')])->whereNotIn('id',[$sale->id])->exists()) {
            return redirect()->back()->with('danger', Shop::find($request->input('shop_id'))->shop_name.'、「日付：'.$request->input('date').'」の売上データが既に存在します。');
        }

        try {
            DB::beginTransaction();
            $sale->date = $request->input('date');
            $sale->shop_id = $request->input('shop_id');
            $sale->transaction_count = $request->input('transaction_count');
            $sale->save();

            foreach ($sale->saleDetails as $detail) {
                $detail->delete();
            }

            for ($i = 0; $i < count($request->input('sku')); $i++ ) {
                if (!$request->input('sku')[$i]) {
                    throw new \Exception('SKUが必要です。');
                }
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'sku' => $request->input('sku')[$i],
                    'quantity' => $request->input('quantity')[$i],
                    'amount' => $request->input('amount')[$i]
                ]);
            }
            DB::commit();

            $chatworktitle = 'Bubo Sales Tracker [Sale Detail Updated]';
            $chatworkbody = "Bubo Sales Trackerの売上情報が更新されました。"
                . "\n[shop_name] " . $sale->shop->shop_name
                . "\n[sales_date] " . $sale->date
                . "\n[total_quantity] " . array_sum($request->input('quantity'))
                . "\n[total_amount] " . array_sum($request->input('amount'));
            Chatwork::postToLogRoom($chatworktitle, $chatworkbody);

            return redirect()->route('sale.index')->with('info', $sale->shop->shop_name . 'の売上情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Sale $sale)
    {
        if (Auth::user()->is_admin === 0) {
            if ($sale->is_processing || $sale->is_sumaregi_committed) {
                return redirect()->back()->with('danger', $sale->shop->shop_name.'の'.$sale->date.'のデータはすでに提出済みです。この売上データを削除することはできません。');
            }
        }

        try {
            DB::beginTransaction();
            foreach ($sale->saleDetails as $detail) {
                $detail->delete();
            }

            $sale->delete();

            DB::commit();
            return redirect()->back()->with('warning', $sale->shop->shop_name.'の'.$sale->date.'のデータを削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
