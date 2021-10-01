<html >

{{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet"> --}}

<head>
 <title></title>
</head>
<body>
    <div class="col-lg-3 text-center">

        {{-- <h1>{{ $certs->cert_title}}</h1> --}}
        <div>
            <p>{{$title}} ------------ {{ $name }} --------- {{ $date }}</p>
        </div>

            <img src="{{ public_path('img/wew.png') }}" alt="..." class="rounded-circle img-fluid" >
        
    </div>
    
</body>
</body>
</html>