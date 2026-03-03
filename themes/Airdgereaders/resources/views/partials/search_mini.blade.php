@php
  $search = request()->query('search');
  $subfield = request()->query('subfield');
  $type = request()->query('type');
@endphp
<div class="s002">
    <form>
        <div class="inner-form">
          <div class="input-field first-wrap">
            <div class="icon-wrap"></div>
            <input id="mini-search" value="{{ $search ? $search : '' }}" type="text" placeholder="What are you looking for?" />
          </div>
          <div class="input-field third-wrap">
            <div class="icon-wrap"></div>
            <select data-trigger="" placeholder="subject" id="mini-subfield" name="subfield">
              <option value="{{ $subfield ? $subfield : '' }}" >{{ $subfield ? $subfield : 'Select subject' }}</option>
              <option value="" >All Subjects</option>
              @foreach($subfields as $subject)
                <option value="{{$subject->slug}}">{{$subject->title}}</option>
              @endforeach
            </select>
          </div>
          <div class="input-field fouth-wrap">
            <div class="icon-wrap"></div>
            <select data-trigger="" id="mini-type" name="choices-single-defaul">
              <option value="{{ $type ? $type : '' }}">{{ $type ? $type : 'Select Type' }}</option>
              <option value="">All Types</option>
              @foreach($resourceTypes as $type)
                <option>{{$type->title}}</option>
              @endforeach
            </select>
          </div>
          <div class="input-field fifth-wrap">
            <button id="submit-btn" onclick="onSubmitMiniSearchForm(event)" class="btn-search" type="button">SEARCH</button>
          </div>
        </div>
    </form>
</div>

<script type="text/javascript">
  function onSubmitMiniSearchForm(event) {
    event.preventDefault()
    var url = window.location.href.split('?')[0];
    var search = document.getElementById('mini-search').value;
    var type = document.getElementById('mini-type').value;
    var subfield = document.getElementById('mini-subfield').value;
    window.location.href = `${url}/?search=${search}&subfield=${subfield}&type=${type}`
  }
</script>

