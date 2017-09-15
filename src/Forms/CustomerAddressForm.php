<?php

namespace Lefamed\LaravelBillwerk\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CustomerAddressForm
 *
 * @package Lefamed\LaravelBillwerk\Forms
 */
class CustomerAddressForm extends Form
{
	protected $languageName = 'ld-billwerk::entity.customer';

	public function buildForm()
	{
		$this
			->add('company_name', 'text')
			->add('first_name', 'text')
			->add('last_name', 'text')
			->add('street', 'text')
			->add('house_number', 'text')
			->add('postal_code', 'text')
			->add('city', 'text')
			->add('country', 'choice', [
				'choices' => [
					'DE' => 'DE'
				]
			])
			->add('email_address', 'text');
	}
}
