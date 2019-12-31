import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import _ from 'lodash';
import * as moment from 'moment';
import 'moment/locale/de';
import 'paymentfont/css/paymentfont.css';

import BearerErrorCode from './Portal/BearerErrorCode';
import CreditCardComponent from './Order/CreditCardComponent';
import SepaDebitComponent from './Order/SepaDebitComponent';

moment.locale('de');

export default class OrderComponent extends Component {
	constructor() {
		super();

		this.state = {
			orderPreview: null,
			loadingOverlay: false,
			paymentMethod: 'Debit',
			termsAccepted: false,
			paymentDetails: {},
			paymentDetailsValid: false,
			paymentError: null,
			paymentSuccess: false,
			couponCode: '',
			applyCouponButtonDisabled: false
		};

		//init billwerk services
		this.signupService = new BillwerkJS.Signup();

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
		this.handleInputChange = this.handleInputChange.bind(this);
		this.handleCheckboxInput = this.handleCheckboxInput.bind(this);
		this.applyCouponCode = this.applyCouponCode.bind(this);
	}

	handleInputChange(e) {
		this.setState({
			[e.target.name]: e.target.value
		});
	}

	handleCheckboxInput(e) {
		this.setState({
			[e.target.name]: e.target.checked
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

	isTrial() {
		return this.state.orderPreview.IsTrial;
	}

	getTrialPeriod() {
		let unit, quantity = this.state.orderPreview.TrialPeriod.Quantity;
		switch (this.state.orderPreview.TrialPeriod.Unit) {
			case 'Day':
				unit = quantity !== 1 ? 'Tage' : 'Tag;'
				break;
		}

		return quantity + ' ' + unit;
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
	updatePaymentDetails(details, valid) {
		this.setState({
			paymentDetails: details,
			paymentDetailsValid: valid
		});
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
				let paymentService = new BillwerkPaymentService({
					publicApiKey: bwPublicKey,
					providerReturnUrl: finishUrl
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
						(res) => {
							if(res.Url) {
								location.href = res.Url;
							} else {
								// -- order successfull -- //
								this.setState({
									paymentSuccess: true
								});
							}
						},
						(err) => {
							// -- order failed -- //
							//error occured while executing payment
							this.setState({
								paymentError: BearerErrorCode.getPaymentErrorMessage(_.head(err.errorCode)),
								loadingOverlay: false
							});
						}
					);
				}, () => {
					console.error('Error on creating payment service');
				});
			});
	}

	applyCouponCode(e) {
		this.setState({
			applyCouponButtonDisabled: true
		});

		//load new order preview with coupon code
		axios.post('/api/billing/order/preview', {
			planVariantId,
			couponCode: this.state.couponCode
		})
			.then((res) => {
				this.setState({
					orderPreview: res.data,
					applyCouponButtonDisabled: false
				});
			});
	}

	isOrderValid() {
		return this.state.paymentDetailsValid && this.state.termsAccepted;
	}

	render() {
		return (
			<div>
				{(() => {
					if (this.state.orderPreview && !this.state.loadingOverlay && !this.state.paymentSuccess) {
						return (
							<div>

								{(() => {
									if (this.state.paymentError) {
										return (
											<p className="alert alert-warning" style={{marginTop: 20}}>
												{this.state.paymentError}
											</p>
										)
									}
								})()}

								<div className="content-block">
									<fieldset>
										<legend>{this.isTrial() ? 'Ihr kostenloser Testzeitraum' : 'Übersicht'}</legend>

										{(() => {
											if (!this.isTrial()) {
												//if order does not start with a trial, display the line items
												return (
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
															return _.map(this.state.orderPreview.RecurringFee.LineItems, (line, index) => {
																return (
																	<tr key={index}>
																		<th>
																			{line.Description}
																			<span
																				className="small text-muted">{line.ProductDescription ? ' (' + line.ProductDescription + ')' : ''}</span>
																			<br/>
																			{moment(line.PeriodStart).format('ll')}
																			- {moment(line.PeriodEnd).format('ll')}
																		</th>
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
														{(() => {
															if (this.state.orderPreview.RecurringFee.LineItems.length > 1) {
																return (
																	<tfoot>
																	<tr style={{
																		borderTop: '3px solid black',
																		background: '#f1f1f1'
																	}}>
																		<th>Gesamt</th>
																		<td colSpan={1}></td>
																		<td>{this.state.orderPreview.Total} {this.getCurrency()}</td>
																		<td>{this.state.orderPreview.TotalVat} {this.getCurrency()}</td>
																		<td>{this.state.orderPreview.TotalGross} {this.getCurrency()}</td>
																	</tr>
																	</tfoot>
																)
															}
														})()}
													</table>
												)
											} else {
												//if order starts with a trial, display some information about the trial
												return (
													<div>
														<p className="lead">
															Testen Sie coogaa.de {this.getTrialPeriod()} kostenlos und
															unverbindlich!
														</p>
														<p>
															Sie können innerhalb des Testzeitraums bequem über die
															Händler-Konsole jederzeit kündigen. In diesem Fall belasten
															wir Ihr Konto nicht!
														</p>
													</div>
												)
											}
										})()}

										<p>
											<strong>Hinweis:</strong>
											Nach Abschluss des Vertrages fallen wiederkehrende Gebühren an!
											Die nächste Gebühr in Höhe
											von {this.state.orderPreview.NextTotalGross} {this.getCurrency()} wird
											fällig
											am {OrderComponent.formatDate(this.state.orderPreview.NextTotalGrossDate)}.
										</p>

										<hr/>

										{((() => {
											if (this.state.orderPreview.Coupon) {
												if (this.state.orderPreview.Coupon.ErrorCode) {
													return <span className="text-danger">Dieser Code ist leider nicht gültig!</span>
												} else if (this.state.orderPreview.Coupon.CouponId) {
													return <span
														className="text-success">Gutschein "{this.state.orderPreview.Coupon.CouponCode}" erfolgreich angewendet!</span>
												}
											}
										}))()}

										<form className="form-inline">
											<div className="row">
												<div className="form-group col-sm-9">
													<input type="text" className="form-control" name="couponCode"
														   placeholder="Coupon-Code" style={{width: '100%'}}
														   onBlur={this.handleInputChange}/>
												</div>
												<div className="col-sm-3">
													<button type="submit" className="btn btn-success btn-block"
															onClick={this.applyCouponCode}
															disabled={this.state.applyCouponButtonDisabled}>
														<i className="fa fa-check fa-fw"/>
														Gutschein anwenden
													</button>
												</div>
											</div>
										</form>
									</fieldset>
								</div>

								<div className="content-block">
									<fieldset>
										<legend>Zahlungsweise</legend>

										<div className="row payment-methods">
											<div className="col-sm-6">
												<label>
													<input type="radio" name="payment-method"
														   onChange={() => this.setState({
															   paymentMethod: 'Debit',
															   paymentDetailsValid: true
														   })}
														   checked={this.state.paymentMethod === 'Debit'}/>

													<ul className="list-inline text-center payment-methods-o">
														<li><i className="pf pf-sepa"></i></li>
													</ul>
												</label>
											</div>
											<div className="col-sm-6">
												<label>
													<input type="radio" name="payment-method"
														   onChange={() => this.setState({paymentMethod: 'CreditCard:Stripe'})}
														   checked={this.state.paymentMethod === 'CreditCard:Stripe'}/>

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
												case 'Debit':
													return <SepaDebitComponent onChange={this.updatePaymentDetails}/>
												case 'CreditCard:Stripe':
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
												<input type="checkbox" name="termsAccepted"
													   checked={this.state.termsAccepted}
													   onChange={this.handleCheckboxInput}/>&nbsp;
												Ich habe die <a href="">Geschäftsbedinungen</a> gelesen und akzeptiere
												diese.
											</label>
										</div>

										<button type="submit" className="btn btn-primary" onClick={this.commitOrder}
												disabled={!this.state.paymentDetailsValid || !this.state.termsAccepted}
												style={{marginTop: 20}}>
											Jetzt zahlungspflichtig bestellen
										</button>
									</fieldset>
								</div>
							</div>
						)
					} else if (this.state.paymentSuccess === true) {
						return (
							<div className="content-block">
								<i className="fa fa-check-circle fa-4x pull-left text-success"></i>
								<p>
									<strong>Vielen Dank für Ihre Bestellung!</strong>
								</p>
								<p>
									Der Freischaltungsprozess dauert nur wenige Minuten, Sie erhalten anschließend eine
									E-Mail!
								</p>
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

if (document.getElementById('order')) {
	ReactDOM.render(<OrderComponent/>, document.getElementById('order'));
}
