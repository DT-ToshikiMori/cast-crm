@extends('layouts.crm')
@section('title', 'ホーム')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
@endphp

@section('content')

@if($unassignedCount > 0)
<div class="alert-glass mt-2">
  <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
  <div class="alert-body">
    <div class="alert-title">未整理の来店が{{ $unassignedCount }}件あります</div>
    <div class="alert-text">あとでお客さんを選べます</div>
  </div>
  <a href="{{ route('crm.visits.unassigned') }}" class="btn-glass" style="white-space:nowrap">
    整理する <i class="bi bi-arrow-right"></i>
  </a>
</div>
@endif

<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-calendar-event"></i>
    今日の予定
    <span class="tag" style="margin-left:auto">{{ now()->format('n/j') }}</span>
  </div>

  <div class="section-divider"></div>

  <div style="font-size:13px;color:var(--text-secondary);font-weight:600;margin-bottom:12px">
    <i class="bi bi-gift-fill text-gold"></i> 誕生日が近い
  </div>

  @forelse($birthdaySoon as $c)
    <a href="{{ route('crm.customer.show', $c->id) }}" class="customer-row">
      <div class="avatar" style="background:{{ $avatarColors[crc32($c->name) % count($avatarColors)] }}">{{ mb_substr($c->name, 0, 1) }}</div>
      <div class="info">
        <div class="name">{{ $c->name }}</div>
        <div class="meta"><i class="bi bi-gift"></i> {{ $c->birthday ?? '-' }}</div>
      </div>
      <span class="tag tag-warning"><i class="bi bi-bell-fill"></i> リマインド</span>
    </a>
  @empty
    <div class="empty-state">
      <i class="bi bi-emoji-smile"></i>
      <div class="empty-title">誕生日の予定なし</div>
      <div class="empty-text">のんびりしてね</div>
    </div>
  @endforelse
</div>

<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-clock-history"></i>
    最近来てない（優先）
  </div>

  @forelse($stale as $c)
    @php
      $d = $c->days_since_last_visit;
      $dClass = $d >= 60 ? 'very-stale' : ($d >= 30 ? 'stale' : ($d >= 7 ? 'recent' : 'fresh'));
    @endphp
    <a href="{{ route('crm.customer.show', $c->id) }}" class="customer-row">
      <div class="avatar" style="background:{{ $avatarColors[crc32($c->name) % count($avatarColors)] }}">{{ mb_substr($c->name, 0, 1) }}</div>
      <div class="info">
        <div class="name">{{ $c->name }}</div>
        <div class="meta"><i class="bi bi-calendar-check"></i> {{ $c->last_visit ?? '-' }}</div>
      </div>
      <span class="days-ind {{ $dClass }}">{{ $d }}日</span>
    </a>
  @empty
    <div class="empty-state">
      <i class="bi bi-emoji-smile"></i>
      <div class="empty-title">みんな来てくれてる！</div>
    </div>
  @endforelse
</div>
@endsection
