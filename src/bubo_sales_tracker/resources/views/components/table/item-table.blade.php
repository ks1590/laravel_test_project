<div class="m-8 w-full">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <x-table.common.header title="カテゴリ名"></x-table.common.header>
                <x-table.common.header title="商品名"></x-table.common.header>
                <x-table.common.header title="SKU"></x-table.common.header>
                <th class="p-3 text-right font-bold uppercase text-gray-500 border-gray-300">単価</th>
                    @foreach($shops as $shop)
                        <x-table.common.store-stock-header :title="$shop['shop_name']"></x-table.common.store-stock-header>
                    @endforeach
                    @if(auth()->user()->is_admin === 1)
                        <x-table.common.header title=""></x-table.common.header>
                    @endif
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr class="bg-white lg:hover:bg-gray-100">
                    <x-table.common.body :value="@isset($item->category->name) ? $item->category->name : '-' " hiddenTitle="カテゴリ名"></x-table.common.body>
                    <x-table.common.body :value="$item->display_name"></x-table.common.body>
                    <x-table.common.body :value="$item->sku"></x-table.common.body>
                    <td class="p-3 text-gray-800 text-right border-b">
                        {{ number_format($item->price) }}
                    </td>
                    @foreach($shops as $shop)
                        <x-table.common.store-stock-body
                            :shop="$shop"
                            :itemSku="$item->sku"
                            :stocks="$stocks"
                            :hiddenTitle="$shop['shop_name']">
                        </x-table.common.store-stock-body>
                    @endforeach
                    @if(auth()->user()->is_admin === 1)
                        <td class="py-5 border-b border-grey-light flex justify-center">
                            <div class="mr-3">
                                <x-edit-button
                                    :routeName="route('item.edit',$item)"
                                    methodName="GET"
                                    buttonTitle="編集">
                                </x-edit-button>
                            </div>
                            <div class="ml-3">
                                <x-delete-button
                                    :routeName="route('item.destroy', $item)"
                                    methodName="POST"
                                    buttonTitle="削除">
                                </x-delete-button>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
