<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Error') - {{ config('app.name') }}</title>
    <style>
        :root{--bg:#f7f7fa;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280}
        html,body{height:100%;margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif;background:var(--bg);color:#111}
        .error-wrap{min-height:100%;display:flex;align-items:center;justify-content:center;padding:48px}
        .error-card{max-width:800px;width:100%;background:var(--card);border-radius:12px;box-shadow:0 10px 30px rgba(16,24,40,0.08);padding:36px;display:flex;gap:28px;align-items:center}
        .error-illustration{flex:0 0 120px;height:120px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#6fb3ff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:34px}
        .error-body{flex:1}
        .error-code{font-size:28px;font-weight:700;color:var(--accent);margin:0}
        .error-title{font-size:20px;margin:6px 0 12px}
        .error-message{color:var(--muted);margin:0 0 16px}
        .error-actions a{display:inline-block;padding:10px 14px;border-radius:8px;text-decoration:none;color:#fff;background:var(--accent);margin-right:8px}
        .error-actions a.secondary{background:#edf2f7;color:var(--accent)}
        pre{background:#111827;color:#f8fafc;padding:12px;border-radius:8px;overflow:auto}
        @media (max-width:640px){.error-card{flex-direction:column;align-items:stretch}.error-illustration{width:100%;height:84px}}    
    </style>
</head>
<body>
    <div class="error-wrap">
        <div class="error-card">
            <div class="error-illustration">@yield('code', '⚠')</div>
            <div class="error-body">
                <h2 class="error-code">@yield('code_text')</h2>
                <h3 class="error-title">@yield('title')</h3>
                <p class="error-message">@yield('message')</p>
                <div class="error-actions">
                    <a href="{{ url('/') }}">Ir al inicio</a>
                    <a class="secondary" href="{{ url()->previous() }}">Volver</a>
                </div>
                @hasSection('details')
                    <div style="margin-top:16px">@yield('details')</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
