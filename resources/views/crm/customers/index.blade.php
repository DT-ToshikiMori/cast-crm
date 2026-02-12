@extends('layouts.crm')
@section('title', 'お客さん一覧')

@section('content')
  <form class="card p-3 mb-3" method="get" action="{{ route('crm.customers') }}">
    <div class="mb-2 fw-bold">検索</div>
    <div class="input-group">
      <input name="q" value="{{ $q }}" class="form-control" placeholder="名前 / タグ で検索">
      <button class="btn btn-dark">検索</button>
    </div>
    <div class="mt-2 d-flex gap-2 flex-wrap">
      @php $filters = ['all'=>'全部','vip'=>'VIP','stale'=>'最近来てない','birthday'=>'誕生日あり']; @endphp
      @foreach($filters as $key => $label)
        <a class="btn btn-sm {{ $filter===$key ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill"
           href="{{ route('crm.customers', ['filter' => $key, 'q' => $q]) }}">{{ $label }}</a>
      @endforeach
    </div>
  </form>
  <a class="btn btn-dark btn-sm rounded-pill mb-3" href="{{ route('crm.customer.create') }}">＋ お客さん追加</a>

  <div class="card p-3">
    <div class="fw-bold mb-2">結果：{{ $customers->count() }}件</div>

    @forelse($customers as $c)
      <a class="text-decoration-none" href="{{ route('crm.customer.show', $c['id']) }}">
        <div class="py-2 {{ !$loop->first ? 'border-top' : '' }}">
          <div class="d-flex justify-content-between align-items-center">
            <div class="fw-semibold text-dark">{{ $c['name'] }}</div>
            <div class="small text-muted">{{ $c['days_since_last_visit'] }}日</div>
          </div>
          <div class="small text-muted">最終来店：{{ $c['last_visit'] }} / 誕生日：{{ $c['birthday'] ?? '-' }}</div>
          <div class="mt-1 d-flex gap-1 flex-wrap">
            @foreach($c['tag'] as $t)
              <span class="badge text-bg-light">{{ $t }}</span>
            @endforeach
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted">見つからなかった</div>
    @endforelse
  </div>
@endsection