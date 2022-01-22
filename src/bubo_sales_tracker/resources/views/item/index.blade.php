<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="商品一覧"
            buttonTitle="商品新規作成"
            :route="route('item.create')"
        ></x-header>
    </x-slot>
    <x-session-message></x-session-message>
    <div class="flex center w-screen">
        <x-table.item-table
            :items="$items"
            :shops="$shops"
            :stocks="$stocks">
        </x-table.item-table>
    </div>
</x-app-layout>

