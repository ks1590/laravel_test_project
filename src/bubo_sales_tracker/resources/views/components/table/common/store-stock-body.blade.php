<td class="w-full lg:w-auto text-gray-800 text-right border-b p-3">
    @foreach($stocks as $stock)
        @if($shop['sumaregi_tenpo_id'] == $stock['storeId'])
            @if($itemSku == $stock['productCode'])
                {{ $stock['stockAmount'] }}
                @break
            @endif
        @endif
    @endforeach
</td>
