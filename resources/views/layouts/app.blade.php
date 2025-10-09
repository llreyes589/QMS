<!DOCTYPE html>
<html data-theme="emerald">
    <head>
        <title>QMS</title>
    </head>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <body>
        <div id="app">
            
            @yield('content')
        </div>
    </body>
</html>