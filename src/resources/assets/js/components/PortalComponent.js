import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import _ from 'lodash';

import ContractOverviewRow from './Portal/ContractOverviewRow';

export default class PortalComponent extends Component {
	constructor(props) {
		super(props);

		//initialize our state
		this.state = {};
	}

	componentWillMount() {
	}

	render() {
		return (
			<div>
				<fieldset>
					<legend>Ihre Verträge</legend>

					<table className="table table-condensed table-hover">
						<tbody>
						{(() => {
							return _.map(contracts, (reference, id) => {
								return <ContractOverviewRow key={id} contract={id} reference={reference}/>
							})
						})()}
						</tbody>
					</table>
				</fieldset>
			</div>
		)
	}
}


if (document.getElementById('customer-portal')) {
	ReactDOM.render(<PortalComponent/>, document.getElementById('customer-portal'));
}
