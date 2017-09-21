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
			loadingOverlay: false,
			paymentMethod: 'Debit:FakePSP',
			submitEnabled: true,
			paymentDetails: {}
		};

		//init billwerk services
		//this.signupService = new BillwerkJS.Signup();

		//create the cart
		this.cart = {
			planVariantId: planVariantId
		};

		//get order preview
		axios.post('/api/billing/order/preview', {
			planVariantId
		})
			.then((res) => {
				this.setState({
					orderPreview: res.data
				});
			});

		//bind context to our event handlers
		this.updatePaymentDetails = this.updatePaymentDetails.bind(this);
		this.commitOrder = this.commitOrder.bind(this);
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

	static formatDate(date) {
		return moment(date).format('LL');
	}

	scrollToTop(scrollDuration) {
		let scrollStep = -window.scrollY / (scrollDuration / 15),
			scrollInterval = setInterval(function () {
				if (window.scrollY !== 0) {
					window.scrollBy(0, scrollStep);
				}
				else clearInterval(scrollInterval);
			}, 15);
	}

	/**
	 * Upate the stored payment details in the current state. Used for props on payment method components.
	 *
	 * @param details
	 */
	updatePaymentDetails(details) {
		this.setState({paymentDetails: details});
	}

	commitOrder() {
		//show loading spinner
		this.setState({loadingOverlay: true});

		//document.body.scrollTop = 0;
		this.scrollToTop(2000);

		//create order for the current customer
		axios.post('/api/billing/order', {
			planVariantId: planVariantId
		})
			.then((res) => {
				//create the payment service
				let paymentService = new BillwerkJS.Payment({
					publicApiKey: bwPublicKey
				}, () => {
					//payment service is ready, continue with the payment process
					this.signupService.paySignupInteractive(
						paymentService,
						this.state.paymentDetails,
						{
							OrderId: res.data.Id,
							GrossTotal: res.data.TotalGross,
							Currency: res.data.Currency
						},
						() => {
							console.log('success');
						},
						(err) => {
							console.error(err);
						}
					);
				}, () => {
					console.error('Error on creating payment service');
				});
			});
	}

	render() {
		return (
			<div>
				{(() => {
					if (this.state.orderPreview && !this.state.loadingOverlay) {
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
											fällig am {OrderComponent.formatDate(this.state.orderPreview.NextTotalGrossDate)}.
										</p>
									</fieldset>
								</div>

								<div className="content-block">
									<fieldset>
										<legend>Zahlungsweise</legend>

										<div className="row payment-methods">
											<div className="col-sm-6">
												<label>
													<input type="radio" name="payment-method"
														   onChange={() => this.setState({paymentMethod: 'Debit:FakePSP'})}
														   checked={this.state.paymentMethod === 'Debit:FakePSP'}/>

													<ul className="list-inline text-center payment-methods-o">
														<li><i className="pf pf-sepa"></i></li>
													</ul>
												</label>
											</div>
											<div className="col-sm-6">
												<label>
													<input type="radio" name="payment-method"
														   onChange={() => this.setState({paymentMethod: 'CreditCard:FakePSP'})}
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
													return <SepaDebitComponent onChange={this.updatePaymentDetails}/>
												case 'CreditCard:FakePSP':
													return <CreditCardComponent onChange={this.updatePaymentDetails}/>
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

										<button type="submit" className="btn btn-primary" onClick={this.commitOrder}
												disabled={!this.state.submitEnabled} style={{marginTop: 20}}>
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

if(document.getElementById('order')) {
	ReactDOM.render(<OrderComponent/>, document.getElementById('order'));
}
