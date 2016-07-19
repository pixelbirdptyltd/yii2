<?php
/**
 * @copyright Copyright (c) 2016 PIXEL BIRD PTY LTD
 * @link http://www.pixelbird.com.au
 * @author Dustin Gray
 * @version 1.0
 */
namespace common\components;

use Yii;
use yii\base\Component;
use SoapClient;

/**
 * Extends Yii2 Compnent to connect to the EziDebit v3.5 Web Services API

class EziDebitApi extends Component
{
	public $digitalKey = 'INSERT YOUR DIGITAL KEY HERE';
	public $pci = 'https://api.demo.ezidebit.com.au/v3-5/pci?singleWsdl'; // PCI SANDBOX CHANGE IN PRODUCTION
	public $nonPci = 'https://api.demo.ezidebit.com.au/v3-5/nonpci?singleWsdl'; // NON-PCI SANDBOX CHANGE IN PRODUCTION

	/**
	 * EZIDEBIT SCHEDULED PAYMENTS
	 * 4.3 Web Services
	 */

	/**
	 * 4.3.1 Process Realtime Credit Card Payment
	 *
	 * This method allows you to process a credit card payment in real time.
	 *
	 * @param array $data
	 */
	public function processRealtimeCreditCardPayment($data)
	{
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'CreditCardNumber' => $data['cardNumber'],
				'CreditCardExpiryMonth' => $data['cardExpiryMonth'],
				'CreditCardExpiryYear' => $data['cardExpiryYear'],
				'CreditCardCCV' => $data['cardCvv'],
				'NameOnCreditCard' => $data['cardName'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'CustomerName' => $data['customerFullName'],
				'PaymentReference' => $data['paymentRef']
		];

		return $soapclient->processRealtimeCreditCardPayment($params);
	}

	/**
	 * 4.3.2 Process Realtime Token Payment
	 *
	 * This method allows you to process a credit card payment in real time using a
	 * customer’s credit card number previously stored by Ezidebit. This requires a
	 * token to be passed in to identify the correct customer credit card.
	 *
	 * @param array $data
	 */
	public function processRealtimeTokenPayment($data) {
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'token' => $data['token'],
				'paymentAmountInCents' => $data['paymentAmountInCents'],
				'customerName' => $data['customerName'],
				'PaymentReference' => $data['paymentRef']
		];

		return $soapclient->processRealtimeTokenPayment($params);
	}

	/**
	 * EZIDEBIT SCHEDULED PAYMENTS
	 * 5.3 Web Services
	 */

	/**
	 * 5.3.1 Add Bank Debit
	 *
	 * This method is designed to accept requests that include both
	 * Customer (Payer) and Payment details in a single call. It will
	 * create, update or maintain a Customer and schedule the payment
	 * to be debited from the account. A system utilising this method
	 * will provide a unique Payer Reference from the integrating
	 * system as well as a unique Payment Reference for each payment.
	 *
	 * @param array $data
	 */
	public function addBankDebit($data) {
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'YourSystemReference' => $data['systemRef'],
				'YourGeneralReference' => $data['generalRef'],
				'LastName' => $data['lastName'],
				'FirstName' => $data['firstName'],
				'EmailAddress' => $data['email'],
				'MobilePhoneNumber' => $data['phone'],
				'PaymentReference' => $data['paymentRef'],
				'BankAccountName' => $data['bankAccountName'],
				'BankAccountBSB' => $data['bankAccountBSB'],
				'BankAccountNumber' => $data['bankAccountNumber'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'DebitDate' => $data['debitDate'],
				'SmsPaymentReminder' => $data['smsReminder'],
				'SmsFailedNotification' => $data['smsFailed'],
				'SmsExpiredCard' => $data['smsExpired'],
				'Username' => $data['username']
		];

		return $soapclient->addBankDebit($params);
	}

	/*
	 * 5.3.2 Add Card Debit
	 *
	 * This method is designed to accept requests that include both
	 * Customer (Payer) and Payment details in a single call. It will
	 * either create, update or maintain a Customer and schedule the
	 * payment to be debited from the account. A system utilising this
	 * method will provide a unique Payer Reference from the integrating
	 * system as well as a unique Payment Reference for each payment.
	 *
	 * @param array $data
	 */
	public function addCardDebit($data) {
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'YourSystemReference' => $data['systemRef'],
				'YourGeneralReference' => $data['generalRef'],
				'LastName' => $data['lastName'],
				'FirstName' => $data['firstName'],
				'EmailAddress' => $data['email'],
				'MobilePhoneNumber' => $data['phone'],
				'PaymentReference' => $data['paymentRef'],
				'NameOnCreditCard' => $data['cardName'],
				'CreditCardNumber' => $data['cardNumber'],
				'CreditCardExpiryMonth' => $data['cardExpiryMonth'],
				'CreditCardExpiryYear' => $data['cardExpiryYear'],
				'CreditCardCCV' => $data['cardCvv'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'DebitDate' => $data['debitDate'],
				'SmsPaymentReminder' => $data['smsReminder'],
				'SmsFailedNotification' => $data['smsFailed'],
				'SmsExpiredCard' => $data['smsExpired'],
				'Username' => $data['username']
		];

		return $soapclient->addCardDebit($params);
	}

	/**
	 * 5.3.3 Add Customer
	 *
	 * This method creates a new customer record in the Ezidebit
	 * database from which you will be able to debit payments.
	 *
	 * @param array $data
	 */
	public function addCustomer($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'YourSystemReference' => $data['systemRef'],
				'YourGeneralReference' => $data['generalRef'],
				'LastName' => $data['lastName'],
				'FirstName' => $data['firstName'],
				'AddressLine1' => $data['addressLine1'],
				'AddressLine2' => $data['addressLine2'],
				'AddressSuburb' => $data['suburb'],
				'AddressState' => $data['state'],
				'AddressPostCode' => $data['postCode'],
				'EmailAddress' => $data['email'],
				'MobilePhoneNumber' => $data['phone'],
				'ContractStartDate' => $data['startDate'],
				'SmsPaymentReminder' => $data['smsReminder'],
				'SmsFailedNotification' => $data['smsFailed'],
				'SmsExpiredCard' => $data['smsExpired'],
				'Username' => $data['username']
		];

		return $response = $soapclient->addCustomer($params);
	}

	/**
	 * 5.3.4 Add Payment
	 *
	 * This method is used to add a single payment to the Customer’s
	 * payment schedule to be debited on the date provided in the
	 * DebitDate field.
	 *
	 * The existing payment schedule may or may
	 * not already have payments that have been added individually
	 * or as part of a created schedule. This method will simply
	 * create a new payment for the identified customer on the date
	 * provided.
	 *
	 * A PaymentReference may be included in the data. The PaymentReference
	 * can then be used later to retrieve the status of, or full transaction
	 * details of the payment. The payment reference is maintained within
	 * Ezidebit’s systems and is included in some reporting to the client,
	 * however the PaymentReference value will not appear on a customer’s
	 * bank account or credit card statement.
	 *
	 * @param array $data
	 */
	public function addPayment($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'DebitDate' => $data['debitDate'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'PaymentReference' => $data['paymentRef'],
				'Username' => $data['username']
		];

		return $response = $soapclient->addPayment($params);
	}

	/**
	 * 5.3.5 Add Payment Unique
	 *
	 * This method is used to add a single payment to the Customer’s
	 * payment schedule to be debited on the date provided in the
	 * DebitDate field.
	 *
	 * @param array $data
	 */
	public function addPaymentUnique($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'DebitDate' => $data['debitDate'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'PaymentReference' => $data['paymentRef'],
				'Username' => $data['username']
		];

		return $response = $soapclient->addPaymentUnique($params);
	}

	/**
	 * 5.3.6 Change Customer Status
	 *
	 * This method allows you to change the processing status of a Customer record.
	 *
	 * @param array $data
	 */
	public function changeCustomerStatus($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'NewStatus' => $data['newStatus'],
				'Username' => $data['username']
		];

		return $response = $soapclient->changeCustomerStatus($params);
	}

	/**
	 * 5.3.7 Change Scheduled Amount
	 *
	 * This method allows you to change the debit amount for one or more payments
	 * that exist in the Customer’s payment schedule.
	 *
	 * @param array $data
	 */
	public function changeScheduledAmount($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'ChangeFromPaymentNumber' => $data['fromPaymentNumber'],
				'ChangeFromDate' => $data['changeFromDate'],
				'NewPaymentAmountInCents' => $data['newPaymentAmountInCents'],
				'ApplyToAllFuturePayments' => $data['applyToAllFuturePayments'],
				'Username' => $data['username']
		];

		return $response = $soapclient->changeScheduledAmount($params);
	}

	/**
	 * 5.3.8 Change Scheduled Date
	 *
	 * This method allows you to change the debit date for a single payment
	 * in a Customer’s payment schedule.
	 *
	 * @param array $data
	 */
	public function changeScheduledDate($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'ChangeFromDate' => $data['changeFromDate'],
				'PaymentReference' => $data['paymentRef'],
				'ChangeToDate' => $data['changeToDate'],
				'KeepManualPayments' => $data['keepManualPayments'],
				'Username' => $data['username']
		];

		return $response = $soapclient->changeScheduledDate($params);
	}

	/**
	 * 5.3.9 Clear Schedule
	 *
	 * Payment schedule is a concept used for managing recurring payments
	 * for the costs of goods or services. There are two methods which a
	 * payment schedule can be utilised, payment to a specified total amount
	 * for cost of goods or ongoing schedule for a subscription or service.
	 *
	 * In some situations, cancelling the payment schedule is required but
	 * within the Ezidebit system cancelling a schedule doesn’t mean the party
	 * is cancelled from the system, only that payments will not be drawn.
	 * The party is still active in the system but will not be debited.
	 *
	 * Cancelled schedules will move a party to an ongoing and on-demand status.
	 * The payer is not removed because they and their payment methods are still
	 * valid, only inactive and not being debited. In this status, the payer
	 * can be billed again without needing the AddPayer process to occur.
	 * One-off payments can be triggered manually or a new payment schedule
	 * can be created.
	 *
	 * The ClearSchedule method will remove payments that exist in the payment
	 * schedule for the given customer. You can control whether all payments are
	 * deleted, or if you wish to preserve any manually added payments, and delete
	 * an ongoing cyclic schedule. The customer will continue to exist, the future
	 * payments will have been cleared and ongoing payments will be manually triggered.
	 *
	 * @param array $data
	 */
	public function clearSchedule($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'PaymentReference' => $data['paymentRef'],
				'KeepManualPayments' => $data['keepManualPayments'],
				'Username' => $data['username']
		];

		return $response = $soapclient->clearSchedule($params);
	}

	/**
	 * 5.3.10 Create Schedule
	 *
	 * This method allows you to create a new schedule for the given Customer.
	 * It will create a schedule for on-going debits (up to 20 payments will
	 * exist at a point in time), or will calculate a schedule to fulfil a
	 * required total payment amount, or number of payments.
	 *
	 * @param array $data
	 */
	public function createSchedule($data) {
		
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'ScheduleStartDate' => $data['scheduleStartDate'],
				'SchedulePeriodType' => $data['schedulePeriodType'],
				'DayOfWeek' => $data['dayOfWeek'],
				'DayOfMonth' => $data['dayOfMonth'],
				'FirstWeekOfMonth' => $data['firstWeekOfMonth'],
				'SecondWeekOfMonth' => $data['secondWeekOfMonth'],
				'ThirdWeekOfMonth' => $data['thirdWeekOfMonth'],
				'FourthWeekOfMonth' => $data['fourthWeekOfMonth'],
				'PaymentAmountInCents' => $data['paymentAmountInCents'],
				'LimitToNumberOfPayments' => $data['limitToNumberOfPayments'],
				'LimitToTotalAmountInCents' => $data['limitToTotalAmountInCents'],
				'KeepManualPayments' => $data['keepManualPayments'],
				'Username' => $data['username']
		];

		return $soapclient->createSchedule($params);
	}
	/**
	 * 5.3.11
	 *
	 * This method will allow for either the deletion of an entire payment schedule
	 * or the deletion of one specific scheduled payment.
	 *
	 * @param array $data
	 */
	public function deletePayment($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'PaymentReference' => $data['paymentRef'],
				'DebitDate' => $data['debitDate'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'Username' => $data['username']
		];

		return $response = $soapclient->deletePayment($params);
	}

	/**
	 * 5.3.12 Edit Customer Bank Account
	 *
	 * This method will Add or Edit the Bank Account detail on record for a Customer (Payer).
	 *
	 * @param array $data
	 */
	public function editCustomerBankAccount($data) {
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'YourSystemReference' => $data['systemRef'],
				'BankAccountName' => $data['bankAccountName'],
				'BankAccountBSB' => $data['bankAccountBSB'],
				'BankAccountNumber' => $data['bankAccountNumber'],
				'PaymentAmountInCents' => $data['amountInCents'],
				'Reactivate' => $data['reactivate']
		];

		return $soapclient->editCustomerBankAccount($params);
	}

	/**
	 * 5.3.13 Edit Customer Credit Card
	 *
	 * This method will Add or Edit the Credit Card detail on record for a Customer (Payer).
	 *
	 * @param array $data
	 */
	public function editCustomerCreditCard($data) {
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'NameOnCreditCard' => $data['cardName'],
				'CreditCardNumber' => $data['cardNumber'],
				'CreditCardExpiryYear' => $data['cardExpiryYear'],
				'CreditCardExpiryMonth' => $data['cardExpiryMonth'],
				'Reactivate' => $data['reactivate'],
				'Username' => $data['username']
		];

		return $soapclient->editCustomerCreditCard($params);
	}

	/**
	 * 5.3.14 Edit Customer Details
	 *
	 * This method allows you to update the details for an existing customer within the
	 * Ezidebit system.
	 *
	 * @param array $data
	 */
	public function editCustomerDetails($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'YourGeneralReference' => $data['generalRef'],
				'LastName' => $data['lastName'],
				'FirstName' => $data['firstName'],
				'AddressLine1' => $data['addressLine1'],
				'AddressLine2' => $data['addressLine2'],
				'AddressSuburb' => $data['suburb'],
				'AddressState' => $data['state'],
				'AddressPostCode' => $data['postCode'],
				'EmailAddress' => $data['email'],
				'MobilePhoneNumber' => $data['phone'],
				'ContractStartDate' => $data['startDate'],
				'SmsPaymentReminder' => $data['smsReminder'],
				'SmsFailedNotification' => $data['smsFailed'],
				'SmsExpiredCard' => $data['smsExpired'],
				'Username' => $data['username']
		];

		return $response = $soapclient->editCustomerDetails($params);
	}

	/**
	 * 5.3.15 Get Customer Account Details
	 *
	 * This method allows you to retrieve the Bank Account or Credit Card details
	 * recorded for the customer. This includes both the bank account and
	 * credit card details where available.
	 *
	 * @param array $data
	 */
	public function getCustomerAccountDetails($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef']
		];

		return $response = $soapclient->getCustomerAccountDetails($params);
	}

	/**
	 * 5.3.16 Get Customer Details
	 *
	 * This method retrieves details about the given Customer.
	 *
	 * @param array $data
	 */
	public function getCustomerDetails($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef']
		];

		return $response = $soapclient->getCustomerDetails($params);
	}

	/**
	 * 5.3.17 Get Customer Fees
	 *
	 * This method allows you to retrieve data relating to fees paid by
	 * the customer. This includes:
	 * - Standard customer fees for all products configured for the client;
	 * - Customer fees for a specific customer;
	 * - Standard customer fees for a selected payment source or all payment
	 *   sources (Scheduled, Web, Phone).
	 *
	 *   @param array $data
	 */
	public function getCustomerFees($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'PaymentSource' => $data['paymentSource'],
				'Username' => $data['username']
		];

		return $response = $soapclient->getCustomerFees($params);
	}

	/**
	 * 5.3.18 Get Customer List
	 *
	 * This method allows you to retrieve customer information from Ezidebit’s
	 * direct debit payment system.
	 *
	 * @param array $data
	 */
	public function getCustomerList($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->pci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef'],
				'CustomerStatus' => $data['customerStatus'],
				'OrderBy' => $data['orderBy'],
				'Order' => $data['order'],
				'PageNumber' => $data['pageNumber']
		];

		return $response = $soapclient->getCustomerList($params);
	}

	/**
	 * 5.3.19 Get Payments
	 *
	 * This method allows you to retrieve payment information from across Ezidebit’s
	 * various payment systems. It provides you with access to scheduled, pending and
	 * completed payments made through all payment channels.
	 *
	 * @param array $data
	 */
	public function getPayments($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'PaymentType' => $data['paymentType'],
				'PaymentMethod' => $data['paymentMethod'],
				'PaymentSource' => $data['paymentSouce'],
				'PaymentReference' => $data['paymentReference'],
				'DateFrom' => $data['dateFrom'],
				'DateTo' => $data['dateTo'],
				'DateField' => $data['dateField'],
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef']
		];

		return $response = $soapclient->getPayments($params);
	}

	/**
	 * 5.3.20 Get Payment Detail
	 *
	 * This method retrieves details about the given payment. It can only be used to
	 * retrieve information about payments where Ezidebit was provided with a
	 * PaymentReference.
	 *
	 * @param array $data
	 */
	public function getPaymentDetail($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'PaymentReference' => $data['paymentReference']
		];

		return $response = $soapclient->getPaymentDetail($params);
	}

	/**
	 * 5.3.21 Get Payment Status
	 *
	 * This method allows you to retrieve the status of a particular payment from the
	 * direct debit system where a PaymentReference has been provided.
	 *
	 * @param array $data
	 */
	public function getPaymentStatus($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'PaymentReference' => $data['paymentReference']
		];

		return $response = $soapclient->getPaymentStatus($params);
	}

	/**
	 * 5.3.22 Get Scheduled Payments
	 *
	 * This method allows you to retrieve information about payments that are scheduled
	 * for a given Customer in the Ezidebit direct debit system.
	 *
	 * @param array $data
	 */
	public function getScheduledPayments($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'DateFrom' => $data['dateFrom'],
				'DateTo' => $data['dateTo'],
				'EziDebitCustomerID' => $data['eziDebitCID'],
				'YourSystemReference' => $data['systemRef']
		];

		return $response = $soapclient->getScheduledPayments($params);
	}

	/**
	 * 5.3.23 Is Bsb Valid
	 *
	 * This method allows you to verify if a BSB number is in our system.
	 *
	 * @param array $data
	 */
	public function isBsbValid($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'BankAccountBSB' => $data['bankAccountBSB']
		];

		return $response = $soapclient->isBsbValid($params);
	}

	/**
	 * 5.3.24 Payment Exchange Version
	 *
	 * This method returns the version of our web services and API that you are
	 * connecting to. This can be used as a check to ensure that you’re connecting
	 * to the web services that you expect to, based on the API document that you have.
	 */
	public function paymentExchangeVersion() {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		return $response = $soapclient->paymentExchangeVersion();
	}

	/**
	 * 5.3.25 Process Refund
	 *
	 * This method allows you to process a refund for a real-time credit card payment or
	 * a direct debit payment from a bank account/credit card. Refunds can only be processed
	 * for successful payments and must reference the PaymentIDs of the original transaction.
	 *
	 * Where a product description was supplied for a transaction that was processed via
	 * WebPay, this cannot be used as a search criteria in performing a refund as the Product
	 * Description field is not unique. In the API submission, a unique identifier is required
	 * to identify the single transaction to match and perform the refund against.
	 */
	public function processRefund($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'DigitalKey' => $this->digitalKey,
				'PaymentID' => $data['paymentId'],
				'BankReceiptID' => $data['bankReceiptId'],
				'RefundAmountInCents' => $data['refundAmountInCents']
		];

		return $response = $soapclient->processRefund($params);
	}

	/**
	 * 5.3.26 Test Function
	 *
	 * This method is provided for development and testing purposes. It simply allows you to
	 * connect to the web services, send data and receive the same value back with a “RECEIVED OK”
	 * message appended to the end of it. It is recommended that you begin with this method to
	 * ensure proper connectivity and handling of returned data.
	 */
	public function testFunction($data) {
		// provide the EziDebit API endpoint
		$soapclient = new SoapClient($this->nonPci);

		$params = [
				'ParameterToTest' => $data['parameterToTest']
		];

		return $response = $soapclient->testFunction($params);
	}
}
