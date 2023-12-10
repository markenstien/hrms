<?php
	function wPacking($packing) {
		return "{$packing}'s";
	}

	function wBrands() {
		$retVal = [];
		$categoryModel = model('CategoryModel');
		$categoryModel->db->query(
			"SELECT name 
				FROM {$categoryModel->table}
					WHERE category = 'BRAND'
					GROUP BY name
					ORDER BY RAND()
					LIMIT 8"
		);

		$results = $categoryModel->db->resultSet();

		if($results) {
			foreach($results as $key => $row) {
				$retVal[] = $row->name;
			}
		}

		return $retVal;
	}
?>