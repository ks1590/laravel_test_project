<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="ユーザー一覧"
            buttonTitle="新規ユーザー作成"
            :route="route('user.create')"
        ></x-header>
    </x-slot>
    <x-session-message></x-session-message>
    <div class="flex center w-screen">
        <x-table.user-table :users="$users"></x-table.user-table>
    </div>
</x-app-layout>
