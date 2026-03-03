<div data-v-77f12232="" id="mobile_stats" class="row flex  mx-auto">
   <div data-v-77f12232="" class="col-xs-4 stati  flex flex-col md:flex-row justify-around list-group-item  text-center">Unique Reads: 
      <span data-v-77f12232="" class="d-block max-w-max badge badge-primary badge-pill  mx-auto ">{{ $resources->sum('read_count') }}</span>
   </div>
   <div data-v-77f12232=""class="col-xs-4 stati flex flex-col md:flex-row justify-around list-group-item  text-center">Downloads:
      <span data-v-77f12232="" class="d-block badge max-w-max badge-primary badge-pill mx-auto  ">{{ $resources->sum('download_count') }}</span>
   </div>
   <div data-v-77f12232="" class="col-xs-4 stati bg-clouds   flex flex-col md:flex-row justify-around list-group-item  text-center">Total Works:
        <span data-v-77f12232="" class="d-block max-w-max badge badge-primary badge-pill  mx-auto ">{{ $resources->count() }}</span>
   </div>
   <!-- <div data-v-77f12232="" class="col-xs-3 stati bg-clouds flex flex-col md:flex-row justify-around list-group-item text-center">Payouts
      <span data-v-77f12232="" class="d-block max-w-max badge badge-primary badge-pill  mx-auto  ">0</span>
   </div> -->
</div>
