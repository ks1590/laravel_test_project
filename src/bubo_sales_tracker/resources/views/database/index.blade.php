<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('バックアップ') }}
            </h2>
        </div>
    </x-slot>
    <x-session-message></x-session-message>
    <div class="m-8 min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="shadow-md min-w-0 mb-5 p-5 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="flex">
                <h2 class="mt-3 mb-3 text-2xl font-black w-auto inline-block">
                    MySQL バックアップファイル | 直近のバックアップ：【{{ $time }}】
                </h2>
            </div>
            <div class="flex flex-col justify-center">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="">
                            <form id="download-backup" method="GET" action="{{ route('database.downloadBackup') }}" class="w-full">
                                <div>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <select class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" name="filename">
                                            @foreach($filenames as $filename)
                                                <option value="{{$filename}}">{{ $filename }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="pt-3 text-right">
                                    <button type="submit" class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 hover:border-blue-600 hover:bg-blue-500 hover:text-white shadow-md py-1 px-6 inline-flex items-center">
                                        ダウンロード
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
