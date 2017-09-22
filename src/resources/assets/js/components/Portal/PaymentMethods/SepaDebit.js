import React, {Component} from 'react';
import moment from 'moment';

moment.locale('de');

export default class SepaDebit extends Component {
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
						<td>SEPA-Lastschrift</td>
					</tr>
					<tr>
						<th>Kontoinhaber</th>
						<td>{this.props.payment.Holder}</td>
					</tr>
					<tr>
						<th>IBAN</th>
						<td>{this.props.payment.IBAN}</td>
					</tr>
					<tr>
						<th>BIC</th>
						<td>{this.props.payment.BIC}</td>
					</tr>
					<tr>
						<th>Mandatsreferenz</th>
						<td>
							{this.props.payment.MandateReference}<br/>
							unterzeichnet {moment(this.props.payment.MandateReferenceDate).format('ll')}
						</td>
					</tr>
					</tbody>
				</table>

				<a href="#"><i className="fa fa-pencil"/> Ändern</a>
			</div>
		)
	}
}
