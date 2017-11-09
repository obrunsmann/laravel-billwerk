import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';

moment.locale('de');

import BearerErrorCode from './Portal/BearerErrorCode';
import SepaDebit from './Portal/PaymentMethods/SepaDebit';
import CreditCard from './Portal/PaymentMethods/CreditCard';

import SepaDebitComponent from './Order/SepaDebitComponent';
import CreditCardComponent from './Order/CreditCardComponent';

import 'paymentfont/css/paymentfont.css';

export default class ContractComponent extends Component {

	constructor(props) {
		super(props);

		this.state = {
			loading: true,
			contract: {},

			changePaymentDetails: false,
			paymentDetailsValid: false,
			paymentDetails: {},

			paymentErrorCode: null
		};

		//bind context to event handlers
		this.doCancellation = this.doCancellation.bind(this);
		this.changePaymentDetails = this.changePaymentDetails.bind(this);
		this.cancelChangePaymentDetails = this.cancelChangePaymentDetails.bind(this);
		this.updatePaymentDetails = this.updatePaymentDetails.bind(this);
		this.applyNewPaymentDetails = this.applyNewPaymentDetails.bind(this);
		this.downloadInvoice = this.downloadInvoice.bind(this);
	}

	componentWillMount() {
		//get the token for the contract
		axios.get('/api/billing/contract/' + contractId + '/token')
			.then((res) => {
				this.token = res.data.Token;

				//get the portal service
				this.portalService = new BillwerkJS.Portal(this.token);
				this.refreshContract();
			});
	}

	downloadInvoice(invoiceId) {
		window.location = this.portalService.invoicePdfDownloadUrl(invoiceId);
	}

	refreshContract() {
		this.portalService.contractDetails((contract) => {
			this.setState({
				contract,
				loading: false
			});
		}, (err) => console.error);
	}

	getContract() {
		return this.state.contract.Contract;
	}

	getCurrentPlan() {
		return this.state.contract.CurrentPlan;
	}

	getPaymentIconName() {
		switch (this.getContract().PaymentBearer.Type) {
			case 'CreditCard':
				return 'pf-credit-card';
			case 'BankAccount':
				return 'pf-sepa';
			case 'InvoicePayment':
				return 'pf-rechnung';
		}
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

	doCancellation() {
		let res = confirm('Paket zum ' + moment(this.state.contract.EndDateIfCancelledNow).format('LL') + ' kündigen?');
		if (res) {
			//submit the cancellation
			this.setState({loading: true});
			this.portalService.contractCancel((res) => {
				this.refreshContract();
			}, (err) => {
				console.error(err);
			});
		}
	}

	changePaymentDetails() {
		this.setState({changePaymentDetails: true});
	}

	cancelChangePaymentDetails() {
		this.setState({changePaymentDetails: false});
	}

	applyNewPaymentDetails() {
		//show loading indicator
		this.setState({loading: true, changePaymentDetails: false});

		//update the payment details
		let paymentService = new BillwerkPaymentService({
			publicApiKey: bwPublicKey,
			providerReturnUrl: finishUrl
		}, () => {
			this.portalService.paymentChange(
				paymentService,
				this.state.paymentDetails,
				(res) => {
					//handle black label psp
					if(res.Url) {
						location.href = res.Url;
					} else {
						this.setState({paymentErrorCode: null});
						this.refreshContract();
					}
				},
				(err) => {
					this.setState({
						loading: false,
						changePaymentDetails: true,
						paymentErrorCode: BearerErrorCode.getPaymentErrorMessage(_.head(err.errorCode))
					});
				}
			);
		}, () => {
			console.error('Error on creating payment service');
		});
	}

	render() {
		//loading spinner
		if (this.state.loading) {
			return (
				<div className="content-block text-center">
					<i className="fa fa-spin fa-circle-o-notch fa-4x"/><br/>Hole Daten...
				</div>
			)
		}

		return (
			<div>
				<div className="row">
					<div className="col-md-6">
						<div className="content-block">
							<fieldset>
								<legend>
									<i className={'pf ' + this.getPaymentIconName()}/> Ihre Zahlungsdaten
								</legend>

								{(() => {
									if (this.state.contract.PaymentsBlocked) {
										return (
											<p className="alert alert-danger">
												<strong>Achtung:</strong> Ihre aktuelle Zahlungsart kann momentan nicht
												verwendet werden. Bitte wählen Sie eine alternative Zahlungsweise oder
												kontaktieren Sie unseren Support!
											</p>
										)
									}
								})()}

								{(() => {
									//display payment method information
									switch (this.getContract().PaymentBearer.Type) {
										case 'CreditCard':
											return <CreditCard payment={this.getContract().PaymentBearer}/>
										case 'BankAccount':
											return <SepaDebit payment={this.getContract().PaymentBearer}/>
									}
								})()}

								{(() => {
									if (!this.state.changePaymentDetails) {
										return <a href={void(0)} onClick={this.changePaymentDetails}><i
											className="fa fa-pencil"/>
											Ändern</a>;
									}
								})()}
							</fieldset>

							{(() => {
								if (this.state.changePaymentDetails) {
									return (
										<fieldset>
											<legend>Zahlungsweise ändern</legend>

											<p>Bitte wählen Sie Ihre gewünschte Zahlungsart aus:</p>

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
												if (this.state.paymentErrorCode) {
													return (
														<div className="alert alert-danger">
															{this.state.paymentErrorCode}
														</div>
													)
												}
											})()}

											{(() => {
												switch (this.state.paymentMethod) {
													case 'Debit:FakePSP':
														return <SepaDebitComponent
															onChange={this.updatePaymentDetails}/>
													case 'CreditCard:FakePSP':
														return <CreditCardComponent
															onChange={this.updatePaymentDetails}/>
												}
											})()}

											<button className="btn btn-success btn-sm"
													disabled={!this.state.paymentDetailsValid}
													onClick={this.applyNewPaymentDetails}>
												<i className="fa fa-save fa-fw"/>
												Neue Zahlungsart speichern
											</button>
											oder <a href={void(0)}
													onClick={this.cancelChangePaymentDetails}>Abbrechen</a>
										</fieldset>
									)
								}
							})()}
						</div>

						<div className="content-block">
							<fieldset>
								<legend>
									<i className="fa fa-file-text-o fa-fw"/> Ihre Dokumente
								</legend>

								<div className="list-group">
									{(() => {
										return _.map(this.state.contract.RecentInvoices, (invoice) => {
											return (
												<a className="list-group-item" href={void(0)} key={invoice.Id}>
													<span className="pull-right text-muted small">
														{invoice.TotalGross} {this.getContract().Currency}
													</span>

													<a href={void(0)} onClick={() => this.downloadInvoice(invoice.Id)}>
														<i className="fa fa-download fa-fw"/>
													</a>

													{invoice.InvoiceNumber} <span
													className="small text-muted">vom {moment(invoice.InvoiceDate).format('ll')}</span>
												</a>
											)
										});
									})()}
								</div>
							</fieldset>
						</div>
					</div>
					<div className="col-md-6">
						<div className="content-block">
							<fieldset>
								<legend>
									<i className="fa fa-pencil-square-o  fa-fw"/>
									Ihr aktuelles Abonnement
								</legend>

								<table className="table">
									<tbody>
									<tr>
										<th>Paket</th>
										<td>
											{this.getCurrentPlan().PlanName} (<span
											className="text-muted small">seit {moment(this.getCurrentPlan().StartDate).format('ll')}</span>)
										</td>
									</tr>
									{(() => {
										if (this.getContract().EndDate) {
											return (
												<tr>
													<th>Kündigung zum</th>
													<td>{moment(this.getContract().EndDate).format('LL')}</td>
												</tr>
											)
										} else {
											return (
												<tr>
													<th>Nächste Abrechnung</th>
													<td>{moment(this.getContract().NextBillingDate).format('LL')}</td>
												</tr>
											)
										}
									})()}
									</tbody>
								</table>

								{(() => {
									if (!this.getContract().EndDate) {
										return (
											<a href="#" className="pull-right small" onClick={this.doCancellation}>Jetzt
												kündigen</a>
										)
									}
								})()}

							</fieldset>
						</div>

						<div className="content-block">
							<fieldset>
								<legend>
									<i className="fa fa-money fa-fw"/> Aktueller Saldo
								</legend>

								<p className={'lead text-center ' + (this.getContract().CurrentBalance <= 0 ? 'text-success' : 'text-danger')}>
									{this.getContract().CurrentBalance * -1} {this.getContract().Currency}
								</p>

								{(() => {
									if (this.getContract().CurrentBalance < 0) {
										return (
											<p className="text-muted small text-justify">
												<i className="fa fa-info-circle fa-fw text-info"/>
												Ihr Saldo wird automatisch mit der nächsten Gutschrift verrechnet.
											</p>
										)
									} else if (this.getContract().CurrentBalance > 0) {
										return (
											<p className="text-muted small text-justify">
												<i className="fa fa-info-circle fa-fw text-info"/>
												Dieser Betrag ist mit der nächsten Abrechnung zur Zahlung fällig.
												Sollten Sie Ihre Zahlungseinstellungen jetzt ändern, würde dieser Betrag
												sofort abgerechnet.
											</p>
										)
									}
								})()}
							</fieldset>
						</div>

					</div>
				</div>
			</div>
		)
	}

}

if (document.getElementById('contract-detail')) {
	ReactDOM.render(<ContractComponent/>, document.getElementById('contract-detail'));
}
