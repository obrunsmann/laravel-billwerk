import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import _ from 'lodash';
import * as moment from 'moment';
import 'moment/locale/de';
import 'paymentfont/css/paymentfont.css';

import CreditCardComponent from './Order/CredtCardComponent';
import SepaDebitComponent from './Order/SepaDebitComponent';

moment.locale('de');

export default class OrderComponent extends Component {
	constructor() {
		super();

		this.state = {
			orderPreview: null,
			paymentMethod: 'Debit:FakePSP',
			submitEnabled: false
		};

		//init billwerk services
		this.signupService = new BillwerkJS.Signup();
		this.paymentService = new BillwerkJS.Payment({
			publicApiKey: bwPublicKey,
			providerReturnUrl: ''
		}, () => {
			console.log('deine mudda is ready');
		}, () => {
			console.error('Deine Mudda hat nen Fehler gefunden');
		});

		//create the cart
		this.cart = {
			planVariantId: planVariantId
		};

		//get order preview
		axios.get('/api/billing/order/preview/' + planVariantId)
			.then((res) => {
				this.setState({
					orderPreview: res.data
				});
			});
	}

	getProduct() {
		if (this.state.orderPreview) {
			return this.state.orderPreview.RecurringFee.LineItems[0];
		}
	}

	getFeePeriod() {
		if (this.state.orderPreview.RecurringFee.FeePeriod.Quantity === 1) {
			return this.state.orderPreview.RecurringFee.FeePeriod.Unit;
		} else {
			return this.state.orderPreview.RecurringFee.FeePeriod.Quantity + ' ' + this.state.orderPreview.RecurringFee.FeePeriod.Unit;
		}
	}

	getCurrency() {
		return this.state.orderPreview.Currency;
	}

	formatDate(date) {
		return moment(date).format('LL');
	}

	render() {
		return (
			<div>
				{(() => {
					if (this.state.orderPreview) {
						return (
							<div>
								<div className="content-block">
									<fieldset>
										<legend>Übersicht</legend>
										<table className="table table-condensed">
											<thead>
											<tr>
												<th>Paket</th>
												<th>Einzelpreis</th>
												<th>Summe Netto</th>
												<th>MwSt.</th>
												<th>Summe Brutto</th>
											</tr>
											</thead>
											<tbody>
											{(() => {
												return _.map(this.state.orderPreview.RecurringFee.LineItems, (line) => {
													return (
														<tr key={line.ProductId}>
															<th>{line.Description}</th>
															<td>{line.PricePerUnit} {this.getCurrency()}
																/ {this.getFeePeriod()}</td>
															<td>{line.TotalNet} {this.getCurrency()}</td>
															<td>{line.TotalVat} {this.getCurrency()}</td>
															<td>{line.TotalGross} {this.getCurrency()}</td>
														</tr>
													)
												});
											})()}
											</tbody>
										</table>

										<p>
											<strong>Hinweis:</strong>
											Nach Abschluss des Vertrages fallen wiederkehrende Gebühren an!
											Die nächste Gebühr in Höhe
											von {this.state.orderPreview.NextTotalGross} {this.getCurrency()} wird
											fällig am {this.formatDate(this.state.orderPreview.NextTotalGrossDate)}.
										</p>
									</fieldset>
								</div>

								<div className="content-block">
									<fieldset>
										<legend>Zahlungsweise</legend>

										<div className="row payment-methods">
											<div className="col-md-6">
												<label>
													<input type="radio" name="payment-method"
														   onClick={() => this.setState({paymentMethod: 'Debit:FakePSP'})}
														   checked={this.state.paymentMethod === 'Debit:FakePSP'}/>

													<ul className="list-inline text-center payment-methods-o">
														<li><i className="pf pf-sepa"></i></li>
													</ul>
												</label>
											</div>
											<div className="col-md-6">
												<label>
													<input type="radio" name="payment-method"
														   onClick={() => this.setState({paymentMethod: 'CreditCard:FakePSP'})}
														   checked={this.state.paymentMethod === 'CreditCard:FakePSP'}/>

													<ul className="list-inline text-center payment-methods-o">
														<li><i className="pf pf-visa"></i></li>
														<li><i className="pf pf-mastercard"></i></li>
													</ul>
												</label>
											</div>
										</div>

										<hr/>

										{(() => {
											switch (this.state.paymentMethod) {
												case 'Debit:FakePSP':
													return <SepaDebitComponent/>
												case 'CreditCard:FakePSP':
													return <CreditCardComponent/>
											}
										})()}
									</fieldset>
								</div>

								<div className="content-block">
									<fieldset>
										<legend>Vertragsabschluss</legend>


										<div className="checkbox>">
											<label>
												<input type="checkbox"/>&nbsp;
												Ich habe die <a href="">Geschäftsbedinungen</a> gelesen und akzeptiere
												diese.
											</label>
										</div>

										<button type="submit" className="btn btn-primary" disabled={!this.state.submitEnabled} style={{marginTop: 20}}>
											Jetzt zahlungspflichtig bestellen
										</button>
									</fieldset>
								</div>
							</div>
						)
					} else {
						return (
							<div className="content-block">
								<p className="text-center">
									<i className="fa fa-spin fa-circle-o-notch fa-3x"></i>
									<br/>
									Lädt...
								</p>
							</div>
						)
					}
				})()}
			</div>
		);
	}
}

if (document

		.getElementById(
			'order'
		)) {
	ReactDOM
		.render(
			<OrderComponent/>,
			document
				.getElementById(
					'order'
				))
	;
}
