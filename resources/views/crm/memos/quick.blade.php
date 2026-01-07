@extends('layouts.crm')
@section('title', 'ひとことメモ')

@section('content')
  <div class="card p-3 mt-3 mb-3">
    <div class="fw-bold mb-1">ひとことメモ（来店なし）</div>
    <div class="text-muted small">LINE返した・電話した・約束が流れた…みたいな“関係性ログ”を残す用。</div>
  </div>

  <form class="card p-3 mb-3" method="get" action="{{ route('crm.memos.quick') }}">
    <div class="fw-bold mb-2">お客さんを探す</div>
    <div class="input-group">
      <input name="q" value="{{ $q }}" class="form-control" placeholder="名前 / タグ で検索">
      <button class="btn btn-dark">検索</button>
    </div>

    @if(!empty($selectedId))
      <input type="hidden" name="customer_id" value="{{ $selectedId }}">
    @endif
  </form>

  <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">お客さんを選ぶ</div>
      <div class="small text-muted">（{{ $customers->count() }}件）</div>
    </div>

    <div class="list-group list-group-flush">
      @forelse($customers as $c)
        @php $isSelected = (string)$c['id'] === (string)$selectedId; @endphp

        <label class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer;">
          <div class="d-flex gap-2 align-items-start">
            <input class="form-check-input mt-1" type="radio" name="customer_pick" {{ $isSelected ? 'checked' : '' }}>
            <div>
              <div class="fw-semibold">{{ $c['name'] }}</div>
              <div class="small text-muted">最終来店：{{ $c['last_visit'] }}（{{ $c['days_since_last_visit'] }}日）</div>
              <div class="mt-1 d-flex gap-1 flex-wrap">
                @foreach($c['tag'] as $t)
                  <span class="badge text-bg-light">{{ $t }}</span>
                @endforeach
              </div>
            </div>
          </div>

          <a class="btn btn-outline-dark btn-sm rounded-pill"
             href="{{ route('crm.memos.quick', ['q' => $q, 'customer_id' => $c['id']]) }}">
            選択
          </a>
        </label>
      @empty
        <div class="text-muted">該当なし</div>
      @endforelse
    </div>
  </div>

  <div class="card p-3">
    <div class="fw-bold mb-2">メモを書く</div>

    <div class="mb-2 small text-muted">
      選択中：
      @if(!empty($selectedId))
        <span class="fw-semibold">
          {{ optional($customers->firstWhere('id', (int)$selectedId))['name'] ?? '（一覧から選んでね）' }}
        </span>
      @else
        <span class="fw-semibold">（未選択）</span>
      @endif
    </div>

    <textarea class="form-control" rows="3" placeholder="例：仕事トラブルで今週は厳しそう。責めるのNG。"></textarea>

    <button class="btn btn-dark w-100 rounded-pill mt-3" type="button">
      保存（見た目だけ）
    </button>

    <div class="text-muted small mt-2">
      ※ 今はUIだけ。次のステップでPOST＋セッション保存 or DB保存にする。
    </div>
  </div>
@endsection