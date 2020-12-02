<?php
require_once('class.dbcontrol.php');
require_once('common.php');

class DB extends DbControl
{
	//var $host = 'localhost';
	//var $db = 'stresert_hms_staff';
	//var $user = 'stresert_root_ad';
    //var $pass = 'stresertad1234';

	var $host = 'localhost';
	var $db = 'stresert_hms_staff';
	var $user = 'root';
	var $pass = '';

	// var $host = 'localhost';
	// var $db = 'stresert_hms';
	// var $user = 'stresert_root_ad';
	// var $pass = 'stresertad1234';

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
						$add = 'WHERE  `id` = '.$id;
					}
					else{
						$ctn = '';
						if(isset($query['categoryid']))
						{
							$ctn .= " `categoryid` = ".$query['categoryid']." ";
						}
						if(isset($query['is_active']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `is_active` = ".$query['is_active']." ";
						}
						if(isset($query['is_delete']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `is_delete` = ".$query['is_delete']." ";
						}
						if(isset($query['locationid']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `locationid` = ".$query['locationid']." ";
						}
						$add = 'WHERE '.$ctn;
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
							$wh .= ' `user_transactions`.`date_created` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['staffid']) &&  $query['staffid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_transactions`.`staffid` = '.$query['staffid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*,
							(SELECT CONCAT(surname, " ", firstname)as namz FROM `user_types` WHERE `user_types`.`id` = `user_transactions`.`staffid` ) AS fullname
						FROM 
							`user_transactions` '.$add.' order by date_created DESC';;
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
				elseif($num === 7)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `is_delete` = 0 AND `id` = '.$id;
					}
					else{$add = 'WHERE `is_delete` = 0';}
					$sql = '
						SELECT 
							*,
							(SELECT COUNT(*) as mid FROM `user_job_transactions` WHERE `user_job_transactions`.`categoryid` = `user_jobs`.`id` ) AS qty
						FROM 
							`user_jobs` '.$add;
				}
				elseif($num === 8)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE  `id` = '.$id;
					}
					else{
						$ctn = '';
						if(isset($query['categoryid']))
						{
							$ctn .= " `categoryid` = ".$query['categoryid']." ";
						}
						if(isset($query['is_active']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `is_active` = ".$query['is_active']." ";
						}
						if(isset($query['is_delete']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `is_delete` = ".$query['is_delete']." ";
						}
						if(isset($query['locationid']))
						{
							$ctn .= strlen($ctn) > 0 ? '  AND ':'';
							$ctn .= " `locationid` = ".$query['locationid']." ";
						}
						$add = 'WHERE '.$ctn;
					}
					$sql = '
						SELECT 
							*,
							(SELECT name as nm FROM `user_jobs` WHERE `user_job_transactions`.`categoryid` = `user_jobs`.`id` LIMIT 1 ) AS categoryname
						FROM 
							`user_job_transactions` '.$add;
				}
				elseif($num === 9)
				{
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						
						if(isset($query['staffid']) &&  $query['staffid'] !== '')
						{
							$wh .= ' `user_job_transactions`.`staffid` = '.$query['staffid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*
						FROM 
							`user_job_transactions` '.$add.' order by date_created DESC';;
				}
				elseif($num === 12)
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
							$wh .= ' `user_leave_transactions`.`date_created` BETWEEN CAST("'.$query['starts'].'" AS DATE) AND CAST("'.$query['ends'].'" AS DATE) ';
						}
						if(isset($query['staffid']) &&  $query['staffid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_leave_transactions`.`staffid` = '.$query['staffid'];

						}
						if(isset($query['is_approved']) &&  $query['is_approved'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_leave_transactions`.`is_approved` = '.$query['is_approved'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*,
							(SELECT surname as nm FROM `user_types` WHERE `user_leave_transactions`.`staffid` = `user_types`.`id` LIMIT 1 ) AS surname,
							(SELECT firstname as nm FROM `user_types` WHERE `user_leave_transactions`.`staffid` = `user_types`.`id` LIMIT 1 ) AS firstname

						FROM 
							`user_leave_transactions` '.$add.' order by date_created DESC';
				}
				elseif($num === 11)
				{
					$add = '';
					if(isset($id) && $id !== NULL)
					{
						$add = 'WHERE `id` = '.$id;
					}
					else
					{
						$wh = '';
						if(isset($query['staffid']) &&  $query['staffid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_job_transactions`.`staffid` = '.$query['staffid'];

						}
						if(isset($query['categoryid']) &&  $query['categoryid'] !== '')
						{
							$wh .= strlen($wh) > 0 ? ' AND ' : '';
							$wh .= ' `user_job_transactions`.`categoryid` = '.$query['categoryid'];

						}
						
						$add = strlen($wh) > 0 ? ' WHERE ' : '';
						$add .= $wh;
					}
					
						
					
					$sql = '
						SELECT 
							*,
							(SELECT surname as nm FROM `user_types` WHERE `user_job_transactions`.`staffid` = `user_types`.`id` LIMIT 1 ) AS surname,
							(SELECT firstname as nm FROM `user_types` WHERE `user_job_transactions`.`staffid` = `user_types`.`id` LIMIT 1 ) AS firstname,
							(SELECT name as nm FROM `user_jobs` WHERE `user_job_transactions`.`categoryid` = `user_jobs`.`id` LIMIT 1 ) AS jobname

						FROM 
							`user_job_transactions` '.$add.' order by date_created DESC';;
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
	public function selectNotification($date, $table, $col)
	{
		try{
		
			$sql = '
			SELECT 
				COUNT(id) as id			
			FROM 
				`'.$table.'` 
			WHERE 
				`'.$table.'`.`'.$col.'` > CAST("'.$date.'" AS DATE) 
				 ';

			$dbh = $this->construct();	
			$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			return $row;
		}catch (PDOException $e){
				$msg = $db.":";
				$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
				return $msg;
		}

	}
	public function selectuserstatistics($date, $num, $location = null)
	{
		if(isset($location) && $location !== NULL && $location !== 3)
		{
			$add = 'WHERE `user_types`.`is_delete` = 0 AND `locationid` = '.$locationid;
		}
		else{$add = 'WHERE `user_types`.`is_delete` = 0';}
		
		try
		{
				if($num === 1)
				{
					 $sql = '
						SELECT 
							categoryid,
							`user_categorys`.`name` AS categoryname,
							COUNT(`user_types`.`id`) AS num
							
						FROM 
							`user_types`
						LEFT JOIN 
							`user_categorys`
						ON 
							`user_types`.`categoryid` = `user_categorys`.`id`
						'.$add.'
						GROUP BY categoryid ';						
				}
				if($num === 2)
				{
					$sql = '
						SELECT 
							gender,
							COUNT(`user_types`.`id`) AS num
							
						FROM 
							`user_types`
						LEFT JOIN 
							`user_categorys`
						ON 
							`user_types`.`categoryid` = `user_categorys`.`id`
						 '.$add.'
						GROUP BY gender ';					
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