<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Controle de Chaves') · Controle de Chaves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --ink: #172338; --muted: #768398; --blue: #245bd3; --canvas: #f3f6fa; --line: #e8edf3; }
        body { background: var(--canvas); color: var(--ink); font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }
        .navbar { background: var(--ink); min-height: 62px; }
        .navbar-brand { font-weight: 700; }
        .brand-mark { display: inline-grid; place-items: center; width: 32px; height: 32px; border-radius: 9px; background: #294e8d; color: #fff; margin-right: 9px; }
        .app-wrap { max-width: 1180px; }
        .page-title { font-size: 1.65rem; font-weight: 700; letter-spacing: -.035em; }
        .page-subtitle, .muted { color: var(--muted); }
        .card { border: 1px solid var(--line); border-radius: 13px; box-shadow: 0 3px 14px rgba(26, 44, 72, .035); }
        .metric-label { color: var(--muted); font-size: .82rem; }
        .metric-value { font-size: 2rem; font-weight: 750; }
        .table thead th { color: #65738a; text-transform: uppercase; font-size: .69rem; letter-spacing: .055em; border-bottom-color: var(--line); }
        .table tbody td { vertical-align: middle; border-color: #eef1f5; }
        .btn-primary { --bs-btn-bg: var(--blue); --bs-btn-border-color: var(--blue); --bs-btn-hover-bg: #194bb8; --bs-btn-hover-border-color: #194bb8; }
        .form-control, .form-select { border-color: #dfe6ef; }
        .pagination { margin-bottom: 0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container app-wrap">
        <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}"><span class="brand-mark"><i class="bi bi-arrow-left-right"></i></span>Controle de Empréstimos</a>
        @auth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Abrir menu"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="main-nav">
                <div class="navbar-nav ms-lg-4 me-auto">
                    <a class="nav-link" href="{{ route('dashboard') }}">Painel</a>
                    <a class="nav-link" href="{{ route('loans.index') }}">Movimentações</a>
                </div>
                <div class="d-flex align-items-center gap-3 py-2 py-lg-0">
                    <span class="text-white-50 small">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn btn-sm btn-outline-light" type="submit">Sair</button></form>
                </div>
            </div>
        @endauth
    </div>
</nav>
<main class="container app-wrap py-4 py-lg-5">
    @if (session('status')) <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button></div> @endif
    @if (session('error')) <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button></div> @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>document.querySelectorAll('[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => { if (!window.confirm(form.dataset.confirm)) event.preventDefault(); }));</script>
</body>
</html>
