<div class="m-8 w-full">
    <table class="w-full">
    <thead class="bg-gray-50">
        <tr>
            <x-table.common.header title="ID"></x-table.common.header>
            <x-table.common.header title="ユーザー名"></x-table.common.header>
            <x-table.common.header title="店舗名"></x-table.common.header>
            <x-table.common.header title="管理者権限"></x-table.common.header>
            <x-table.common.header title=""></x-table.common.header>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr class="bg-white lg:hover:bg-gray-100">
                <x-table.common.body :value="$user->id" hiddenTitle="ID"></x-table.common.body>
                <x-table.common.body :value="$user->username" hiddenTitle="ユーザー名"></x-table.common.body>
                @if(isset($user->shop->shop_name))
                    <x-table.common.body :value="$user->shop->shop_name"></x-table.common.body>
                @else
                    <x-table.common.body :value="'-'"></x-table.common.body>
                @endif
                @if($user->is_admin)
                    <x-table.common.body :value="'あり'"></x-table.common.body>
                @else
                    <x-table.common.body :value="'なし'"></x-table.common.body>
                @endif
                <td class="py-5 border-b border-grey-light flex justify-center">
                    <div class="mr-3">
                        <x-edit-button
                            :routeName="route('user.edit',$user)"
                            methodName="GET"
                            buttonTitle="編集">
                        </x-edit-button>
                    </div>
                    @if($user->id === Auth::id())
                    @else
                        <div class="ml-3">
                            <x-delete-button
                                :routeName="route('user.destroy', $user)"
                                methodName="POST"
                                buttonTitle="削除">
                            </x-delete-button>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
