<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (env('APP_NAME'))
        {{ env('APP_NAME') }}
        @else
        API Template
        @endif    
    </title>
    <style>
        body {
            background: #121212;
            background-color: #121212;
            margin: 0%;
            padding: 0%;
            overflow: hidden !important;
        }
        .container {
            overflow: hidden !important;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .logo {
            height: auto;
            width: 300px;
            max-width: 50vw;
            opacity: 0.1;
        }
    </style>
</head>
<body>
    <div class="container">    
        <img class="logo" src="{{ asset('/storage/ui/app/logo.svg') }}" />
    </div>
    
</body>
</html>