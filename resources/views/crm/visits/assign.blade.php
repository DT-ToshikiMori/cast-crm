@extends('layouts.crm')
@section('title', '来店を紐づけ')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
$visitIcons = ['来店'=>'bi-shop','同伴'=>'bi-cup-straw','アフター'=>'bi-moon-stars'];
@endphp

@section('content')

{{-- Visit context --}}
<div class="alert-glass mt-2">
  <div class="alert-icon"><i class="bi {{ $visitIcons[$visit->type] ?? 'bi-shop' }}"></i></div>
  <div class="alert-body">
    <div class="alert-title">{{ $visit->type }} <span style="font-size:13px;color:var(--text-secondary)">／ {{ $visit->time }}</span></div>
    <div class="alert-text">{{ $visit->memo ?: 'メモなし' }}</div>
  </div>
  <a href="{{ route('crm.visits.unassigned') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> 戻る</a>
</div>

{{-- Options --}}
<div class="card-glass">
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <a class="btn-glass" href="{{ route('crm.customer.create', ['from' => 'unassigned', 'visit_id' => $visit->id]) }}">
      <i class="bi bi-person-plus-fill"></i> 新しいお客さんを追加
    </a>
    <a class="btn-ghost" href="{{ route('crm.visits.unassigned') }}">後でやる</a>
  </div>
</div>

{{-- Search --}}
<form class="card-glass" method="get" action="{{ route('crm.visits.assign', $visit->id) }}">
  <div class="search-wrap">
    <i class="bi bi-search"></i>
    <input name="q" value="{{ $q }}" class="form-control" placeholder="名前・タグで検索">
  </div>
  @if(!empty($selectedCustomerId))
    <input type="hidden" name="customer_id" value="{{ $selectedCustomerId }}">
  @endif
</form>

{{-- Customer list --}}
<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-people"></i>
    お客さんを選ぶ
    <span style="margin-left:auto;font-size:13px;color:var(--text-muted)">{{ $customers->count() }}件</span>
  </div>

  @forelse($customers as $c)
    @php $isSelected = (string)$c->id === (string)$selectedCustomerId; @endphp
    <div class="customer-row" id="c-{{ $c->id }}" style="justify-content:space-between">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <div class="avatar avatar-sm" style="background:{{ $avatarColors[crc32($c->name) % count($avatarColors)] }}">{{ mb_substr($c->name, 0, 1) }}</div>
        <div style="flex:1;min-width:0">
          <div class="name">
            {{ $c->name }}
            @if($isSelected) <span class="tag tag-vip" style="font-size:10px">選択中</span> @endif
          </div>
          <div class="meta"><i class="bi bi-calendar-check"></i> {{ $c->last_visit ?? '-' }}（{{ $c->days_since_last_visit }}日）</div>
          <div class="tags-wrap">
            @foreach($c->tag as $t)
              <span class="tag {{ $t === 'VIP' ? 'tag-vip' : '' }}">{{ $t }}</span>
            @endforeach
          </div>
        </div>
      </div>
      <a class="{{ $isSelected ? 'btn-gold' : 'btn-glass' }}" style="font-size:13px;padding:8px 16px;min-height:36px"
         href="{{ route('crm.visits.assign', ['visitId' => $visit->id, 'q' => $q, 'customer_id' => $c->id]) }}#c-{{ $c->id }}">
        {{ $isSelected ? '選択中' : '選択' }}
      </a>
    </div>
  @empty
    <div class="empty-state">
      <i class="bi bi-search"></i>
      <div class="empty-title">該当なし</div>
    </div>
  @endforelse
</div>

{{-- Confirm --}}
<form class="card-glass" method="post" action="{{ route('crm.visits.assignStore', $visit->id) }}">
  @csrf
  <div class="card-title"><i class="bi bi-link-45deg"></i> 確認</div>

  <div style="font-size:14px;color:var(--text-secondary);margin-bottom:16px">
    紐づけ先：
    @if(!empty($selectedCustomerId))
      @php $sel = $customers->firstWhere('id', (int)$selectedCustomerId); @endphp
      <span style="display:inline-flex;align-items:center;gap:6px;font-weight:600;color:var(--text-primary)">
        @if($sel)
          <span class="avatar avatar-sm" style="background:{{ $avatarColors[crc32($sel->name) % count($avatarColors)] }};width:24px;height:24px;font-size:12px">{{ mb_substr($sel->name, 0, 1) }}</span>
          {{ $sel->name }}
        @else
          （一覧から選んでね）
        @endif
      </span>
      <input type="hidden" name="customer_id" value="{{ $selectedCustomerId }}">
    @else
      <span style="font-weight:600">（未選択）</span>
    @endif
  </div>

  <button class="btn-gold w-100" type="submit" {{ empty($selectedCustomerId) ? 'disabled style=opacity:0.5' : '' }}>
    <i class="bi bi-link-45deg"></i> 紐づける
  </button>
</form>
@endsection
