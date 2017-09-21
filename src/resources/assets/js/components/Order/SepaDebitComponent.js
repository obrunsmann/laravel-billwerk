import React, {Component} from 'react';

export default class SepaDebitComponent extends Component {
	constructor() {
		super();

		this.state = {
			bearer: 'Debit:FakePSP',
			iban: '',
			bic: '',
			accountHolder: '',
			acceptMandate: false
		};

		this.handleInputChange = this.handleInputChange.bind(this);
		this.handleInputChangeRaw = this.handleInputChangeRaw.bind(this);
		this.handleCheckboxChange = this.handleCheckboxChange.bind(this);
	}

	isFormValid() {
		return this.state.iban !== '' &&
			this.state.bic !== '' &&
			this.state.accountHolder !== '' &&
			this.state.acceptMandate === true;
	}

	handleInputChange(e) {
		this.setState({
			[e.target.name]: _.trim(e.target.value).split(' ').join('')
		}, () => {
			this.props.onChange(this.state);
		});
	}

	/**
	 * Handle input change without removing whitespace from string
	 *
	 * @param e
	 */
	handleInputChangeRaw(e) {
		this.setState({
			[e.target.name]: _.trim(e.target.value)
		}, () => {
			this.props.onChange(this.state, this.isFormValid());
		});
	}

	handleCheckboxChange(e) {
		this.setState({
			[e.target.name]: e.target.checked
		}, () => {
			this.props.onChange(this.state, this.isFormValid());
		});
	}

	render() {
		return (
			<div>
				<div className="form-group">
					<input placeholder="Kontoinhaber" type="text" name="accountHolder" className="form-control"
						   onBlur={this.handleInputChangeRaw}/>
				</div>

				<div className="row">
					<div className="col-md-6">
						<div className="form-group">
							<input placeholder="IBAN" type="text" name="iban" className="form-control"
								   onBlur={this.handleInputChange}/>
						</div>
					</div>
					<div className="col-md-6">
						<div className="form-group">
							<input placeholder="BIC" type="text" className="form-control" name="bic"
								   onBlur={this.handleInputChange}/>
						</div>
					</div>
				</div>

				<div className="checkbox>">
					<label>
						<input type="checkbox" name="acceptMandate" onChange={this.handleCheckboxChange}
							   checked={this.state.acceptMandate}/>
						<strong>Ich stimme dem SEPA Lastschrift-Mandat zu:</strong>
					</label>

					<p className="text-muted small text-justify">
						Ich ermächtige/ Wir ermächtigen
						(A) LEFAMED Development GmbH Zahlungen von meinem/ unserem Konto mittels Lastschrift
						einzuziehen. Zugleich
						(B) weise ich mein/ weisen wir unser Kreditinstitut an, die von LEFAMED Development GmbH auf
						mein/ unser Konto gezogenen Lastschriften einzulösen.
						Hinweis: Ich kann/ Wir können innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die
						Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem/ unserem
						Kreditinstitut vereinbarten Bedingungen.
					</p>
				</div>
			</div>
		)
	}
}
