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

export default class FinishComponent extends Component {
	constructor() {
		super();

		this.state = {
			loadingOverlay: true,
			error: false
		};
	}

	componentDidMount() {
		let me = this;
		BillwerkJS.finalize(function () {
			me.setState({loadingOverlay: false});
		}, function (err) {
			me.setState({error: true});
		});
	}

	render() {
		// error message
		if (this.state.error === true) {
			return (
				<div className="content-block">
					<p className="alert alert-danger">
						Es ist ein Fehler aufgetreten. Bitte kontaktieren Sie den Support!
					</p>
				</div>
			)
		}

		// loading indicator
		if (this.state.loadingOverlay === true) {
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

		// success message
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
	}
}

if (document.getElementById('order-finish')) {
	ReactDOM.render(<FinishComponent/>, document.getElementById('order-finish'));
}
