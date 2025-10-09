<!DOCTYPE html>
<html data-theme="corporate">
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