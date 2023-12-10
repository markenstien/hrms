<?php 	



	class AutomaticLogoutSettingModel extends Model

	{

		public $table = 'automatic_logout_settings';

		



		public function getMeta()

		{

			$this->db->query(

				"SELECT user.firstname , user.lastname , user.username ,

					user.id as user_id , max_duration , logout_setting.id as id 

					FROM $this->table as logout_setting

					

					LEFT JOIN users as user 

					ON user.id = logout_setting.user_id order by user.firstname asc"

			);



			return $this->db->resultSet();

		}



		public function getByUser($userId)

		{

			return parent::single([

				'user_id' => $userId

			]);

		}



		/**

		 * Override

		 */

		public function update($values , $id)

		{

			$maxDurationInMinutes = $this->convertMaxDurationInHoursToMinutes($values['hours'] , $values['minutes']);

			

			return parent::update([

				'max_duration' => $maxDurationInMinutes,

			], $id);

		}



		public function convertMaxDurationInHoursToMinutes($hours , $minutes)

		{

			$hoursInMinutes = intval($hours* 60);

			$completeMinutes = intval($minutes);

			return $hoursInMinutes + $completeMinutes;

		}

	}