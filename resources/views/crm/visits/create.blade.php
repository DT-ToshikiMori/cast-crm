@extends('layouts.crm')
@section('title', '来店を記録')

@section('content')
  @if (session('status'))
    <div class="alert alert-success mt-3">{{ session('status') }}</div>
  @endif

  <form class="card mt-3 p-3" method="post" action="{{ route('crm.visits.store') }}">
    @csrf
    <div class="fw-bold mb-2">来店記録</div>

    <div class="mb-3">
      <label class="form-label small text-muted">種別</label>
      <select name="type" class="form-select">
        <option>来店</option>
        <option>同伴</option>
        <option>アフター</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label small text-muted">金額（任意）</label>
      <input name="amount" class="form-control" placeholder="例：30000">
    </div>

    <div class="mb-3">
      <label class="form-label small text-muted">メモ</label>
      <textarea name="note" class="form-control" rows="3" placeholder="例：延長、会話内容、次の約束"></textarea>
    </div>

    <button class="btn btn-dark w-100 rounded-pill" type="submit">保存</button>
  </form>
@endsection
