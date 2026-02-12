@extends('layouts.crm')
@section('title', 'ホーム')

@section('content')

<div class="card p-3 mt-3 mb-3 border border-warning">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <div class="fw-bold">⚠ 未整理の来店があります</div>
      <div class="small text-muted">あとでお客さんを選べます</div>
    </div>
    <a href="{{ route('crm.visits.unassigned') }}"
       class="btn btn-outline-dark btn-sm rounded-pill">
      整理する
    </a>
  </div>
</div>
  <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">今日の予定</div>
      <span class="badge text-bg-light">{{ now()->format('n/j') }}</span>
    </div>

    <div class="small text-muted mb-2">誕生日が近い</div>
    @forelse($birthdaySoon as $c)
      <a class="text-decoration-none" href="{{ route('crm.customer.show', $c['id']) }}">
        <div class="d-flex justify-content-between align-items-center py-2 border-top">
          <div>
            <div class="fw-semibold text-dark">{{ $c['name'] }}</div>
            <div class="small text-muted">誕生日：{{ $c['birthday'] ?? '-' }}</div>
          </div>
          <span class="badge text-bg-warning">リマインド</span>
        </div>
      </a>
    @empty
      <div class="text-muted small">なし</div>
    @endforelse
  </div>

  <div class="card p-3 mb-3">
    <div class="fw-bold mb-2">最近来てない（優先）</div>
    @foreach($stale as $c)
      <a class="text-decoration-none" href="{{ route('crm.customer.show', $c['id']) }}">
        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->first ? 'border-top' : '' }}">
          <div>
            <div class="fw-semibold text-dark">{{ $c['name'] }}</div>
            <div class="small text-muted">最終来店：{{ $c['last_visit'] ?? '-' }}（{{ $c['days_since_last_visit'] }}日）</div>
          </div>
          <span class="badge text-bg-light">見る</span>
        </div>
      </a>
    @endforeach
  </div>
@endsection