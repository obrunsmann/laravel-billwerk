import React, {Component} from 'react';
import CardReactFormContainer from 'card-react';
import _ from 'lodash';
import 'card-react/lib/card.css';

export default class CreditCartComponent extends Component {
	constructor() {
		super();

		this.state = {
			bearer: 'CreditCard:FakePSP',
			cardNumber: '',
			expiryMonth: '',
			expiryYear: '',
			cardHolder: '',
			cvc: ''
		};

		this.handleInputChange = this.handleInputChange.bind(this);
		this.handleInputChangeRaw = this.handleInputChangeRaw.bind(this);
	}

	isFormValid() {
		return this.state.cardNumber !== '' &&
			this.state.expiryMonth !== '' &&
			this.state.expiryYear !== '' &&
			this.state.cardHolder !== '' &&
			this.state.cvc !== '';
	}

	handleInputChange(e) {
		if (e.target.name === 'expiry') {
			//handle expiry date  (we need to split string into year + month for our state)
			let parts = _.split(e.target.value, '/');
			let expiry = {
				expiryMonth: _.trim(parts[0]).split(' ').join('')
			};

			if (parts[1]) {
				expiry.expiryYear = _.trim(parts[1]).split(' ').join('');
			}

			this.setState(expiry, () => {
				this.props.onChange(this.state, this.isFormValid());
			});
		} else {
			//handle all other form inputs
			this.setState({
				[e.target.name]: _.trim(e.target.value).split(' ').join('')
			}, () => {
				this.props.onChange(this.state, this.isFormValid());
			});
		}
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

	render() {
		return (
			<div>
				<CardReactFormContainer
					container="card-wrapper"
					formInputsNames={
						{
							number: 'cardNumber',
							expiry: 'expiry',
							cvc: 'cvc',
							name: 'cardHolder'
						}
					}
					formatting={true}
				>

					<div id="card-wrapper"></div>

					<div style={{marginTop: 20}}>
						<div className="form-group">
							<input placeholder="Nummer" type="text" name="cardNumber" className="form-control"
								   onChange={this.handleInputChange}/>
						</div>

						<div className="row">
							<div className="col-md-6">
								<div className="form-group">
									<input placeholder="MM / JJ" type="text" name="expiry"
										   className="form-control" onChange={this.handleInputChange}/>
								</div>
							</div>
							<div className="col-md-6">
								<div className="form-group">
									<input placeholder="CVC" type="text" name="cvc" className="form-control"
										   onChange={this.handleInputChange}/>
								</div>
							</div>
						</div>

						<div className="form-group">
							<input placeholder="Name auf der Karte" type="text" name="cardHolder"
								   className="form-control" onChange={this.handleInputChangeRaw}/>
						</div>
					</div>


				</CardReactFormContainer>
			</div>
		)
	}
}
