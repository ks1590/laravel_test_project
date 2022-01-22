<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('売上') }}
            </h2>
            <div class="flex">
                <a class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 hover:border-blue-600 hover:bg-blue-500 hover:text-white shadow-md mx-2 py-1 px-6 inline-flex items-center" href="{{ 'sale/create' }}">売上入力</a>
                @if(Auth::user()->is_admin === 1)
                    <a href="{{ route('smaregi.updateStockAll') }}" type="button" class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 hover:border-blue-600 hover:bg-blue-500 hover:text-white shadow-md mx-2 py-1 px-6 inline-flex items-center" onclick="return confirm('スマレジの在庫情報を更新します。よろしいですか？');">スマレジ一括反映</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="hidden shadow bg-gray-50 lg:block">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-between items-center">
                <div class="font-semibold text-xl text-gray-800 border-b-2 border-blue-600">期間合計：¥ {{ number_format($netAmount) }}</div>
                <form class="" action="{{ route('sale.index') }}" method="GET">
                    @if(Auth::user()->is_admin === 1)
                        <div class="inline-flex items-center">
                            <label class="mr-1" for="shop_id">店舗</label>
                            <select id="shop_id" name="shop_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">全て</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ request()->get('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->shop_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="inline-flex items-center ml-4">
                        <label class="mr-1">日付</label>
                        <input id="start-date" type="date" name="start-date" value="{{ request()->get('start-date') ? request()->get('start-date') : $start_date }}">
                        <span class="mx-1">〜</span>
                        <input id="end-date" type="date" name="end-date" value="{{ request()->get('end-date') ? request()->get('end-date') : $end_date }}">
                    </div>
                    <button class="bg-white text-gray-800 font-bold rounded border-b-2 border-indigo-500 hover:border-indigo-600 hover:bg-indigo-500 hover:text-white shadow-md mx-2 py-1 px-6 inline-flex items-center" type="submit">検索</button>
                </form>
            </div>
        </div>
    </div>

    <x-session-message></x-session-message>

    <div class="flex justify-center w-screen">
        <table class="border-collapse w-screen m-8 shadow-lg">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-3 font-bold uppercase text-gray-500 border-gray-300 hidden lg:table-cell">日付</th>
                @if(Auth::user()->is_admin === 1)
                    <th class="p-3 font-bold uppercase text-gray-500 border-gray-300 hidden lg:table-cell">店名</th>
                @endif
                <th class="p-3 font-bold uppercase text-gray-500 border-gray-300 hidden lg:table-cell">取引数</th>
                <th class="p-3 font-bold uppercase text-gray-500 border-gray-300 hidden lg:table-cell">点数合計</th>
                <th class="p-3 font-bold uppercase text-right text-gray-500 border-gray-300 hidden lg:table-cell">金額合計</th>
                <th class="p-3 font-bold uppercase text-gray-500 border-gray-300 hidden lg:table-cell"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($sales as $sale)
                <tr class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-5 lg:mb-0">
                    <td class="w-full lg:w-auto p-4 text-gray-800 text-left border-b block lg:table-cell relative lg:static lg:text-center">
                        <div class="hidden lg:block">
                            <a href="{{ route('sale.show',$sale) }}"
                               class="no-underline hover:underline inline-flex items-center text-blue-500">
                                {{ date('Y/m/d', strtotime($sale->date)) }}
                            </a>
                        </div>
                        <div class="flex justify-between items-center lg:hidden">
                            <a href="{{ route('sale.show',$sale) }}"
                               class="no-underline hover:underline inline-flex items-center text-blue-500 font-bold">
                                {{ date('Y/m/d', strtotime($sale->date)) }}
                            </a>
                            <div x-data="{ dropdownOpen: false }">
                                <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block bg-white p-1 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="dropdownOpen" class="absolute -right-3 mt-2 w-28 bg-gray-50 rounded-md overflow-hidden shadow-xl z-20 text-center">
                                    <div class="border-gray-400 border-b-2">
                                        @if($sale->is_processing === 0 && $sale->is_sumaregi_committed === 0)
                                            <a href="{{ route('sale.edit',$sale) }}" type="button"
                                               class="no-underline hover:underline inline-flex items-center text-blue-500 px-5 py-3">
                                                <span class="font-semibold">編集する</span>
                                            </a>
                                        @else
                                            <div class="font-semibold p-3">編集不可</div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        @if(Auth::user()->is_admin === 1 || $sale->is_processing === 0 && $sale->is_sumaregi_committed === 0)
                                            <form id="delete" action="{{ route('sale.destroy',$sale) }}" method="POST" class="h-6">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="no-underline hover:underline inline-flex items-center text-blue-500" onclick="return confirm('本当に削除してもよろしいですか？');">
                                                    <span class="font-semibold">削除する</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="font-semibold">削除不可</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    @if(Auth::user()->is_admin === 1)
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-right lg:text-center border-b block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 h-full px-3 py-1 text-md font-bold uppercase flex items-center">店舗名</span>
                        {{ $sale->shop->shop_name }}
                    </td>
                    @endif
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-right lg:text-center border-b block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 h-full px-3 py-1 text-md font-bold flex items-center">取引数</span>
                        {{ number_format($sale->transaction_count) }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-right lg:text-center border-b block lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 h-full px-3 py-1 text-md font-bold flex items-center">点数合計</span>
                        {{ $sale->totalQuantity() }}
                    </td>
                    <td class="w-full lg:w-auto p-3 text-gray-800 text-right lg:text-center border-b block lg:text-right lg:table-cell relative lg:static">
                        <span class="lg:hidden absolute top-0 left-0 h-full px-3 py-1 text-md font-bold uppercase flex items-center">金額合計</span>
                        ¥ {{ number_format($sale->totalAmount()) }}
                    </td>
                    <td class="w-full lg:w-auto pt-3 pb-3 text-gray-800 text-center border-b block lg:table-cell relative lg:static">
                        <div class="mx-5">
                            @if($sale->is_processing === 0 && $sale->is_sumaregi_committed === 0)
                                <a href="{{ route('smaregi.updateStock',$sale) }}" type="button"
                                   class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-md py-2 px-3 inline-flex items-center" onclick="return confirm('スマレジの在庫情報を更新します。よろしいですか？');">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="ml-2">スマレジに反映する</span>
                                </a>
                            @else
                                <div class="font-bold py-1 px-2 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="ml-2">スマレジ反映済み</span>
                                </div>
                            @endif
                            <div x-data="{ dropdownOpen: false }" class="hidden lg:block lg:float-right lg:pt-2">
                                <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block bg-transparent focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="dropdownOpen" class="absolute right-1 mt-2 w-28 bg-gray-50 rounded-md overflow-hidden shadow-xl z-20 text-center">
                                    <div class="border-gray-400 border-b-2 p-3">
                                        @if($sale->is_processing === 0 && $sale->is_sumaregi_committed === 0)
                                            <a href="{{ route('sale.edit',$sale) }}"
                                               class="no-underline hover:underline inline-flex items-center text-blue-500">
                                                <span class="font-semibold font-semibold">編集する</span>
                                            </a>
                                        @else
                                            <div class="font-semibold">編集不可</div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        @if(Auth::user()->is_admin === 1 || $sale->is_processing === 0 && $sale->is_sumaregi_committed === 0)
                                            <form id="delete" action="{{ route('sale.destroy',$sale) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="no-underline hover:underline inline-flex items-center text-blue-500" onclick="return confirm('本当に削除してもよろしいですか？');">
                                                    <span class="font-semibold">削除する</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="font-semibold">削除不可</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-center pb-8">{{ $sales->appends($_GET)->links() }}</div>
</x-app-layout>
