<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="liff-id" content="{{ config('services.line.liff_id') }}">
  <title>ログイン中...</title>
  <style>
    body {
      background: #0a0a0f;
      color: #ffffff;
      font-family: -apple-system, BlinkMacSystemFont, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
      display: flex; align-items: center; justify-content: center;
      min-height: 100vh; margin: 0;
    }
    .loading-box { text-align: center; }
    .spinner {
      width: 48px; height: 48px;
      border: 3px solid rgba(255,255,255,0.1);
      border-top-color: #d4af37;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin: 0 auto 20px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #status-text { color: #a8a8b8; font-size: 14px; }
  </style>
</head>
<body>
  <div class="loading-box">
    <div class="spinner"></div>
    <div id="status-text">LINEログイン中...</div>
  </div>

  <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
  <script>
  (function() {
    var liffId = document.querySelector('meta[name="liff-id"]').content;
    var statusEl = document.getElementById('status-text');

    if (!liffId) {
      statusEl.textContent = '開発モード: ログインをスキップ...';
      fetch('/auth/line', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ line_user_id: 'dev_user_001', name: '開発ユーザー', picture_url: null }),
      }).then(function(res) {
        if (res.ok) { window.location.href = '/crm'; }
        else { statusEl.textContent = 'ログインに失敗しました'; }
      });
      return;
    }

    liff.init({ liffId: liffId }).then(function() {
      if (!liff.isLoggedIn()) { statusEl.textContent = 'LINEにリダイレクト中...'; liff.login(); return; }
      statusEl.textContent = 'プロフィール取得中...';
      liff.getProfile().then(function(profile) {
        statusEl.textContent = 'ログイン中...';
        fetch('/auth/line', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ line_user_id: profile.userId, name: profile.displayName, picture_url: profile.pictureUrl || null }),
        }).then(function(res) {
          if (res.ok) { window.location.href = '/crm'; }
          else { statusEl.textContent = 'ログインに失敗しました'; }
        });
      });
    }).catch(function(err) { console.error('LIFF init error:', err); statusEl.textContent = 'エラーが発生しました'; });
  })();
  </script>
</body>
</html>
