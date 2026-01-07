@extends('layouts.crm')
@section('title', 'リマインド')

@section('content')
  <div class="card p-3 mb-3">
    <div class="fw-bold mb-2">誕生日</div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" checked>
      <label class="form-check-label">7日前に通知</label>
    </div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" checked>
      <label class="form-check-label">前日に通知</label>
    </div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" checked>
      <label class="form-check-label">当日に通知</label>
    </div>
  </div>

  <div class="card p-3">
    <div class="fw-bold mb-2">再来店</div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" checked>
      <label class="form-check-label">30日来店なし</label>
    </div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox">
      <label class="form-check-label">45日来店なし</label>
    </div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox">
      <label class="form-check-label">60日来店なし</label>
    </div>

    <div class="mt-3">
      <label class="form-label small text-muted">通知時間</label>
      <input class="form-control" value="22:00">
    </div>
  </div>
@endsection