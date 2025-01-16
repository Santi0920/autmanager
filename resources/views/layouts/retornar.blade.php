@if (!session()->has('email'))
    @php
        header("Location: .");
        exit();
    @endphp
@else
@endif
