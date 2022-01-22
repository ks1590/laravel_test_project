<x-form.common.title :formTitle="$formTitle"></x-form.common.title>
<x-form.common.error-message></x-form.common.error-message>
<div class="px-4 py-5 bg-white space-y-6 sm:p-6">
    <x-form.common.input-section
        labelTitle="ユーザー名"
        inputName="username"
        type="text"
        :currentValue="$userName">
    </x-form.common.input-section>
    <div>
        <label for="shop_id" class="block text-sm font-medium text-gray-700">店舗名</label>
        <select name="shop_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">-</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" {{ $currentShop == $shop->shop_name ? 'selected' : '-' }}>{{ $shop->shop_name }}</option>
            @endforeach
        </select>
    </div>
    @if (request()->is('*user/create'))
        <x-form.common.password-section></x-form.common.password-section>
    @endif
    @if (request()->is('*edit*') && $userName === Auth::user()->username)
    @else
        <x-form.common.password-section></x-form.common.password-section>
        <div>
            <input type="checkbox" name="is_admin" id="is_admin" {{ $isAdmin === 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin">管理者権限</label>
        </div>
    @endif
</div>
<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
    <x-form.common.submit-button :submitTitle="$submitTitle"></x-form.common.submit-button>
</div>
