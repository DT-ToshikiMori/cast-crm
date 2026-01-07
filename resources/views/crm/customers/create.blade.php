@extends('layouts.crm')
@section('title', 'お客さんを追加')

@section('content')

@if(request('from') === 'unassigned')
  <div class="card p-3 mb-3 border border-warning">
    <div class="fw-bold">未整理の来店から追加中</div>
    <div class="text-muted small">
      いまは「未整理の来店ログ」を処理しています。<br>
      まず新しいお客さんとして追加して、次に来店へ紐づける流れです（画面のみ）。
    </div>

    <a class="btn btn-light btn-sm rounded-pill mt-2"
       href="{{ route('crm.visits.assign', request('visit_id', 9001)) }}">
      ← 来店の紐づけに戻る
    </a>
  </div>
@endif

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold mb-1">入力を確認してね</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card p-3">
    <div class="fw-bold mb-2">お客さんを追加</div>

    <form method="post" action="{{ route('crm.customer.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label small text-muted">名前 / あだ名（必須）</label>
        <input name="name" class="form-control" value="{{ old('name') }}" placeholder="例：タクミ">
      </div>

      <div class="mb-3">
        <label class="form-label small text-muted">誕生日（任意 / MM-DD）</label>
        <input name="birthday" class="form-control" value="{{ old('birthday') }}" placeholder="例：02-10">
        <div class="form-text">年は入れなくてOK（通知用）</div>
      </div>

      <div class="mb-3">
        <label class="form-label small text-muted">タグ（任意 / カンマ区切り）</label>
        <input name="tags" class="form-control" value="{{ old('tags') }}" placeholder="例：VIP, シャンパン, 同伴多い">
      </div>

      <div class="mb-3">
        <label class="form-label small text-muted">ひとことメモ（任意）</label>
        <textarea name="memo" class="form-control" rows="3" placeholder="例：山崎好き。次は週末。">{{ old('memo') }}</textarea>
      </div>

      <button class="btn btn-dark w-100 rounded-pill">登録する</button>

      @if(request('from') === 'unassigned')
        <button class="btn btn-outline-dark w-100 rounded-pill mt-2" type="button">
            この来店に紐づけて完了（見た目だけ）
        </button>
        @endif
    </form>
  </div>
@endsection