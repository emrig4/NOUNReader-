<div class="ereaders-detail-thumb-text">
    <h2>{{ $resource->title }}</h2>
    <div class="star-rating"><span class="star-rating-box" style="width: {{$resource->rating() }}%">></span></div>
    <small>( {{ count($resource->reviews) }} )</small>
    <div class="clearfix"></div>
   

    <!-- @if( session('currency') ) -->

   <!--  <span>{{ $resource->price ? session('currency') : '' }} {{ $resource->price ? currency_exchange($resource->price, $resource->currency, session('currency') )  : 'Free' }}</span> -->
    <!-- 
    @else
        <span>{{ $resource->currency }} {{ $resource->price ? $resource->price  : 'Free' }}</span> 
    @endif -->
     <div class="clearfix"></div>
    <div class="mb-5 max-h-72 overflow-hidden" style="text-align: justify;">
        {!! $resource->overview !!}
    </div>
   

   
    
    
    <ul class="ereaders-detail-social">
        <!-- <li><h6>Share:</h6></li> -->
        <!-- <li><a href="https://www.facebook.com/sharer/sharer.php?u={{route('resources.show', $resource->slug )}}" class="fa fa-facebook"></a></li> -->
        <!-- <li><a href="https://twitter.com/login?lang=en" class="fa fa-twitter"></a></li> -->
    </ul>
</div>


<div class="modal fade right" id="reportresourcemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    @includeWhen(auth()->user(), 'partials.reportresource_modal')
</div>

 <div class="modal fade right" id="downloadresourcemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    @if(auth()->user() )
        @includeWhen(auth()->user(), 'partials.downloadresource_modal')
    @else
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div  class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                            <div class="flex justify-between items-center space-x-5">
                                <div class="flex items-center space-x-5">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full  sm:mx-0 sm:h-10 sm:w-10" style="background: whitesmoke">
                                        <!-- Heroicon name: outline/exclamation -->

                                        <svg class="h-6 w-6 " style="color: #8d94a0;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="122.88px" height="122.878px" viewBox="0 0 122.88 122.878" enable-background="new 0 0 122.88 122.878" xml:space="preserve">
                                            <g>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M101.589,14.7l8.818,8.819c2.321,2.321,2.321,6.118,0,8.439l-7.101,7.101 c1.959,3.658,3.454,7.601,4.405,11.752h9.199c3.283,0,5.969,2.686,5.969,5.968V69.25c0,3.283-2.686,5.969-5.969,5.969h-10.039 c-1.231,4.063-2.992,7.896-5.204,11.418l6.512,6.51c2.321,2.323,2.321,6.12,0,8.44l-8.818,8.819c-2.321,2.32-6.119,2.32-8.439,0 l-7.102-7.102c-3.657,1.96-7.601,3.456-11.753,4.406v9.199c0,3.282-2.685,5.968-5.968,5.968H53.629 c-3.283,0-5.969-2.686-5.969-5.968v-10.039c-4.063-1.232-7.896-2.993-11.417-5.205l-6.511,6.512c-2.323,2.321-6.12,2.321-8.441,0 l-8.818-8.818c-2.321-2.321-2.321-6.118,0-8.439l7.102-7.102c-1.96-3.657-3.456-7.6-4.405-11.751H5.968 C2.686,72.067,0,69.382,0,66.099V53.628c0-3.283,2.686-5.968,5.968-5.968h10.039c1.232-4.063,2.993-7.896,5.204-11.418l-6.511-6.51 c-2.321-2.322-2.321-6.12,0-8.44l8.819-8.819c2.321-2.321,6.118-2.321,8.439,0l7.101,7.101c3.658-1.96,7.601-3.456,11.753-4.406 V5.969C50.812,2.686,53.498,0,56.78,0h12.471c3.282,0,5.968,2.686,5.968,5.969v10.036c4.064,1.231,7.898,2.992,11.422,5.204 l6.507-6.509C95.471,12.379,99.268,12.379,101.589,14.7L101.589,14.7z M61.44,36.92c13.54,0,24.519,10.98,24.519,24.519 c0,13.538-10.979,24.519-24.519,24.519c-13.539,0-24.519-10.98-24.519-24.519C36.921,47.9,47.901,36.92,61.44,36.92L61.44,36.92z" />
                                            </g>
                                        </svg>

                                    </div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Download Resource</h3>
                                </div>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="mt-8 text-center sm:ml-4 sm:text-left">

                                <div class="flex space-x-2 w-full mt-4">
                                    <p>Please login or create an account to access this resource</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a href="{{ route('login') }}" class="ereaders-detail-btn">Login</a>
                        <a href="{{ route('register') }}" class="ereaders-detail-btn">Register </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
