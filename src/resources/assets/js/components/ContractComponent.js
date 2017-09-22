import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';

moment.locale('de');

import SepaDebit from './Portal/PaymentMethods/SepaDebit';

import 'paymentfont/css/paymentfont.css';

export default class ContractComponent extends Component {

	constructor(props) {
		super(props);

		this.state = {
			loading: true,
			contract: {}
		};

		this.doCancellation = this.doCancellation.bind(this);
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

								<SepaDebit payment={this.getContract().PaymentBearer}/>

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
					</div>
				</div>
			</div>
		)
	}

}

if (document.getElementById('contract-detail')) {
	ReactDOM.render(<ContractComponent/>, document.getElementById('contract-detail'));
}
