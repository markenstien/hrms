<?php 

	class ScannedCardController extends Controller
	{
		public function index()
		{
			return $this->view('scanned_card/index');
		}
	}