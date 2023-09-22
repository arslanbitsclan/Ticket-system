@extends('layouts.app')
@section('title', 'Customer')
@section('content')
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Customer</h1>
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
				<li class="breadcrumb-item text-muted">Customer</li>
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
		
		<!--begin::Card-->
		<div class="card">
			<!--begin::Card header-->
			<div class="card-header border-0 pt-6">
				<!--begin::Card title-->
				<div class="card-title">
					<!--begin::Search-->
					<div class="d-flex align-items-center position-relative my-1">
						<!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
						<span class="svg-icon svg-icon-1 position-absolute ms-6">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
								<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
						<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search customer" />
					</div>
					<!--end::Search-->
				</div>
				<!--begin::Card title-->
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
						@can('add-customer')
						<!--begin::Add customer-->
						<a href="{{ route('customer.create') }}" class="btn btn-primary">Add customer</a>
						<!--end::Add customer-->
						@endcan
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			<!--begin::Card body-->
			<div class="card-body pt-0">
				<!--begin::Table-->
				<table class="table align-middle table-row-dashed fs-6 gy-5" id="customer_table">
					<!--begin::Table head-->
					<thead>
						<!--begin::Table row-->
						<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
							<th class="text-left min-w-125px">Sr#</th>
							<th class="min-w-125px">First Name</th>
							<th class="min-w-125px">Last Name</th>
							<th class="min-w-125px">Email Name</th>
							<th class="min-w-125px  @if(!auth()->user()->can('edit-customer') && !auth()->user()->can('delete-customer') ) text-end  @endif">Created Date</th>
							@canany(['update-customer','delete-customer'])
							<th class="text-end min-w-70px">Actions</th>
							@endcanany
						</tr>
						<!--end::Table row-->
					</thead>
					<!--end::Table head-->
				</table>
				<!--end::Table-->
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
	</div>
<!--end::Content container-->
</div>
@endsection
@push('scripts')
<script>
      $(document).ready(function () {
            
		try {
			const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
			var data = function (d) { //This function is used to give ajax the data, but this function enables to later change the ajax data so that we can set export flag to false in ajax data
				d._token = _token;
			};

			let data_table = $('#customer_table').DataTable({
				serverSide: true,
				stateSave: false,
				ajax: {
					url: "{{ route('customer.fetch') }}",
					data: data,
					type: "post",
					error: function (xhr, error, thrown) {
						if (xhr.status == 419) { //If customer is loggedout due to inactivity, then reload page
							location.reload();
						}
						else {
							Swal.fire({
								text: 'There was some problem in getting data. Please try later or refresh page',
								icon: "error",
								buttonsStyling: false,
								confirmButtonText: "Ok, got it!",
								customClass: {
									confirmButton: "btn fw-bold btn-primary",
								}
							})
							
						}
					}
				},
				columns: [
					null,
					null,
					null,
					null,
					null,
					@canany(['edit-customer','delete-customer'])
					{ "orderable": false },
					@endcanany
				]
			});
			
			filterSearch.addEventListener('input', function (e) {
			    data_table.search(e.target.value).draw();
			});

		}
		catch (error) {
			Swal.fire({
				text: error,
				icon: "error",
				buttonsStyling: false,
				confirmButtonText: "Ok, got it!",
				customClass: {
					confirmButton: "btn fw-bold btn-primary",
				}
        	})
		}
		
		$(document).on("click", ".delete", function(){
			var id = $(this).attr("data-delete-id");
			Swal.fire({
                text: "Are you sure you would like to delete?",
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
                    $.ajax({
						url: "{{ route('customer.delete-ajax')}}",
						type: "POST",
						data:  {id: id},
						success: function (res) {
							if(res.success){
								window.location.href = "{{ route('customer.index') }}"; 
							}else{
								Swal.fire({
									text: 'Something Went Wrong! Please Try Again Later',
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Ok, got it!",
									customClass: {
										confirmButton: "btn fw-bold btn-primary",
									}
								})
							}
						}
					});
                }
            });
		})
			
    });  
</script>
@endpush