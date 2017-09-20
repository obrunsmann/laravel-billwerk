import React, {Component} from 'react';
import CardReactFormContainer from 'card-react';
import 'card-react/lib/card.css';

export default class CreditCartComponent extends Component {
	render() {
		return (
			<div>
				<CardReactFormContainer
					container="card-wrapper"
					formInputsNames={
						{
							number: 'CCnumber',
							expiry: 'CCexpiry',
							cvc: 'CCcvc',
							name: 'CCname'
						}
					}
					classes={
						{
							valid: 'valid-input',
							invalid: 'invalid-input'
						}
					}
					formatting={true}
				>

					<div id="card-wrapper"></div>

					<div style={{marginTop: 20}}>
						<div className="form-group">
							<input placeholder="Nummer" type="text" name="CCnumber" className="form-control"/>
						</div>

						<div className="row">
							<div className="col-md-6">
								<div className="form-group">
									<input placeholder="MM / JJ" type="text" name="CCexpiry"
										   className="form-control"/>
								</div>
							</div>
							<div className="col-md-6">
								<div className="form-group">
									<input placeholder="CVC" type="text" name="CCcvc" className="form-control"/>
								</div>
							</div>
						</div>

						<div className="form-group">
							<input placeholder="Name auf der Karte" type="text" name="CCname" className="form-control"/>
						</div>
					</div>


				</CardReactFormContainer>
			</div>
		)
	}
}
