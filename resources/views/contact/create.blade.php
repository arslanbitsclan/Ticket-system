@extends('layouts.app')
@section('title', 'Create a new contact')
@section('content')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Create a new contact</h1>
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
				<li class="breadcrumb-item text-muted">Contact</li>
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
                    <h3 class="fw-bold m-0">Create a new contact</h3>
                </div>
            </div>
            <form class="form" action="{{ route('contact.store') }}" method="POST" id="kt_modal_add_contact_form">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="first_name">First name</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter first name" name="first_name" id="first_name" value="{{ old('first_name') }}"/>
                        </div>
                        
                         <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="last_name">Last name</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter last name" name="last_name" id="last_name" value="{{ old('last_name') }}"/>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="email">Email</label>
                            <input class="form-control form-control-solid" type="email" placeholder="Please enter email" name="email" id="email" value="{{ old('email') }}"/>
                        </div>
                    
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="phone">Phone</label>
                            <input class="form-control form-control-solid" type="number" placeholder="Please enter phone number" name="phone" id="phone" value="{{ old('phone') }}"/>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="organization_id">Organization</label>
                            <select aria-label="Select a organization" data-control="select2"  data-placeholder="Select a organization"  id="organization_id" name="organization_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a organization_id</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->name ?'selected' :'' }}> {{ $organization->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        

                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="address">Address</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter address" name="address" id="address" value="{{ old('address') }}"/>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="city">City</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter city" name="city" id="city" value="{{ old('city') }}"/>
                        </div>
                        
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="country">Country</label>
                            <select aria-label="Select a country" data-control="select2"  data-placeholder="Select a country"  id="country" name="country" class="form-select form-select-solid fw-bold">
                                <option value="">Select a country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country') == $country->name ?'selected' :'' }}> {{ $country->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                    </div>
                 
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" id="kt_modal_add_contact_cancel">Discard</button>
                    <button type="submit" id="kt_modal_add_contact_submit" class="btn btn-primary">
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
var KTModalcontactAdd = function () {
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
                    first_name: {
						validators: {
							notEmpty: {
								message: 'The first name is required'
							}
						}
					},
                    last_name: {
						validators: {
							notEmpty: {
								message: 'The last name is required'
							}
						}
					},
                    email: {
						validators: {
							notEmpty: {
                                message: "The email is required"
                            },
                            emailAddress: {
                                message: 'The email is not a valid email address'
                            }
						}
					},
                    phone: {
                        validators: {
                            notEmpty: {
                                message: "The phone number is required"
                            },
                            stringLength: {
                                min: 11,
                                message: "The phone number must be more than 7 characters"
                            },
                            regexp: {
                                message: "The phone number can only consist of number",
                                regexp: /^[0-9]*$/
                            },
                        }
                    },
                    address: {
						validators: {
							notEmpty: {
								message: 'The address is required'
							}
						}
					},
                    city: {
						validators: {
							notEmpty: {
								message: 'The city is required'
							}
						}
					},
                    country: {
						validators: {
							notEmpty: {
								message: 'The country is required'
							}
						}
					},
                    organization_id: {
						validators: {
							notEmpty: {
								message: 'The organization is required'
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
                    window.location.href = "{{ route('contact.index') }}"; 			
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
            form = document.querySelector('#kt_modal_add_contact_form');
            submitButton = form.querySelector('#kt_modal_add_contact_submit');
            cancelButton = form.querySelector('#kt_modal_add_contact_cancel');
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalcontactAdd.init();
});

</script>
@endpush