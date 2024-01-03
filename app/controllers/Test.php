<?php

	use Services\QRTokenService;
	load(['QRTokenService'], APPROOT.DS.'services');
	
	class Test extends Controller
	{
		public function index() {
			QRTokenService::renewOrCreate(QRTokenService::LOGIN_TOKEN);
		}
	}