@extends('ld-billwerk::layout')

@section('content')
	<div class="content-block">
		<h1>Rechnungsadresse</h1>
		<p>Pflegen Sie hier die Adresse des Rechnungsempfängers und Vertragspartners.</p>

		{!! form_start($form) !!}
		{{ csrf_field() }}

		<div class="row">
			<div class="col-md-6">
				<fieldset>
					<legend>Stammdaten</legend>
					{!! form_row($form->company_name) !!}
					<div class="row">
						<div class="col-md-6">
							{!! form_row($form->first_name) !!}
						</div>
						<div class="col-md-6">
							{!! form_row($form->last_name) !!}
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							{!! form_row($form->street) !!}
						</div>
						<div class="col-md-4">
							{!! form_row($form->house_number) !!}
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							{!! form_row($form->postal_code) !!}
						</div>
						<div class="col-md-8">
							{!! form_row($form->city) !!}
						</div>
					</div>
					{!! form_row($form->country) !!}
				</fieldset>
			</div>
			<div class="col-md-6">
				<fieldset>
					<legend>Kontakt</legend>
					{!! form_row($form->email_address) !!}
					<span class="help-block">
							Diese E-Mail Adresse dient als buchhalterischer Kontakt und erhält die E-Mail Rechnungen.
						</span>
				</fieldset>
			</div>
		</div>

		{!! form_rest($form) !!}

		<button type="submit" class="btn btn-success">
			<i class="fa fa-save fa-fw"></i>
			Speichern
		</button>
		oder
		<a href="{{ route('billwerk.account') }}">
			zurück
		</a>
		{!! form_end($form) !!}
	</div>
@endsection
