<?php 	



	class LoginDeviceModel extends Model

	{



		public $table = 'device_logins';



		public function __construct()

		{

			parent::__construct();



			$this->user = model('UserModel');

		}



		public function register($params)

		{

			//check if user already has login on that device

			$deviceLogin = $this->getByUserAndDevice(...[

				$params['userId'],

				$params['deviceType']

			]);



			if($deviceLogin) 

			{

				$this->addError("User already has device");

				$this->device = $deviceLogin;

				return false;

			}

			return parent::store([

				'user_id' => $params['userId'],

				'type'    => $params['deviceType'],

				'login_key' => $params['loginKey']

			]);

		}





		public function loginKey()

		{



		}


		public function getByUserAndDevice($userId,$deviceType = 'rfid')

		{

			return parent::single([

				'user_id' => $userId,

				'type' => $deviceType

			]);

		}

		public function getByKeyAndDevice($loginKey,$deviceType)
		{	
			return parent::single([
				'login_key' => $loginKey,
				'type' => $deviceType
			]);
		}
		public function getNoDeviceLogin($deviceType = 'rfid')
		{
			$deviceLogins = parent::all();
			$userIds = [];
			foreach($deviceLogins as $login) {
				array_push($userIds, $login->user_id);
			}
			$users = $this->user->all(" not id in('".implode("','", $userIds)."')");
			return $users;
		}

		public function getMeta()
		{
			$deviceLogins = parent::all(null,'id desc');
			foreach($deviceLogins as $login) {
				$login->user = $this->user->single([
					'id' => $login->user_id
				]);
			}
			return $deviceLogins;
		}

	}