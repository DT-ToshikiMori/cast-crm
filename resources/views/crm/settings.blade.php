@extends('layouts.crm')
@section('title', '設定')

@section('content')
  <div class="card p-3">
    <div class="fw-bold mb-2">プロフィール</div>

    <div class="mb-3">
      <label class="form-label small text-muted">表示名</label>
      <input class="form-control" value="もり">
    </div>

    <div class="mb-3">
      <label class="form-label small text-muted">店名（任意）</label>
      <input class="form-control" value="Sample Lounge">
    </div>

    <div class="d-grid gap-2">
      <button class="btn btn-outline-dark rounded-pill" type="button">データを書き出す（将来）</button>
      <a class="btn btn-light rounded-pill" href="#">利用規約</a>
    </div>
  </div>
@endsection