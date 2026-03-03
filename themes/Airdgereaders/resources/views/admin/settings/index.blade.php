@php
@endphp
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Settings</h2>
            <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <form class="w-2/3 items-center mt-0" action="#">
                    <label class="form-control-addon-within  rounded-full overflow-hidden">
                        <input type="text" class="form-control border-none" placeholder="Search">
                        <span class="flex items-center pr-4"><button type="button" class="dark:text-gray-700 dark:hover:text-primary text-secondary hover:text-primary btn btn-link la la-search text-2xl leading-none"></button></span>
                    </label>
                </form>
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                    </div>
                </div>
            </div>
        </div>



        <!-- Card Column -->
        <div class="lg:flex my-5">
            <div class="lg:w-1/2 pt-5 lg:pt-0">
                <div class="card p-5">
                    <h3 class="label">General Settings</h3>
                    <form action="{{ route('admin.settings.store') }}" method="POST" >
                        @method('post')
                        @csrf
                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Active Theme</label>
                            <input type="text" disabled readonly name="active_theme" value="{{ setting('active_theme') }}" class="form-control mt-2 pt-5" id="input-1" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Default Locale</label>
                            <input type="text" value="{{ setting('default_locale') }}" class="form-control mt-2 pt-5" name="default_locale" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Default Timezone</label>
                            <input type="text" value="{{ setting('default_timezone') }}" class="form-control mt-2 pt-5" name="default_timezone" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Enable Registrations</label>
                            <select name="enable_registrations" class="form-control mt-2 pt-5">
                                <option  value="0">No</option>
                                <option  value="1">Yes</option>
                            </select>
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="email_verification">Email Verification Required</label>
                            <select name="email_verification_required" class="form-control mt-2 pt-5">
                                <option  value="0">No (Direct Registration - 2022 Style)</option>
                                <option  value="1">Yes (Email Verification - 2026 Style)</option>
                            </select>
                        </div>

                        <div class="mt-10">
                            <button type="submit" class="btn btn_primary border-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/2 lg:p-4 pt-5 lg:pt-0">
                <div class="card p-5">
                    <h3 class="label">Exchange Rates</h3>
                    <form action="{{ route('admin.settings.store') }}" method="POST" >
                        @csrf
                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Default Currency</label>
                            <select name="default_currency" class="form-control mt-2 pt-5">
                                @foreach(setting('supported_currencies') as $currency)
                                    <option {{ setting('default_currency') == $currency ? 'selected' : '' }}  value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>

                         <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Naira Rate</label>
                            <input type="text" value="{{ setting('ngn_rate') }}" class="form-control mt-2 pt-5" name="ngn_rate" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Ranc Rate</label>
                            <input type="text" value="{{ setting('ranc_rate') }}" class="form-control mt-2 pt-5" name="ranc_rate" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Base Currency Rate (USD)</label>
                            <input type="text" value="{{ setting('usd_rate') }}" class="form-control mt-2 pt-5" name="usd_rate" placeholder="Enter text here">
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="btn btn_primary border-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/2 lg:p-4 pt-5 lg:pt-0">
                <div class="card p-5">
                    <h3 class="label">Charges</h3>
                    <form action="{{ route('admin.settings.store') }}" method="POST" >
                    @csrf
                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Ranc Per Read</label>
                            <input type="text" value="{{ setting('ranc_per_onread') }}" class="form-control mt-2 pt-5" name="ranc_per_onread" placeholder="Enter text here">
                        </div>

                         <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Ranc Per Download</label>
                            <input type="text" value="{{ setting('ranc_per_ondownload') }}" class="form-control mt-2 pt-5" name="ranc_per_ondownload" placeholder="Enter value">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Owner Percent on Read</label>
                            <input type="text" value="{{ setting('resource_owner_percent_onread') }}" class="form-control mt-2 pt-5" name="resource_owner_percent_onread" placeholder="Enter text here">
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Owner Percent on Download</label>
                            <input type="text" value="{{ setting('resource_owner_percent_ondownload') }}" class="form-control mt-2 pt-5" name="resource_owner_percent_ondownload" placeholder="Enter text here">
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="btn btn_primary border-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:flex my-5">
            <div class="lg:w-1/2 lg:p-4 pt-5 lg:pt-0">
                <div class="card p-5">
                    <h3 class="label">Integration</h3>
                    <form action="{{ route('admin.settings.store') }}" method="POST" >
                    @csrf
                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Tawk Widget</label>
                            <textarea type="text" value="{{ setting('tawk_widget') }}" class="form-control mt-2 pt-5" rows="3" name="tawk_widget" placeholder="Enter text here">{{ setting('tawk_widget') }}</textarea>
                        </div>

                        <div class="relative mt-10">
                            <label class="dark:bg-gray-900  absolute block bg-white top-0 ml-3 px-1 rounded " for="input-1">Mailchamp Settings</label>
                            <textarea type="text" value="{{ setting('mailchamp_settings') }}" class="form-control mt-2 pt-5" rows="3" name="mailchamp_settings" placeholder="Enter text here"></textarea>
                        </div>


                        <div class="mt-10">
                            <button type="submit" class="btn btn_primary border-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>


  @endSection
  @push('js')
  @endPush