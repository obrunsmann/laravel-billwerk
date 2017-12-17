@if(app()->environment() === 'production')
	<script type="text/javascript" src="https://app.billwerk.com/selfService/billwerkJS"></script>
@else
	<script type="text/javascript" src="https://sandbox.billwerk.com/selfService/billwerkJS"></script>
@endif
