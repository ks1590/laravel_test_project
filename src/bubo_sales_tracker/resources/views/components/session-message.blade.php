@php
    $session_flag = false;
    if(session('success'))
    {
        $color = "bg-blue-100 border border-blue-400 text-blue-700";
        $message = session('success');
        $session_flag = true;
    }
    elseif(session('info'))
    {
        $color = "bg-green-100 border-green-400 text-green-700";
        $message = session('info');
        $session_flag = true;
    }
    elseif(session('warning'))
    {
        $color = "bg-yellow-100 border-yellow-400 text-yellow-700";
        $message = session('warning');
        $session_flag = true;
    }
    elseif(session('danger'))
    {
        $color = "bg-red-100 border-red-400 text-red-700";
        $message = session('danger');
        $session_flag = true;
    }
@endphp
@if($session_flag)
    <div class="max-w-7xl mx-5 sm:px-6 lg:px-8">
        <div class="mt-8 border px-4 py-3 rounded relative {{ $color }}">
            {{ $message }}
        </div>
    </div>
@endif
