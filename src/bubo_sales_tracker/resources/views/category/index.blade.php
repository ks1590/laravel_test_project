<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="カテゴリ一覧"
            buttonTitle="新規カテゴリ作成"
            :route="route('category.create')"
        ></x-header>
    </x-slot>
    <x-session-message></x-session-message>
    <div class="flex center w-screen">
        <x-table.category-table :categories="$categories"></x-table.category-table>
    </div>
</x-app-layout>

