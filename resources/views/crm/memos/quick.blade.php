@extends('layouts.crm')
@section('title', 'ひとことメモ')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
@endphp

@section('content')

@if (session('status'))
  <div class="alert-success-glass mt-2"><i class="bi bi-check-circle-fill"></i> {{ session('status') }}</div>
@endif

<div class="card-glass mt-2">
  <div class="card-title"><i class="bi bi-chat-square-text"></i> ひとことメモ</div>
  <div style="font-size:13px;color:var(--text-secondary)">
    LINE返した・電話した・約束が流れた…みたいな"関係性ログ"を残す用。
  </div>
</div>

{{-- Search --}}
<form class="card-glass" method="get" action="{{ route('crm.memos.quick') }}">
  <div class="search-wrap">
    <i class="bi bi-search"></i>
    <input name="q" value="{{ $q }}" class="form-control" placeholder="名前・タグで検索">
  </div>
  @if(!empty($selectedId))
    <input type="hidden" name="customer_id" value="{{ $selectedId }}">
  @endif
</form>

{{-- Customer selection --}}
<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-people"></i>
    お客さんを選ぶ
    <span style="margin-left:auto;font-size:13px;color:var(--text-muted)">{{ $customers->count() }}件</span>
  </div>

  @forelse($customers as $c)
    @php $isSelected = (string)$c->id === (string)$selectedId; @endphp
    <div class="customer-row" id="c-{{ $c->id }}" style="justify-content:space-between">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <div class="avatar avatar-sm" style="background:{{ $avatarColors[crc32($c->name) % count($avatarColors)] }}">{{ mb_substr($c->name, 0, 1) }}</div>
        <div>
          <div class="name">{{ $c->name }}</div>
          <div class="meta"><i class="bi bi-calendar-check"></i> {{ $c->last_visit ?? '-' }}（{{ $c->days_since_last_visit }}日）</div>
        </div>
      </div>
      <a class="{{ $isSelected ? 'btn-gold' : 'btn-glass' }}" style="font-size:13px;padding:8px 16px;min-height:36px"
         href="{{ route('crm.memos.quick', ['q' => $q, 'customer_id' => $c->id]) }}#c-{{ $c->id }}">
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

{{-- Memo form --}}
<form class="card-glass" method="post" action="{{ route('crm.memos.quickStore') }}">
  @csrf
  <div class="card-title"><i class="bi bi-sticky-fill"></i> メモを書く</div>

  <div style="font-size:14px;color:var(--text-secondary);margin-bottom:12px">
    選択中：
    @if(!empty($selectedId))
      @php $sel = $customers->firstWhere('id', (int)$selectedId); @endphp
      <span style="display:inline-flex;align-items:center;gap:6px;font-weight:600;color:var(--text-primary)">
        @if($sel)
          <span class="avatar avatar-sm" style="background:{{ $avatarColors[crc32($sel->name) % count($avatarColors)] }};width:24px;height:24px;font-size:12px">{{ mb_substr($sel->name, 0, 1) }}</span>
          {{ $sel->name }}
        @endif
      </span>
      <input type="hidden" name="customer_id" value="{{ $selectedId }}">
    @else
      <span style="font-weight:600">（未選択）</span>
    @endif
  </div>

  <textarea name="text" class="form-control" rows="3" placeholder="例：仕事トラブルで今週は厳しそう。責めるのNG。" style="margin-bottom:16px"></textarea>

  <button class="btn-gold w-100" type="submit" {{ empty($selectedId) ? 'disabled style=opacity:0.5' : '' }}>
    <i class="bi bi-save"></i> 保存する
  </button>
</form>
@endsection
