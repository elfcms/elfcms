<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ELF CMS</title>
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/afacad/afacad.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/admin/fonts/inter/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('elfcms/welcome/css/welcome.css') }}">
</head>
<body>
    <div class="welcome-bg"></div>
    <div class="cms_logo">
        {!! iconHtmlLocal('elfcms/admin/images/logo/logo.svg', svg:true) !!}
    </div>
    <h1>Welcome to ELF CMS</h1>
    <button class="start-button">Start</button>
</body>
</html>
