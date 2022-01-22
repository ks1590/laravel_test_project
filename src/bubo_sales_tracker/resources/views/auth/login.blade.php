<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <div class="flex flex-col w-9/12 bg-white shadow-xl px-8 sm:px-8 md:px-8 lg:px-8 py-8 rounded-3xl w-50 max-w-md">
            <div class="self-center text-2xl font-bold text-gray-800">
                {{ __('bubó BARCELONA') }}
            </div>
            <div class="mt-2 self-center text-2xl font-bold text-gray-800">
                {{ __('売上管理アプリ') }}
            </div>
            <hr class="mt-4 border-2">

            <div class="mt-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="flex flex-col mb-5">
                        <div class="mt-3">
                            <x-label for="username" :value="__('ユーザー名')" />

                            <x-input id="username" class="text-sm rounded-2xl border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                     type="text"
                                     name="username"
                                     :value="old('username')" required autofocus />
                        </div>
                    </div>
                    <div class="flex flex-col mb-6">
                        <div class="mt-3">
                            <x-label for="password" :value="__('パスワード')" />

                            <x-input id="password" class="text-sm rounded-2xl border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                     type="password"
                                     name="password"
                                     required autocomplete="current-password"/>
                        </div>
                    </div>

                    <div class="flex w-full">
                        <button type="submit"
                                class="bg-gradient-to-r from-blue-400 to-blue-700
                                       hover:from-blue-700 hover:to-blue-800
                                       focus:from-blue-700 focus:to-blue-800
                                       rounded-full w-full font-bold text-xl text-white mt-4 py-2">
                            {{ __('ログイン') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
