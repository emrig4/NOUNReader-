@extends('layouts.auth')
    @push('css')
        <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/ms-form.css') }}" rel="stylesheet">
    @endPush
    @section('content')
        <div class="container-fluid mt-5">
            <div class="row justify-content-center">
                <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-6 text-center p-0 mt-3 mb-2">
                    <div class="card px-4 pt-4 pb-0 mt-3 mb-3">
                        <h3 id="heading">Student Account</h3>
                        <p>Fill all form field to go to next step</p>
                        <form id="msform" name="student-signup-msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li id="school" class="active"><strong>School</strong></li>
                                <li id="academic"><strong>Academics</strong></li>
                                <li id="personal"><strong>Personal</strong></li>
                                 <!-- <li id="payment"><strong>Image</strong></li> -->
                                 <li id="account"><strong>Account</strong></li>
                                <li id="confirm"><strong>Terms</strong></li>
                            </ul>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> <br> 
                            <!-- fieldsets -->

                            <!-- School -->
                            <fieldset>
                                <div class="form-card">
                                    <label class="fieldlabels">Choose School: *</label>
                                    <!-- <input type="text" name="school" list="school-list">
                                    <datalist id="school-list">
                                        foreach($schools as $school)
                                            <option value="">
                                        endForeach
                                    </datalist> -->
                                    <select class="form-control" id="school-list" name="school_id" required="required">
                                        <option value="">Select</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                                        @endForeach
                                    </select>
                                </div>
                                <p class="mt-3">Can't find your school? <span><a class="text-danger" href="">make a request</a></span></p>
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>

                            <!-- Academic -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">School/Faculty: *</label>
                                        <input type="text" name="falculty" placeholder="School/Faculty" />
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Department: *</label>
                                        <input type="text" name="department" placeholder="Department" />
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Program Type: *</label>
                                        <select class="form-control w-100" name="program_type" required="">
                                            <option value="">Select</option>
                                            <option value="undergraduate">Undergraduate</option>
                                            <option value="postgraduate">Postgraduate</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Current Level: *</label>
                                        <input type="text" name="level" placeholder="Level *e.g 100" />
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" /> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset>

                            <!-- Personal -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Full Name: *</label>
                                        <input type="text" name="name" placeholder="Full Name" required="" />
                                    </div>

                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Contact No.: *</label>
                                        <input type="text" name="phone" placeholder="Contact No." />
                                    </div>

                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Alternate Contact No.: *</label>
                                        <input type="text" name="phone_2" placeholder="Alternate Contact No." />
                                    </div>

                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Prefered Pronoun: *</label>
                                        <select class="form-control mb-2 w-100" name="pronoun">
                                            <option>Select</option>
                                            <option>He/Him</option>
                                            <option>She/Her</option>
                                            <option>They/Them</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" />
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset>


                            <!-- Image -->
                            <!-- <fieldset>
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="fs-title">Image Upload:</h2>
                                        </div>
                                        <div class="col-5">
                                            <h2 class="steps">Step 5 - 5</h2>
                                        </div>
                                    </div>
                                    <label class="fieldlabels">Upload Your Photo:</label>
                                    <input type="file" name="pic" accept="image/*">
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" />
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset> -->

                             <!-- Account -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Email: *</label>
                                        <input type="email" name="email" placeholder="Email" />
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Username: *</label>
                                        <input type="text" name="username" placeholder="Username" />
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Password: *</label>
                                        <input type="password" name="password" id="password" placeholder="Password" />
                                    </div>
                                    <div class="input-group col-md-6">
                                        <label class="fieldlabels">Confirm Password: *</label>
                                        <input type="password" name="confirm_password" placeholder="Confirm Password" />
                                    </div>
                                </div> 
                                <input type="button" name="next" class="next action-button" value="Next" />
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset>

                            <!-- Terms -->
                            <fieldset>
                                <div class="form-card">
                                    <div class="px-3 row justify-content-center" style="height: 150px; overflow-y: scroll;">
                                        <!-- <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div> -->
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum. eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5 class="purple-text text-center"></h5>
                                        </div>
                                    </div>

                                    <div class="">
                                        <input class="form-check-input" type="checkbox" name="terms" style="width: 60px" value="accepted" id="terms"> 
                                        <label class="form-check-label" style="margin-left:30px" for="flexCheckDefault">
                                           I have read and accept Lnaid Terms
                                        </label>
                                    </div>

                                </div>

                                <!-- <div id="ajaxLoading" style="position: relative; top: 60px"> -->
                                    <img id="ajaxLoading" src="{{asset('assets/img/spinners/preloader.gif')}} " title="working..." />
                                <!-- </div> -->
                                <input type="button" id="submit" name="next" class="action-button" value="Submit" />
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endSection
    @push('js')
        <script src=" {{ asset('assets/js/jquery.validate.min.js')}} "></script>
        <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
        <script src="{{ asset('assets/js/ms-script.js') }}"></script>

        @include('partials.error', ['position' => 'toast-bottom-left' ])
        @include('partials.flash', ['position' => 'toast-bottom-left', 'timeout' => 1000 ])



    @endPush


