import React, {Component} from 'react';
import moment from 'moment';

moment.locale('de');

export default class CreditCard extends Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div>
				<table className="table table-condensed">
					<tbody className="small">
					<tr>
						<th>Zahlungsart</th>
						<td>Kreditkarte ({this.props.payment.CardType})</td>
					</tr>
					<tr>
						<th>Kartennummer</th>
						<td>**** **** **** {this.props.payment.Last4}</td>
					</tr>
					<tr>
						<th>Gültig bis</th>
						<td>{this.props.payment.ExpiryMonth} / {this.props.payment.ExpiryYear}</td>
					</tr>
					<tr>
						<th>Inhaber</th>
						<td>{this.props.payment.Holder}</td>
					</tr>
					</tbody>
				</table>
			</div>
		)
	}
}
