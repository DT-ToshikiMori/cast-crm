@extends('layouts.crm')
@section('title', 'リマインド')

@section('content')

<div class="card-glass mt-2">
  <div class="card-title"><i class="bi bi-gift-fill"></i> 誕生日</div>

  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" checked id="bd7">
    <label class="form-check-label" for="bd7"><i class="bi bi-bell text-gold"></i> 7日前に通知</label>
  </div>
  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" checked id="bd1">
    <label class="form-check-label" for="bd1"><i class="bi bi-bell text-gold"></i> 前日に通知</label>
  </div>
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" checked id="bd0">
    <label class="form-check-label" for="bd0"><i class="bi bi-bell-fill text-gold"></i> 当日に通知</label>
  </div>
</div>

<div class="card-glass">
  <div class="card-title"><i class="bi bi-clock-history"></i> 再来店</div>

  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" checked id="rv30">
    <label class="form-check-label" for="rv30"><i class="bi bi-bell text-gold"></i> 30日来店なし</label>
  </div>
  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" id="rv45">
    <label class="form-check-label" for="rv45"><i class="bi bi-bell text-gold"></i> 45日来店なし</label>
  </div>
  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" id="rv60">
    <label class="form-check-label" for="rv60"><i class="bi bi-bell text-gold"></i> 60日来店なし</label>
  </div>

  <div class="section-divider"></div>

  <div>
    <label class="form-label"><i class="bi bi-alarm"></i> 通知時間</label>
    <input class="form-control" type="time" value="22:00">
  </div>
</div>
@endsection
