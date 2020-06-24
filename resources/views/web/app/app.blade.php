<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Blog --- 程序员的自我修养</title>
    <script type='text/javascript'>
        window.Laravel = <?php echo json_encode([
                                'csrfToken' => csrf_token(),
                            ]); ?>
    </script>
</head>

<body>
    <div id="app">
        <categories></categories>
        <nav></nav>
        <div class="container">
            <router-view></router-view>
        </div>
        <footers></footers>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
