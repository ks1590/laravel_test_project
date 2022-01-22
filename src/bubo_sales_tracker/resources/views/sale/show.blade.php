<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="売上詳細"
            buttonTitle="売上一覧"
            :route="route('sale.index')"
        ></x-header>
    </x-slot>

    <div class="flex justify-center w-screen">
        <div class="w-full">
            <div class="grid gap-6 mb-8 py-6 px-4 md:grid-cols-4">
                <div class="min-w-0 md:col-span-1">
                    <div class="shadow-md p-5 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <div class="relative flex flex-row justify-between items-center m-3">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">日付</span>
                            <div class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 p-2">{{ date('Y/m/d', strtotime($sale->date)) }}</div>
                        </div>
                        @if(Auth::user()->is_admin === 1)
                            <div class="relative flex flex-row justify-between items-center mx-3 mt-7">
                                <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">店舗</span>
                                <div class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 p-2">{{ $sale->shop->shop_name }}</div>
                            </div>
                        @endif
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">取引数</span>
                            <div class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 p-2">{{ $sale->transaction_count }}</div>
                        </div>
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7 hidden lg:block">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">点数合計</span>
                            <div class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 p-2">{{ $sale->totalQuantity() }}</div>
                        </div>
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7 hidden lg:block">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">金額合計</span>
                            <div class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 p-2">{{ $sale->totalAmount() }}</div>
                        </div>
                    </div>
                </div>

                <div class="min-w-0 md:col-span-3">
                    <div class="shadow-md p-5 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="p-5 font-bold text-sm uppercase text-gray-500 border-gray-300 hidden lg:table-cell">
                                    商品 (SKU)
                                </th>
                                <th class="p-5 font-bold text-sm uppercase text-gray-500 border-gray-300 hidden lg:table-cell">
                                    単価
                                </th>
                                <th class="p-5 font-bold text-sm uppercase text-gray-500 border-gray-300 hidden lg:table-cell">
                                    点数
                                </th>
                                <th class="p-5 font-bold text-sm uppercase text-gray-500 border-gray-300 hidden lg:table-cell">
                                    金額
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sale->saleDetails as $value)
                                <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">SKU</span>
                                        <div>
                                            <label for="sku" class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="sku" name="sku" type="text" value="{{ \App\Models\Item::where('sku',$value->sku)->exists() ? \App\Models\Item::where('sku',$value->sku)->first()->display_name . ' (' . $value->sku . ')' : '-' }}" readonly class="bg-gray-200 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">price</span>
                                        <div>
                                            <label for="price"
                                                   class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="price" name="price" type="number"
                                                       value="{{ $value->item->price }}" readonly
                                                       class="bg-gray-200 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">quantity</span>
                                        <div>
                                            <label for="quantity"
                                                   class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="quantity" name="quantity" type="number"
                                                       value="{{ $value->quantity }}" readonly
                                                       class="bg-gray-200 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">amount</span>
                                        <div>
                                            <label for="amount" class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="amount" name="amount" type="number"
                                                       value="{{ $value->item->price * $value->quantity }}" readonly
                                                       class="bg-gray-200 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
