@extends('ld-billwerk::layout')

@push('scripts')
	<script type="text/javascript" src="https://sandbox.billwerk.com/selfService/billwerkJS"></script>
	<script type="text/javascript">
		const contractId = '{{ $contractId }}';
	</script>
	<script type="text/javascript" src="{{ mix('js/portal.js') }}"></script>
@endpush

@section('content')
	<div id="contract-detail"></div>
@endsection
