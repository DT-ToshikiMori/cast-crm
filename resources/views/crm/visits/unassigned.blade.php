@extends('layouts.crm')
@section('title', '未整理の来店ログ')

@section('content')
  @if (session('status'))
    <div class="alert alert-success mt-3">{{ session('status') }}</div>
  @endif

  <div class="card p-3 mt-3 mb-3">
    <div class="fw-bold mb-1">未整理の来店ログ</div>
    <div class="text-muted small">
      忙しいときに記録した来店です。<br>
      あとでお客さんを選んで整理できます。
    </div>
  </div>

  <div class="card p-3">
    <div class="fw-bold mb-2">
      未整理（{{ $unassignedVisits->count() }}件）
    </div>

    @foreach($unassignedVisits as $v)
      <div class="py-3 {{ !$loop->first ? 'border-top' : '' }}">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <div class="fw-semibold">
            {{ $v['type'] }}
            <span class="text-muted small">／ {{ $v['time'] }}</span>
          </div>
          <span class="badge text-bg-warning">未整理</span>
        </div>

        @if($v['memo'])
          <div class="small text-muted mb-2">
            メモ：{{ $v['memo'] }}
          </div>
        @else
          <div class="small text-muted mb-2">
            メモ：なし
          </div>
        @endif

        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark btn-sm rounded-pill"
                href="{{ route('crm.visits.assign', $v['id']) }}">
                お客さんを選ぶ
            </a>
          <button class="btn btn-light btn-sm rounded-pill" type="button">
            後でやる
          </button>
        </div>
      </div>
    @endforeach
  </div>
@endsection