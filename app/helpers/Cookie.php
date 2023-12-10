<?php 	

	class Cookie
	{

		public static $prefix = '_KDK_SUPA_COOKIE_';

		public static function set($name , $value , $availability = '/'){

			$cookieName = strtoupper(self::$prefix.$name);

			$time = time() + (86400 * 30);
			
			setcookie($cookieName , json_encode($value) , $time , $availability);
		}

		public static function get($name){

			$cookieName = strtoupper(self::$prefix.$name);

			if(isset($_COOKIE[$cookieName]))
				return json_decode($_COOKIE[$cookieName]);

			return FALSE;
		}


		public static function remove($name)
		{
			$cookieName = strtoupper(self::$prefix.$name);
			setcookie($cookieName, "", time() - 3600);
			return true;
		}
	}