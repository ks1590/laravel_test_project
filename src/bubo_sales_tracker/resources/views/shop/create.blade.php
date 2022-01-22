<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="新規店舗作成"
            buttonTitle="一覧に戻る"
            :route="route('shop.index')">
        </x-header>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col justify-center">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <form id="shop-create" method="POST" action="{{route('shop.store')}}" class="w-full">
                                @csrf
                                <x-form.shop-content
                                    formTitle="店舗詳細"
                                    :shopName="''"
                                    :sumaregiTenpoId="''"
                                    submitTitle="作成">
                                </x-form.shop-content>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
