<?php 	

	function db_get_user($userId)
	{
		$db = Database::getInstance();

		$tableUser  = DB_PREFIX.'users';
		$tablePersonal  = DB_PREFIX.'personal';

		$db->query(
			"SELECT user.* , personal.* , user.id as id 
				FROM $tableUser as user 

				LEFT JOIN $tablePersonal as personal
				ON user.id = personal.user_id

				WHERE user.id = {$userId} "
		);

		return $db->single();
	}


	function db_single($tableName , $fields = '*' , $condition = null, $orderby = null)
	{
		$db = Database::getInstance();


		if(is_array($condition))
			$condition = db_condition_equal($condition);

		$sql = db_query($tableName , $fields , $condition, $orderby);

		try{
			$db->query($sql);
			return $db->single();
		}catch(Exception $e)
		{
			return false;
		}	
	}


	function db_query($tableName , $fields = '*' , 
		$condition = null, $orderby= null , 
		$limit = null , $offset = null)
	{
		if(is_array($fields))
		{
			$sql = "SELECT  ".implode(',',$fields)." from $tableName";
		}else{
			$sql = "SELECT $fields from $tableName";
		}

		if(! is_null($condition)) {

			$sql .= " WHERE $condition ";
		}

		if(!is_null($orderby)) {
			$sql .= " ORDER BY $orderby";
		}

		if(!is_null($limit) && is_null($offset)) {
			$sql .= " LIMIT $limit";
		}

		if(!is_null($offset) && is_null($limit))
		{
			$sql .= " offset $offset";
		}

		if(!is_null($offset) && !is_null($limit))
		{
			$sql .= " LIMIT $offset , $limit";
		}

		return $sql;
	}


	function db_condition_equal($params)
	{
		$WHERE = '';

		$counter = 0;
		$increment = 0;

		foreach($params as $key => $row) 
		{
			if($counter < $increment){
				$WHERE .= ' AND ';
				$counter++;
			}

			$WHERE .= " $key = '{$row}'";

			$increment++;
		}

		return $WHERE;
	}