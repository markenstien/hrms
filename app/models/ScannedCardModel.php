<?php 	

	class ScannedCardModel extends Model
	{
		public $table = 'scanned_card';
		
		public function getRecentCode()
		{
			return parent::dbgetDesc('id' , null , 1);
		}

		public function clearCodes()
		{
			$this->db->query(
				"DELETE FROM {$this->table}"
			);

			return $this->db->execute();
		}
	}