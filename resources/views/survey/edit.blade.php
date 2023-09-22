@extends('layouts.app')
@section('title', $survey->uid .' - '.$survey->subject)
@section('content')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $survey->uid .' - '.$survey->subject }}</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-400 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">Survey</li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
	</div>
	<!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-fluid">
		<div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $survey->uid .' - '.$survey->subject }}</h3>
                </div>
            </div>
            <form class="form" action="{{ route('survey.update',$survey->id) }}" method="POST" id="kt_modal_add_ticket_form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body border-top p-9">
                    <div class="row g-9 mb-7">
                    
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="user_id">Customer</label>
                            <select aria-label="Select a customer" data-control="select2"  data-placeholder="Select a customer"  id="user_id" name="user_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $survey->user_id == $customer->id ?'selected' :'' }}> {{ $customer->first_name . ' ' .$customer->last_name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                       
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="assigned_to">Assigned to</label>
                            <select aria-label="Select a assigned" data-control="select2"  data-placeholder="Select a assigned"  id="assigned_to" name="assigned_to" class="form-select form-select-solid fw-bold">
                                <option value="">Select a assigned</option>
                                @foreach($assigned_to as $assign)
                                    <option value="{{ $assign->id }}" {{ $survey->assigned_to == $assign->id ?'selected' :'' }}> {{ $assign->first_name.' '.$assign->last_name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="call_type">Call Type</label>
                            <select aria-label="Select a call type" data-control="select2"  data-placeholder="Select a call type"  id="call_type" name="call_type" class="form-select form-select-solid fw-bold">
                                <option value="">Select a call type</option>
                                <option value="1" {{ $survey->call_type == "1" ?'selected' :'' }}>Request</option>
                                <option value="2" {{ $survey->call_type == "2" ?'selected' :'' }}>Inquiry</option>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="subject">Subject</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter subject" name="subject" id="subject" value="{{ $survey->subject }}"/>
                        </div>
                        
                        
                    </div>
                    
                    @foreach ($evaluations as $evaluation)
                        <div class="row g-9 mb-7">
                            <h3>{{ $evaluation->type }}</h3>
                            <div class="separator my-10"></div>
                            @foreach ($evaluation->questions as $k => $question)
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2" for="answer[{{  $evaluation->id }}][{{ $question->id }}]">{{ $question->question }}</label>
                                    <input class="form-control form-control-solid" type="text" placeholder="Please enter answer" name="answer[{{  $evaluation->id }}][{{ $question->id }}]" id="answer[{{  $evaluation->id }}][{{ $question->id }}]" data-fv-not-empty="true" data-fv-not-empty___message="The answer is required" value="{{ $survey->answers[$k]->answer }}" />
                                </div>
                                

                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2" for="call_type">Score (%)</label>
                                    <select aria-label="Select a score" data-control="select2"  data-placeholder="Select a score"  id="score[{{  $evaluation->id }}][{{ $question->id }}]" name="score[{{  $evaluation->id }}][{{ $question->id }}]" class="form-select form-select-solid fw-bold" data-fv-not-empty="true" data-fv-not-empty___message="The score is required">
                                        <option value="">Select a score</option>
                                        @for ($i = 0;  $i <= 100 ; $i++)
                                            <option value={{ $i }} {{ $survey->answers[$k]->score == $i ?'selected' :'' }}> {{ $i }}%</option>
                                        @endfor
                                    </select>
                                </div>
                        
                            @endforeach
                        </div>
                    @endforeach
                    
                    
                 
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" id="kt_modal_add_survey_cancel">Discard</button>
                    <button type="submit" id="kt_modal_add_survey_submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div> 
            </form>                              
        </div>
	</div>
    <!--end::Content container-->
</div>
@endsection
@push('scripts')
<script>
// Class definition
var KTModaluserAdd = function () {
    var submitButton;
    var cancelButton;
    var validator;
    var form;
    // Init form inputs
    var handleForm = function () {
         
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
                    user_id: {
						validators: {
							notEmpty: {
								message: 'The customer is required'
							}
						}
					},
                    call_type: {
						validators: {
							notEmpty: {
								message: 'The call type is required'
							}
						}
					},
                    assigned_to: {
                        validators: {
                            notEmpty: {
                                message: "The assign is required"
                            }
                        }
                    },
                    subject: {
						validators: {
							notEmpty: {
								message: 'The subject is required'
							}
						}
					},
				},
				plugins: {
                    declarative: new FormValidation.plugins.Declarative(),
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
						rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
					})
				}
			}
		);

		// Action buttons
		$(submitButton).on('click', function (e) {
			e.preventDefault();

			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');

					if (status == 'Valid') {
						submitButton.setAttribute('data-kt-indicator', 'on');

						// Disable submit button whilst loading
						submitButton.disabled = true;
                        form.submit(); // Submit form

                     						
					} else {
						Swal.fire({
							text: "Sorry, looks like there are some errors detected, please try again.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn btn-primary"
							}
						});
					}
				});
			}
		});

        $(cancelButton).on('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form	
                    window.location.href = "{{ route('survey.index') }}"; 			
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                    form.reset();
                }
            });
        });
    }

    return {
        // Public functions
        init: function () {
            // Elements
            form = document.querySelector('#kt_modal_add_ticket_form');
            submitButton = form.querySelector('#kt_modal_add_survey_submit');
            cancelButton = form.querySelector('#kt_modal_add_survey_cancel');
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModaluserAdd.init();
});

</script>
@endpush