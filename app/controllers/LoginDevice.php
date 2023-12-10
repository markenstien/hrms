<?php 	

	class LoginDevice extends Controller
	{

		public function __construct()
		{
			$this->user = model('UserModel');

			$this->loginDevice = model('LoginDeviceModel');
		}


		public function index()
		{
			$registeredDevices = $this->loginDevice->getMeta();

			$data = [
				'registeredDevices' => $registeredDevices
			];

			return $this->view('login_device/index' , $data);
		}

		public function edit($id)
		{
			$device = $this->loginDevice->get($id);

			$users = arr_layout_keypair($this->user->all() , ['id' , 'firstname@lastname']);

			$data  = [
				'device' => $device,
				'users' => $users
			];

			return $this->view('login_device/edit' , $data);
		}

		public function create()
		{

			$noDevideUsers = $this->loginDevice->getNoDeviceLogin();

			$users = arr_layout_keypair($noDevideUsers , ['id' , 'firstname@lastname']);

			$data  = [
				'users' => $users
			];

			return $this->view('login_device/create' , $data);
		}

		public function store()
		{
			$post = request()->inputs();

			$res = $this->loginDevice->register([
				'userId' => $post['user_id'],
				'loginKey' => $post['login_key'],
				'deviceType'    => $post['type']
			]);

			if(!$res) {
				Flash::set($this->loginDevice->getError());
			}else{
				Flash::set("Device login saved");
			}

			return redirect('LoginDevice/index');
		}


		public function update()
		{
			$post = request()->posts();
			
			$response = $this->loginDevice->update([
				'login_key' => $post['login_key']
			],$post['id']);

			if($response) {
				Flash::set("Login key update successfull");
			}else{
				Flash::set("failed to update" , 'danger');
			}

			return request()->return();
		}
	}