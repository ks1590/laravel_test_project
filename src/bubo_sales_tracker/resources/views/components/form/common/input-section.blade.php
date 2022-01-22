<div>
    <label for="{{ $inputName }}" class="block text-sm font-medium text-gray-700">
        {{ $labelTitle }}
    </label>
    <div class="mt-1 flex rounded-md shadow-sm">
        <input id="{{ $inputName }}" name="{{ $inputName }}" type="{{ $type }}" value="{{ $currentValue }}" required class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
    </div>
</div>
