@extends('layouts.crm')
@section('title', '未整理の来店ログ')

@php
$visitIcons = ['来店'=>'bi-shop','同伴'=>'bi-cup-straw','アフター'=>'bi-moon-stars'];
@endphp

@section('content')

@if (session('status'))
  <div class="alert-success-glass mt-2"><i class="bi bi-check-circle-fill"></i> {{ session('status') }}</div>
@endif

<div class="card-glass mt-2">
  <div class="card-title"><i class="bi bi-info-circle-fill"></i> 未整理の来店ログ</div>
  <div style="font-size:13px;color:var(--text-secondary)">
    忙しいときに記録した来店です。あとでお客さんを選んで整理できます。
  </div>
</div>

<div class="card-glass">
  <div class="card-title">
    <i class="bi bi-exclamation-triangle"></i>
    未整理（{{ $unassignedVisits->count() }}件）
  </div>

  @forelse($unassignedVisits as $v)
    <div style="padding:16px 0;{{ !$loop->first ? 'border-top:1px solid var(--glass-border)' : '' }}">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <div style="display:flex;align-items:center;gap:8px">
          <i class="bi {{ $visitIcons[$v->type] ?? 'bi-shop' }} text-gold" style="font-size:18px"></i>
          <span style="font-weight:600">{{ $v->type }}</span>
          <span style="font-size:13px;color:var(--text-muted)"><i class="bi bi-clock"></i> {{ $v->time }}</span>
        </div>
        <span class="tag tag-warning"><i class="bi bi-exclamation-triangle"></i> 未整理</span>
      </div>

      <div style="font-size:13px;color:var(--text-secondary);margin-bottom:12px">
        <i class="bi bi-chat-dots"></i> {{ $v->memo ?: 'メモなし' }}
      </div>

      <div style="display:flex;gap:10px">
        <a class="btn-glass" href="{{ route('crm.visits.assign', $v->id) }}">
          <i class="bi bi-person-check"></i> お客さんを選ぶ
        </a>
        <button class="btn-ghost" type="button">後でやる</button>
      </div>
    </div>
  @empty
    <div class="empty-state">
      <i class="bi bi-check-circle"></i>
      <div class="empty-title">すべて整理済み！</div>
    </div>
  @endforelse
</div>
@endsection
