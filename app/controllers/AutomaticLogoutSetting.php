<?php 

	

	class AutomaticLogoutSetting extends Controller

	{



		public function __construct()

		{

			$this->autologout = model('AutomaticLogoutSettingModel');

			$this->user = model('UserModel');

		}



		public function index()

		{

			$logoutSettings = $this->autologout->getMeta();

			

			return $this->view('logout_setting/index' , compact('logoutSettings'));

		}





		public function create()

		{

			$setting = $this->autologout->get($id);



			$data = [

				'account' => $user

			];



			return $this->view('logout_setting/create' , $data);

		}



		public function edit($id)

		{



			$setting = $this->autologout->get($id);



			$user = $this->user->get($setting->user_id);



			$data = [

				'setting' => $setting,

				'account' => $user

			];



			return $this->view('logout_setting/edit' , $data);

		}



		public function update()

		{

			$post = request()->posts();



			if(!validateMinutes($post['minutes']))

			{

				Flash::set("Invalid minutes" , 'danger');

				return request()->return();

			}

			/**OVERRIDDEN */

			$result = $this->autologout->update([

				'minutes' => $post['minutes'],

				'hours' => $post['hours'],

			] , $post['id']);



			if($result) {

				Flash::set("Duration updated");

			}



			return request()->return();

		}

	}