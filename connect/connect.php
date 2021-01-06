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
	public function selectStaff($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL){
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
		else{return $this->many($sql);}}
	public function selectDropdown($schoolid, $num)	{
		if($num == 1)
		{
			$sql = '
			SELECT 
				`terms`.`id` as id,
				`terms`.`id` as termid,
				`sessions`.`id` as sessionid,
				`sessions`.`name` as sessionname,
				`terms`.`name` as termname,
				 CONCAT(`sessions`.`name`," ",`terms`.`name`,"") as name
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
		if($num == 3)
		{
			$sql = '
			SELECT 
				`subjects`.`id` as id,
				`departments`.`id` as sid,
				`subjects`.`name` as name,
				`departments`.`name` as departmentname
			FROM
				`departments`
			LEFT JOIN
				`subjects`
			ON
				`departments`.`id` = `subjects`.`departmentid`
			WHERE
				`departments`.`schoolid` = '.$schoolid.' AND
				`departments`.`is_delete` =  0 AND
				`subjects`.`is_delete` = 0  
			';
		}
		if($num == 4)
		{
			$sql = '
			SELECT 
				`terms`.`id` as id,
				`terms`.`id` as termid,
				`sessions`.`id` as sessionid,
				`sessions`.`name` as sessionname,
				`terms`.`name` as termname,
				 CONCAT(`sessions`.`name`," ",`terms`.`name`," ") as name
			FROM
				`sessions`
			LEFT JOIN
				`terms`
			ON
				`sessions`.`id` = `terms`.`sessionid`
			WHERE
				`sessions`.`schoolid` = '.$schoolid.' AND
				`terms`.`is_active` = 1  
			';
		}
		if($num == 5)
		{
			$sql = '
			SELECT 
				`terms`.`id` as termid,
				`sessions`.`id` as sessionid,
				`sessions`.`name` as sessionname,
				`terms`.`name` as termname,
				 CONCAT(`sessions`.`name`," ",`terms`.`name`," Term") as name
			FROM
				`sessions`
			LEFT JOIN
				`terms`
			ON
				`sessions`.`id` = `terms`.`sessionid`
			WHERE
				`sessions`.`schoolid` = '.$schoolid.' ORDER BY `terms`.`id` desc LIMIT 1
			';
		}
		if($num == 6)
		{
			$sql = '
			SELECT 
				`terms`.`id` as termid,
				`sessions`.`id` as sessionid,
				`sessions`.`name` as sessionname,
				`terms`.`name` as termname,
				 CONCAT(`sessions`.`name`," ",`terms`.`name`," Term") as name
			FROM
				`sessions`
			LEFT JOIN
				`terms`
			ON
				`sessions`.`id` = `terms`.`sessionid`
			WHERE
				`sessions`.`schoolid` = '.$schoolid.' ORDER BY `terms`.`id` desc LIMIT 1
			';
		}
		if($num == 7)
		{
			$sql = '
			SELECT 
				`terms`.`id` as termid,
				`sessions`.`id` as sessionid,
				`sessions`.`name` as sessionname,
				`terms`.`name` as termname,
				 CONCAT(`sessions`.`name`," ",`terms`.`name`," Term") as name
			FROM
				`sessions`
			LEFT JOIN
				`terms`
			ON
				`sessions`.`id` = `terms`.`sessionid`
			WHERE
				`sessions`.`schoolid` = '.$schoolid.' ORDER BY `terms`.`id` desc LIMIT 1
			';
		}
		

		
		return $this->many($sql);}
	public function selectAccess($table , $id = NULL, $where  = NULL, $tabl, $orderby  = NULL, $groupby  = NULL)
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
		try
		{
			if($table == 'accessstaffclass')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
			 }
			if($table == 'accessstaffsubject')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemabbrv1,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid1';
			 }
			 if($table == 'accessstaffsubjectnum')
			{
				$staff = $where['clientid'];
				$grp = $where['grp'];
				
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemabbrv1,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname,
					(SELECT COUNT( id ) FROM `'.$tabl.'` WHERE `'.$tabl.'`.`contact` = '.$staff.' AND `'.$tabl.'`.`grp` = 4  LIMIT 1) AS num
				FROM 
				`'.$tabl.'`
					 WHERE `'.$tabl.'`.`clientid` = '.$staff.' AND `'.$tabl.'`.`grp` = 2  ORDER BY itemid1';
			 }
			if($table == 'accessstudentclass')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
			 }
			if($table == 'accessstudentclassfull')
			{
				$sql = '
				SELECT
					 *,
				(SELECT abbrv FROM `schools` WHERE `schools`.`id` = `students`.`schoolid`  LIMIT 1) AS schoolabbrv,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					`'.$tabl.'`.`id` AS cid
					
				FROM 
					`students`
				LEFT JOIN
					`'.$tabl.'` 
				ON
					`students`.`id` = `'.$tabl.'`.`clientid`
				'.$add.' ORDER BY surname, firstname, itemid';
			 }
			if($table == 'accessstudentsubject')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
			 }
			

			if($id){
					$dbh = $this->construct();	
					$sth = $dbh->query($sql);
					while ($row = $sth->fetch(PDO::FETCH_OBJ))
					return $row;}
			if($where){
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				while ($row = $sth->fetch(PDO::FETCH_OBJ))
				{
					array_push($rows, $row);
				}
				return $rows;}	
		}	
		catch (PDOException $e)
		{
			$msg = $db.":";
			$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
			return $msg;
		}		
	}
	public function staffStudent($ids, $grp )
	{
		try
		{
			if($grp == 1)
			{
				$sql = 'SELECT * FROM `schools` WHERE id IN ('.$ids.')';
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
	public function selectGroups($ids, $grp )
	{
		try
		{
			if($grp == 1)
			{
				$sql = 'SELECT * FROM `schools` WHERE id IN ('.$ids.')';
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
	public function selectAccessSimple($session , $term, $staff, $grp)
	{
		$tabl = 'access'.$session;
		try
		{
			if($grp == 1)
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'`
				WHERE
					 grp = 1 AND clientid = '.$staff.' AND termid = '.$term.'
					ORDER BY itemid';
			 }
			if($grp == 2)
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'`
				WHERE
					 grp = 1 AND clientid = '.$staff.' AND termid = '.$term.'
					ORDER BY itemid';
			 }
			if($table == 'accessstudentclass')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
			 }
			if($table == 'accessstudentclassfull')
			{
				$sql = '
				SELECT
					 *,
				(SELECT abbrv FROM `schools` WHERE `schools`.`id` = `students`.`schoolid`  LIMIT 1) AS schoolabbrv,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					`'.$tabl.'`.`id` AS cid
					
				FROM 
					`students`
				LEFT JOIN
					`'.$tabl.'` 
				ON
					`students`.`id` = `'.$tabl.'`.`clientid`
				'.$add.' ORDER BY surname, firstname, itemid';
			 }
			if($table == 'accessstudentsubject')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
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
				if($table == 'caunits' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `cas` WHERE `cas`.`id` = `caunits`.`caid` LIMIT 1  ) as casname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'grades' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `grades`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'gradeunits' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `grades` WHERE `grades`.`id` = `gradeunits`.`gradeid` LIMIT 1  ) as gradename
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
				if($table == 'units')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `units`.`departmentid` LIMIT 1  ) as departmentname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'fees')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `fees`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'offices')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `offices`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'jobs')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `offices` WHERE `offices`.`id` = `jobs`.`officeid` LIMIT 1  ) as officename
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'levels')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `levels`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'subjects')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `subjects`.`departmentid` LIMIT 1  ) as departmentname,
						 , 
						 (SELECT `name` as nm from `units` WHERE `units`.`id` = `subjects`.`unitid` LIMIT 1  ) as unitname,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `subjects`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'themes')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `subjects` WHERE `subjects`.`id` = `themes`.`subjectid` LIMIT 1  ) as subjectname,
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'inventorys')
				{
					$sql = '
					SELECT
						 *,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `inventorys`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'maintenances')
				{
					$sql = '
					SELECT
						 *,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `maintenances`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'inventoryunits')
				{
					$sql = '
					SELECT
						 *,
						 (SELECT `name` as nm from `inventorys` WHERE `inventorys`.`id` = `inventoryunits`.`inventoryid` LIMIT 1  ) as inventoryname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'maintenanceunits')
				{
					$sql = '
					SELECT
						 *,
						 (SELECT `name` as nm from `maintenances` WHERE `maintenances`.`id` = `maintenanceunits`.`maintenanceid` LIMIT 1  ) as maintenancename
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
				if($table == 'admissions')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `admissions`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'staffs')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `staffs`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `abbrv` as nm from `departments` WHERE `departments`.`id` = `staffs`.`departmentid` LIMIT 1  ) as departmentname,
						 
						 (SELECT `abbrv` as nm from `levels` WHERE `levels`.`id` = `staffs`.`designationid` LIMIT 1  ) as levelname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'staffsearch')
				{
					$ser = '%'.$where['search'].'%';
					$sql = '
					SELECT
					    id,
						CONCAT(surname," ",firstname," ",middlename) as name,
						employment_no,
						photo
					FROM 
					`staffs` 
					WHERE
						`surname` like "'.$ser.'" OR
						`firstname` like "'.$ser.'" OR
						`middlename` like "'.$ser.'" OR
						`employment_no` like "'.$ser.'"
						';
				}
				if($table == 'studentsearch')
				{
					$ser = '%'.$where['search'].'%';
					$sql = '
					SELECT
					    id,
						CONCAT(surname," ",firstname," ",middlename) as name,
						admission_no,
						photo
					FROM 
					`students` 
					WHERE
						`surname` like "'.$ser.'" OR
						`firstname` like "'.$ser.'" OR
						`middlename` like "'.$ser.'" OR
						`admission_no` like "'.$ser.'"
						';
				}
				if($table == 'notices' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `surname` as nm from `staffs` WHERE `staffs`.`id` = `notices`.`staffid` LIMIT 1  ) as staffname, 
						 (SELECT `photo` as nm from `staffs` WHERE `staffs`.`id` = `notices`.`staffid` LIMIT 1  ) as photo
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'birthday')
				{
					$sql = '
					SELECT
					    id,
						CONCAT(surname," ",firstname," ",middlename) as name,
						admission_no as numb,
						photo
					FROM 
					`students` WHERE DATE(dob) = CURDATE() 
					UNION
					SELECT
					    id,
						CONCAT(surname," ",firstname," ",middlename) as name,
						employment_no as numb,
						photo
					FROM 
					`staffs` WHERE DATE(dob) > CURDATE() 
						';
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
	public function selectes($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
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
				
			if( $table == 'staffeducations' || 
				$table == 'staffexperiences' || 
				$table == 'staffprofessionals' || 
				$table == 'stafflogs' || 
				$table == 'staffjobs' || 
				$table == 'staffaccesss' || 
				$table == 'staffleaves'
			  )
			{
				$sql = '
				SELECT
					 *, 
					 (SELECT `surname` as nm from `staffs` WHERE `staffid` = `staffs`.`id` LIMIT 1  ) as fullname
				FROM 
				`'. $table .'` '.$add;
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
	public function creatTableAccess($tablename)
	{
		$tb = 'access'.$tablename;
		$sql = "
		CREATE TABLE IF NOT EXISTS `".$tb."` (
			  `id` int(150) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
			  `grp` int(4) NOT NULL,
			  `termid` int(10) NOT NULL,
			  `itemid` int(10) NOT NULL,
			  `itemid1` int(10) DEFAULT 0,
			  `clientid` int(100) NOT NULL,
			  `contact` int(10) NOT NULL DEFAULT 0,
			  `is_active` tinyint(1) NOT NULL,
			  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
			  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
			  `checker` varchar(200) NOT NULL UNIQUE
			);
		";
		try
			{
				$dbh = $this->construct();
				$detail = $sql;
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
	public function creatTableStore($tablename)
	{
		$tb = 'access'.$tablename;
		$sql = "
		CREATE TABLE IF NOT EXISTS `".$tb."` (
			  `id` int(150) NOT NULL,
			  `grp` int(4) NOT NULL,
			  `termid` int(10) NOT NULL,
			  `itemid` int(10) NOT NULL,
			  `itemid1` int(10) DEFAULT 0,
			  `clientid` int(100) NOT NULL,
			  `contact` int(10) NOT NULL DEFAULT 0,
			  `is_active` tinyint(1) NOT NULL,
			  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
			  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
			  `checker` varchar(200) NOT NULL
			);
		";
		try
			{
				$dbh = $this->construct();
				$detail = $sql;
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