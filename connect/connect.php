<?php
require_once('class.dbcontrol.php');
require_once('common.php');

class DB extends DbControl
{
	var $host = 'localhost';
	var $db = 'stresert_hms_jebba';
	var $user = 'root';
	var $pass = '';

	//var $host = "localhost:3306";
	//var $db = "jickadun_elearn";
	//var $user = "jickadun_admin";
	//var $pass = '!@#$james414';

	protected function ect($a, $b){return $a;}
	protected function dct($a, $b){return $a;}
	protected function construct(){
		
		try
		{
			$dbh = new PDO("mysql:host=". $this->host ."; dbname=". $this->db , $this->user,$this->pass);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (PDOException $e)
		{
			print ("Could not connect to server.\n");
			print ("getMessage(): " . $e->getMessage () . "\n");
		}
		
		return $dbh;}
	protected function array_to_pdo_params($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = "`$name` = ?";
 		 }
  		return implode(', ', $temp);}
	protected function array_to_pdo_key($array){
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = "`$name` ";
 		 }
  		return "( ".implode(', ', $temp)." )";}
	protected function object_to_array($data) {
		if ((! is_array($data)) and (! is_object($data))) return 'xxx'; //$data;
		
		$result = array();
		
		$data = (array) $data;
		foreach ($data as $key => $value) {
			if (is_object($value)) $value = (array) $value;
			if (is_array($value)) 
			$result[$key] = $value;
			else
				$result[$key] = $value;
		}
		
		return $result;
		}
	protected function array_or_array($d,$e){
		if(is_array($e)){
			$i =$e;
		}else{
		$i = explode(',',$e);
		}
		$item = '(';
		foreach($i as $ii){
			$item .= $d."='".$ii."' OR "; 
			}
		return substr($item,0,-3).')';}	
	protected function array_to_pdo_place($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = " ? ";
 		 }
  		return "( ".implode(', ', $temp)." )";}
	protected function array_to_pdo_value($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = " :$name ";
 		 }
  		return "( ".implode(', ', $temp)."  )";}
	protected function ifTableExist($a, $b, $c){
		try {
		 $result = $c->query("SELECT $a FROM $b LIMIT 1");
		} 
		catch (Exception $e) {
			return FALSE;
		}
    	return $b;}
	public function select($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL){
		$add = parent::whereClause($where);
		$add .= parent::orderByClause($orderby);
		if(isset($columns))
			{
				$col = parent::columnChoice($columns);
			}
		else
			{
			$col = '*';	
			}
		try
		{
		$sql = 'SELECT '. $col .' FROM `'. $table .'` '.$add;
				
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				
			while ($row = $sth->fetch(PDO::FETCH_OBJ)){
				array_push($rows, $row);
			}
			return $rows;
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}}
	public function selectOne($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL){
		$add = parent::whereClause($where);
		$add .= parent::orderByClause($orderby);
		if(isset($columns))
			{
				$col = parent::columnChoice($columns);
			}
		else
			{
			$col = '*';	
			}
		try
		{
	  $sql = 'SELECT '. $col .' FROM `'. $table .'` '.$add;
			
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			return $row;
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}}
	public function insert($table, $column){
		$num = NULL;
		try
			{
				$key = $this->array_to_pdo_key($column);
				$place = $this->array_to_pdo_place($column);
				$val = $this->array_to_pdo_value($column);
				$queryData = array_values($column);
			    $detail = "INSERT INTO `". $table ."`  ". $key ."	VALUES   ". $place ."";
				$dbh = $this->construct();
				if (!($sth = $dbh->prepare($detail)))
					{
					print ("errorInfo: ". print_r($dbh->errorInfo()) . "\n");
					}
				elseif (!$sth->execute($queryData))
						{
						print ("errorInfo: " . print_r($dbh->errorInfo())  . "\n");
						}
					else{
						$num = $dbh->lastInsertId();
						}
					
					
			}
		catch (PDOException $e)
			{
				$num = ("The statement failed.\n");
				$num .= ("getCode: ". $e->getCode () . "\n");
				$num .= ("getMessage: ". $e->getMessage () . "\n");
			}
			return($num);
		}
	public function update($table, $column, $where){
		$num = NULL;
		$add = parent::whereClause($where);
		try
			{
				
				$data = $this->array_to_pdo_params($column);
				$query = " UPDATE `". $table ."` SET ". $data ." ".$add;
				
				// Convert the data array to indexed and append the WHERE parameter(s) to it
				$queryData = array_values($column);
				$db =  $this->construct();
				$stmt = $db->prepare($query); // Obviously add the appropriate error handling
				$stmt->execute($queryData);
				if($stmt){$num = 1;}
					
			}
		catch (PDOException $e)
			{
				
				print ("The statement failed.\n");
				print ("getCode: ". $e->getCode () . "\n");
				print ("getMessage: ". $e->getMessage () . "\n");
			}
			return $num;
		}	
	public function delete($table, $where){
		$add = parent::whereClause($where);
		try
			{
				$dbh = $this->construct();
				$detail = "DELETE FROM `". $table ."`  ". $add ."";
				$dbh = $this->construct();
				$sth = $dbh->prepare($detail);
				$sth->execute ();
				return 1;
			}
		catch (PDOException $e)
			{
				print ("The statement failed.\n");
				print ("getCode: ". $e->getCode () . "\n");
				print ("getMessage: ". $e->getMessage () . "\n");
			}
		}
	public function createdbtable($table, $fields){
		
			$sql = "CREATE TABLE IF NOT EXISTS `$table` (";
			$pk  = '';
		
			foreach($fields as $field => $type)
			{
			  $sql.= "`".$type['name']."` ".$type['type']." ";
			  if(isset($type['num']) && $type['num'] > 0){
			  $sql.= " (".$type['num'].") ";
			  }
			  if ($type['PK'] == true)
			  {
				$pk = $type['name'];
				$sql.= ' AUTO_INCREMENT';
			  }
			  
			  if ($type['FK'] == true)
			  {
				$sql.= ' ,FOREIGN KEY (`'.$type['name'].'`) REFERENCES `'.$type['ref'].'` (`'.$type['refname'].'`)';
			  }
			  $sql.= ",";
			}
		
			$sql = rtrim($sql,',') . ', PRIMARY KEY (`'.$pk.'`)';
		
			$sql .= ") CHARACTER SET utf8 COLLATE utf8_general_ci;  ";;
			$dbh = $this->construct();
			$dbh->exec($sql);
			if($dbh !== false) { return 1; }
			else{
				print ("errorInfo: " . print_r($dbh->errorInfo())  . "\n");
				}}
	public function createTableSemester($id){
			$sc_table = 'student_class'.$id;
			//students class
				$sc = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id')
				);
			
			   $this->createdbtable($sc_table, $sc);
			}
	
	public function selectinventory($query, $num, $id = NULL)
	{
		
		try
		{
			if($num === 0)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT COUNT(*) as mid FROM `inventory_types` WHERE `inventory_types`.`categoryid` = `inventory_categorys`.`id` ) AS qty
						FROM 
							`inventory_categorys` '.$add;
				}
				if($num === 1)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = ' WHERE `is_delete` = 0 ';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `inventory_categorys` WHERE `inventory_types`.`categoryid` = `inventory_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`inventory_types` '.$add;
				}
				elseif($num === 2)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `inventory_categorys` WHERE `inventory_types`.`categoryid` = `inventory_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`inventory_types` '.$add;
				}
				elseif($num === 3)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{
						$add = 'WHERE `is_delete` = 0 AND `categoryid` = '.$query['categoryid'];
					}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `inventory_categorys` WHERE `inventory_types`.`categoryid` = `inventory_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`inventory_types` '.$add;
				}
				elseif($num === 4)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `inventory_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['inventoryid']) &&  $query['inventoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `inventory_transactions`.`inventoryid` = '.$query['inventoryid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `inventory_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `inventory_categorys` WHERE `inventory_transactions`.`categoryid` = `inventory_categorys`.`id` LIMIT 1 ) AS categoryname,
							(SELECT name as nm FROM `inventory_types` WHERE `inventory_transactions`.`categoryid` = `inventory_types`.`id` LIMIT 1 ) AS inventoryname,
							(SELECT surname as nm FROM `user_types` WHERE `inventory_transactions`.`userid` = `user_types`.`id` LIMIT 1 ) AS username
						FROM 
							`inventory_transactions` '.$add.' order by transaction_date DESC';;
				}
				elseif($num === 5)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `inventory_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['inventoryid']) &&  $query['inventoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `inventory_transactions`.`inventoryid` = '.$query['inventoryid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `inventory_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					$sql = '
						SELECT 
							status,
							inventoryid,
							(SELECT name as nm FROM `inventory_types` WHERE `inventory_transactions`.`categoryid` = `inventory_types`.`id` LIMIT 1 ) AS inventoryname,
							SUM(quantity) AS qty
						FROM 
							`inventory_transactions` '.$add.' GROUP BY  status, inventoryid ';
				}
				

	  			if($id)
				{
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;
				}
				else
				{
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				}
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}

	public function selectroom($query, $num, $id = NULL)
	{
		if(isset($query['locationid']) && $query['locationid'] != 3)
		{
			$rm = ' `room_categorys`.`locationid` = '.$query['locationid'].' AND ';
		}else{
			$rm = '';		
		}

		if(isset($query['currentdate']) && $query['currentdate'] != 3)
		{
			$cd = $query['currentdate'];
		}else{
			$cd = date('now');		
		}

		try
		{
			if($num === 0)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE '.$rm.' `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE '.$rm.'  `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT COUNT(*) as mid FROM `room_types` WHERE `room_types`.`categoryid` = `room_categorys`.`id` ) AS qty
						FROM 
							`room_categorys` '.$add;
				}
				if($num === 1)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE '.$rm.'  `room_types`.`is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = ' WHERE '.$rm.' `room_types`.`is_delete` = 0';}
					$sql ='
						SELECT 
						 		`room_types`.`id` AS id,
						 		`room_types`.`categoryid` AS categoryid,
						 		`room_types`.`name` AS name,
						 		`room_types`.`description` AS description,
						 		`room_types`.`is_active` AS is_active,
						 		`room_types`.`is_delete` AS is_delete,
						 		(SELECT COUNT(id) AS GRP FROM `room_maintenance` 
						 		 WHERE  
						 			`room_maintenance`.`locationid` = `room_types`.`id` AND 
						 			DATE(`room_maintenance`.`transaction_date`) = '.$cd.' AND 
						 			`is_active` = 0 
						 		GROUP BY `room_types`.`id`) AS maintenancenum 
						FROM
							 	`room_categorys`
						LEFT JOIN
							 	`room_types`
						ON
							`room_categorys`.`id` = `room_types`.`categoryid`
							 	'. $add .'';
							 
				}
				elseif($num === 2)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE '.$rm.'  `room_types`.`is_delete` = 0 AND `room_types`.`id` = '.$id;
					}
					else{$add = ' WHERE '.$rm.' `room_types`.`is_delete` = 0';}
					$sql ='
						SELECT 
						 		`room_types`.`id` AS id,
						 		`room_types`.`categoryid` AS categoryid,
						 		`room_types`.`name` AS name,
						 		`room_types`.`description` AS description,
						 		`room_types`.`is_active` AS is_active,
						 		`room_types`.`is_delete` AS is_delete
						FROM
							 	`room_categorys`
						LEFT JOIN
							 	`room_types`
						ON
							`room_categorys`.`id` = `room_types`.`categoryid`
							 	'. $add .'';
				}
				elseif($num === 3)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE '.$rm.' `is_delete` = 0 AND `id` = '.$id;
					}
					else{
						$add = 'WHERE '.$rm.' `is_delete` = 0 AND `categoryid` = '.$query['categoryid'];
					}
					$sql ='
						SELECT 
						 		`room_types`.`id` AS id,
						 		`room_types`.`categoryid` AS categoryid,
						 		`room_types`.`name` AS name,
						 		`room_types`.`description` AS description,
						 		`room_types`.`is_active` AS is_active,
						 		`room_types`.`is_delete` AS is_delete
						FROM
							 	`room_categorys`
						LEFT JOIN
							 	`room_types`
						ON
							`room_categorys`.`id` = `room_types`.`categoryid`
							 	'. $add .'';
				}
				elseif($num === 4)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `room_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['roomid']) &&  $query['roomid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_transactions`.`roomid` = '.$query['roomid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					  $sql = '
						SELECT 
							*,
							
							(SELECT name as nm FROM `room_types` WHERE `room_transactions`.`roomid` = `room_types`.`id` LIMIT 1 ) AS roomname,
							(SELECT surname as nm FROM `user_types` WHERE `room_transactions`.`userid` = `user_types`.`id` LIMIT 1 ) AS username
						FROM 
							`room_transactions` 

						'.$add.' order by transaction_date DESC';
				}
				elseif($num === 5)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `room_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['roomid']) &&  $query['roomid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_transactions`.`roomid` = '.$query['roomid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					$sql = '
						SELECT 
							status,
							roomid,
							(SELECT name as nm FROM `room_types` WHERE `room_transactions`.`categoryid` = `room_types`.`id` LIMIT 1 ) AS roomname,
							SUM(quantity) AS qty
						FROM 
							`room_transactions` '.$add.' GROUP BY  status, roomid ';
				}
				elseif($num === 6)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `room_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['roomid']) &&  $query['roomid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_transactions`.`roomid` = '.$query['roomid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					  $sql = '
						SELECT 
							*,
							
							(SELECT name as nm FROM `room_types` WHERE `room_transactions`.`roomid` = `room_types`.`id` LIMIT 1 ) AS roomname,
							(SELECT surname as nm FROM `user_types` WHERE `room_transactions`.`userid` = `user_types`.`id` LIMIT 1 ) AS username
						FROM 
							`room_transactions` '.$add.' order by transaction_date DESC';;
				}
				elseif($num === 7)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `room_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['roomid']) &&  $query['roomid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_transactions`.`roomid` = '.$query['roomid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					 $sql = '
						SELECT 
							status,
							roomid,
							(SELECT name as nm FROM `room_types` WHERE `room_transactions`.`categoryid` = `room_types`.`id` LIMIT 1 ) AS roomname,
							SUM(quantity) AS qty
						FROM 
							`room_transactions` '.$add.' GROUP BY phone ';
				}
				elseif($num === 8)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `room_maintenance`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['locationid']) &&  $query['locationid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `room_maintenance`.`locationid` = '.$query['locationid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
					 $sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `room_types` WHERE `room_maintenance`.`locationid` = `room_types`.`id` LIMIT 1 ) AS roomname
						FROM 
							`room_maintenance` 

						'.$add.' order by transaction_date DESC';
				}
	  			if($id)
				{
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;
				}
				else
				{
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				}
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	public function selectroomstatistics($date, $num, $loc)
	{
		if($loc !== null && $loc != 3)
		{
			$rm = ' WHERE `room_categorys`.`locationid` = '.$loc.' ';
		}else{
			$rm = '';		
		}

		try
		{
				if($num === 1)
				{
					$sql = '
						SELECT 
							*,
							`room_transactions`.`id` AS id,
							`room_type`.`id` AS cid,
							(SELECT name as nm FROM `room_categorys` WHERE `room_type`.`categoryid` = `room_categorys`.`id` LIMIT 1 ) AS categoryname

						FROM 
							`room_transactions` 
						RIGHT JOIN
							 (SELECT 
							 		`room_types`.`id` AS id,
							 		`room_types`.`categoryid` AS categoryid,
							 		`room_types`.`name` AS name,
							 		`room_types`.`description` AS description,
							 		`room_types`.`is_active` AS is_active,
							 		`room_types`.`is_delete` AS is_delete,
							 		`room_categorys`.`locationid` AS locationid
							 FROM
							 	`room_categorys`
							 LEFT JOIN
							 	`room_types`
							 ON
							 	`room_categorys`.`id` = `room_types`.`categoryid`
							 	'. $rm .'
							 ) AS room_type

						ON
							 `room_transactions`.`roomid` = `room_type`.`id`
						WHERE 
							 DATE(`room_transactions`.`transaction_date`) = "'.$date.'"';
				}
				if($num === 2)
				{
					$sql = '
						SELECT 
							COUNT(`room_transactions`.`id`) AS id,
							COUNT(DISTINCT `room_transactions`.`phone`) AS guest,
							MONTH(`room_transactions`.`transaction_date`) AS month,
							YEAR(`room_transactions`.`transaction_date`) AS year,
							SUM(`room_transactions`.`id`) AS duration,
							(SELECT COUNT(id) AS num FROM room_types) AS roomnum
						FROM 
						    (SELECT
						    	*
						    FROM
						    	`room_transactions`
						    RIGHT JOIN 
						    (SELECT 
							 	`room_types`.`id` AS sid
							 FROM
							 	`room_categorys`
							 LEFT JOIN
							 	`room_types`
							 ON
							 	`room_categorys`.`id` = `room_types`.`categoryid`
							 	'. $rm .'
							 ) AS room_type 
							 ON `room_type`.`sid` =  `room_transactions`.`roomid`) AS room_transactions
						WHERE `room_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							 YEAR(`room_transactions`.`transaction_date`), MONTH(`room_transactions`.`transaction_date`)';
				}
				if($num === 3)
				{
					$sql = '
						SELECT 
							COUNT(`room_transactions`.`id`) AS id,
							MONTH(`room_transactions`.`transaction_date`) AS month,
							YEAR(`room_transactions`.`transaction_date`) AS year,
							SUM(`room_transactions`.`id`) AS duration,
							(SELECT COUNT(id) AS num FROM room_types) AS roomnum
						FROM 
							`room_transactions` 
						WHERE `room_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							 YEAR(`room_transactions`.`transaction_date`), MONTH(`room_transactions`.`transaction_date`)';
				}
				if($num === 4)
				{
				  $sql = '
						SELECT 
							`room_categorys`.`name` AS categoryname,
					 		`room_types`.`id` AS id,
					 		`room_types`.`categoryid` AS categoryid,
					 		`room_types`.`name` AS name,
					 		`room_types`.`description` AS description,
					 		`room_types`.`is_active` AS is_active,
					 		`room_types`.`is_delete` AS is_delete,
					 		`room_categorys`.`locationid` AS locationid

						FROM
							 `room_categorys`
						LEFT JOIN
							 `room_types`
						ON
							 `room_categorys`.`id` = `room_types`.`categoryid`
						'. $rm .'
						AND 
							`room_types`.`is_active` = 0
						AND
							 DATE(`room_types`.`is_created`) <= "'.$date.'"';
				}
				if($num === 5)
				{
					$sql = '
						SELECT 
							`room_categorys`.`name` AS categoryname,
					 		`room_types`.`id` AS id,
					 		`room_types`.`categoryid` AS categoryid,
					 		`room_types`.`name` AS name,
					 		`room_types`.`description` AS description,
					 		`room_types`.`is_active` AS is_active,
					 		`room_types`.`is_delete` AS is_delete
						FROM
							 `room_categorys`
						LEFT JOIN
							 `room_types`
						ON
							 `room_categorys`.`id` = `room_types`.`categoryid`
						'. $rm .'
						AND 
							`room_types`.`is_active` = 1
						AND
							 DATE(`room_types`.`is_created`) <= "'.$date.'"';
				}
				if($num === 6)
				{ $sql = '
						SELECT 
							COUNT(DISTINCT `room_maintenance`.`id`) AS ids 
						FROM 
							`room_maintenance` 
						RIGHT JOIN
							(SELECT 
						 		`room_types`.`id` AS id
							FROM
								 `room_categorys`
							LEFT JOIN
								 `room_types`
							ON
								 `room_categorys`.`id` = `room_types`.`categoryid`
							
							'.$rm.'
							) AS `room_type`
						 ON  
						 	`room_maintenance`.`locationid` = `room_type`.`id` 
						 WHERE
						 	 DATE(`room_maintenance`.`transaction_date`) = "'.$date.'" 
						 AND 
						 	`is_active` = 0 
						 GROUP BY 
						 	`room_maintenance`.`is_active`';			 
				}
				
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	
	public function selectmaintenance($query, $num, $id = NULL)
	{
		if(isset($query['locationid']) && $query['locationid'] != 3)
		{
			$rm = ' `maintenance_transactions`.`locationid` = '.$query['locationid'].' AND ';
		}else{
			$rm = '';		
		}
		try
		{
			if($num === 0)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT COUNT(*) as mid FROM `maintenance_types` WHERE `maintenance_types`.`categoryid` = `maintenance_categorys`.`id` ) AS qty
						FROM 
							`maintenance_categorys` '.$add;
				}
				if($num === 1)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = ' WHERE `is_delete` = 0 ';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `maintenance_categorys` WHERE `maintenance_types`.`categoryid` = `maintenance_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`maintenance_types` '.$add;
				}
				elseif($num === 2)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `maintenance_categorys` WHERE `maintenance_types`.`categoryid` = `maintenance_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`maintenance_types` '.$add;
				}
				elseif($num === 3)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{
						$add = 'WHERE `is_delete` = 0 AND `categoryid` = '.$query['categoryid'];
					}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `maintenance_categorys` WHERE `maintenance_types`.`categoryid` = `maintenance_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`maintenance_types` '.$add;
				}
				elseif($num === 4)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['maintenanceid']) &&  $query['maintenanceid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `maintenance_transactions`.`location` = '.$query['maintenanceid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					 $sql = '
						SELECT 
							*,
							(SELECT name FROM maintenance_categorys WHERE `maintenance_categorys`.`id` =  (SELECT categoryid as nm FROM `maintenance_types` WHERE `maintenance_transactions`.`maintenanceid` = `maintenance_types`.`id` LIMIT 1 ) ) AS categoryname,
							(SELECT categoryid as nm FROM `maintenance_types` WHERE `maintenance_transactions`.`maintenanceid` = `maintenance_types`.`id` LIMIT 1 ) AS categoryid,
							(SELECT name as nm FROM `maintenance_types` WHERE `maintenance_transactions`.`maintenanceid` = `maintenance_types`.`id` LIMIT 1 ) AS maintenancename,
							(SELECT name as nm FROM `room_types` WHERE `maintenance_transactions`.`location` = `room_types`.`id` LIMIT 1 ) AS roomname,
							(SELECT surname as nm FROM `user_types` WHERE `maintenance_transactions`.`userid` = `user_types`.`id` LIMIT 1 ) AS username
						FROM 
							`maintenance_transactions` '.$add.' order by transaction_date DESC LIMIT 200';
				}
				elseif($num === 5)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['maintenanceid']) &&  $query['maintenanceid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `maintenance_transactions`.`maintenanceid` = '.$query['maintenanceid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `maintenance_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					$sql = '
						SELECT 
							status,
							maintenanceid,
							(SELECT name as nm FROM `maintenance_types` WHERE `maintenance_transactions`.`categoryid` = `maintenance_types`.`id` LIMIT 1 ) AS maintenancename,
							SUM(quantity) AS qty
						FROM 
							`maintenance_transactions` '.$add.' GROUP BY  status, maintenanceid ';
				}
				elseif($num === 6)
				{
					
						$add = '';
						if(isset($query['starts']) && isset($query['ends']))
						{
							$add .= ' WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
					
					 $sql = '
						SELECT 
							*,
							`maintenance_type`.`categoryname` AS categoryname,
							`maintenance_type`.`categoryid` AS categoryid,
							`maintenance_type`.`maintenancename` AS maintenancename,
							(SELECT name as nm FROM `room_types` WHERE `maintenance_transactions`.`location` = `room_types`.`id` LIMIT 1 ) AS roomname

						FROM 
							`maintenance_transactions` 
						RIGHT JOIN 
							( SELECT 
								`maintenance_categorys`.`name` AS categoryname,
								`maintenance_categorys`.`id` AS categoryid,
								`maintenance_types`.`name` AS maintenancename,
								`maintenance_types`.`id` AS id
							  FROM
							  	`maintenance_categorys`
							  LEFT JOIN
							  	`maintenance_types`
							  ON
							  	`maintenance_categorys`.`id` = `maintenance_types`.`categoryid`
							  WHERE
							  	`maintenance_categorys`.`id` = '.$query["categoryid"].'
							)
							AS maintenance_type
						ON
							`maintenance_transactions`.`maintenanceid` = `maintenance_type`.`id`
						'.$add.' order by is_completed, status, transaction_date';
				}
				elseif($num === 7)
				{
					
						$add = '';
						if(isset($query['starts']) && isset($query['ends']))
						{
							$add .= ' WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) AND status = '.$query['status'].' ';
						}
					
					  $sql = '
						SELECT 
							*,
							`maintenance_type`.`categoryname` AS categoryname,
							`maintenance_type`.`categoryid` AS categoryid,
							`maintenance_type`.`maintenancename` AS maintenancename,
							(SELECT name as nm FROM `room_types` WHERE `maintenance_transactions`.`location` = `room_types`.`id` LIMIT 1 ) AS roomname
						FROM 
							`maintenance_transactions` 
						RIGHT JOIN 
							( SELECT 
								`maintenance_categorys`.`name` AS categoryname,
								`maintenance_categorys`.`id` AS categoryid,
								`maintenance_types`.`name` AS maintenancename,
								`maintenance_types`.`id` AS id
							  FROM
							  	`maintenance_categorys`
							  LEFT JOIN
							  	`maintenance_types`
							  ON
							  	`maintenance_categorys`.`id` = `maintenance_types`.`categoryid`
							  
							)
							AS maintenance_type
						ON
							`maintenance_transactions`.`maintenanceid` = `maintenance_type`.`id`
						'.$add.' order by is_completed, status, transaction_date';
				}
				elseif($num === 8)
				{
					
						$add = '';
						if(isset($query['starts']) && isset($query['ends']))
						{
							$add .= ' WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) AND is_completed = '.$query['is_completed'].' ';
						}
					
					 $sql = '
						SELECT 
							*,
							`maintenance_type`.`categoryname` AS categoryname,
							`maintenance_type`.`categoryid` AS categoryid,
							`maintenance_type`.`maintenancename` AS maintenancename,
							(SELECT name as nm FROM `room_types` WHERE `maintenance_transactions`.`location` = `room_types`.`id` LIMIT 1 ) AS roomname
						FROM 
							`maintenance_transactions` 
						RIGHT JOIN 
							( SELECT 
								`maintenance_categorys`.`name` AS categoryname,
								`maintenance_categorys`.`id` AS categoryid,
								`maintenance_types`.`name` AS maintenancename,
								`maintenance_types`.`id` AS id
							  FROM
							  	`maintenance_categorys`
							  LEFT JOIN
							  	`maintenance_types`
							  ON
							  	`maintenance_categorys`.`id` = `maintenance_types`.`categoryid`
							  
							)
							AS maintenance_type
						ON
							`maintenance_transactions`.`maintenanceid` = `maintenance_type`.`id`
						'.$add.' order by is_completed, status, transaction_date';
				}
	  			if($id)
				{
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;
				}
				else
				{
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				}
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	
	public function selectuser($query, $num, $id = NULL)
	{
		
		try
		{
			if($num === 0)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT COUNT(*) as mid FROM `user_types` WHERE `user_types`.`categoryid` = `user_categorys`.`id` ) AS qty
						FROM 
							`user_categorys` '.$add;
				}
				if($num === 1)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = ' WHERE `is_delete` = 0 ';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `user_categorys` WHERE `user_types`.`categoryid` = `user_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`user_types` '.$add;
				}
				elseif($num === 2)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `user_categorys` WHERE `user_types`.`categoryid` = `user_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`user_types` '.$add;
				}
				elseif($num === 3)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{
						$add = 'WHERE `is_delete` = 0 AND `categoryid` = '.$query['categoryid'];
					}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `user_categorys` WHERE `user_types`.`categoryid` = `user_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`user_types` '.$add;
				}
				elseif($num === 4)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `user_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['userid']) &&  $query['userid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_transactions`.`userid` = '.$query['userid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `user_categorys` WHERE `user_transactions`.`categoryid` = `user_categorys`.`id` LIMIT 1 ) AS categoryname,
							(SELECT name as nm FROM `user_types` WHERE `user_transactions`.`categoryid` = `user_types`.`id` LIMIT 1 ) AS username,
							(SELECT surname as nm FROM `user_types` WHERE `user_transactions`.`userid` = `user_types`.`id` LIMIT 1 ) AS username
						FROM 
							`user_transactions` '.$add.' order by transaction_date DESC';;
				}
				elseif($num === 5)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['starts']) && isset($query['ends']) && $query['starts'] !== NULL && $query['starts'] !== '' && $query['ends'] !== NULL && $query['ends'] !== '')
						{
							$wh .= ' `user_transactions`.`transaction_date` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['userid']) &&  $query['userid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_transactions`.`userid` = '.$query['userid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_transactions`.`categoryid` = '.$query['categoryid'];

						}
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					$sql = '
						SELECT 
							status,
							userid,
							(SELECT name as nm FROM `user_types` WHERE `user_transactions`.`categoryid` = `user_types`.`id` LIMIT 1 ) AS username,
							SUM(quantity) AS qty
						FROM 
							`user_transactions` '.$add.' GROUP BY  status, userid ';
				}
				

	  			if($id)
				{
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;
				}
				else
				{
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				}
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	
	public function selectuserstatistics($date, $num)
	{
		
		try
		{
				if($num === 1)
				{
					$sql = '
						SELECT 
							COUNT(id) AS staffnum,
							(SELECT name as nm FROM `user_categorys` WHERE `user_types`.`categoryid` = `user_categorys`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`user_types`
						WHERE
							`user_types`.`is_delete` = 0
						GROUP BY categoryid ';
						
				}
				if($num === 2)
				{
					$sql = '
						SELECT 
							COUNT(id) AS staffnum,
							gender
						FROM 
							`user_types`
						WHERE
							`user_types`.`is_delete` = 0
						GROUP BY gender';
						
				}

				if($num === 2)
				{
					$sql = '
						SELECT 
							COUNT(`room_transactions`.`id`) AS id,
							MONTH(`room_transactions`.`transaction_date`) AS month,
							YEAR(`room_transactions`.`transaction_date`) AS year,
							SUM(`room_transactions`.`id`) AS duration,
							(SELECT COUNT(id) AS num FROM room_types) AS roomnum
						FROM 
							`room_transactions` 
						WHERE `room_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							 YEAR(`room_transactions`.`transaction_date`), MONTH(`room_transactions`.`transaction_date`)';
				}
				
				

	  			
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	public function selectmaintenancestatistics($date, $num)
	{
		if(isset($date['locationid']) && $date['locationid'] != 3 )
		{
			$rm = '`maintenance_transactions`.`locationid` = '.$date['locationid'].' AND ';
		}else
		{
			$rm = '';
		}
		try
		{
		
				if($num === 1)
				{
					$sql = '
					     SELECT 
					     		COUNT(`P`.`sid`) AS num,
					     		`P`.`categoryid`,
					     		(SELECT name AS NM FROM `maintenance_categorys` WHERE `maintenance_categorys`.`id` =`P`.`categoryid` LIMIT 1) AS categoryname
					     FROM 
							(SELECT 
								`maintenance_transactions`.`id` as sid,
								`maintenance_types`.`categoryid` as categoryid
								
							FROM 
								`maintenance_transactions`
							LEFT JOIN
								`maintenance_types` 
							ON  
							`maintenance_transactions`.`maintenanceid` = `maintenance_types` .`id`
							WHERE
							'.$rm.' 
							`maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE) ) AS P
						GROUP BY 
							categoryid
							 ';
				}
				if($num === 2)
				{
					$sql = '
						SELECT 
							COUNT(`id`) AS num,
							status
						FROM 
							`maintenance_transactions` 
						WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							status
							 ';
				}
				if($num === 3)
				{
					$sql = '
						SELECT 
							COUNT(`id`) AS num,
							is_completed
						FROM 
							`maintenance_transactions` 
						WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							is_completed
							 ';
				}
				
				if($num === 4)
				{
					$sql = '
						SELECT 
							COUNT(`maintenance_transactions`.`id`) AS maintenancenum,
							MONTH(`maintenance_transactions`.`transaction_date`) AS month,
							YEAR(`maintenance_transactions`.`transaction_date`) AS year,
							AVG(`maintenance_transactions`.`resolutiontime`) AS num,
							(SELECT COUNT(id) AS num FROM maintenance_types) AS maintenancenumS
						FROM 
							`maintenance_transactions` 
						WHERE '.$rm.' `maintenance_transactions`.`transaction_date` BETWEEN CAST("'.$date['startdate'].'" AS DATE) AND CAST("'.$date['enddate'].'" AS DATE)
						GROUP BY 
							 YEAR(`maintenance_transactions`.`transaction_date`), MONTH(`maintenance_transactions`.`transaction_date`)';
				}

	  			
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	public function selected($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		
		try
		{
				if($table == 'room_categorys' || $table == 'room_maintenances' || $table == 'room_maintenances' )
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}elseif(isset($where))
					{
						$add = parent::wheresClause($where);
						$add .= parent::orderByClause($orderby);
					}
					$sql = 'SELECT * FROM `'. $table .'` '.$add;
				}
				
				

	  			if($id)
				{
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;
				}
				if($where)
				{
					$rows = array();
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					{
						array_push($rows, $row);
					}
					return $rows;
				}
			
				
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	public function many($sql){
		try{
			    $rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				while ($row = $sth->fetch(PDO::FETCH_OBJ))
				{
					array_push($rows, $row);
				}
				return $rows;
			}
		catch (PDOException $e){
			$msg = $db.":";
			$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
			return $msg;
			}}
	public function single($sql){
		try{
			   $dbh = $this->construct();	
			   $sth = $dbh->query($sql);
			   while ($row = $sth->fetch(PDO::FETCH_OBJ))
			   return $row;
			}
		catch (PDOException $e){
				$msg = $db.":";
				$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
				return $msg;
			}}
	
}




?>