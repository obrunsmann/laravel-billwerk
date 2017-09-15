@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="content-block">
			<h1>Account</h1>

			<div class="row">
				<div class="col-md-6">
					<fieldset>
						<legend>
							<i class="fa fa-id-card-o"></i>
							Ihre Adressdaten
						</legend>

						<address>
							<strong>{{ $customer->customer_name }}</strong><br><br>

							@if($customer->customer_sub_name)
								{{ $customer->customer_sub_name }}<br>
							@endif
							{{ $customer->street }} {{ $customer->house_number }}<br>
							{{ $customer->postal_code }} {{ $customer->city }}<br>
							{{ $customer->country }}
						</address>


						<a href="{{ route('billwerk.account.edit-address') }}" class="btn btn-xs">
							<i class="fa fa-pencil"></i>
							Ändern
						</a>
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset>
						<legend>
							<i class="fa fa-money"></i>
							Zahlungsart
						</legend>

						<strong>Bankeinzug</strong>

						<p>
							<strong>IBAN:</strong>
							DExx xxxx xxxx xxxx xxxx xx<br>

							<strong>BIC:</strong>
							ASDSDSDSDSJ
						</p>

						<a href="#" class="btn btn-xs">
							<i class="fa fa-pencil"></i>
							Ändern
						</a>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
@endsection
