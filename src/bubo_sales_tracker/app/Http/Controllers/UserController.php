<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::all();
        return view('user.create', ['shops' => $shops]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['string', 'unique:users', 'required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            if ($request->input('is_admin') === 'on')
            {
                $shop_id = null;
                $is_admin = true;
            }else
            {
                $shop_id = $request->input('shop_id');
                $is_admin = false;
            }

            User::create([
                'username' => $request->username,
                'shop_id' => $shop_id,
                'is_admin' => $is_admin,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->to(route('user.index'))->with('success', $request->username . 'を新規作成しました。');

        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User  $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        $shops = Shop::all();
        $current_shop = isset($user->shop->shop_name) ? $user->shop->shop_name : '';
        return view("user.edit", ["user" => $user, "shops" => $shops, "current_shop" => $current_shop]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['string', 'required', Rule::unique('users')->ignore($user->id)],
            'shop_id' => ['integer', Rule::unique('users')->ignore($user->id)],
        ]);

        try {
            if ($request->input('is_admin') === 'on')
            {
                $shop_id = null;
                $is_admin = true;
            }else
            {
                $shop_id = $request->get('shop_id');
                $is_admin = false;
            }

            $user->username = $request->get('username');
            $user->shop_id = $shop_id;
            $user->is_admin = $is_admin;
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->to(route('user.index'))->with('info', $request->username . 'の登録情報を更新しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->back()->with('warning',  $user->username.'の店舗情報を削除しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
