<?php 
	
	namespace Classes\Payroll;

	class Payroll
	{

		private $intent = 'BUSINESS_TO_INDIVIDUAL_TRANSFER';

		private $auth = [];

		private $meta = [];

		private $origin = [
			'senderDomain'   => 'sender tmp',
			'recieverDomain' => 'reciever tmp'
		];

		private $recipient = [];

		private $amount = 0;

		/*
		*Business to Individual transfer
		*/
		public function send()
		{
			$dataStructure = [
				'intent' => $this->intent,

				'auth'   => $this->auth,

				'meta' => $this->meta,

				'origin' => $this->origin,

				'recipient' => $this->recipient,
				
				'amount' => $this->amount
  			];


  			$dataInJSON = json_encode($dataStructure);

  			$response = api_call('post' , 'http://dev.pera/api/BusinessToIndividual/send' , [
  				'data' => $dataInJSON
  			]);

  			dump($response);
		}


		public function setAmount($amount)
		{
			$this->amount = $amount;
		}

		public function setMeta($params)
		{
			$this->meta = [
				'description' => $params['description'],
				'controlNumber' => $params['controlNumber']
			];
		}

		public function setOrigin($params)
		{
			$this->origin = [
				'senderDomain'   => 'sender tmp',
				'recieverDomain' => 'reciever tmp'
			];
		}
		public function setRecipient($params)
		{
			$this->recipient = [
				'mobileNumber' => $params['mobileNumber'],
				'firstname'    => $params['firstname'],
				'lastname'     => $params['lastname']
			];
		}

		public function init($params)
		{
			$this->auth = [
				'key' => $params['key'],
				'secret' => $params['secret']
			];
		}
	}