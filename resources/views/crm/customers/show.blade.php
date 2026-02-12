@extends('layouts.crm')
@section('title', $customer->name.' の詳細')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
$visitIcons = ['来店'=>'bi-shop','同伴'=>'bi-cup-straw','アフター'=>'bi-moon-stars'];
@endphp

@section('content')

{{-- Hero --}}
<div class="card-glass">
  <div style="display:flex;gap:16px;align-items:center">
    <div class="avatar avatar-lg" style="background:{{ $avatarColors[crc32($customer->name) % count($avatarColors)] }}">{{ mb_substr($customer->name, 0, 1) }}</div>
    <div style="flex:1">
      <div style="font-size:22px;font-weight:700">{{ $customer->name }}</div>
      <div style="font-size:13px;color:var(--text-secondary);margin-top:4px">
        <i class="bi bi-calendar-check"></i> 最終：{{ $customer->last_visit ?? '-' }}（{{ $customer->days_since_last_visit }}日）
        @if($customer->birthday) &middot; <i class="bi bi-gift"></i> {{ $customer->birthday }} @endif
      </div>
      <div class="tags-wrap">
        @foreach($customer->tag as $t)
          <span class="tag {{ $t === 'VIP' ? 'tag-vip' : '' }}">{{ $t }}</span>
        @endforeach
      </div>
    </div>
  </div>

  <div class="actions-grid">
    <button class="action-btn" type="button"><i class="bi bi-chat-fill"></i> LINE送る</button>
    <button class="action-btn" type="button"><i class="bi bi-gift"></i> 誕生日編集</button>
    <button class="action-btn" type="button"><i class="bi bi-tags-fill"></i> タグ編集</button>
    <a class="action-btn" href="{{ route('crm.visits.create', ['customer_id' => $customer->id]) }}"><i class="bi bi-calendar-plus"></i> 来店記録</a>
  </div>
</div>

{{-- Memos --}}
<div class="card-glass">
  <div class="card-title"><i class="bi bi-sticky-fill"></i> メモ</div>

  @forelse($customer->memo as $m)
    <div style="padding:12px 0;{{ !$loop->first ? 'border-top:1px solid var(--glass-border)' : '' }}">
      <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px"><i class="bi bi-calendar3"></i> {{ $m->date }}</div>
      <div style="font-size:15px">{{ $m->text }}</div>
    </div>
  @empty
    <div class="empty-state">
      <i class="bi bi-sticky"></i>
      <div class="empty-title">まだメモなし</div>
    </div>
  @endforelse
</div>

{{-- Visit History --}}
<div class="card-glass">
  <div class="card-title"><i class="bi bi-clock-history"></i> 来店履歴</div>

  @forelse($customer->visits as $v)
    <div style="padding:12px 0;{{ !$loop->first ? 'border-top:1px solid var(--glass-border)' : '' }}">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;align-items:center;gap:8px">
          <i class="bi {{ $visitIcons[$v->type] ?? 'bi-shop' }} text-gold"></i>
          <span style="font-weight:600">{{ $v->type }}</span>
        </div>
        <span style="font-size:13px;color:var(--text-muted)">{{ $v->date }}</span>
      </div>
      <div style="font-size:13px;color:var(--text-secondary);margin-top:4px">
        @if($v->amount)<i class="bi bi-currency-yen"></i> {{ number_format($v->amount) }}@endif
        @if($v->note) &middot; {{ $v->note }}@endif
      </div>
    </div>
  @empty
    <div class="empty-state">
      <i class="bi bi-clock-history"></i>
      <div class="empty-title">来店履歴なし</div>
    </div>
  @endforelse
</div>
@endsection
