<x-app-layout>
    <x-slot name="header">
        <x-header
            headerTitle="カテゴリ編集"
            buttonTitle="一覧に戻る"
            :route="route('category.index')">
        </x-header>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col justify-center">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <form id="category-update" method="POST" action="{{route('category.update',$category)}}" class="w-full">
                                @csrf
                                @method('PUT')
                                <x-form.category-content
                                    formTitle="カテゴリ詳細"
                                    :categoryName="$category->name"
                                    submitTitle="更新">
                                </x-form.category-content>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
