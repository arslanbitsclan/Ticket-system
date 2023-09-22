
@if (isset($errors) && $errors->any())
    @foreach ($errors->all() as $error)
        <script>
            Swal.fire({
            text: `{{ $error }}`,
            icon: "error",
             buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
            }
        })
        </script>
    @endforeach
@endif



@if (Session::has('warning'))
    <script>
        Swal.fire({
            text: `{!!Session::get("warning")!!}`,
            icon: "error",
             buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
            }
        })
    </script>
@endif

@if (Session::has('success'))
    <script>
        Swal.fire({
            text: `{!!Session::get("success")!!}`,
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
            }
        });
    </script>
@endif
