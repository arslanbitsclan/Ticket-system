@extends('layouts.app')
@section('title', 'Create a new ticket')
@section('content')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Create a new ticket</h1>
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
				<li class="breadcrumb-item text-muted">Ticket</li>
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
                    <h3 class="fw-bold m-0">Create a new ticket</h3>
                </div>
            </div>
            <form class="form" action="{{ route('ticket.store') }}" method="POST" id="kt_modal_add_ticket_form" enctype="multipart/form-data">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row g-9 mb-7">
                    
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="user_id">Customer</label>
                            <select aria-label="Select a customer" data-control="select2"  data-placeholder="Select a customer"  id="user_id" name="user_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->first_name . ' ' .$customer->last_name ?'selected' :'' }}> {{ $customer->first_name . ' ' .$customer->last_name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="priority_id">Priority</label>
                            <select aria-label="Select a priority" data-control="select2"  data-placeholder="Select a priority"  id="priority_id" name="priority_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a priority</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->name ?'selected' :'' }}> {{ $priority->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="status_id">Status</label>
                            <select aria-label="Select a status" data-control="select2"  data-placeholder="Select a status"  id="status_id" name="status_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a status</option>
                                @foreach($status as $s)
                                    <option value="{{ $s->id }}" {{ old('status_id') == $s->name ?'selected' :'' }}> {{ $s->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="department_id">Department</label>
                            <select aria-label="Select a department" data-control="select2"  data-placeholder="Select a department"  id="department_id" name="department_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->name ?'selected' :'' }}> {{ $department->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="assigned_to">Assigned to</label>
                            <select aria-label="Select a assigned" data-control="select2"  data-placeholder="Select a assigned"  id="assigned_to" name="assigned_to" class="form-select form-select-solid fw-bold">
                                <option value="">Select a assigned</option>
                                @foreach($assigned_to as $assign)
                                    <option value="{{ $assign->id }}" {{ old('department') == $assign->first_name.' '.$assign->last_name ?'selected' :'' }}> {{ $assign->first_name.' '.$assign->last_name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="type_id">Type</label>
                            <select aria-label="Select a type" data-control="select2"  data-placeholder="Select a type"  id="type_id" name="type_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->name ?'selected' :'' }}> {{ $type->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="subject">Subject</label>
                            <input class="form-control form-control-solid" type="text" placeholder="Please enter subject" name="subject" id="subject" value="{{ old('subject') }}"/>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="category_id">Category</label>
                            <select aria-label="Select a type" data-control="select2"  data-placeholder="Select a category"  id="category_id" name="category_id" class="form-select form-select-solid fw-bold">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category') == $category->name ?'selected' :'' }}> {{ $category->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                         
                        <div class="col-md-6 fv-row">
                            <label for="attachment" class="fs-6 fw-semibold mb-2">Attachments</label>
                            <input class="form-control form-control form-control-solid" type="file" name="files[]" id="attachment" multiple />
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label for="details" class="required fs-6 fw-semibold mb-2">Request Details</label>
                            <textarea class="form-control form-control form-control-solid" name="details" id="details" data-kt-autosize="true"></textarea>
                        </div>
                        
                    </div>
                 
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" id="kt_modal_add_ticket_cancel">Discard</button>
                    <button type="submit" id="kt_modal_add_ticket_submit" class="btn btn-primary">
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
                    priority_id: {
						validators: {
							notEmpty: {
								message: 'The priority is required'
							}
						}
					},
                    status_id: {
						validators: {
							notEmpty: {
                                message: "The status is required"
                            }
						}
					},
                    department_id: {
						validators: {
							notEmpty: {
								message: 'The department is required'
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
                    type_id: {
						validators: {
							notEmpty: {
								message: 'The type is required'
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
                    category_id: {
						validators: {
							notEmpty: {
								message: 'The category is required'
							}
						}
					},
                    details: {
						validators: {
							notEmpty: {
								message: 'The detail is required'
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
                    window.location.href = "{{ route('user.index') }}"; 			
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
            submitButton = form.querySelector('#kt_modal_add_ticket_submit');
            cancelButton = form.querySelector('#kt_modal_add_ticket_cancel');
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