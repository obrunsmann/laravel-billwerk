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
			termsAccepted: false,
			paymentDetails: {},
			paymentDetailsValid: false,
			paymentError: null,
			paymentSuccess: false
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
		console.log(details, valid);
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
						(res) => {
							// -- order successfull -- //
							this.setState({
								paymentSuccess: true
							});
						},
						(err) => {
							// -- order failed -- //
							//error occured while executing payment
							this.setState({
								paymentError: _.head(err.errorCode),
								loadingOverlay: false
							});
						}
					);
				}, () => {
					console.error('Error on creating payment service');
				});
			});
	}

	static getPaymentErrorMessage(errorCode) {
		let messages = {
			'UnmappedError': 'Unbekannter Fehler, versuchen Sie eine alternative Zahlungsart oder prüfen Sie die Eingabe Ihrer Daten erneut!',
			'Unknown': 'Unbekannter Fehler, versuchen Sie eine alternative Zahlungsart oder prüfen Sie die Eingabe Ihrer Daten erneut!',
			'InvalidBic': 'BIC ungültig!',
			'IteroServerError': 'Serverfehler, bitte versuchen Sie es in einem Augenblick erneut!',
			'PspServerError': 'Die Zahlung konnte nicht zum Zahlungsanbieter weitergeleitet werden. Bitte versuchen Sie es später erneut!',
			'Timeout': 'Zeitüberschreitung bei der Verbindung. Bitte versuchen Sie es später erneut.',
			'InvalidKey': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidConfiguration': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidPaymentData': 'Eingegebene Zahlungsweise ist nicht gültig. Bitte kontrollieren Sie Ihre Eingabe!',
			'InvalidData': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'Aborted': 'Zahlung wurde abgebrochen.',
			'AcquirerServerError': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'AuthorizationRejected': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'RateLimit': 'Zu häufige Anfragen. Bitte versuchen Sie es später erneut.',
			'InvalidTransaction': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'AmountLimitExceeeded': 'Die Zahlung wurde wegen mangelnder Deckung abgewiesen. Bitte nutze eine andere Zahlungsweise.',
			'InvalidPaymentMethod': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidCardType': 'Die eingegebene Kreditkarte wird von uns leider nicht unterstützt. Bitte wähle eine andere Zahlungsweise.',
			'InvalidCardNumber': 'Die eingegebene Kreditkartennummer ist nicht gültig.',
			'InvalidExpirationDate': 'Ablaufdatum ist ungültig.',
			'InvalidCardCvc': 'CVC (Sicherheitscode) ist ungültig. Bitte kontrollieren Sie Ihre Eingabe und versuchen Sie es erneut.',
			'InvalidCardHolder': 'Bitte kontrollieren Sie den Karteninhaber. Die Eingabe war nicht gültig.',
			'3DsProblem': '3D-Secure ist fehlgeschlagen. Bitte versuchen Sie es erneut oder wähle eine andere Zahlungsweise.',
			'InvalidAmount': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidCurrency': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidAccountNumber': 'Fehlerhafte Kontonummer, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidAccountHolder': 'Fehlerhafter Kontoinhaber, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidBankCode': 'Fehlerhafte Bankleitzahl, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidIban': 'Fehlerhafte IBAN, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidCountry': 'Fehlerhaftes Land, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'BearerRejected': 'Die Zahlungsweise wurde abgelehnt. Details wurden uns aus Datenschutzgründen nicht übermittelt. Bitte versuchen Sie es erneut mit einer alternativen Zahlungsart!',
			'BearerExpired': 'Die Zahlungsweise ist abgelaufen. Bitte nutze eine alternative Zahlungsweise.',
			'InvalidCouponCode': 'Der Gutscheincode ist nicht gültig, bitte prüfen Sie Ihre Eingabe und versuchen Sie es erneut!',

			'LimitExceeded': 'Limit überschritten. Bitte nutze eine alternative Zahlungsweise.',
			'BearerInvalid': 'Die Zahlungsweise wurde abgelehnt. Details wurden uns aus Datenschutzgründen nicht übermittelt. Bitte versuchen Sie es erneut mit einer alternativen Zahlungsart!',
			'LoginError': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InsufficientBalance': 'Der Kontostand reicht leider nicht aus. Bitte versuchen Sie es später erneut.',
			'AlreadyExecuted': 'Die Zahlung wurde bereits abgesendet.',
			'InvalidPreconditions': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InternalError': 'Interner Fehler. .',
			'InternalProviderError': 'Unbekannter Fehler.',
			'PermissionDenied': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'Canceled': 'Zahlung abgebrochen.',
			'Rejected': 'Zahlung zurückgewiesen.'
		};

		return messages[errorCode] || messages['Unknown'];
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
												{this.getPaymentErrorMessage(this.state.paymentError)}
											</p>
										)
									}
								})()}

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
											fällig
											am {OrderComponent.formatDate(this.state.orderPreview.NextTotalGrossDate)}.
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
