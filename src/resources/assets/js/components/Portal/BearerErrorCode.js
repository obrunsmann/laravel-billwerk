export default class BearerErrorCode {
	static getPaymentErrorMessage(errorCode) {
		let messages = {
			'UnmappedError': 'Unbekannter Fehler, versuchen Sie eine alternative Zahlungsart oder prüfen Sie die Eingabe Ihrer Daten erneut!',
			'Unknown': 'Unbekannter Fehler, versuchen Sie eine alternative Zahlungsart oder prüfen Sie die Eingabe Ihrer Daten erneut!',
			'InvalidBic': 'BIC ungültig!',
			'IteroServerError': 'Serverfehler, bitte versuchen Sie es in einem Augenblick erneut!',
			'PspServerError': 'Die Zahlung konnte nicht zum Zahlungsanbieter weitergeleitet werden. Bitte versuchen Sie es später erneut!',
			'Timeout': 'Zeitüberschreitung bei der Verbindung. Bitte versuchen Sie es später erneut.',
			'InvalidKey': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidConfiguration': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidPaymentData': 'Eingegebene Zahlungsweise ist nicht gültig. Bitte kontrollieren Sie Ihre Eingabe!',
			'InvalidData': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'Aborted': 'Zahlung wurde abgebrochen.',
			'AcquirerServerError': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'AuthorizationRejected': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'RateLimit': 'Zu häufige Anfragen. Bitte versuchen Sie es später erneut.',
			'InvalidTransaction': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'AmountLimitExceeeded': 'Die Zahlung wurde wegen mangelnder Deckung abgewiesen. Bitte nutze eine andere Zahlungsweise.',
			'InvalidPaymentMethod': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidCardType': 'Die eingegebene Kreditkarte wird von uns leider nicht unterstützt. Bitte wähle eine andere Zahlungsweise.',
			'InvalidCardNumber': 'Die eingegebene Kreditkartennummer ist nicht gültig.',
			'InvalidExpirationDate': 'Ablaufdatum ist ungültig.',
			'InvalidCardCvc': 'CVC (Sicherheitscode) ist ungültig. Bitte kontrollieren Sie Ihre Eingabe und versuchen Sie es erneut.',
			'InvalidCardHolder': 'Bitte kontrollieren Sie den Karteninhaber. Die Eingabe war nicht gültig.',
			'3DsProblem': '3D-Secure ist fehlgeschlagen. Bitte versuchen Sie es erneut oder wähle eine andere Zahlungsweise.',
			'InvalidAmount': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidCurrency': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InvalidAccountNumber': 'Fehlerhafte Kontonummer, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidAccountHolder': 'Fehlerhafter Kontoinhaber, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidBankCode': 'Fehlerhafte Bankleitzahl, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidIban': 'Fehlerhafte IBAN, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'InvalidCountry': 'Fehlerhaftes Land, bitte prüfen Sie Ihre Daten und versuchen Sie es erneut!',
			'BearerRejected': 'Die Zahlungsweise wurde abgelehnt. Details wurden uns aus Datenschutzgründen nicht übermittelt. Bitte versuchen Sie es erneut mit einer alternativen Zahlungsart!',
			'BearerExpired': 'Die Zahlungsweise ist abgelaufen. Bitte nutze eine alternative Zahlungsweise.',
			'InvalidCouponCode': 'Der Gutscheincode ist nicht gültig, bitte prüfen Sie Ihre Eingabe und versuchen Sie es erneut!',

			'LimitExceeded': 'Limit überschritten. Bitte nutze eine alternative Zahlungsweise.',
			'BearerInvalid': 'Die Zahlungsweise wurde abgelehnt. Details wurden uns aus Datenschutzgründen nicht übermittelt. Bitte versuchen Sie es erneut mit einer alternativen Zahlungsart!',
			'LoginError': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InsufficientBalance': 'Der Kontostand reicht leider nicht aus. Bitte versuchen Sie es später erneut.',
			'AlreadyExecuted': 'Die Zahlung wurde bereits abgesendet.',
			'InvalidPreconditions': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'InternalError': 'Interner Fehler. .',
			'InternalProviderError': 'Unbekannter Fehler.',
			'PermissionDenied': 'Unbekannter Fehler. Bitte versuchen Sie es später erneut.',
			'Canceled': 'Zahlung abgebrochen.',
			'Rejected': 'Zahlung zurückgewiesen.'
		};

		return messages[errorCode] || messages['Unknown'];
	}
}
