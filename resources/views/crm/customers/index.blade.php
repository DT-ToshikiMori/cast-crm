@extends('layouts.crm')
@section('title', 'お客さん一覧')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
$filterIcons = ['all'=>'bi-grid-fill','vip'=>'bi-star-fill','stale'=>'bi-clock-history','birthday'=>'bi-gift'];
$filterLabels = ['all'=>'全部','vip'=>'VIP','stale'=>'最近来てない','birthday'=>'誕生日あり'];
@endphp

@section('content')

<form class="card-glass" method="get" action="{{ route('crm.customers') }}">
  <div class="search-wrap mb-3">
    <i class="bi bi-search"></i>
    <input name="q" value="{{ $q }}" class="form-control" placeholder="名前・タグで検索">
  </div>
  <div class="filter-pills">
    @foreach($filterLabels as $key => $label)
      <a class="pill {{ $filter===$key ? 'active' : '' }}"
         href="{{ route('crm.customers', ['filter' => $key, 'q' => $q]) }}">
        <i class="bi {{ $filterIcons[$key] }}"></i> {{ $label }}
      </a>
    @endforeach
  </div>
</form>

<div style="margin-bottom:16px">
  <a class="btn-glass" href="{{ route('crm.customer.create') }}">
    <i class="bi bi-person-plus-fill"></i> お客さん追加
  </a>
</div>

<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-people-fill"></i>
    結果：{{ $customers->count() }}件
  </div>

  @forelse($customers as $c)
    @php
      $d = $c->days_since_last_visit;
      $dClass = $d >= 60 ? 'very-stale' : ($d >= 30 ? 'stale' : ($d >= 7 ? 'recent' : 'fresh'));
    @endphp
    <a href="{{ route('crm.customer.show', $c->id) }}" class="customer-row">
      <div class="avatar" style="background:{{ $avatarColors[crc32($c->name) % count($avatarColors)] }}">{{ mb_substr($c->name, 0, 1) }}</div>
      <div class="info">
        <div class="name">{{ $c->name }}</div>
        <div class="meta">
          <i class="bi bi-calendar-check"></i> {{ $c->last_visit ?? '-' }}
          @if($c->birthday) &middot; <i class="bi bi-gift"></i> {{ $c->birthday }} @endif
        </div>
        <div class="tags-wrap">
          @foreach($c->tag as $t)
            <span class="tag {{ $t === 'VIP' ? 'tag-vip' : '' }}">{{ $t }}</span>
          @endforeach
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <span class="days-ind {{ $dClass }}">{{ $d }}日</span>
        <i class="bi bi-chevron-right chevron"></i>
      </div>
    </a>
  @empty
    <div class="empty-state">
      <i class="bi bi-search"></i>
      <div class="empty-title">見つからなかった</div>
      <div class="empty-text">検索条件を変えてみて</div>
    </div>
  @endforelse
</div>
@endsection
