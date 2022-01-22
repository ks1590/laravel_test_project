<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="店舗一覧"
            buttonTitle="新規店舗作成"
            :route="route('shop.create')"
        ></x-header>
    </x-slot>
    <x-session-message></x-session-message>
    <div class="flex center w-screen">
        <x-table.shop-table :shops="$shops"></x-table.shop-table>
    </div>
</x-app-layout>
