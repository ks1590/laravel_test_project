<table class="border-collapse w-screen m-8">
    <thead class="bg-gray-50">
    <tr>
        <x-table.common.header title="ID"></x-table.common.header>
        <x-table.common.header title="店舗名"></x-table.common.header>
        <x-table.common.header title="スマレジ店舗ID"></x-table.common.header>
        <x-table.common.header title=""></x-table.common.header>
    </tr>
    </thead>
    <tbody>
        @foreach($shops as $shop)
            <tr class="bg-white lg:hover:bg-gray-100">
                <x-table.common.body :value="$shop->id"></x-table.common.body>
                <x-table.common.body :value="$shop->shop_name"></x-table.common.body>
                <x-table.common.body :value="$shop->sumaregi_tenpo_id"></x-table.common.body>
                <td class="py-5 border-b border-grey-light flex justify-center">
                    <div class="mr-3">
                        <x-edit-button
                            :routeName="route('shop.edit',$shop)"
                            methodName="GET"
                            buttonTitle="編集">
                        </x-edit-button>
                    </div>
                    <div class="ml-3">
                        <x-delete-button
                            :routeName="route('shop.destroy', $shop)"
                            methodName="POST"
                            buttonTitle="削除">
                        </x-delete-button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
