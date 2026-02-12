@extends('layouts.crm')
@section('title', $customer->name.' の詳細')

@php
$avatarColors = ['#9b59b6','#e91e63','#3498db','#1abc9c','#e67e22','#e74c3c'];
$visitIcons = ['来店'=>'bi-shop','同伴'=>'bi-cup-straw','アフター'=>'bi-moon-stars'];
$presetTags = ['VIP','太客','シャンパン','ワイン','同伴多い','アフター','指名','フリー','連絡先交換済み','要注意'];
@endphp

@section('content')

@if (session('status'))
  <div class="alert-success-glass mt-2"><i class="bi bi-check-circle-fill"></i> {{ session('status') }}</div>
@endif

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
    <button class="action-btn" type="button" onclick="togglePanel('birthdayPanel')"><i class="bi bi-gift"></i> 誕生日編集</button>
    <button class="action-btn" type="button" onclick="togglePanel('tagPanel')"><i class="bi bi-tags-fill"></i> タグ編集</button>
    <a class="action-btn" href="{{ route('crm.visits.create', ['customer_id' => $customer->id]) }}"><i class="bi bi-calendar-plus"></i> 来店記録</a>
    <a class="action-btn" href="{{ route('crm.memos.quick', ['customer_id' => $customer->id]) }}"><i class="bi bi-chat-text"></i> メモ追加</a>
  </div>
</div>

{{-- Birthday Edit Panel --}}
<div class="card-glass" id="birthdayPanel" style="display:none">
  <form method="post" action="{{ route('crm.customer.update', $customer->id) }}">
    @csrf
    <input type="hidden" name="field" value="birthday">
    <div class="card-title"><i class="bi bi-gift"></i> 誕生日を編集</div>
    @php
      $bMonth = $customer->birthday ? (int)explode('-', $customer->birthday)[0] : '';
      $bDay = $customer->birthday ? (int)explode('-', $customer->birthday)[1] : '';
    @endphp
    <div style="display:flex;gap:10px;margin-bottom:16px">
      <select name="birthday_month" class="form-select" style="flex:1">
        <option value="">月</option>
        @for($m = 1; $m <= 12; $m++)
          <option value="{{ $m }}" {{ $bMonth == $m ? 'selected' : '' }}>{{ $m }}月</option>
        @endfor
      </select>
      <select name="birthday_day" class="form-select" style="flex:1">
        <option value="">日</option>
        @for($d = 1; $d <= 31; $d++)
          <option value="{{ $d }}" {{ $bDay == $d ? 'selected' : '' }}>{{ $d }}日</option>
        @endfor
      </select>
    </div>
    <button class="btn-gold w-100" type="submit"><i class="bi bi-check2"></i> 保存</button>
  </form>
</div>

{{-- Tag Edit Panel --}}
<div class="card-glass" id="tagPanel" style="display:none">
  <form method="post" action="{{ route('crm.customer.update', $customer->id) }}">
    @csrf
    <input type="hidden" name="field" value="tags">
    <input type="hidden" name="tags" id="editTagsInput" value="{{ implode(',', $customer->tag ?? []) }}">
    <div class="card-title"><i class="bi bi-tags-fill"></i> タグを編集</div>
    <div id="editTagPicker" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px">
      @foreach($presetTags as $pt)
        <button type="button" class="pill edit-tag-pick {{ in_array($pt, $customer->tag ?? []) ? 'active' : '' }}" data-tag="{{ $pt }}" onclick="editToggleTag(this)">{{ $pt }}</button>
      @endforeach
    </div>
    <div style="display:flex;gap:8px;margin-bottom:10px">
      <input id="editCustomTagInput" class="form-control" placeholder="その他のタグ" style="flex:1">
      <button type="button" class="btn-glass" onclick="editAddCustomTag()" style="white-space:nowrap;padding:10px 14px">追加</button>
    </div>
    <div id="editCustomTags" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px"></div>
    <button class="btn-gold w-100" type="submit"><i class="bi bi-check2"></i> 保存</button>
  </form>
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

<script>
function togglePanel(id) {
  var el = document.getElementById(id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// Tag editing
var editTags = {!! json_encode($customer->tag ?? []) !!};
// Load custom tags (not in presets) on init
(function() {
  var presets = {!! json_encode($presetTags) !!};
  editTags.forEach(function(t) {
    if (presets.indexOf(t) < 0) editAddCustomTagValue(t);
  });
})();
function editSyncTags() { document.getElementById('editTagsInput').value = editTags.join(','); }
function editToggleTag(el) {
  var tag = el.dataset.tag;
  var idx = editTags.indexOf(tag);
  if (idx >= 0) { editTags.splice(idx, 1); el.classList.remove('active'); }
  else { editTags.push(tag); el.classList.add('active'); }
  editSyncTags();
}
function editAddCustomTag() {
  var input = document.getElementById('editCustomTagInput');
  var tag = input.value.trim();
  if (!tag || editTags.indexOf(tag) >= 0) return;
  editAddCustomTagValue(tag);
  input.value = '';
}
function editAddCustomTagValue(tag) {
  if (editTags.indexOf(tag) < 0) editTags.push(tag);
  var el = document.createElement('button');
  el.type = 'button'; el.className = 'pill active'; el.textContent = tag + ' ✕';
  el.onclick = function() { editTags.splice(editTags.indexOf(tag), 1); el.remove(); editSyncTags(); };
  document.getElementById('editCustomTags').appendChild(el);
  editSyncTags();
}
</script>
@endsection
