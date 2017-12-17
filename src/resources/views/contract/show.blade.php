@extends('ld-billwerk::layout')

@push('styles')
	<link rel="stylesheet" href="{{ mix('css/billing.css') }}" type="text/css">
@endpush

@push('scripts')
	<script type="text/javascript" src="https://sandbox.billwerk.com/selfService/billwerkJS"></script>
	<script type="text/javascript">
		const BillwerkPaymentService = BillwerkJS.Payment;
		const bwPublicKey = '{{ config('laravel-billwerk.auth.public_key') }}';
		const contractId = '{{ $contractId }}';
		const finishUrl = '{{ route('billwerk.psp.finalize') }}';
	</script>
	<script type="text/javascript" src="{{ mix('js/portal.js') }}"></script>
@endpush

@section('content')
	<div id="contract-detail"></div>
@endsection
