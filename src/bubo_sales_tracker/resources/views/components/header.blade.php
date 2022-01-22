<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $headerTitle }}
    </h2>
    @if(auth()->user()->is_admin === 1)
        <div class="flex">
            <a class="bg-white text-gray-800 font-bold rounded border-b-2 border-blue-500 hover:border-blue-600 hover:bg-blue-500 hover:text-white shadow-md mx-2 py-1 px-6 inline-flex items-center" href="{{ $route }}">{{ $buttonTitle }}</a>
        @if (request()->is('*item'))
            <x-modal.csv-import></x-modal.csv-import>
        @endif
        </div>
    @endif
</div>
