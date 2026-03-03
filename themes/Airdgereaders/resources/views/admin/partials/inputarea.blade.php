@push('css')
<link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/ms-form.css') }}" rel="stylesheet">
@endPush

<div>
    <form id="msform" method="post" action="{{ $actionurl }}" name="student-signup-msform">
        @csrf
        <!-- progressbar -->
        <ul id="progressbar">
            <li id="formular" class="active"><strong>Enter Formular</strong></li>
            <!-- <li id="parameters"><strong>Parameters</strong></li> -->
            <!-- <li id="calculations"><strong>Calculations</strong></li> -->
            <li id="account"><strong>Accept Terms</strong></li>
            <!-- <li id="confirm"><strong>Results</strong></li> -->
        </ul>

        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div>
        </div> <br>
        <!-- fieldsets -->

        <!-- Formular -->
        <fieldset>
            <div class="form-card">
                <label class="fieldlabels">Enter Compound Formular: *</label>
                <input type="text" id="formularInput" onchange="processFormular(event)" name="formular" placeholder="Eg: H2O+Cu(NO3)2 + H2O - H12" required="" />
                <input type="hidden" id="parsedFormular" name="parsedformular" />

                <label class="fieldlabels">Enter Compound Density: *</label>
                <input type="text" id="densityInput" name="density" placeholder="Density in g/cm3 " required="" />

                <div class="p-4">
                    <h4 class="my-2 uppercase">Fraction By</h4>
                    <div class="flex space-x-4">
                        <label class="custom-radio">
                            <input onchange="processFormular()" type="radio" id="fractionMole" name="fraction_by" checked="" value="mole">
                            <span></span>
                            <span>Mole</span>
                        </label>
                        <label class="custom-radio">
                            <input onchange="processFormular()" type="radio" id="fractionWeight" name="fraction_by" value="weight">
                            <span></span>
                            <span>Weight</span>
                        </label>

                    </div>
                    <div class="lg:w-1/4 mt-3" id="weightfractionHolder">

                    </div>

                </div>

            </div>

            <pre id="resultHolder" class="h-20">

                                </pre>
            <p class="mt-3">Having issue with formular ? <span><a class="text-danger" href="">see guide</a></span></p>
            <input type="button" name="next" class="next action-button" value="Next" />
        </fieldset>

        <!-- parameters -->
        <!--  <fieldset>
                                <div class="form-card">
                                    <label class="fieldlabels">Enter Compound Density: *</label>
                                    <input type="text" id="densityInput" name="density" placeholder="Density in g/cm3 " required=""/>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" /> 
                                 <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                            </fieldset> -->

        <!-- Terms -->
        <fieldset>
            <div class="form-card">
                <div class="px-3 row justify-content-center" style="height: 250px; overflow-y: scroll;">
                    <!-- <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div> -->
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
                    <input class="form-check-input" type="checkbox" name="terms" style="width: 60px" value="accepted" id="terms" required="">
                    <label class="form-check-label" style="margin-left:30px" for="flexCheckDefault">
                        I have read and accept Lnaid Terms
                    </label>
                </div>

            </div>

            <div id="ajaxLoading" style="position: relative; top: 60px">
                <img id="ajaxLoading" src="{{asset('assets/images/spinners/preloader.gif')}} " title="working..." />
            </div>
            <button type="submit" class="action-button" value="Submit">SUBMIT</button>
            <!-- <input type="button" id="submit" name="next" class="action-button" value="Submit" /> -->
            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
        </fieldset>

        <!-- result -->
        <fieldset>
            <div class="form-card">

            </div>
            <input type="button" name="next" class="next action-button" value="Next" />
            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
        </fieldset>
    </form>
</div>


@push('js')

<script src=" {{ asset('assets/js/jquery.validate.min.js')}} "></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/ms-script.js') }}"></script>
<script src="{{ asset('assets/chemparse/molecular.js') }}"></script>

<script type="text/javascript">
function processFormular(event) {
    let formular = document.getElementById('formularInput').value
    let fractionWeight = document.getElementById('fractionWeight').checked
    let fractionMole = document.getElementById('fractionMole').checked
    let resultHolder = document.getElementById('resultHolder')
    let parsedFormularHolder = document.getElementById('parsedFormular')
    let weightfractionHolder = document.getElementById('weightfractionHolder')
    console.log('fractionWeight', fractionWeight)
    console.log('fractionMole', fractionMole)
    let parseFormularFunc = () => {
        let compounds = formular.split('+');
        let compoundGroups = []
        compounds.forEach((a) => {
            compoundGroups.push(parse(flatten(a)))
        })
        let stringyfiedCompoundGroups = JSON.stringify(compoundGroups)
        resultHolder.innerHTML = `<p>${stringyfiedCompoundGroups}</p>`
        console.log('parsedFormular', stringyfiedCompoundGroups)
        return stringyfiedCompoundGroups
    }
    // by weight algorithm
    if (fractionWeight) {
        weightfractionHolder.innerHTML = '<input type="text" name="weightfraction" required="">  <p>Enter the weight fraction of each compound in the mixture, separated by commas, do ensure their sum is unity</p>'
        parsedFormularHolder.value = parseFormularFunc()
    }
    if (fractionMole) {
        weightfractionHolder.innerHTML = ''
        parsedFormularHolder.value = parseFormularFunc()
    }
}

</script>
@endPush

