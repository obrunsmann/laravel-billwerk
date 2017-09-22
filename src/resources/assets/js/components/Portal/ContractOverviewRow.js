import React, {Component} from 'react';
import axios from 'axios';
import moment from 'moment';

import 'paymentfont/css/paymentfont.css';

moment.locale('de');

export default class ContractOverviewRow extends Component {

	constructor(props) {
		super(props);

		this.state = {
			contract: {},
			loading: true
		};
	}

	componentWillMount() {
		//get the token for the contract
		axios.get('/api/billing/contract/' + this.props.contract + '/token')
			.then((res) => {
				this.token = res.data.Token;

				//get the portal service
				this.portalService = new BillwerkJS.Portal(this.token);
				this.portalService.contractDetails((contract) => {
					this.setState({
						contract,
						loading: false
					});
				}, (err) => console.error);
			});
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

	render() {
		if (this.state.loading) {
			return (
				<tr>
					<td><i className="fa fa-spin fa-circle-o-notch fa-fw"></i> Lade Details..</td>
				</tr>
			)
		} else {
			return (
				<tr key={this.props.contract} className="small">
					<td>
						<a href={'/account/contract/' + this.props.contract}>
							<strong>{this.getCurrentPlan().PlanName}</strong><br/>
							{this.props.reference}
						</a>
					</td>
					<td>
						{(() => {
							if (this.getContract().EndDate) {
								return (
									<span>
									<strong>Letzte Abrechnung:</strong><br/>
										{moment(this.getContract().EndDate).format('ll')}
									</span>
								)
							} else {
								return (
									<span>
						<strong>Nächste Abrechnung:</strong><br/>
										{moment(this.getContract().NextBillingDate).format('ll')}
									</span>
								)
							}
						})()}
					</td>
					<td>
						<i className={'pf ' + this.getPaymentIconName()}></i>
					</td>
				</tr>
			)
		}
	}
}
