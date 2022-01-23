<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $admin_flag = auth()->user()->is_admin;
            if ($admin_flag) {
                $shops = Shop::all()->toArray();
            } else {
                $current_shop = auth()->user()->shop;
                $shops = Shop::find($current_shop)->toArray();
            }

//            $smaregi = new SmaregiController;
//            $store_stock_list = $smaregi->fetchStockByStore($shops);
//            $product_list = $smaregi->fetchProductsByCategory();
//            $stocks = $smaregi->mergeStoreStockAndProductCode($store_stock_list, $product_list);
            $stocks = 0;

//            $items = Item::orderBy('category_id', 'asc')->orderBy('display_name', 'asc')->get();
            $items = Shop::first()->items;

            return view('item.index', ['items' => $items, 'shops' => $shops, 'stocks' => $stocks]);
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $shops = Shop::all();
        return view('item.create', ['categories' => $categories, 'shops' => $shops]);
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
            'display_name' => ['string', 'unique:items', 'required'],
            'sku' => ['string', 'unique:items', 'required'],
            'price' => ['integer', 'required'],
        ]);

        try {
            $item = Item::create([
                'category_id' => $request->category_id,
                'display_name' => $request->display_name,
                'sku' => $request->sku,
                'price' => $request->price,
            ]);

//            item_shopテーブルに保存
            $item->shops()->sync($request->get('shop_ids', []));

            return redirect()->to(route('item.index'))->with('success', $request->display_name . 'を新規作成しました。');

        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $shops = Shop::all();

        return view('item.edit', ["item" => $item, 'categories' => $categories, 'shops' => $shops]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'display_name' => ['string', 'required', Rule::unique('items')->ignore($item->id)],
            'sku' => ['string', 'required', Rule::unique('items')->ignore($item->id)],
            'price' => ['integer', 'required'],
        ]);

        try {
            $item->category_id = $request->get('category_id');
            $item->display_name = $request->get('display_name');
            $item->sku = $request->get('sku');
            $item->price = $request->get('price');
            $item->save();

//            item_shopテーブル更新
            $item->shops()->sync($request->get('shop_ids', []));

            return redirect()->to(route('item.index'))->with('info', $item->display_name . 'の登録情報を更新しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();

            return redirect()->back()->with('warning', $item->display_name . 'を削除しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
