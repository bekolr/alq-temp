<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Check-in')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap desde CDN (simple y rápido) --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Fuente opcional --}}
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap">

    <style>
        body {
            font-family: 'Montserrat', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .checkin-card {
            max-width: 900px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            overflow: hidden;
        }

        .checkin-header {
            background: #1e3c72;
            color: #fff;
            padding: 20px 25px;
        }

        .checkin-header h1 {
            font-size: 1.6rem;
            margin: 0;
            font-weight: 600;
        }

        .checkin-header p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .checkin-body {
            padding: 20px 25px 10px;
        }

        .checkin-footer {
            padding: 10px 25px 20px;
            text-align: right;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 8px;
            color: #333;
        }

        .small-muted {
            font-size: 0.8rem;
            color: #888;
        }

        .form-control-sm, .custom-select-sm {
            font-size: 0.85rem;
        }

        .badge-tag {
            background: rgba(255,255,255,0.15);
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.7rem;
            margin-left: 8px;
        }
    </style>

    @yield('css')
</head>
<body>

<div class="checkin-card">
    <div class="checkin-header d-flex align-items-center justify-content-between">
        <div>
            <h1>@yield('header_title', 'Registro de Check-in')</h1>
            <p>@yield('header_subtitle', 'Completá tus datos para registrar tu estadía.')</p>
        </div>
        <div class="text-right d-none d-md-block">
            <span class="badge badge-tag">Alojamiento temporal</span><br>
            <span class="badge badge-tag">Check-in online</span>
        </div>
    </div>

    <div class="checkin-body">
        @yield('content')
    </div>

    <div class="checkin-footer">
        @yield('footer')
    </div>
</div>

{{-- JS Bootstrap --}}
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('js')

</body>
</html>
