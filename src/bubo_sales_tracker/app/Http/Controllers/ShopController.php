<?php

namespace App\Http\Controllers;

use App\Facades\Chatwork;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::all();

        return view('shop.index', ['shops' => $shops]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('shop.create');
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
            'shop_name' => ['string', 'unique:shops', 'required'],
            'sumaregi_tenpo_id' => ['integer', 'unique:shops', 'required'],
        ]);

        try {
            Shop::create([
                'shop_name' => $request->input('shop_name'),
                'sumaregi_tenpo_id' => $request->input('sumaregi_tenpo_id'),
            ]);

            return redirect()->to(route('shop.index'))->with('success', $request->shop_name . 'を新規作成しました。');

        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        // Not in use
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Shop $shop)
    {
        return view("shop.edit", ["shop" => $shop]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Shop $shop)
    {
        $request->validate([
            'shop_name' => ['string','required',Rule::unique('shops')->ignore($shop->id)],
            'sumaregi_tenpo_id' => ['integer','required',Rule::unique('shops')->ignore($shop->id)],
        ]);

        try {
            $shop->shop_name = $request->get('shop_name');
            $shop->sumaregi_tenpo_id = $request->get('sumaregi_tenpo_id');
            $shop->save();

            return redirect()->to(route('shop.index'))->with('info', $request->shop_name . 'の登録情報を更新しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shop $shop)
    {
        try {
            $shop->delete();

            return redirect()->back()->with('warning',  $shop->shop_name.'の店舗情報を削除しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
