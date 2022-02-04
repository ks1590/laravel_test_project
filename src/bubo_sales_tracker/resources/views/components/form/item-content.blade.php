<x-form.common.title :formTitle="$formTitle"></x-form.common.title>
<x-form.common.error-message></x-form.common.error-message>
<div class="px-4 py-5 bg-white space-y-6 sm:p-6">
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">カテゴリ名</label>
        <select name="category_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="">-</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $currentCategory == $category->name ? 'selected' : '-' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <x-form.common.input-section
        labelTitle="商品名"
        inputName="display_name"
        type="text"
        :currentValue="$displayName">
    </x-form.common.input-section>
    <x-form.common.input-section
        labelTitle="SKU"
        inputName="sku"
        type="text"
        :currentValue="$sku">
    </x-form.common.input-section>
    <x-form.common.input-section
        labelTitle="単価"
        inputName="price"
        type="number"
        :currentValue="$price">
    </x-form.common.input-section>
    <div class="block">
        @foreach($shops as $shop)
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="w-5 h-5 text-green-600 border-1 border-gray-400 rounded-md focus:ring-0" name="shop_ids[]" value={{ $shop->id }} {{ $shop->items->find($sku) ? "checked" : ""}}/>
                    <p class="ml-2">{{ $shop->shop_name }}</p>
                </label>
            </div>
        @endforeach
    </div>
</div>
<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
    <x-form.common.submit-button :submitTitle="$submitTitle"></x-form.common.submit-button>
</div>
