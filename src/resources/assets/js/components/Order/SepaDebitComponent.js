import React, {Component} from 'react';

export default class SepaDebitComponent extends Component {
	render() {
		return (
			<div>
				<div className="form-group">
					<input placeholder="Kontoinhaber" type="text" className="form-control"/>
				</div>

				<div className="row">
					<div className="col-md-6">
						<div className="form-group">
							<input placeholder="IBAN" type="text" className="form-control"/>
						</div>
					</div>
					<div className="col-md-6">
						<div className="form-group">
							<input placeholder="BIC" type="text" className="form-control"/>
						</div>
					</div>
				</div>

				<div className="checkbox>">
					<label>
						<input type="checkbox"/> <strong>Ich stimme dem SEPA Lastschrift-Mandat zu:</strong>
					</label>

					<p className="text-muted small text-justify">
						Ich ermächtige/ Wir ermächtigen
						(A) LEFAMED Development GmbH Zahlungen von meinem/ unserem Konto mittels Lastschrift einzuziehen. Zugleich
						(B) weise ich mein/ weisen wir unser Kreditinstitut an, die von LEFAMED Development GmbH auf mein/ unser Konto gezogenen Lastschriften einzulösen.
						Hinweis: Ich kann/ Wir können innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem/ unserem Kreditinstitut vereinbarten Bedingungen.
					</p>
				</div>
			</div>
		)
	}
}
