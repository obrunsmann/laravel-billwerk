<?php

namespace Lefamed\LaravelBillwerk\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Lefamed\LaravelBillwerk\Models\Contract;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class OrderSucceeded
 * @package Lefamed\LaravelBillwerk\Notifications
 */
class OrderSucceeded extends Notification
{
	use Queueable;

	/**
	 * @var \stdClass
	 */
	private $order;

	/**
	 * OrderSucceeded constructor.
	 * @param \stdClass $order
	 */
	public function __construct(\stdClass $order)
	{
		$this->order = $order;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->subject(trans('ld-billwerk::notifications.orderSucceeded.subject'))
			->greeting(trans('ld-billwerk::notifications.headline', [
				'name' => $notifiable->customer_name
			]))
			->line(trans('ld-billwerk::notifications.orderSucceeded.line1', [
				'product' => $this->order->LineItems[0]->Description ?? ''
			]))
			->action(
				trans('ld-billwerk::notifications.orderSucceeded.action'),
				route('merchant.dashboard.index')
			);
	}
}
