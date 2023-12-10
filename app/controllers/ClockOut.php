<?php 	
	
	class ClockOut extends Controller
	{


		public function __construct()
		{
			$this->model = model('TimelogModel');
			$this->clockOutModel = model('ClockOutModel');

		}

		public function clockOutAndPay()
		{
			$q = request()->inputs();

			$r = $this->clockOutModel->clockOut($q['userId']);

			if(!$r) {
				Flash::set( $this->clockOutModel->getErrorString()  , 'danger');
				return request()->return();
			}

			Flash::set("Clocked out and money sent to pera e");

			return request()->return();
		}
	}