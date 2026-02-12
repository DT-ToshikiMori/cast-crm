<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="liff-id" content="{{ config('services.line.liff_id') }}">
  <title>@yield('title', 'キャストメモ')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --bg-primary: #0a0a0f;
      --bg-secondary: #15151d;
      --bg-tertiary: #1d1d28;
      --glass-bg: rgba(255,255,255,0.05);
      --glass-border: rgba(255,255,255,0.08);
      --glass-hover: rgba(255,255,255,0.10);
      --text-primary: #ffffff;
      --text-secondary: #a8a8b8;
      --text-muted: #6a6a7a;
      --accent-gold: #d4af37;
      --accent-gold-light: #f4cf57;
      --gradient-gold: linear-gradient(135deg, #d4af37 0%, #f4cf57 100%);
      --status-warning: #ffc107;
      --status-danger: #e74c3c;
      --status-success: #2ecc71;
      --radius-sm: 12px;
      --radius-md: 16px;
      --radius-lg: 20px;
      --radius-pill: 999px;
      --shadow-sm: 0 2px 8px rgba(0,0,0,0.3);
      --shadow-md: 0 4px 16px rgba(0,0,0,0.4);
      --shadow-glow: 0 0 20px rgba(212,175,55,0.25);
    }

    * { box-sizing: border-box; }

    body {
      background: var(--bg-primary);
      color: var(--text-primary);
      font-family: -apple-system, BlinkMacSystemFont, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
      font-size: 16px;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    /* ── Layout ── */
    .app-container {
      max-width: 520px;
      margin: 0 auto;
      padding: 16px 16px 100px;
    }

    /* ── Header ── */
    .app-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .app-logo {
      font-size: 20px;
      font-weight: 700;
      background: var(--gradient-gold);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .app-tagline {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    /* ── Glass Card ── */
    .card-glass {
      background: var(--glass-bg);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-md);
      padding: 20px;
      margin-bottom: 16px;
    }
    .card-glass .card-title {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 16px;
      color: var(--text-primary);
    }
    .card-glass .card-title i { color: var(--accent-gold); font-size: 18px; }

    .section-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent 0%, var(--glass-border) 50%, transparent 100%);
      margin: 16px 0;
    }

    /* ── Customer Avatar ── */
    .avatar {
      width: 44px; height: 44px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }
    .avatar-sm { width: 36px; height: 36px; font-size: 15px; }
    .avatar-lg { width: 64px; height: 64px; font-size: 28px; }

    /* ── Customer Row ── */
    .customer-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 0;
      text-decoration: none;
      color: inherit;
      transition: all 0.15s ease;
    }
    .customer-row + .customer-row { border-top: 1px solid var(--glass-border); }
    .customer-row:hover { opacity: 0.85; }
    .customer-row:active { transform: scale(0.98); }
    .customer-row .info { flex: 1; min-width: 0; }
    .customer-row .name { font-weight: 600; font-size: 15px; color: var(--text-primary); }
    .customer-row .meta { font-size: 13px; color: var(--text-secondary); margin-top: 2px; display: flex; align-items: center; gap: 6px; }
    .customer-row .meta i { font-size: 11px; opacity: 0.7; }
    .customer-row .chevron { color: var(--text-muted); font-size: 16px; }

    /* ── Tags / Badges ── */
    .tag {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px;
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-pill);
      font-size: 11px; font-weight: 600;
      color: var(--text-secondary);
    }
    .tag-vip {
      background: var(--gradient-gold);
      color: var(--bg-primary);
      border: none;
    }
    .tag-warning {
      background: rgba(255,193,7,0.15);
      color: var(--status-warning);
      border-color: rgba(255,193,7,0.3);
    }
    .tags-wrap { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px; }

    /* ── Days Indicator ── */
    .days-ind {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 2px 8px; border-radius: var(--radius-sm);
      font-size: 11px; font-weight: 600;
    }
    .days-ind.fresh  { background: rgba(46,204,113,0.15); color: var(--status-success); }
    .days-ind.recent { background: rgba(255,193,7,0.15); color: var(--status-warning); }
    .days-ind.stale  { background: rgba(230,126,34,0.15); color: #e67e22; }
    .days-ind.very-stale { background: rgba(231,76,60,0.15); color: var(--status-danger); }

    /* ── Buttons ── */
    .btn-gold {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: var(--gradient-gold);
      color: var(--bg-primary);
      border: none;
      border-radius: var(--radius-pill);
      padding: 12px 24px;
      font-size: 15px; font-weight: 700;
      min-height: 48px;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-gold:hover { box-shadow: var(--shadow-glow); color: var(--bg-primary); }
    .btn-gold:active { transform: scale(0.96); }
    .btn-gold.w-100 { width: 100%; }

    .btn-glass {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: var(--glass-bg);
      color: var(--text-primary);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-pill);
      padding: 10px 20px;
      font-size: 14px; font-weight: 600;
      min-height: 44px;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-glass:hover { background: var(--glass-hover); border-color: var(--accent-gold); color: var(--accent-gold); }
    .btn-glass:active { transform: scale(0.96); }

    .btn-ghost {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      background: transparent;
      color: var(--text-secondary);
      border: none;
      padding: 10px 16px;
      font-size: 14px; font-weight: 500;
      min-height: 44px;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-ghost:hover { color: var(--text-primary); }

    /* ── Filter Pills ── */
    .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
    .pill {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 16px;
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-pill);
      color: var(--text-secondary);
      font-size: 13px; font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .pill:hover { background: var(--glass-hover); border-color: var(--accent-gold); color: var(--accent-gold); }
    .pill.active { background: var(--gradient-gold); color: var(--bg-primary); border-color: transparent; }
    .pill i { font-size: 12px; }

    /* ── Forms ── */
    .form-label { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary); font-weight: 600; margin-bottom: 6px; }
    .form-label i { color: var(--accent-gold); font-size: 14px; }
    .form-control, .form-select {
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-sm);
      color: var(--text-primary);
      font-size: 16px;
      padding: 14px 16px;
      transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
      background: var(--glass-hover);
      border-color: var(--accent-gold);
      box-shadow: 0 0 0 3px rgba(212,175,55,0.1);
      color: var(--text-primary);
    }
    .form-control::placeholder { color: var(--text-muted); }
    .form-text { color: var(--text-muted); font-size: 12px; }
    .form-check-input { background-color: var(--glass-bg); border-color: var(--glass-border); }
    .form-check-input:checked { background-color: var(--accent-gold); border-color: var(--accent-gold); }
    .form-check-input:focus { box-shadow: 0 0 0 3px rgba(212,175,55,0.15); }
    .form-check-label { color: var(--text-primary); }

    /* ── Search ── */
    .search-wrap { position: relative; }
    .search-wrap .bi-search { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
    .search-wrap .form-control { padding-left: 44px; }

    /* ── Alert Card ── */
    .alert-glass {
      display: flex; align-items: center; gap: 14px;
      padding: 16px 20px;
      background: rgba(255,193,7,0.08);
      border: 1px solid rgba(255,193,7,0.2);
      border-radius: var(--radius-md);
      margin-bottom: 16px;
    }
    .alert-glass .alert-icon {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--gradient-gold);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .alert-glass .alert-icon i { font-size: 18px; color: var(--bg-primary); }
    .alert-glass .alert-body { flex: 1; }
    .alert-glass .alert-title { font-weight: 700; font-size: 14px; }
    .alert-glass .alert-text { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

    .alert-success-glass {
      display: flex; align-items: center; gap: 10px;
      padding: 14px 20px;
      background: rgba(46,204,113,0.1);
      border: 1px solid rgba(46,204,113,0.2);
      border-radius: var(--radius-md);
      margin-bottom: 16px;
      font-size: 14px; color: var(--status-success);
    }
    .alert-success-glass i { font-size: 18px; }

    .alert-danger-glass {
      padding: 14px 20px;
      background: rgba(231,76,60,0.1);
      border: 1px solid rgba(231,76,60,0.2);
      border-radius: var(--radius-md);
      margin-bottom: 16px;
      font-size: 14px; color: var(--status-danger);
    }

    /* ── Type Selector (visit types) ── */
    .type-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
    .type-btn {
      padding: 16px 8px;
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-md);
      display: flex; flex-direction: column; align-items: center; gap: 6px;
      cursor: pointer; transition: all 0.2s ease;
      color: var(--text-secondary);
    }
    .type-btn:hover { background: var(--glass-hover); border-color: var(--accent-gold); }
    .type-btn.active { background: var(--gradient-gold); border-color: transparent; color: var(--bg-primary); }
    .type-btn i { font-size: 24px; }
    .type-btn.active i { color: var(--bg-primary); }
    .type-btn:not(.active) i { color: var(--accent-gold); }
    .type-btn span { font-size: 13px; font-weight: 600; }

    /* ── Quick Actions Grid ── */
    .actions-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 16px; }
    .action-btn {
      padding: 16px 12px;
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-md);
      display: flex; flex-direction: column; align-items: center; gap: 6px;
      text-decoration: none; color: var(--text-secondary);
      transition: all 0.2s ease; font-size: 13px; font-weight: 600;
    }
    .action-btn i { font-size: 22px; color: var(--accent-gold); }
    .action-btn:hover { background: var(--glass-hover); border-color: var(--accent-gold); color: var(--accent-gold); transform: translateY(-2px); }

    /* ── Empty State ── */
    .empty-state { text-align: center; padding: 32px 16px; color: var(--text-muted); }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.5; }
    .empty-state .empty-title { font-size: 15px; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state .empty-text { font-size: 13px; }

    /* ── Settings list ── */
    .settings-list { border-radius: var(--radius-md); overflow: hidden; }
    .settings-item {
      display: flex; align-items: center; gap: 14px;
      padding: 16px 20px;
      background: var(--glass-bg);
      border: none; border-bottom: 1px solid var(--glass-border);
      width: 100%; text-align: left;
      color: var(--text-primary); font-size: 15px;
      text-decoration: none;
      transition: background 0.15s ease;
    }
    .settings-item:last-child { border-bottom: none; }
    .settings-item:hover { background: var(--glass-hover); }
    .settings-item i:first-child { font-size: 20px; color: var(--accent-gold); width: 24px; text-align: center; }
    .settings-item span { flex: 1; }
    .settings-item .bi-chevron-right { font-size: 14px; color: var(--text-muted); }
    .settings-item.danger { color: var(--status-danger); }
    .settings-item.danger i { color: var(--status-danger); }

    /* ── Bottom Nav ── */
    .bottom-nav {
      position: fixed; left: 0; right: 0; bottom: 0;
      background: rgba(15,15,23,0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-top: 1px solid var(--glass-border);
      z-index: 1000;
      padding-bottom: env(safe-area-inset-bottom);
    }
    .bottom-nav .nav { max-width: 520px; margin: 0 auto; padding: 8px 0 4px; }
    .bottom-nav .nav-link {
      display: flex; flex-direction: column; align-items: center; gap: 3px;
      color: var(--text-muted);
      font-size: 10px; font-weight: 600;
      padding: 6px 0;
      transition: color 0.2s ease;
      text-decoration: none;
    }
    .bottom-nav .nav-link i { font-size: 22px; }
    .bottom-nav .nav-link.active { color: var(--accent-gold); }

    /* ── FAB ── */
    .fab-wrap {
      position: fixed;
      bottom: calc(100px + env(safe-area-inset-bottom));
      right: max(16px, calc((100vw - 520px) / 2 + 16px));
      z-index: 999;
      display: flex; flex-direction: column; align-items: flex-end; gap: 12px;
    }
    .fab-menu {
      display: flex; flex-direction: column; align-items: flex-end; gap: 10px;
      opacity: 0; pointer-events: none;
      transform: translateY(10px);
      transition: all 0.25s ease;
    }
    .fab-menu.open { opacity: 1; pointer-events: all; transform: translateY(0); }
    .fab-menu-item {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
    }
    .fab-menu-label {
      padding: 8px 14px;
      background: var(--bg-tertiary);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius-sm);
      font-size: 13px; font-weight: 600;
      color: var(--text-primary);
      white-space: nowrap;
      box-shadow: var(--shadow-sm);
    }
    .fab-menu-icon {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: var(--bg-tertiary);
      border: 1px solid var(--glass-border);
      display: flex; align-items: center; justify-content: center;
      box-shadow: var(--shadow-sm);
    }
    .fab-menu-icon i { font-size: 20px; color: var(--accent-gold); }
    .fab-btn {
      width: 56px; height: 56px;
      border-radius: 50%;
      background: var(--gradient-gold);
      border: none;
      display: flex; align-items: center; justify-content: center;
      box-shadow: var(--shadow-md);
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .fab-btn i { font-size: 26px; color: var(--bg-primary); transition: transform 0.3s ease; }
    .fab-btn:hover { box-shadow: var(--shadow-glow); }
    .fab-btn.open i { transform: rotate(45deg); }

    /* ── Utilities ── */
    .text-gold { color: var(--accent-gold); }
    .text-secondary-custom { color: var(--text-secondary); }
    .text-muted-custom { color: var(--text-muted); }
    .gap-tags { gap: 6px; }

    /* Override Bootstrap .card borders */
    .card { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: var(--radius-md); }
    .border-warning { border-color: rgba(255,193,7,0.3) !important; }

    /* Links */
    a { color: var(--accent-gold); }
    a:hover { color: var(--accent-gold-light); }

    /* Override Bootstrap alert */
    .alert { border-radius: var(--radius-md); }
  </style>
</head>
<body>
  <div class="app-container">
    <div class="app-header">
      <div>
        <div class="app-logo">キャストメモ</div>
        <div class="app-tagline">思い出すためのメモ</div>
      </div>
    </div>

    @yield('content')
  </div>

  {{-- FAB --}}
  <div class="fab-wrap">
    <div class="fab-menu" id="fabMenu">
      <a href="{{ route('crm.memos.quick') }}" class="fab-menu-item">
        <span class="fab-menu-label">ひとことメモ</span>
        <span class="fab-menu-icon"><i class="bi bi-chat-text"></i></span>
      </a>
      <a href="{{ route('crm.visits.create') }}" class="fab-menu-item">
        <span class="fab-menu-label">来店を記録</span>
        <span class="fab-menu-icon"><i class="bi bi-calendar-plus"></i></span>
      </a>
    </div>
    <button class="fab-btn" id="fabBtn" onclick="document.getElementById('fabMenu').classList.toggle('open');this.classList.toggle('open')">
      <i class="bi bi-plus-lg"></i>
    </button>
  </div>

  {{-- Bottom Nav --}}
  <div class="bottom-nav">
    <ul class="nav nav-justified">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('crm.home') ? 'active' : '' }}" href="{{ route('crm.home') }}">
          <i class="bi {{ request()->routeIs('crm.home') ? 'bi-house-fill' : 'bi-house' }}"></i>
          ホーム
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('crm.customers*') || request()->routeIs('crm.customer.*') ? 'active' : '' }}" href="{{ route('crm.customers') }}">
          <i class="bi {{ request()->routeIs('crm.customers*') || request()->routeIs('crm.customer.*') ? 'bi-people-fill' : 'bi-people' }}"></i>
          一覧
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('crm.reminders') ? 'active' : '' }}" href="{{ route('crm.reminders') }}">
          <i class="bi {{ request()->routeIs('crm.reminders') ? 'bi-bell-fill' : 'bi-bell' }}"></i>
          リマインド
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('crm.settings') ? 'active' : '' }}" href="{{ route('crm.settings') }}">
          <i class="bi {{ request()->routeIs('crm.settings') ? 'bi-gear-fill' : 'bi-gear' }}"></i>
          設定
        </a>
      </li>
    </ul>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
  <script>
  // Close FAB menu when clicking outside
  document.addEventListener('click', function(e) {
    var fab = document.getElementById('fabBtn');
    var menu = document.getElementById('fabMenu');
    if (fab && menu && !fab.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('open');
      fab.classList.remove('open');
    }
  });

  // LIFF
  (function() {
    var liffId = document.querySelector('meta[name="liff-id"]').content;
    if (!liffId) return;
    liff.init({ liffId: liffId }).then(function() {
      if (!liff.isLoggedIn()) { liff.login(); return; }
      liff.getProfile().then(function(profile) {
        fetch('/auth/line', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ line_user_id: profile.userId, name: profile.displayName, picture_url: profile.pictureUrl || null }),
        });
      });
    }).catch(function(err) { console.error('LIFF init error:', err); });
  })();
  </script>
</body>
</html>
