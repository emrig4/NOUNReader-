@php
  $search = request()->query('search');
  $field = request()->query('field');
  $subfield = request()->query('subfield');
  $type = request()->query('type');
@endphp
<div class="s009">
  <form action="{{ route('resources.search') }}" method="GET">
    <div class="inner-form">
      <div class="basic-search">
        <div class="input-field">
          <input id="mega-search" name="search" value="{{ $search ? $search : '' }}" type="text" placeholder="Type Keywords" />
          <div class="icon-wrap">
            <svg class="svg-inline--fa fa-search fa-w-16" fill="#23A455" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
            </svg>
          </div>
        </div>
      </div>
      <div class="advance-search">
        <!-- <span class="desc">ADVANCED SEARCH</span> -->
        <div class="row">
          <!-- <div class="input-field"> -->
           <!--  <div class="input-select">
              <select data-trigger="" id="mega-field" name="field" >
                <option placeholder="" value="{{ $field ? $field : '' }}">{{ $field ? $field : 'Select Field' }}</option>
                <option placeholder="" value="">All Fields</option>
                <option value="History">History</option>
                <option value="Humanities">Humanities</option>
                <option>Antropology</option>
              </select>
            </div> -->
         <!--  </div>
          <div class="input-field">
            <div class="input-select">
              <select data-trigger="" id="mega-subfield" name="subfield">
                <option value="{{ $subfield ? $subfield : '' }}" >{{ $subfield ? $subfield : 'Select subject' }}</option>
                <option placeholder="" value="">All Subfields</option>
                <option>Physics</option>
                <option>Chemistry</option>
              </select>
            </div>
          </div> -->
          <div class="input-field">
            <div class="input-select">
              <select data-trigger="" id="mega-type" name="type">
                <option value="{{ $type ? $type : '' }}">{{ $type ? $type : 'Select Resource Type' }}</option>
                <option placeholder="" value="">All Resource Types</option>
                @foreach($resourceTypes as $type)
                <option>{{$type->title}}</option>
                @endforeach
              </select>
            </div>
          </div>
        <!-- </div> -->
       <!--  -->
        <div class="row third">
          <div class="input-field">
            <div class="result-count">
              <!-- <span>108 </span>results -->
            </div>
            <div class="group-btn">
             
              <button  data-url="{{ route('resources.search') }}" id="url" class="btn-search"  onclick="onSubmitMegaSearchForm(event)">SEARCH</button>

               <button  class="btn-search bg-gray-100 px-4 "  id="delete" data-dismiss="modal" aria-label="Close" >CLOSE</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  function onSubmitMegaSearchForm(event) {
    event.preventDefault()
    var url =  document.getElementById('url').dataset.url; //window.location.origin + '/resources';
    url.replace('public/g','')
    console.log(url);
    let search = document.getElementById('mega-search').value;
    let field = '' //document.getElementById('mega-field').value;
    let type = document.getElementById('mega-type').value;
    let subfield = '' //document.getElementById('mega-subfield').value;
    location.replace(`${url}/?search=${search}&field=${field}&subfield=${subfield}&type=${type}`)

  }
</script>
