@extends('layouts.crm')
@section('title', '来店を紐づけ')

@section('content')
  <div class="card p-3 mt-3 mb-3 border border-warning">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <div class="fw-bold">この来店を紐づけ</div>
        <div class="small text-muted">未整理の来店ログに、お客さんを選んで紐づけます。</div>
      </div>
      <a href="{{ route('crm.visits.unassigned') }}" class="btn btn-light btn-sm rounded-pill">戻る</a>
    </div>

    <div class="mt-3">
      <div class="fw-semibold">
        {{ $visit['type'] }}
        <span class="text-muted small">／ {{ $visit['time'] }}</span>
        <span class="badge text-bg-warning ms-2">未整理</span>
      </div>
      <div class="small text-muted mt-1">
        メモ：{{ $visit['memo'] ?: 'なし' }}
      </div>
    </div>
  </div>

  <div class="card p-3 mb-3 border border-warning">
  <div class="fw-bold mb-2">この来店の処理</div>

  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-outline-dark btn-sm rounded-pill"
       href="{{ route('crm.customer.create', ['from' => 'unassigned', 'visit_id' => $visit['id']]) }}">
      ＋ 新しいお客さんとして追加
    </a>

    <a class="btn btn-light btn-sm rounded-pill"
       href="{{ route('crm.visits.unassigned') }}">
      後でやる
    </a>
  </div>

  <div class="text-muted small mt-2">
    既存にいない場合は「新しいお客さんとして追加」→ 追加後にこの画面へ戻って紐づけ（今は画面のみ）。
  </div>
</div>

  <form class="card p-3 mb-3" method="get" action="{{ route('crm.visits.assign', $visit['id']) }}">
    <div class="fw-bold mb-2">お客さんを探す</div>
    <div class="input-group">
      <input name="q" value="{{ $q }}" class="form-control" placeholder="名前 / タグ で検索">
      <button class="btn btn-dark">検索</button>
    </div>
    @if(!empty($selectedCustomerId))
      <input type="hidden" name="customer_id" value="{{ $selectedCustomerId }}">
    @endif
  </form>

  <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">お客さんを選ぶ</div>
      <div class="small text-muted">（{{ $customers->count() }}件）</div>
    </div>

    @forelse($customers as $c)
      @php $isSelected = (string)$c['id'] === (string)$selectedCustomerId; @endphp

      <div class="py-2 {{ !$loop->first ? 'border-top' : '' }}">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div>
            <div class="fw-semibold">
              {{ $c['name'] }}
              @if($isSelected)
                <span class="badge text-bg-dark ms-1">選択中</span>
              @endif
            </div>
            <div class="small text-muted">最終来店：{{ $c['last_visit'] }}（{{ $c['days_since_last_visit'] }}日）</div>
            <div class="mt-1 d-flex gap-1 flex-wrap">
              @foreach($c['tag'] as $t)
                <span class="badge text-bg-light">{{ $t }}</span>
              @endforeach
            </div>
          </div>

          <a class="btn {{ $isSelected ? 'btn-dark' : 'btn-outline-dark' }} btn-sm rounded-pill"
             href="{{ route('crm.visits.assign', ['visitId' => $visit['id'], 'q' => $q, 'customer_id' => $c['id']]) }}">
            {{ $isSelected ? '選択中' : '選択' }}
          </a>
        </div>
      </div>
    @empty
      <div class="text-muted">該当なし</div>
    @endforelse
  </div>

  <form class="card p-3" method="post" action="{{ route('crm.visits.assignStore', $visit['id']) }}">
    @csrf
    <div class="fw-bold mb-2">確認</div>

    <div class="small text-muted mb-2">
      紐づけ先：
      @if(!empty($selectedCustomerId))
        <span class="fw-semibold">
          {{ optional($customers->firstWhere('id', (int)$selectedCustomerId))['name'] ?? '（一覧から選んでね）' }}
        </span>
        <input type="hidden" name="customer_id" value="{{ $selectedCustomerId }}">
      @else
        <span class="fw-semibold">（未選択）</span>
      @endif
    </div>

    <button class="btn btn-dark w-100 rounded-pill" type="submit" {{ empty($selectedCustomerId) ? 'disabled' : '' }}>
      紐づける
    </button>
  </form>
@endsection