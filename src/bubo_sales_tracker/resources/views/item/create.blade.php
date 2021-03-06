<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="商品新規作成"
            buttonTitle="一覧に戻る"
            :route="route('item.index')">
        </x-header>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col justify-center">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <form id="item-create" method="POST" action="{{route('item.store')}}" class="w-full">
                                @csrf
                                <x-form.item-content
                                    formTitle="商品詳細"
                                    :displayName="''"
                                    :sku="''"
                                    :price="''"
                                    :currentCategory="''"
                                    submitTitle="作成"
                                    :categories="$categories"
                                    :shops="$shops">
                                </x-form.item-content>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
