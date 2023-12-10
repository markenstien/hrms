<?php 	
    /**
     * Responsible for api login
     * */

	use Services\QRTokenService;
	load(['QRTokenService'],APPROOT.DS.'services');
	load(['TimeCardResponse'] , APPROOT.DS.'classes');
	class Authentication extends Controller
	{

		public function __construct()
		{
			$this->loginDevice = model('LoginDeviceModel');
			$this->user = model('UserModel');
			$this->timelog = model('TimelogModel');
			$this->timelog_meta_model = model('TimelogMetaModel');
			$this->clockOutModel = model('ClockOutModel');
			$this->timeCardResponse = new TimeCardResponse();
			$this->loginSession = model('LoginSessionModel');
		}


        /*
        *Authenticating user using different platforms
        *rfid-login
        */  
		public function index()
		{
			/*
			*passed params device , loginkey
			*/
			$post = request()->inputs();
			$this->AUTH_RFID($post,'external');

		}

		private function AUTH_RFID($post)
		{
			$isActionOk = false;
			$todayMilitaryTime = todayMilitary();

			$device = $this->loginDevice->getByKeyAndDevice($post['loginKey'] , $post['deviceType']);

			$this->timeCardResponse->setPunchDateTime(date_long($todayMilitaryTime, 'M d ,Y h:i:s a'))
			->setPunchtime($todayMilitaryTime, 'h:i:s a')
			->setPunchDate($todayMilitaryTime, 'M d ,Y');

			//collect card info
			$this->timelog->db->query(
				"INSERT INTO scanned_card(card_key)
				VALUES('{$post['loginKey']}')"    
			);
			$this->timelog->db->insert();
			
			if(!$device){
				$this->timeCardResponse->addMessage("Your KeyCard is not yet registered.")
				->setUser(null)
				->setAction(null);
				return ee(api_response($this->timeCardResponse->getResponse(), $isActionOk));
			}
			

			$response = $this->timelog_meta_model->log($device->user_id);

			$this->timeCardResponse->setUser($this->timelog_meta_model->getUser())
			->setAction($this->timelog_meta_model->getAction());

			/**
			 * RESPONSE ISSUE
			 */
			if (!$response) {
				$this->timeCardResponse->addMessage($this->timelog_meta_model->getErrorString());
				return ee(api_response($this->timeCardResponse->getResponse(), $isActionOk));
			}
			/**
			 * NO ISSUE
			 */
			$action = $this->timelog_meta_model->getAction();

			if (isEqual($action, $this->timelog_meta_model::$CLOCKED_IN)) {
				$this->timeCardResponse->addMessage("Logged In");
			} else {
				$convertHoursToMinutes = minutesToHours($this->timelog_meta_model->getValidWorkHours());
				$this->timeCardResponse->addMessage("Logged Out");
				$this->timeCardResponse->addMessage("Total Time : {$convertHoursToMinutes}");
			}
			
			$isActionOk = true;
			return ee(api_response($this->timeCardResponse->getResponse(), $isActionOk));
		}



		public function QR_AUTH_LOADER()
		{
			$inputs = request()->inputs();
			if (!isset($inputs['token']))
				return $this->__flashNTE();
			$qrLastestToken = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);
			if($qrLastestToken != $inputs['token']){
				echo "
					<div><h1>QR-CODE EXPIRE PLEASE RE-SCAN</h1></div>
				";
				die();
			}
			/**
			 * check if token matched new qrcode.
			 */
			$this->loginSession->start();
			$token = $this->loginSession->token();
			if($token) {
				return redirect('QRLoginController/index');
			}
		}

		public function QR_END_AUTH()
		{

		}
		

		private function __flashNTE() {
			$html = "<h1>YOU ARE VIOLATING OUR COMPANIES TIMEKEEPING POLICY.</h1>";
			$html .= "<h1>BE AWARE THAT THIS OFFENSE IS NTE(Notice to Explain).</h1>";
			$html .= "<h1>SUCCEEDING VIOLATORS WILL BE RECORDED AND PROPER ACTION WILL BE TAKEN BY THE MANAGEMENT</h1>";

			echo "<div style='text-align:center;padding:30px;width:50%;margin:0px auto'>";
				echo "<div style='color:red'>";
					echo $html;
				echo "</div>";

				echo "<div>";
					echo "<strong>IMPORTANT</strong> : TAKING PICTURE OF TIMEKEEPING QRCODE IS NOT ALLOWED,QR CODE IS RANDOMLY CHANGING ON RANDOM MOMENTS";
				echo "</div>";
				
				echo "<hr>You can close this browser now,and scan the QR on provided outlets";
			echo "</div>";
			die();
		}
	}