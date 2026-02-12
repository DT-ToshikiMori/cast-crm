<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="liff-id" content="{{ config('services.line.liff_id') }}">
  <title>ログイン中...</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f7fb; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .loading-box { text-align: center; }
    .spinner-border { width: 3rem; height: 3rem; }
  </style>
</head>
<body>
  <div class="loading-box">
    <div class="spinner-border text-secondary mb-3" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="text-muted" id="status-text">LINEログイン中...</div>
  </div>

  <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
  <script>
  (function() {
    const liffId = document.querySelector('meta[name="liff-id"]').content;
    const statusEl = document.getElementById('status-text');

    // LIFF_ID が未設定ならローカル開発モード — テストユーザーで自動ログイン
    if (!liffId) {
      statusEl.textContent = '開発モード: ログインをスキップ...';
      fetch('/auth/line', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          line_user_id: 'dev_user_001',
          name: '開発ユーザー',
          picture_url: null,
        }),
      }).then(function(res) {
        if (res.ok) {
          window.location.href = '/crm';
        } else {
          statusEl.textContent = 'ログインに失敗しました';
        }
      });
      return;
    }

    liff.init({ liffId: liffId }).then(function() {
      if (!liff.isLoggedIn()) {
        statusEl.textContent = 'LINEにリダイレクト中...';
        liff.login();
        return;
      }

      statusEl.textContent = 'プロフィール取得中...';

      liff.getProfile().then(function(profile) {
        statusEl.textContent = 'ログイン中...';

        fetch('/auth/line', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            line_user_id: profile.userId,
            name: profile.displayName,
            picture_url: profile.pictureUrl || null,
          }),
        }).then(function(res) {
          if (res.ok) {
            window.location.href = '/crm';
          } else {
            statusEl.textContent = 'ログインに失敗しました';
          }
        });
      });
    }).catch(function(err) {
      console.error('LIFF init error:', err);
      statusEl.textContent = 'エラーが発生しました';
    });
  })();
  </script>
</body>
</html>
