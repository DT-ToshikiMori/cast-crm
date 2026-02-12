@extends('layouts.crm')
@section('title', '設定')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
$userName = auth()->user()->name ?? 'ユーザー';
$pictureUrl = auth()->user()->line_picture_url;
@endphp

@section('content')

{{-- Profile --}}
<div class="card-glass mt-2">
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
    @if($pictureUrl)
      <img src="{{ $pictureUrl }}" alt="{{ $userName }}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;flex-shrink:0">
    @else
      <div class="avatar avatar-lg" style="background:{{ $avatarColors[crc32($userName) % count($avatarColors)] }}">{{ mb_substr($userName, 0, 1) }}</div>
    @endif
    <div>
      <div style="font-size:20px;font-weight:700">{{ $userName }}</div>
      <div style="font-size:13px;color:var(--text-secondary)">Sample Lounge</div>
    </div>
  </div>

  <div style="margin-bottom:20px">
    <label class="form-label"><i class="bi bi-person"></i> 表示名</label>
    <input class="form-control" value="{{ $userName }}">
  </div>

  <div>
    <label class="form-label"><i class="bi bi-shop"></i> 店名（任意）</label>
    <input class="form-control" value="Sample Lounge">
  </div>
</div>

{{-- Menu --}}
<div class="settings-list">
  <button class="settings-item" type="button">
    <i class="bi bi-download"></i>
    <span>データを書き出す</span>
    <i class="bi bi-chevron-right"></i>
  </button>
  <a class="settings-item" href="#">
    <i class="bi bi-file-text"></i>
    <span>利用規約</span>
    <i class="bi bi-chevron-right"></i>
  </a>
  <form method="post" action="{{ route('auth.logout') }}" style="display:contents">
    @csrf
    <button class="settings-item danger" type="submit">
      <i class="bi bi-box-arrow-right"></i>
      <span>ログアウト</span>
      <i class="bi bi-chevron-right"></i>
    </button>
  </form>
</div>
@endsection
