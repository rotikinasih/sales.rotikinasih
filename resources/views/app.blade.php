<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="Datta Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
        <meta name="keywords" content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, datta able, datta able bootstrap admin template, free admin theme, free dashboard template"/>
        <meta name="author" content="CodedThemes"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ mix('/js/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('/fonts/fontawesome/css/fontawesome-all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/plugins/animation/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
        <script src="{{ asset('/js/vendor-all.min.js') }}"></script>
        <script src="{{ asset('/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/js/pcoded.min.js') }}"></script>
        <link rel="icon" href="{{ asset('/images/logo.png') }}" type="image/ico" />
    </head>
    <body>
        @inertia
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>


<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Datta Able Bootstrap admin template made using Bootstrap 4..." />
    <meta name="keywords" content="admin templates, bootstrap admin..." />
    <meta name="author" content="CodedThemes"/>

    {{-- Ubah asset lokal ke secure_asset --}}
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <link rel="stylesheet" href="{{ secure_asset('/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('/plugins/animation/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('/css/style.css') }}">

    <script src="{{ secure_asset('/js/vendor-all.min.js') }}"></script>
    <script src="{{ secure_asset('/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ secure_asset('/js/pcoded.min.js') }}"></script>

    <link rel="icon" href="{{ secure_asset('/images/logo.png') }}" type="image/ico" />
</head>
<body>
    @inertia

    {{-- CDN sudah pakai https (boleh tetap) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
