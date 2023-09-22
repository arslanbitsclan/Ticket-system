@extends('layouts.app')
@section('title', $evaluation->type)
@section('content')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $evaluation->type }}</h1>
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
				<li class="breadcrumb-item text-muted">Evaluation</li>
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
                    <h3 class="fw-bold m-0">{{ $evaluation->type }}</h3>
                </div>
            </div>
            <form class="form" action="{{ route('evaluation.update',$evaluation->id) }}" method="POST" id="kt_modal_add_role_form">
                @csrf
                @method('PATCH')
                <div class="card-body border-top p-9">
                    
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="type">Type</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter evaluation type" name="type" id="type" value="{{ old('type') ?? $evaluation->type }}"/>
                        </div>
                    </div>
                    
                    @isset($evaluation->questions)
                        @foreach ($evaluation->questions as $key => $question)
                            @if($key == 0)
                                <div class="row g-9 mb-7">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2" for="question">Question</label>
                                        <input class="form-control form-control-solid" type="text" placeholder="Please enter question" required name="question[]" id="question" value="{{ $question->question }}"/>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <button type="button" class="btn  btn-icon btn-light-success mt-8 add-question" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Question"><i class="fa-solid fa-plus"></i></button> &nbsp;
                                    </div>
                                </div>
                            @else
                                <div class="question">
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2" for="question">Question</label>
                                            <input class="form-control form-control-solid" type="text" required placeholder="Please enter question" name="question[]" id="question" value="{{ $question->question }}"/>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <button type="button" class="btn  btn-icon btn-light-success mt-8 add-question" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Question"><i class="fa-solid fa-plus"></i></button> &nbsp;
                                            <button type="button" class="btn  btn-icon btn-light-danger mt-8 delete-question" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Question"><i class="fa-sharp fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endisset
                    
                    <div id="append-question"></div>
                    
                    
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" id="kt_modal_add_role_cancel">Discard</button>
                    <button type="submit" id="kt_modal_add_role_submit" class="btn btn-primary">
                        <span class="indicator-label">Update</span>
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
var KTModalRoleAdd = function () {
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
                    'name': {
						validators: {
							notEmpty: {
								message: 'Role name is required'
							}
						}
					}
				},
				plugins: {
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
                    window.location.href = "{{ route('evaluation.index') }}"; 			
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
            form = document.querySelector('#kt_modal_add_role_form');
            submitButton = form.querySelector('#kt_modal_add_role_submit');
            cancelButton = form.querySelector('#kt_modal_add_role_cancel');
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalRoleAdd.init();
});

$(document).on("click", '.add-question', function(){
    var html = `<div class="question">
        <div class="row g-9 mb-7">
            <div class="col-md-6 fv-row">
                <label class="required fs-6 fw-semibold mb-2" for="question">Question</label>
                <input class="form-control form-control-solid" type="text" required placeholder="Please enter question" name="question[]" id="question"/>
            </div>
            
            <div class="col-md-2">
                <button type="button" class="btn  btn-icon btn-light-success mt-8 add-question" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Question"><i class="fa-solid fa-plus"></i></button> &nbsp;
                <button type="button" class="btn  btn-icon btn-light-danger mt-8 delete-question" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Question"><i class="fa-sharp fa-solid fa-trash"></i></button>
            </div>
        </div>
    </div>`;
    
    $("#append-question").append(html);
});

$(document).on("click", '.delete-question', function(){
   $(this).closest('div.question').remove();
});


</script>
@endpush