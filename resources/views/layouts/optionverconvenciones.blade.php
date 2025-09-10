<div class="tooltip-container">
    <span class="text">
        <svg class="w-10 h-10 text-gray-800 dark:text-white" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2"
                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
            <path stroke="currentColor" stroke-width="2"
                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
    </span>

    <span class="tooltip">
        <div class="container text-justify">
            <div class="row align-items-start">
                <div class="col" style="font-size: 18px; overflow-y: auto; max-height: 140px;">

                    
                        <span class="text-light">
                            ⬇CONVENCIONES⬇
                        </span><br>
                        
                        @foreach($convencion as $conv)
                            <span class="text-dark">( <span class="text-light fw-bold">{{$conv->Codigo}}</span> ) {{$conv->Concepto}}</span><br>
                        @endforeach
    

                </div>
            </div>
        </div>
    </span>
</div>
