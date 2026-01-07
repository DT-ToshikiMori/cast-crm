@extends('layouts.crm')
@section('title', $customer['name'].' の詳細')

@section('content')
  <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <div class="fs-5 fw-bold">{{ $customer['name'] }}</div>
        <div class="small text-muted">
          最終来店：{{ $customer['last_visit'] }}（{{ $customer['days_since_last_visit'] }}日）
          / 誕生日：{{ $customer['birthday'] ?? '未設定' }}
        </div>
        <div class="mt-2 d-flex gap-1 flex-wrap">
          @foreach($customer['tag'] as $t)
            <span class="badge text-bg-light">{{ $t }}</span>
          @endforeach
        </div>
      </div>
      <a class="btn btn-outline-dark btn-sm rounded-pill" href="{{ route('crm.visits.create') }}">来店を記録</a>
    </div>

    <div class="mt-3 d-flex gap-2 flex-wrap">
      <button class="btn btn-dark btn-sm rounded-pill" type="button">LINE送る（見た目だけ）</button>
      <button class="btn btn-outline-dark btn-sm rounded-pill" type="button">誕生日を編集（見た目だけ）</button>
      <button class="btn btn-outline-dark btn-sm rounded-pill" type="button">タグ編集（見た目だけ）</button>
    </div>
  </div>

  <div class="card p-3 mb-3">
    <div class="fw-bold mb-2">メモ</div>
    @forelse($customer['memo'] as $m)
      <div class="py-2 {{ !$loop->first ? 'border-top' : '' }}">
        <div class="small text-muted">{{ $m['date'] }}</div>
        <div>{{ $m['text'] }}</div>
      </div>
    @empty
      <div class="text-muted">まだメモなし</div>
    @endforelse
  </div>

  <div class="card p-3">
    <div class="fw-bold mb-2">来店履歴</div>
    @foreach($customer['visits'] as $v)
      <div class="py-2 {{ !$loop->first ? 'border-top' : '' }}">
        <div class="d-flex justify-content-between">
          <div class="fw-semibold">{{ $v['type'] }}</div>
          <div class="small text-muted">{{ $v['date'] }}</div>
        </div>
        <div class="small text-muted">金額：{{ number_format($v['amount']) }} / {{ $v['note'] }}</div>
      </div>
    @endforeach
  </div>
@endsection