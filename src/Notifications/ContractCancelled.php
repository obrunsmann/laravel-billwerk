<?php

namespace Lefamed\LaravelBillwerk\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Lefamed\LaravelBillwerk\Models\Contract;

/**
 * Class ContractCancelled
 * @package Lefamed\LaravelBillwerk\Notifications
 */
class ContractCancelled extends Notification
{
	use Queueable;

	/**
	 * @var Contract
	 */
	private $contract;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(Contract $contract)
	{
		$this->contract = $contract;
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
			->subject(trans('ld-billwerk::notifications.contractCancelled.subject'))
			->greeting(trans('ld-billwerk::notifications.headline', [
				'name' => $notifiable->customer_name
			]))
			->line(trans('ld-billwerk::notifications.contractCancelled.content', [
				'endDate' => Carbon::parse($this->contract->end_date)->format('d.m.Y')
			]))
			->line(trans('ld-billwerk::notifications.contractCancelled.content2'));
	}
}
