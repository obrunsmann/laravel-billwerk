@if(app()->environment() === 'production')
	<script type="text/javascript" src="https://app.billwerk.com/selfService/billwerkJS"></script>
@else
	@include('vendor.ld-billwerk.inc.billwerkJS')
@endif
