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
      <div style="display:flex;gap:10px">
        <select name="birthday_month" class="form-select" style="flex:1">
          <option value="">月</option>
          @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ old('birthday_month') == $m ? 'selected' : '' }}>{{ $m }}月</option>
          @endfor
        </select>
        <select name="birthday_day" class="form-select" style="flex:1">
          <option value="">日</option>
          @for($d = 1; $d <= 31; $d++)
            <option value="{{ $d }}" {{ old('birthday_day') == $d ? 'selected' : '' }}>{{ $d }}日</option>
          @endfor
        </select>
      </div>
    </div>

    <div style="margin-bottom:20px">
      <label class="form-label"><i class="bi bi-tags"></i> タグ</label>
      <input type="hidden" name="tags" id="tagsInput" value="{{ old('tags') }}">
      @php $presetTags = ['VIP','太客','シャンパン','ワイン','同伴多い','アフター','指名','フリー','連絡先交換済み','要注意']; @endphp
      <div id="tagPicker" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px">
        @foreach($presetTags as $pt)
          <button type="button" class="pill tag-pick" data-tag="{{ $pt }}" onclick="toggleTag(this)">{{ $pt }}</button>
        @endforeach
      </div>
      <div style="display:flex;gap:8px">
        <input id="customTagInput" class="form-control" placeholder="その他のタグ" style="flex:1">
        <button type="button" class="btn-glass" onclick="addCustomTag()" style="white-space:nowrap;padding:10px 14px">追加</button>
      </div>
      <div id="customTags" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px"></div>
    </div>

    <div style="margin-bottom:24px">
      <label class="form-label"><i class="bi bi-sticky"></i> ひとことメモ（任意）</label>
      <textarea name="memo" class="form-control" rows="3" placeholder="例：山崎好き。次は週末。">{{ old('memo') }}</textarea>
    </div>

    <button class="btn-gold w-100" type="submit"><i class="bi bi-check-circle-fill"></i> 登録する</button>
  </form>
</div>

<script>
var selectedTags = [];
(function() {
  var old = document.getElementById('tagsInput').value;
  if (old) {
    old.split(',').forEach(function(t) {
      t = t.trim();
      if (!t) return;
      var btn = document.querySelector('.tag-pick[data-tag="'+t+'"]');
      if (btn) { btn.classList.add('active'); selectedTags.push(t); }
      else { addCustomTagValue(t); }
    });
  }
})();
function syncTags() { document.getElementById('tagsInput').value = selectedTags.join(','); }
function toggleTag(el) {
  var tag = el.dataset.tag;
  var idx = selectedTags.indexOf(tag);
  if (idx >= 0) { selectedTags.splice(idx, 1); el.classList.remove('active'); }
  else { selectedTags.push(tag); el.classList.add('active'); }
  syncTags();
}
function addCustomTag() {
  var input = document.getElementById('customTagInput');
  var tag = input.value.trim();
  if (!tag || selectedTags.indexOf(tag) >= 0) return;
  addCustomTagValue(tag);
  input.value = '';
}
function addCustomTagValue(tag) {
  selectedTags.push(tag);
  var el = document.createElement('button');
  el.type = 'button'; el.className = 'pill active'; el.textContent = tag + ' ✕';
  el.onclick = function() { selectedTags.splice(selectedTags.indexOf(tag), 1); el.remove(); syncTags(); };
  document.getElementById('customTags').appendChild(el);
  syncTags();
}
</script>
@endsection
