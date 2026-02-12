<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'お客さんメモ')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f7fb; }
    .card { border: 0; border-radius: 16px; }
    .badge { border-radius: 999px; }
    .bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(8px);
        border-top: 1px solid rgba(0,0,0,.06);
        z-index: 1000;
    }
    .bottom-nav {
        padding-bottom: env(safe-area-inset-bottom);
    }
    .nav-link { font-size: 12px; }
    .page-content {
        padding-bottom: 72px; /* タブバーの高さ分 */
    }
  </style>
</head>
<body>
  <div class="container py-3" style="max-width: 520px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="fw-bold">キャストメモ</div>
        <div class="text-muted small">“管理”じゃなく、思い出すためのメモ。</div>
      </div>
    </div>
    <a href="{{ route('crm.visits.create') }}" class="btn btn-dark btn-sm rounded-pill">来店を記録</a>
    <a href="{{ route('crm.memos.quick') }}" class="btn btn-outline-dark btn-sm rounded-pill">ひとことメモ</a>

    @yield('content')
  </div>

  <div class="bottom-nav">
    <div class="container py-3 page-content" style="max-width: 520px;">
      <ul class="nav nav-justified py-2">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('crm.home') ? 'fw-bold' : '' }}" href="{{ route('crm.home') }}">ホーム</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('crm.customers*') ? 'fw-bold' : '' }}" href="{{ route('crm.customers') }}">一覧</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('crm.reminders') ? 'fw-bold' : '' }}" href="{{ route('crm.reminders') }}">リマインド</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('crm.settings') ? 'fw-bold' : '' }}" href="{{ route('crm.settings') }}">設定</a></li>
      </ul>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>