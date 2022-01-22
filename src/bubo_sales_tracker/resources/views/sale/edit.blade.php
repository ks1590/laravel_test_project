<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="売上編集"
            buttonTitle="売上一覧"
            :route="route('sale.index')"
        ></x-header>
    </x-slot>

    <x-session-message></x-session-message>

    <div class="flex justify-center w-screen">
        <form method="POST" action="{{route('sale.update',$sale)}}" class="w-full">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-8 py-6 px-4 md:grid-cols-4">
                <div class="min-w-0 md:col-span-1">
                    <div class="shadow-md px-5 py-3 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <div class="relative flex flex-row justify-between items-center m-3">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">日付</span>
                            <input id="date" name="date" type="date" value="{{$sale->date}}" required
                                   class="w-full focus:border-blue-500 w-auto rounded-md sm:text-sm border-gray-300 text-right">
                        </div>
                        @if(Auth::user()->is_admin === 1)
                            <div class="relative flex flex-row justify-between items-center mx-3 mt-7">
                                <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">店舗</span>
                                <select name="shop_id" required
                                        class="w-full focus:ring-blue-500 focus:border-blue-500 w-auto rounded-md sm:text-sm border-gray-300 text-right">
                                    @foreach($shops as $shop)
                                        <option
                                            value="{{ $shop->id }}" {{ $sale->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input id="shop_id" name="shop_id" type="hidden" value="{{$sale->shop_id}}">
                        @endif
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">取引数</span>
                            <input id="transaction_count" name="transaction_count" type="number" value="{{ $sale->transaction_count }}" required
                                   class="w-full rounded-md sm:text-sm border-gray-300 text-right">
                        </div>
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7 hidden lg:block">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">点数合計</span>
                            <input id="total-quantity" type="number" value="{{ $sale->totalQuantity() }}" readonly
                                   class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 text-right">
                        </div>
                        <div class="relative flex flex-row justify-between items-center mx-3 mt-7 hidden lg:block">
                            <span class="absolute -top-4 -left-4 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">金額合計</span>
                            <input id="total-amount" type="number" value="{{ $sale->totalAmount() }}" readonly
                                   class="w-full bg-gray-200 w-auto rounded-md sm:text-sm border-gray-300 text-right">
                        </div>
                    </div>
                </div>

                <div class="min-w-0 md:col-span-3">
                    <div class="shadow-md p-5 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-24">
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
                                    小計
                                </th>
                                <th class="p-5 font-bold text-sm uppercase text-gray-500 border-gray-300 hidden lg:table-cell"></th>
                            </tr>
                            </thead>
                            <tbody id="detailTable">
                            @foreach($sale->saleDetails as $value)
                                <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">SKU</span>
                                        <div>
                                            <label for="sku" class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <select id="sku" name="sku[]" required
                                                        class="sku focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                                    <option value="">-</option>
                                                    @foreach($items as $item)
                                                        <option
                                                            value="{{ $item->sku }}" {{ $value->sku == $item->sku ? 'selected' : '' }}>{{ $item->display_name . ' (' . $item->sku . ')' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                        <span
                                            class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">単価</span>
                                        <div>
                                            <label for="price"
                                                   class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="price" name="price[]" type="number" readonly
                                                       class="price flex-1 block w-full bg-gray-200 rounded-md sm:text-sm border-gray-300 text-right">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span
                                        class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">数量</span>
                                        <div>
                                            <label for="quantity"
                                                   class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="quantity" name="quantity[]" type="number" min="1"
                                                       value="{{ $value->quantity }}"
                                                       required
                                                       class="quantity focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300 text-right">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                        <span
                                            class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">小計</span>
                                        <div>
                                            <label for="amount" class="block text-sm font-medium text-gray-700"></label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input id="amount" name="amount[]" type="number"
                                                       value="{{ $value->amount }}"
                                                       readonly
                                                       class="amount flex-1 block w-full bg-gray-200 rounded-md sm:text-sm border-gray-300 text-right">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                        <div>
                                            <div style="display: none"
                                                 class="del-row bg-white text-gray-800 font-bold rounded border-b-2 border-red-500 hover:border-red-600 hover:bg-red-500 hover:text-white shadow-md py-1 px-6 inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="w-full lg:w-auto p-1 text-gray-800 text-center block relative mt-2">
                            <div id="add-row"
                                 class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 lg:hover:border-blue-600 lg:hover:bg-blue-500 lg:hover:text-white shadow-md py-1 px-6 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="ml-2">入力欄を追加</span>
                            </div>
                        </div>
                        <div class="p-3 bg-gray-50 text-right hidden lg:block">
                            <button type="submit"
                                    class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 hover:border-blue-600 hover:bg-blue-500 hover:text-white shadow-md py-1 px-6 inline-flex items-center">
                                売上を更新する
                            </button>
                        </div>
                    </div>
                    <div class="min-w-0 md:col-span-1 mt-5">
                        <footer class="bg-white shadow-md font-bold py-2 px-4 border-t-2 border-blue-800 hover:border-blue inset-x-0 bottom-0 p-3 fixed lg:hidden">
                            <div class="relative flex flex-row justify-end mx-4 mt-1">
                                <span class="w-24 text-right font-bold text-gray-600">点数合計 :</span>
                                <p id="sm-total-quantity" class="w-24 sm:text-sm text-right">{{ $sale->totalQuantity() }}</p>
                            </div>

                            <div class="relative flex flex-row justify-end mx-4 mt-1">
                                <span class="w-24 text-right font-bold text-gray-600">金額合計 :</span>
                                <p id="sm-total-amount" class="w-24 sm:text-sm text-right">{{ number_format($sale->totalAmount()) }}</p>
                            </div>
                            <div class="flex w-full">
                                <button type="submit"
                                        class="bg-gradient-to-r from-blue-400 to-blue-700
                                       hover:from-blue-700 hover:to-blue-800
                                       focus:from-blue-700 focus:to-blue-800
                                       rounded-full w-full font-bold text-lg text-white m-2 py-2">
                                    売上を更新する</button>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type='text/javascript'>
            $(document).ready(function () {
                toggleDeleteButton();
                inputItemPrice(getItemPriceAll());
                sumItemPrice();

                $('#add-row').on('click', function () {
                    let html = `<tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">
                                <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
                                    <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">SKU</span>
                                    <div>
                                        <label for="sku" class="block text-sm font-medium text-gray-700"></label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <select id="sku" name="sku[]" required
                                                    class="sku focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                                                <option value="">-</option>
                                                @foreach($items as $item)
                    <option value="{{ $item->sku }}">{{ $item->display_name . ' (' . $item->sku . ')' }}</option>
                                                @endforeach
                    </select>
                </div>
            </div>
        </td>
        <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">単価</span>
            <div>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input id="price" name="price[]" type="number" readonly
                           class="price flex-1 block w-full bg-gray-200 rounded-md sm:text-sm border-gray-300 text-right">
                </div>
            </div>
        </td>
        <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">quantity</span>
            <div>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input id="quantity" name="quantity[]" type="number" min="1" required class="quantity focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300 text-right">
                </div>
            </div>
        </td>
        <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
            <span class="lg:hidden absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase">amount</span>
            <div>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input id="amount" name="amount[]" type="number" readonly class="amount flex-1 block w-full bg-gray-200 rounded-md sm:text-sm border-gray-300 text-right">
                </div>
            </div>
        </td>
        <td class="w-full lg:w-auto p-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static text-sm">
            <div>
                <div class="del-row bg-white text-gray-800 font-bold rounded border-b-2 border-red-500 hover:border-red-600 hover:bg-red-500 hover:text-white shadow-md py-1 px-6 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </td>
        </tr>`;
                    $('#detailTable').append(html);
                    toggleDeleteButton();
                });

                $('#detailTable').on('click', '.del-row', function () {
                    const parent = $(this).closest('tr');
                    parent.remove();
                    toggleDeleteButton();
                    reevaluateQuantity();
                    reevaluateAmount();
                });

                $('#detailTable').on('input', '.quantity', function () {
                    reevaluateQuantity();
                    sumItemPrice();
                });

                $('#detailTable').on('change', '.sku', function () {
                    inputItemPrice(getItemPriceAll());
                    sumItemPrice();
                });
            });

            function toggleDeleteButton() {
                if ($('#detailTable tr').length > 1) {
                    $('.del-row').show();
                } else {
                    $('.del-row').hide();
                }
            }

            function reevaluateQuantity() {
                let total = 0;
                $('.quantity').each(function () {
                    if ($(this).val()) {
                        total += parseInt($(this).val(), 10);
                    }
                });

                $('#total-quantity').val(total);
                $('#sm-total-quantity').text(total.toLocaleString());
            }

            function reevaluateAmount() {
                let total = 0;
                $('.amount').each(function () {
                    if ($(this).val()) {
                        total += parseInt($(this).val(), 10);
                    }
                });
                $('#total-amount').val(total);
                $('#sm-total-amount').text('¥ ' + total.toLocaleString());
            }

            function getItemPriceAll() {
                const items = @json($items);
                let itemPriceAll = [];

                $('.sku').each(function () {
                    items.forEach(item => {
                        if ($(this).val() === item.sku) {
                            itemPriceAll.push(item.price);
                        }
                    });
                });
                return itemPriceAll;
            }

            function inputItemPrice(itemPriceAll) {
                let i = 0;
                $('.price').each(function () {
                    $(this).val(itemPriceAll[i++]);
                });
            }

            function sumItemPrice() {
                let prices = [];
                $('.price').each(function () {
                    prices.push($(this).val());
                });

                let quantities = [];
                $('.quantity').each(function () {
                    quantities.push($(this).val());
                });

                let itemAmountList = [];
                let subtotal = 0;
                for (let i = 0; i < prices.length; i++) {
                    itemAmountList.push(prices[i] * quantities[i]);
                    subtotal = subtotal + (prices[i] * quantities[i]);
                }

                i = 0;
                $('.amount').each(function () {
                    $(this).val(itemAmountList[i++]);
                });

                $('#total-amount').val(subtotal);
                $('#sm-total-amount').text('¥ ' + subtotal.toLocaleString());
            }
        </script>
    @endpush
</x-app-layout>
