<?php namespace Bank; 	
	
	interface IBank
	{
		public function connectAuth($secret , $key);

		public function registerAuth($secret , $key);
	}