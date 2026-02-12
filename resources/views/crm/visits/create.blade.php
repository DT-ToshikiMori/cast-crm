@extends('layouts.crm')
@section('title', '来店を記録')

@section('content')

@if (session('status'))
  <div class="alert-success-glass mt-2"><i class="bi bi-check-circle-fill"></i> {{ session('status') }}</div>
@endif

@php $customerId = request('customer_id'); @endphp

@if($customerId)
  @php $customer = auth()->user()->customers()->find($customerId); @endphp
  @if($customer)
    <div class="alert-success-glass mt-2">
      <i class="bi bi-person-fill"></i>
      <span><strong>{{ $customer->name }}</strong> の来店を記録</span>
    </div>
  @endif
@endif

<form class="card-glass mt-2" method="post" action="{{ route('crm.visits.store') }}">
  @csrf
  @if($customerId)
    <input type="hidden" name="customer_id" value="{{ $customerId }}">
  @endif
  <div class="card-title"><i class="bi bi-calendar-plus"></i> 来店を記録</div>

  <div style="margin-bottom:20px">
    <label class="form-label"><i class="bi bi-tag"></i> 種別</label>
    <div class="type-selector">
      <label class="type-btn active" id="type-来店" onclick="selectType(this,'来店')">
        <i class="bi bi-shop"></i><span>来店</span>
      </label>
      <label class="type-btn" id="type-同伴" onclick="selectType(this,'同伴')">
        <i class="bi bi-cup-straw"></i><span>同伴</span>
      </label>
      <label class="type-btn" id="type-アフター" onclick="selectType(this,'アフター')">
        <i class="bi bi-moon-stars"></i><span>アフター</span>
      </label>
    </div>
    <input type="hidden" name="type" id="typeInput" value="来店">
  </div>

  <div style="margin-bottom:20px">
    <label class="form-label"><i class="bi bi-currency-yen"></i> 金額（任意）</label>
    <input name="amount" class="form-control" type="number" placeholder="30000">
  </div>

  <div style="margin-bottom:24px">
    <label class="form-label"><i class="bi bi-chat-text"></i> メモ</label>
    <textarea name="note" class="form-control" rows="3" placeholder="延長、会話内容、次の約束"></textarea>
  </div>

  <button class="btn-gold w-100" type="submit"><i class="bi bi-check2-circle"></i> 保存する</button>
</form>

<script>
function selectType(el, val) {
  document.querySelectorAll('.type-btn').forEach(function(b){b.classList.remove('active')});
  el.classList.add('active');
  document.getElementById('typeInput').value = val;
}
</script>
@endsection
