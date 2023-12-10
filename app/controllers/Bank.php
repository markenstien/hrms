<?php 	

	use Bank\Pera;



	require_once CLASSES.DS.'banks/Pera.php';



	class Bank extends Controller

	{	



		public function __construct()

		{

			$this->pera = model('BankPeraModel');

			$this->user = model('UserModel');

			// $this->banklog = model('BankTransferLogModel');



			$this->auth = whoIs();

		}

		/*

		*Connect to a bank

		*/

		public function create()

		{



			$userId = $_GET['userId'] ?? '';



			//if userid is not set then use logged user id

			if(empty($userId))

				$userId = $this->auth['id'];



            // $this->banklog->getByUser($userId)


			$data = [

				'pera' => $this->pera->getByUser($userId),

				'user' => $this->user->get($userId),

				'logs' => []

			];

			return $this->view('bank/create' , $data);

		}



		public function edit($peraId)

		{

			$pera = $this->pera->get($peraId);

			

			$data = [

				'pera' => $pera

			];



			return $this->view('bank/edit' , $data);

		}



		public function update()

		{

			if( isset($_POST['save']) )

			{

				$this->saveChanges();

			}



			if( isset($_POST['delete']) )

			{

				$this->delete();

			}

			

		}



		private function saveChanges()

		{

			$post = request()->posts();



			$isConnected = $this->pera->testConnection( $post['apiKey'] , $post['apiSecret'] );



			if($isConnected) 

			{

				Flash::set('Pera-E bank detail updated');



				$this->pera->update([

					'api_key' => $post->api_key,

					'api_secret' => $post->api_secret

				] , $post->id);

			}else{

				Flash::set("Invalid bank details cannot connect" , 'danger');

			}



			return request()->return();

		}



		private function delete()

		{

			$peraId = request()->post('pera_id');

			

			$deleteItem = $this->pera->delete($peraId);



			if($deleteItem) {

				Flash::set("Item Deleted" , 'danger');

			}

			

			return redirect('dashboard');

		}



		public function testConnection()

		{

			$peraId = request()->post('pera_id');

			$peraAccount = $this->pera->get($peraId);



			$pera = new Pera();

			$testConnection = $this->pera->testConnection($peraAccount->api_key , $peraAccount->api_secret);



			if($testConnection) {

				Flash::set('Connection ok');

			}else{

				Flash::set('Connection failed' , 'danger');

			}



			return request()->return();

		}



		public function register()

		{

			$post = request()->inputs();

			

			$response = $this->pera->apiRegister($post['apiKey'] , $post['apiSecret']);



			if($response->status) 

			{

				$data = $response->data;



				$result = $this->pera->store([

					'user_id' => $post['userId'],

					'api_key' => $data->apiKey,

					'api_secret' => $data->apiSecret,

					'account_number' => $data->accountNumber,



				]);



				if($result) {

					Flash::set("Bank connected");

				}

			}else{

				Flash::set("Something went wrong with the connection" , 'danger');

			}



			return request()->return();

		}

	}