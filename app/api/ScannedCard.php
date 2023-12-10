<?php 
	

	class ScannedCard extends Controller
	{

		public function __construct()
		{
			$this->scanned_card_model = model('ScannedCardModel');   
		}

		public function getRecent()
		{
			$response = $this->scanned_card_model->getRecentCode();
			ee($response);
		}


		/**
		 * every 5 mins
		 * */
		public function clearCodes()
		{
			$this->scanned_card_model->clearCodes();
		}
	}