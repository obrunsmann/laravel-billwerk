@extends('layouts.app')

@push('styles')
	<link rel="stylesheet" href="{{ mix('css/billing.css') }}" type="text/css">
@endpush

@push('scripts')
	<script type="text/javascript" src="https://sandbox.billwerk.com/selfService/billwerkJS"></script>

	<script type="text/javascript">
		const BillwerkPaymentService = BillwerkJS.Payment;
		var bwPublicKey = '{{ config('laravel-billwerk.auth.public_key') }}';
		var planVariantId = '{{ $planVariantId }}';
	</script>

	<script src="{{ mix('js/order.js') }}" type="text/javascript"></script>
@endpush

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div id="order"></div>
			</div>
			<div class="col-md-4">
				<div class="content-block">
					<fieldset>
						<legend>Rechnungsadresse</legend>


						<address>
							<strong>{{ $customer->customer_name }}</strong><br><br>

							@if($customer->customer_sub_name)
								{{ $customer->customer_sub_name }}<br>
							@endif
							{{ $customer->street }} {{ $customer->house_number }}<br>
							{{ $customer->postal_code }} {{ $customer->city }}<br>
							{{ $customer->country }}
						</address>

						<a href="{{ route('billwerk.order.changeAddress', ['planVariantId' => $planVariantId]) }}">
							<i class="fa fa-pencil fa-fw"></i> Ändern
						</a>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
@endsection
