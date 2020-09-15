<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Book system</title>
        <link rel="stylesheet" href="{{ asset("css/app.css") }}">
        <!-- Fonts -->
        <!-- Styles -->
    </head>
    <body>
       <div id="app">
           <app-component/>
       </div>
    <script src="{{asset("js/app.js")}}"></script>
    </body>
</html>
