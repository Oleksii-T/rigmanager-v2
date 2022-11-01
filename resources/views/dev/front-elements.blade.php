@extends('layouts.page')

@section('content')
	<div class="main-block">
		Hello world
	</div>

    <br>

    <div>
        <p>Swals popups popups:</p>
        <ul>
            <li class="dev-swal-success">Success</li>
            <li class="dev-swal-error">Error</li>
            <li class="dev-swal-wait">Wait</li>
            <li class="dev-swal-ask">Ask</li>
        </ul>
    </div>

    <br>

    <div>
        <p>Swals toasts popups:</p>
        <ul>
            <li class="dev-toast-success">Success</li>
            <li class="dev-toast-error">Error</li>
        </ul>
    </div>
@endsection

@section('scripts')
    <script>
        $('.dev-swal-success').click(function(e) {
            e.preventDefault();
            showPopUp(null, 'Lorem ipsum success', true);
        })

        $('.dev-swal-error').click(function(e) {
            e.preventDefault();
            showPopUp(null, 'Lorem ipsum error', false);
        })

        $('.dev-swal-wait').click(function(e) {
            e.preventDefault();
            loading('Loading waiting');
        })

        $('.dev-swal-ask').click(function(e) {
            e.preventDefault();
            swal.fire({
                title: 'Are you sure',
                text: 'This action can not be undone',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            })
        })

        $('.dev-toast-success').click(function(e) {
            e.preventDefault();
            showToast('Some toasted success message');
        })

        $('.dev-toast-error').click(function(e) {
            e.preventDefault();
            showToast('Some toasted error message', false);
        })
    </script>
@endsection
