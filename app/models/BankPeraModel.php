<?php 	

	use Bank\Pera;

	require_once CLASSES.DS.'banks/Pera.php';



	class BankPeraModel extends Model 

	{



		public $table = 'bank_pera_accounts';

		

		public function getByUser($userId)

		{

			$this->db->query(

				" SELECT firstname , lastname , pera.* 

					FROM $this->table as pera

					

					LEFT JOIN users as user

					ON user.id = pera.user_id



					WHERE pera.user_id = '$userId' "

			);

			

			return $this->db->single();

		}



		public function testConnection($apiKey , $apiSecret)

		{

			return $this->getPeraAPI()->connectAuth($apiKey , $apiSecret);

		}



		public function apiRegister($apiKey , $apiSecret)

		{

			$peraApi = $this->getPeraAPI();



			$register = $peraApi->registerAuth($apiKey , $apiSecret);

			$response = $peraApi->response();



			return $response;

		}



		public function getPeraAPI()

		{

			$pera = new Pera();



			return $pera;

		}

	}