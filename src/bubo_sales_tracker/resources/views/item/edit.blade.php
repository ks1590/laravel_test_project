<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="商品編集"
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
                            <form id="item-update" method="POST" action="{{route('item.update',$item)}}" class="w-full">
                                @csrf
                                @method('PUT')
                                <x-form.item-content
                                    formTitle="商品詳細"
                                    :displayName="$item->display_name"
                                    :sku="$item->sku"
                                    :price="$item->price"
                                    :currentCategory="@isset($item->category->name) ? $item->category->name : '-'"
                                    submitTitle="更新"
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
