@extends('layouts.crm')
@section('title', 'お客さんを追加')

@section('content')

@if(request('from') === 'unassigned')
  <div class="alert-glass mt-2">
    <div class="alert-icon"><i class="bi bi-link-45deg"></i></div>
    <div class="alert-body">
      <div class="alert-title">未整理の来店から追加中</div>
      <div class="alert-text">新しいお客さんとして追加して、来店へ紐づけます。</div>
    </div>
    <a class="btn-ghost" href="{{ route('crm.visits.assign', request('visit_id', 9001)) }}">
      <i class="bi bi-arrow-left"></i> 戻る
    </a>
  </div>
@endif

@if (session('status'))
  <div class="alert-success-glass"><i class="bi bi-check-circle-fill"></i> {{ session('status') }}</div>
@endif

@if ($errors->any())
  <div class="alert-danger-glass">
    <div style="font-weight:700;margin-bottom:6px"><i class="bi bi-exclamation-circle"></i> 入力を確認してね</div>
    <ul style="margin:0;padding-left:20px">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card-glass">
  <div class="card-title"><i class="bi bi-person-plus-fill"></i> お客さんを追加</div>

  <form method="post" action="{{ route('crm.customer.store') }}">
    @csrf
    @if(request('from') === 'unassigned' && request('visit_id'))
      <input type="hidden" name="from" value="unassigned">
      <input type="hidden" name="visit_id" value="{{ request('visit_id') }}">
    @endif

    <div style="margin-bottom:20px">
      <label class="form-label"><i class="bi bi-person"></i> 名前 / あだ名（必須）</label>
      <input name="name" class="form-control" value="{{ old('name') }}" placeholder="例：タクミ">
    </div>

    <div style="margin-bottom:20px">
      <label class="form-label"><i class="bi bi-gift"></i> 誕生日（任意）</label>
      <input name="birthday" class="form-control" value="{{ old('birthday') }}" placeholder="例：02-10">
      <div class="form-text">年は入れなくてOK</div>
    </div>

    <div style="margin-bottom:20px">
      <label class="form-label"><i class="bi bi-tags"></i> タグ（カンマ区切り）</label>
      <input name="tags" class="form-control" value="{{ old('tags') }}" placeholder="例：VIP, シャンパン, 同伴多い">
    </div>

    <div style="margin-bottom:24px">
      <label class="form-label"><i class="bi bi-sticky"></i> ひとことメモ（任意）</label>
      <textarea name="memo" class="form-control" rows="3" placeholder="例：山崎好き。次は週末。">{{ old('memo') }}</textarea>
    </div>

    <button class="btn-gold w-100" type="submit"><i class="bi bi-check-circle-fill"></i> 登録する</button>
  </form>
</div>
@endsection
