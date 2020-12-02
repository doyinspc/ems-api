<?php
require_once('class.dbcontrol.php');
require_once('common.php');

class DB extends DbControl
{
	//var $host = 'localhost';
	//var $db = 'stresert_hms';
	//var $user = 'stresert_root_ad';
	//var $pass = 'stresertad1234';
	
	var $host = 'localhost';
	var $db = 'stresert_ems';
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
	public function selectStaff($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		if($table == 'staffs')
		{
			$add = '';
			if(isset($id)){$add = ' WHERE  ``.`` = '.$id;}
			if(isset($where)){$add = parent::whereClause($where);}
			$sql = '
			SELECT 
			*,
			(SELECT `name` FROM `departments` WHERE `staffs`.`departmentid` = `departments`.`id` ) as departmentname
			FROM
				`staffs`
			'.$add;
		}
		if($table == 'students')
		{
			$add = '';
			if(isset($id)){$add = ' WHERE  ``.`` = '.$id;}
			if(isset($where)){$add = parent::whereClause($where);}
			$sql = '
			SELECT 
			*,
			(SELECT GROUP_CONCAT(CONCAT(address, ":::::", id ) )as passports FROM `student_passports` WHERE `student_passports`.`studentid`  = `students`.`id` GROUPB BY `student_passports`.`studentid` ) as departmentname
			FROM
				`students`
			'.$add;
		}

		if($id){return $this->single($sql);}
		else{return $this->many($sql);}
	}
	public function selectDropdown($schoolid, $num)
	{
		if($num == 1)
		{
			$sql = '
			SELECT 
				`terms`.`id` as id,
				`sessions`.`id` as sid,
				CONCAT(`sessions`.`name`," ",`terms`.`name`," Term") as name
			FROM
				`sessions`
			LEFT JOIN
				`terms`
			ON
				`sessions`.`id` = `terms`.`sessionid`
			WHERE
				`sessions`.`schoolid` = '.$schoolid.' AND
				`sessions`.`is_delete` =  0 AND
				`terms`.`is_delete` = 0  
			';
		}
		if($num == 2)
		{
			$sql = '
			SELECT 
				`claszunits`.`id` as id,
				`claszs`.`id` as sid,
				`claszunits`.`name` as name
			FROM
				`claszs`
			LEFT JOIN
				`claszunits`
			ON
				`claszs`.`id` = `claszunits`.`claszid`
			WHERE
				`claszs`.`schoolid` = '.$schoolid.' AND
				`claszs`.`is_delete` =  0 AND
				`claszunits`.`is_delete` = 0  
			';
		}

		
		return $this->many($sql);
	}
	public function selected($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{

		try
		{
			$add ='';
			if(isset($id) && $id !== NULL)
			{
				$add = 'WHERE `id` = '.$id;
			}elseif(isset($where))
			{
				$add = parent::wheresClause($where);
				$add .= parent::orderByClause($orderby);
			}
				if($table == 'room_categorys' || $table == 'room_maintenances' || $table == 'room_maintenances' )
				{
					$sql = 'SELECT * FROM `'. $table .'` '.$add;
				}
				if($table == 'terms' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `sessions` WHERE `sessions`.`id` = `terms`.`sessionid` LIMIT 1  ) as sessionname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'cas' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `terms` WHERE `terms`.`id` = `cas`.`termid` LIMIT 1  ) as termname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'casunits' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `cas` WHERE `cas`.`id` = `caunits`.`caid` LIMIT 1  ) as casname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'departments')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `departments`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'subjects')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `subjects`.`departmentid` LIMIT 1  ) as departmentname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'claszs')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `claszs`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'claszunits')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `claszunits`.`claszid` LIMIT 1  ) as claszname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'access')
				{
					if($where['grp'] == 1 || $where['grp'] == 2)
					{
						$sql = '
						SELECT
							 *,
							(SELECT name FROM `terms` WHERE `terms`.`id` = termid  LIMIT 1) AS termname,
							(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = itemid  LIMIT 1) AS claszname,
						FROM 
							`staffs`
						LEFT JOIN
							(SELECT 
								id as cid,
								clientid,
								termid,
								itemid,
								grp
							FROM
								`access`
							WHERE
								grp = '.$where['grp'].'

							) AS accesss
						ON 
							`staffs`.`id` = `accesss`.`clientid`
					 '.$add;
					}
					if($where['grp'] == 3 || $where['grp'] == 4)
					{
						$sql = '
						SELECT
							 *
						FROM 
							`students`
						LEFT JOIN
							(SELECT 
								id as cid,
								clientid,
								termid,
								itemid,
								grp
							FROM
								`access`
							WHERE
								grp = '.$where['grp'].'

							) AS accesss
						ON 
							`students`.`id` = `accesss`.`clientid`
					 '.$add;
					}
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