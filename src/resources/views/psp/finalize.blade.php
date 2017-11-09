@extends('layouts.app')

@push('styles')
	<link rel="stylesheet" href="{{ mix('css/billing.css') }}" type="text/css">
@endpush

@push('scripts')
	@include('vendor.ld-billwerk.inc.billwerkJS')

	<script src="{{ mix('js/order.js') }}" type="text/javascript"></script>
@endpush

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="psp-finalize"></div>
			</div>
		</div>
	</div>
@endsection
