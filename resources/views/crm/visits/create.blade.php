@extends('layouts.crm')
@section('title', '来店を記録')

@section('content')
  <div class="card mt-3 p-3">
    <div class="fw-bold mb-2">来店記録</div>

    <div class="mb-3">
      <label class="form-label small text-muted">種別</label>
      <select class="form-select">
        <option>来店</option>
        <option>同伴</option>
        <option>アフター</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label small text-muted">金額（任意）</label>
      <input class="form-control" placeholder="例：30000">
    </div>

    <div class="mb-3">
      <label class="form-label small text-muted">メモ</label>
      <textarea class="form-control" rows="3" placeholder="例：延長、会話内容、次の約束"></textarea>
    </div>

    <button class="btn btn-dark w-100 rounded-pill" type="button">保存（見た目だけ）</button>

    <div class="text-muted small mt-2">
      ※ 今はUIだけ。保存は後でDB繋ぐ。
    </div>
  </div>
@endsection