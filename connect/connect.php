<?php
require_once('class.dbcontrol.php');
require_once('common.php');

class DB extends DbControl
{
	// var $host = 'localhost';
	// var $db = 'ems';
	// var $user = 'doyinspc2';
	// var $pass = 'james414!@#$';
	
	var $host = 'localhost';
	var $db = 'stresert_ems';
	var $user = 'root';
	var $pass = '';

	// var $host = 'localhost';
	// var $db = 'stresert_ems';
	// var $user = 'stresert_ems';
	// var $pass = '!@ems1234';

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
	public function selectDropdown($schoolid, $num, $typeid = NULL, $mid = NULL)	{
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
				`claszunits`.`name` as name,
				`claszs`.`abbrv` as caname
			FROM
				`claszs`
			LEFT JOIN
				`claszunits`
			ON
				`claszs`.`id` = `claszunits`.`claszid`
			WHERE
				`claszs`.`typeid` = '.$typeid.' AND
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
				`unitid` as uid,
				`subjects`.`name` as name,
				`departments`.`name` as departmentname,
				(SELECT name FROM `units` WHERE `units`.`id` = `subjects`.`unitid`) as unitname
			FROM
				`departments`
			LEFT JOIN
				`subjects`
			ON
				`departments`.`id` = `subjects`.`departmentid`
			WHERE
				`subjects`.`typeid` = '.$typeid.' AND
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
		if($num == 8)
		{
			$sql = '
			SELECT 
				`caunits`.`id` as id,
				`cas`.`id` as sid,
				`cas`.`typeid` as typeid,
				`caunits`.`name` as name,
				`caunits`.`maxscore` as maxscore,
				`cas`.`maxscore` as score,
				`cas`.`name` as caname,
				`caunits`.`abbrv` as abbrv,
				`cas`.`abbrv` as caabbrv
			FROM
				`cas`
			LEFT JOIN
				`caunits`
			ON
				`cas`.`id` = `caunits`.`caid`
			WHERE
				`cas`.`termid` = '.$typeid.' AND
				`cas`.`is_active` =  0 AND
				`caunits`.`is_active` = 0  
			';
		}
		if($num == 9)
		{
			$sql = '
			SELECT 
					*
			FROM 
			(SELECT 
				`caunits`.`id` as id,
				`cas`.`id` as sid,
				`cas`.`typeid` as typeid,
				`caunits`.`name` as name,
				`caunits`.`maxscore` as maxscore,
				`cas`.`maxscore` as score,
				`cas`.`name` as caname,
				`caunits`.`abbrv` as abbrv,
				`cas`.`abbrv` as caabbrv,
				`cas`.`termid` as termid,
				(SELECT sessionid FROM `terms` WHERE `terms`.`id` = `cas`.`termid`) as session
			FROM
				`cas`
			LEFT JOIN
				`caunits`
			ON
				`cas`.`id` = `caunits`.`caid`
			WHERE
				`cas`.`is_active` =  0 AND
				`caunits`.`is_active` = 0 ) AS P

			WHERE `P`.`session` = '.$typeid.'
			';
		}
		if($num == 10)
		{
			$sql = '
			SELECT 
				`caunits`.`id` as id,
				`cas`.`id` as sid,
				`cas`.`typeid` as typeid,
				`caunits`.`name` as name,
				`caunits`.`maxscore` as maxscore,
				`cas`.`maxscore` as score,
				`cas`.`name` as caname,
				`caunits`.`abbrv` as abbrv,
				`cas`.`abbrv` as caabbrv
			FROM
				`cas`
			LEFT JOIN
				`caunits`
			ON
				`cas`.`id` = `caunits`.`caid`
			WHERE
				`caunits`.`id` IN ('.$typeid.') AND
				`cas`.`is_active` =  0 AND
				`caunits`.`is_active` = 0  
			';
		}if($num == 11)
		{
			$sql = '
			SELECT 
				*
			FROM
				`claszs`
			WHERE
				`claszs`.`typeid` = '.$typeid.' AND `claszs`.`is_delete` =  0   
			';
		}
		

		
		return $this->many($sql);}
	public function selectScore($table , $id = NULL, $where  = NULL, $tabl, $orderby  = NULL, $groupby  = NULL)
	{
		$add ='';
		$add ='';
		$starts ='';
		$ends ='';
		$ids ='';
		if(isset($id) && $id !== NULL)
		{
			$add = 'WHERE `'.$tabl.'`.`id` = '.$id;
		}
		elseif(isset($where))
		{
			if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
			if(strlen($ids) > 0){unset($where['ids']);}
			if(count($where) > 0)
			{
				$add = parent::wheresClause($where);
				$add .= parent::orderByClause($orderby);
			}
			
			if(strlen($ids) > 0)
			{
				$wh = ' `'.$tabl.'`.`clientid` IN ('.$ids.') ';
				if(strlen($add) > 0)
				{
					$add = $add." AND ".$wh;
				}else
				{
					$add." WHERE ".$wh;
				}
			}
	    }

		try
		{
			if($table == 'accessstudentscore')
			{
				 $sql = '
					SELECT
					 *
				 FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid';
			}
			else if($table == 'accessstudentscores')
			{
				 $sql = '
				 SELECT

					 (SELECT * )
				 FROM 
					`'.$tabl.'` ';
			}
			if($table == 'accessstudentca')
			{
				  $sql = '
					SELECT
					 *
				 FROM 
					`'.$tabl.'` WHERE id IN ('.$where['idx'].') ORDER BY itemid';
			}if($table == 'accessstudentcas')
			{
				  $sql = '
					SELECT
					 *
				    FROM 
					`'.$tabl.'` 
					WHERE 
						`clientid` IN ('.$where['idx'].')  AND
						`termid` = '.$where["reportid"].' AND
						`grp` = '.$where["grp"].'
					ORDER BY itemid';
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
		}		}
	public function selectReport($tabl , $id, $query)
	{
		$ids = $query['ids'];
		$ca = $query['cas'];
		$clasz = $query['clasz'];
		$claszparent = $query['claszparent'];
		$term = $query['termid'];

		$wh = ' `'.$tabl.'`.`clientid` IN ('.$ids.') ';

		try
		{
			if($id == 1)
			{
			  $sql = '
				SELECT
					`'.$tabl.'`.`clientid` AS studentid,
					(SELECT name FROM `subjects` WHERE id = `'.$tabl.'`.`itemid` LIMIT 1) AS subjectname,
					(SELECT abbrv FROM `subjects` WHERE id = `'.$tabl.'`.`itemid` LIMIT 1) AS subjectabbrv,
					`'.$tabl.'`.`itemid` AS subjectid,
					`'.$tabl.'`.`itemid1` AS caid,
					(SELECT caid FROM `caunits` WHERE id = `'.$tabl.'`.`itemid1` LIMIT 1) AS cid,
					(SELECT name FROM `caunits` WHERE id = `'.$tabl.'`.`itemid1` LIMIT 1) AS caunitname,
					(SELECT name FROM  `cas` WHERE id = (SELECT caid FROM `caunits` WHERE id = `'.$tabl.'`.`itemid1` LIMIT 1)) AS caname,
					(SELECT typeid FROM  `cas` WHERE id = (SELECT caid FROM `caunits` WHERE id = `'.$tabl.'`.`itemid1` LIMIT 1)) AS catypeid,
					`'.$tabl.'`.`contact` AS scored,
					`caunits`.`maxscore` AS maxscore,
					CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6)) AS score 
				FROM 
					`'.$tabl.'` 
				LEFT JOIN
					`caunits`
				ON
					`'.$tabl.'`.`itemid1` = `caunits`.`id`
				WHERE
				`'.$tabl.'`.`grp` = 8 AND
				`'.$tabl.'`.`clientid` IN ('.$ids.') 

				';
			}
			elseif($id == 2)
			{
				$sql = '
				SELECT
					subjectid,
					COUNT(DISTINCT studentid) as students,
					SUM(score) as score,
					SUM(maxscore) as maxscore,
					(SUM(score)/SUM(maxscore)) * 100 as avgr
				FROM
						(SELECT
							`'.$tabl.'`.`clientid` AS studentid,
							`'.$tabl.'`.`itemid` AS subjectid,
							`'.$tabl.'`.`contact` AS scored,
							SUM(`caunits`.`maxscore`) AS maxscore,
							SUM(CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6))) AS score 
						FROM 
							`'.$tabl.'` 
						LEFT JOIN
							`caunits`
						ON
							`'.$tabl.'`.`itemid1` = `caunits`.`id`
						WHERE
						`'.$tabl.'`.`grp` = 8  
						GROUP BY `'.$tabl.'`.`clientid` ,`'.$tabl.'`.`itemid` 
						) AS P
				RIGHT JOIN
						(SELECT
							`'.$tabl.'`.`clientid` AS clientid,
							`'.$tabl.'`.`itemid` AS itemid,
							`'.$tabl.'`.`contact` AS contact
						FROM
							`'.$tabl.'`
						LEFT JOIN
							`claszunits`
						ON 
							`'.$tabl.'`.`contact` = `claszunits`.`id`
						WHERE
						`'.$tabl.'`.`grp` = 3 AND 
						`'.$tabl.'`.`termid` = '.$term.' AND
						 `claszunits`.`claszid`  = '.$claszparent.' ) AS Q
				ON
					`P`.`studentid` = `Q`.`clientid`
				GROUP BY  `P`.`subjectid`
			 ';	 
			}
			elseif($id == 3)
			{
				$sql = '
				SELECT
					subjectid,
					COUNT(DISTINCT studentid) as students,
					SUM(score) as score,
					SUM(maxscore) as maxscore,
					(SUM(score)/SUM(maxscore)) * 100 as avgr
				FROM
						(
							SELECT
								`'.$tabl.'`.`clientid` AS studentid,
								`'.$tabl.'`.`itemid` AS subjectid,
								`'.$tabl.'`.`contact` AS scored,
								SUM(`caunits`.`maxscore`) AS maxscore,
								SUM(CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6))) AS score 
							FROM 
								`'.$tabl.'` 
							LEFT JOIN
								(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
							ON
								`'.$tabl.'`.`itemid1` = `caunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 8  
							GROUP BY `'.$tabl.'`.`clientid` ,`'.$tabl.'`.`itemid` 
						) AS P
				RIGHT JOIN
						(
							SELECT
								`'.$tabl.'`.`clientid` AS clientid,
								`'.$tabl.'`.`itemid` AS itemid,
								`'.$tabl.'`.`contact` AS contact
							FROM
								`'.$tabl.'`
							LEFT JOIN
								`claszunits`
							ON 
								`'.$tabl.'`.`contact` = `claszunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 3 AND 
							`'.$tabl.'`.`termid` = '.$term.' AND
							 `'.$tabl.'`.`contact`  = '.$clasz.' 
						 ) AS Q
				ON
					`P`.`studentid` = `Q`.`clientid`
				GROUP BY  `P`.`subjectid`
			 ';	 
			}
			elseif($id == 4)
			{
				$sql = '
				SELECT
					studentid,
					subjectid,
					SUM(score) as score,
					SUM(maxscore) as maxscore,
					(SUM(score)/SUM(maxscore)) * 100 as avgr
				FROM
						(
							SELECT
								`'.$tabl.'`.`clientid` AS studentid,
								`'.$tabl.'`.`itemid` AS subjectid,
								`'.$tabl.'`.`contact` AS scored,
								`caunits`.`maxscore` AS maxscore,
								CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6)) AS score 
							FROM 
								`'.$tabl.'` 
							LEFT JOIN
								(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
							ON
								`'.$tabl.'`.`itemid1` = `caunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 8  
						 
						) AS P
				RIGHT JOIN
						(
							SELECT
								`'.$tabl.'`.`clientid` AS clientid,
								`'.$tabl.'`.`itemid` AS itemid,
								`'.$tabl.'`.`contact` AS contact
							FROM
								`'.$tabl.'`
							LEFT JOIN
								`claszunits`
							ON 
								`'.$tabl.'`.`contact` = `claszunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 3 AND 
							`'.$tabl.'`.`termid` = '.$term.' AND
							 `claszunits`.`claszid`  = '.$claszparent.' 
						 ) AS Q
				ON
					`P`.`studentid` = `Q`.`clientid`
				GROUP BY  `P`.`studentid`, `P`.`subjectid`
			 ';	 
			}
			elseif($id == 5)
			{
				$sql = '
				SELECT 
					studentid,
					subjectid,
					avgr
				FROM
				(
					SELECT
						studentid,
						subjectid,
						SUM(score) as score,
						SUM(maxscore) as maxscore,
						(SUM(score)/SUM(maxscore)) * 100 as avgr
					FROM
							(
								SELECT
									`'.$tabl.'`.`clientid` AS studentid,
									`'.$tabl.'`.`itemid` AS subjectid,
									`'.$tabl.'`.`contact` AS scored,
									`caunits`.`maxscore` AS maxscore,
									CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6)) AS score 
								FROM 
									`'.$tabl.'` 
								LEFT JOIN
									(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
								ON
									`'.$tabl.'`.`itemid1` = `caunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 8  
							 
							) AS P
					RIGHT JOIN
							(
								SELECT
									`'.$tabl.'`.`clientid` AS clientid,
									`'.$tabl.'`.`itemid` AS itemid,
									`'.$tabl.'`.`contact` AS contact
								FROM
									`'.$tabl.'`
								LEFT JOIN
									`claszunits`
								ON 
									`'.$tabl.'`.`contact` = `claszunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 3 AND 
								`'.$tabl.'`.`termid` = '.$term.' AND
								 `claszunits`.`claszid`  = '.$claszparent.' 
							 ) AS Q
					ON
						`P`.`studentid` = `Q`.`clientid`
					GROUP BY  `P`.`studentid`, `P`.`subjectid`
				) AS W
				WHERE `avgr` > 0
			 ';			 
			}
			elseif($id == 6)
			{
				$sql = '
				SELECT 
					studentid,
					subjectid,
					avgr
				FROM
				(
					SELECT
						studentid,
						subjectid,
						SUM(score) as score,
						SUM(maxscore) as maxscore,
						(SUM(score)/SUM(maxscore)) * 100 as avgr
					FROM
							(
								SELECT
									`'.$tabl.'`.`clientid` AS studentid,
									`'.$tabl.'`.`itemid` AS subjectid,
									`'.$tabl.'`.`contact` AS scored,
									`caunits`.`maxscore` AS maxscore,
									CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6)) AS score 
								FROM 
									`'.$tabl.'` 
								LEFT JOIN
									(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
								ON
									`'.$tabl.'`.`itemid1` = `caunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 8  
							 
							) AS P
					RIGHT JOIN
							(
								SELECT
									`'.$tabl.'`.`clientid` AS clientid,
									`'.$tabl.'`.`itemid` AS itemid,
									`'.$tabl.'`.`contact` AS contact
								FROM
									`'.$tabl.'`
								LEFT JOIN
									`claszunits`
								ON 
									`'.$tabl.'`.`contact` = `claszunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 3 AND 
								`'.$tabl.'`.`termid` = '.$term.' AND
								`'.$tabl.'`.`contact`  = '.$clasz.'  
							 ) AS Q
					ON
						`P`.`studentid` = `Q`.`clientid` 
					WHERE `score` > 0
					GROUP BY  `P`.`studentid`, `P`.`subjectid`
				) AS W
				WHERE `avgr` > 0
			 ';			 
			}
			elseif($id == 7)
			{
				$sql = '
				SELECT 
					studentid,
					SUM(avgr) AS total,
					AVG(avgr) AS avg,
					COUNT(subjectid) AS nums
				FROM
				(
					SELECT
						studentid,
						subjectid,
						SUM(score) as score,
						SUM(maxscore) as maxscore,
						(SUM(score)/SUM(maxscore)) * 100 as avgr
					FROM
							(
								SELECT
									`'.$tabl.'`.`clientid` AS studentid,
									`'.$tabl.'`.`itemid` AS subjectid,
									`'.$tabl.'`.`contact` AS scored,
									`caunits`.`maxscore` AS maxscore,
									CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6)) AS score 
								FROM 
									`'.$tabl.'` 
								LEFT JOIN
									(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
								ON
									`'.$tabl.'`.`itemid1` = `caunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 8  
							 
							) AS P
					RIGHT JOIN
							(
								SELECT
									`'.$tabl.'`.`clientid` AS clientid,
									`'.$tabl.'`.`itemid` AS itemid,
									`'.$tabl.'`.`contact` AS contact
								FROM
									`'.$tabl.'`
								LEFT JOIN
									`claszunits`
								ON 
									`'.$tabl.'`.`contact` = `claszunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 3 AND 
								`'.$tabl.'`.`termid` = '.$term.' AND
								 `claszunits`.`claszid`  = '.$claszparent.'  
							 ) AS Q
					ON
						`P`.`studentid` = `Q`.`clientid`
					GROUP BY  `P`.`studentid`, `P`.`subjectid`
				) AS W
				GROUP BY studentid
			 ';			 
			}
			elseif($id == 8)
			{
				$sql = '
				SELECT 
					studentid,
					SUM(avgr) AS total,
					AVG(avgr) AS avg,
					COUNT(subjectid) AS nums
				FROM
				(
					SELECT
						studentid,
						subjectid,
						SUM(score) as score,
						SUM(maxscore) as maxscore,
						(SUM(score)/SUM(maxscore)) * 100 as avgr
					FROM
							(
								SELECT
									`'.$tabl.'`.`clientid` AS studentid,
									`'.$tabl.'`.`itemid` AS subjectid,
									`'.$tabl.'`.`contact` AS scored,
									`caunits`.`maxscore` AS maxscore,
									`'.$tabl.'`.`contact` * ((`caunits`.`maxscore`/ `caunits`.`totalscore`) * `caunits`.`cascore`) AS score
								FROM 
									`'.$tabl.'` 
								LEFT JOIN
									(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 1 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
								ON
									`'.$tabl.'`.`itemid1` = `caunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 8  
							 
							) AS P
					RIGHT JOIN
							(
								SELECT
									`'.$tabl.'`.`clientid` AS clientid,
									`'.$tabl.'`.`itemid` AS itemid,
									`'.$tabl.
									'`.`contact` AS contact
								FROM
									`'.$tabl.'`
								LEFT JOIN
									`claszunits`
								ON 
									`'.$tabl.'`.`contact` = `claszunits`.`id`
								WHERE
								`'.$tabl.'`.`grp` = 3 AND 
								`'.$tabl.'`.`termid` = '.$term.' AND
								`'.$tabl.'`.`contact`  = '.$clasz.'  
							 ) AS Q
					ON
						`P`.`studentid` = `Q`.`clientid`
					GROUP BY  `P`.`studentid`, `P`.`subjectid`
				) AS W
				GROUP BY studentid
			 ';			 
			}
			elseif($id == 9)
			{
			 $sql = '
			  SELECT
			  	studentid,
			  	subjectname,
			  	subjectid,
			  	caid,
			  	totalscore,
			  	cascore,
			  	SUM(scoremax) AS scoremax,
			  	SUM(score) AS score
			  FROM
				(SELECT
					`'.$tabl.'`.`clientid` AS studentid,
					(SELECT name FROM `subjects` WHERE id = `'.$tabl.'`.`itemid` LIMIT 1) AS subjectname,
					`'.$tabl.'`.`itemid` AS subjectid,
					`'.$tabl.'`.`contact` AS scored,
					`caunits`.`caid` AS caid,
					`caunits`.`maxscore` AS maxscore,
					`caunits`.`totalscore` AS totalscore,
					`caunits`.`cascore` AS cascore,
					(`caunits`.`maxscore`/ `caunits`.`totalscore`) * `caunits`.`cascore` AS scoremax,
					`'.$tabl.'`.`contact` * ((`caunits`.`maxscore`/ `caunits`.`totalscore`) * `caunits`.`cascore`) AS score
				FROM 
					`'.$tabl.'` 
				RIGHT JOIN
					(SELECT 
						`caunits`.`id` AS id,
						`caunits`.`maxscore` AS maxscore,
						`caunits`.`name` AS name,
						`caunits`.`abbrv` AS abbrv,
						`cas`.`id` AS caid,
						(SELECT 
							SUM(maxscore) AS ms 
							FROM `caunits`
							WHERE 
							 `caunits`.`caid` = `cas`.`id` AND
							 `caunits`.`is_active` = 0 AND
							 `caunits`.`is_active` = 0  
							GROUP BY `caunits`.`caid` LIMIT 1 
						) AS totalscore,
						`cas`.`maxscore` AS cascore,
						`cas`.`abbrv` AS caabbrv
					FROM
					`caunits`
						RIGHT JOIN
					`cas`
					ON
					`caunits`.`caid` = `cas`.`id`
					WHERE 
					`cas`.`is_active` = 0 AND
					`caunits`.`is_active` = 0 AND
					`cas`.`is_delete` = 0 AND
					`caunits`.`is_delete` = 0 AND
					`cas`.`typeid` = 1 AND
					`caunits`.`id` IN ('.$ca.')
					) AS caunits
				ON
					`'.$tabl.'`.`itemid1` = `caunits`.`id`
				WHERE
				`'.$tabl.'`.`grp` = 8 AND
				`'.$tabl.'`.`clientid` IN ('.$ids.') 
				) AS H
				GROUP BY studentid, subjectid, caid
				';
			}
			elseif($id == 10)
			{
				$sql = '
				SELECT
					subjectid,
					COUNT(DISTINCT studentid) as students,
					SUM(score) as score,
					SUM(maxscore) as maxscore,
					(SUM(score)/SUM(maxscore)) * 10 as avgr
				FROM
						(
							SELECT
								`'.$tabl.'`.`clientid` AS studentid,
								`'.$tabl.'`.`itemid` AS subjectid,
								`'.$tabl.'`.`contact` AS scored,
								SUM(`caunits`.`maxscore`) AS maxscore,
								SUM(CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6))) AS score 
							FROM 
								`'.$tabl.'` 
							LEFT JOIN
								(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 2 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
							ON
								`'.$tabl.'`.`itemid1` = `caunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 8  
							GROUP BY `'.$tabl.'`.`clientid` ,`'.$tabl.'`.`itemid` 
						) AS P
				RIGHT JOIN
						(
							SELECT
								`'.$tabl.'`.`clientid` AS clientid,
								`'.$tabl.'`.`itemid` AS itemid,
								`'.$tabl.'`.`contact` AS contact
							FROM
								`'.$tabl.'`
							LEFT JOIN
								`claszunits`
							ON 
								`'.$tabl.'`.`contact` = `claszunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 3 AND 
							`'.$tabl.'`.`termid` = '.$term.' AND
							 `'.$tabl.'`.`contact`  = '.$clasz.' 
						 ) AS Q
				ON
					`P`.`studentid` = `Q`.`clientid`
				GROUP BY  `P`.`subjectid`
			 ';	 
			}
			elseif($id == 11)
			{
				$sql = '
				SELECT
					subjectid,
					COUNT(DISTINCT studentid) as students,
					SUM(score) as score,
					SUM(maxscore) as maxscore,
					(SUM(score)/SUM(maxscore)) * 10 as avgr
				FROM
						(
							SELECT
								`'.$tabl.'`.`clientid` AS studentid,
								`'.$tabl.'`.`itemid` AS subjectid,
								`'.$tabl.'`.`contact` AS scored,
								SUM(`caunits`.`maxscore`) AS maxscore,
								SUM(CAST(`'.$tabl.'`.`contact` AS DECIMAL(10, 6)) * CAST(`caunits`.`maxscore` AS DECIMAL(10, 6))) AS score 
							FROM 
								`'.$tabl.'` 
							LEFT JOIN
								(SELECT 
										`caunits`.`id` AS id,
										`caunits`.`maxscore` AS maxscore,
										`caunits`.`name` AS name,
										`caunits`.`abbrv` AS abbrv,
										`cas`.`id` AS caid,
										(SELECT 
											SUM(maxscore) AS ms 
											FROM `caunits`
											WHERE 
											 `caunits`.`caid` = `cas`.`id` AND
											 `caunits`.`is_active` = 0 AND
											 `caunits`.`is_active` = 0  
											GROUP BY `caunits`.`caid` LIMIT 1 
										) AS totalscore,
										`cas`.`maxscore` AS cascore,
										`cas`.`abbrv` AS caabbrv
									FROM
									`caunits`
										RIGHT JOIN
									`cas`
									ON
									`caunits`.`caid` = `cas`.`id`
									WHERE 
									`cas`.`is_active` = 0 AND
									`caunits`.`is_active` = 0 AND
									`cas`.`is_delete` = 0 AND
									`caunits`.`is_delete` = 0 AND
									`cas`.`typeid` = 3 AND
									`caunits`.`id` IN ('.$ca.')
									) AS caunits
							ON
								`'.$tabl.'`.`itemid1` = `caunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 8  
							GROUP BY `'.$tabl.'`.`clientid` ,`'.$tabl.'`.`itemid` 
						) AS P
				RIGHT JOIN
						(
							SELECT
								`'.$tabl.'`.`clientid` AS clientid,
								`'.$tabl.'`.`itemid` AS itemid,
								`'.$tabl.'`.`contact` AS contact
							FROM
								`'.$tabl.'`
							LEFT JOIN
								`claszunits`
							ON 
								`'.$tabl.'`.`contact` = `claszunits`.`id`
							WHERE
							`'.$tabl.'`.`grp` = 3 AND 
							`'.$tabl.'`.`termid` = '.$term.' AND
							 `'.$tabl.'`.`contact`  = '.$clasz.' 
						 ) AS Q
				ON
					`P`.`studentid` = `Q`.`clientid`
				GROUP BY  `P`.`subjectid`
			 ';	 
			}
			elseif($id == 12)
			{
				$sql = '
				SELECT 
					`P`.`studentid` AS studentid,
					COUNT(`P`.`score`) AS num, 
					SUM(`P`.`score`) AS total,
					AVG(`P`.`score`) AS score  
				FROM
				(SELECT 
					`itemid` AS subjectid,
					`clientid` AS studentid,
					SUM(`contact`) as score
				FROM
					`'.$tabl.'`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].' AND 
					`'.$tabl.'`.`clientid` IN ('.$ids.')
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`studentid`

				';	 
			}elseif($id == 13)
			{
				$sql = '
				SELECT 
					`P`.`studentid` AS studentid,
					COUNT(`P`.`score`) AS num, 
					SUM(`P`.`score`) AS total,
					AVG(`P`.`score`) AS score  
				FROM
				(SELECT 
					`itemid`AS subjectid,
					`clientid` AS studentid,
					SUM(`contact`) as score
				FROM
					`'.$tabl.'`
				LEFT JOIN
				 (SELECT  `clientid` AS cid 
				 FROM `'.$tabl.'`
				 WHERE
					 `'.$tabl.'`.`grp` = 4 AND 
					 `'.$tabl.'`.`termid` = '.$term.' AND
					 `'.$tabl.'`.`itemid`  IN (
					 	SELECT GROUP_CONCAT(`id`) AS id FROM `claszunits` WHERE `claszid` = '.$claszparent.'
					 )) AS Q
				 ON 
					`'.$tabl.'`.`clientid` = `Q`.`cid`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`studentid`

				';	 
			}
			elseif($id == 14)
			{
				$sql = '
				SELECT 
					`P`.`subjectid` AS subjectid,
					COUNT(`P`.`score`) AS num, 
					SUM(`P`.`score`) AS total,
					AVG(`P`.`score`) AS score  
				FROM
				(SELECT 
					`itemid`AS subjectid,
					`clientid` AS studentid,
					SUM(`contact`) as score
				FROM
					`'.$tabl.'`
				LEFT JOIN
				 (SELECT  `clientid` AS cid 
				 FROM `'.$tabl.'`
				 WHERE
					 `'.$tabl.'`.`grp` = 4 AND 
					 `'.$tabl.'`.`termid` = '.$term.' AND
					 `'.$tabl.'`.`itemid`  IN (
					 	SELECT GROUP_CONCAT(`id`) AS id FROM `claszunits` WHERE `claszid` = '.$claszparent.'
					 )) AS Q
				 ON 
					`'.$tabl.'`.`clientid` = `Q`.`cid`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`subjectid`

				';	 
			}
			elseif($id == 15)
			{
				$sql = '
				SELECT
					`studentid`,
					`studentname`,
					`classid`,
					`claszid`,
					`subjectid`,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = classid LIMIT 1) as classname,
					(SELECT name FROM `claszs` WHERE `claszs`.`id` = `Q`.`claszid` LIMIT 1) as claszname,
					`score`
				FROM
				(SELECT
					`P`.`studentid` AS studentid,
					`P`.`studentname` AS studentname,
					`P`.`classid` AS classid,
					(SELECT claszid FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid` LIMIT 1) as claszid,
					COUNT(`P`.`subjectid`) AS subjectid,
					AVG(`P`.`score`) AS score
				FROM
				(SELECT 
					`studentclass`.`id` AS studentid,
					`studentclass`.`studentname` AS studentname,
					`'.$tabl.'`.`itemid` AS subjectid,
					`studentclass`.`classid` AS classid,
					SUM(`'.$tabl.'`.`contact`) AS score
				FROM
					`'.$tabl.'`
				RIGHT JOIN
					(SELECT 
						`students`.`id` as id, 
						 CONCAT(`surname`," ",`firstname`," ",`middlename`) as studentname ,
						 `itemid` as classid
					FROM 
							`students` 
					RIGHT JOIN 
					 		`'.$tabl.'`
					 ON
					 		`students`.`id` = `'.$tabl.'`.`clientid`
					 WHERE 
					 		`'.$tabl.'`.`grp` = 4 AND `'.$tabl.'`.`termid` = '.$term.'	
					) AS studentclass
				ON 
					`'.$tabl.'`.`clientid` = `studentclass`.`id`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`studentid`) AS Q
				ORDER BY `score` DESC
				';	 
			}
			elseif($id == 16)
			{
				$sql = '
				SELECT
					`Q`.`classid` AS classid,	
					`Q`.`claszid` AS claszid,
					`Q`.`classname` AS classname,
					(SELECT name FROM `claszs` WHERE `claszs`.`id` = `Q`.`claszid` LIMIT 1) as claszname,
					COUNT( DISTINCT `Q`.`studentid`) AS studentid,
					AVG(`Q`.`score`) AS score
				FROM
				(SELECT
					`P`.`classid` AS classid,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid` LIMIT 1) as classname,
					(SELECT claszid FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid` LIMIT 1) as claszid,
					`P`.`studentid` AS studentid,
					AVG(`P`.`score`) AS score
				FROM
				(SELECT 
					`studentclass`.`id` AS studentid,
					`studentclass`.`studentname` AS studentname,
					`'.$tabl.'`.`itemid` AS subjectid,
					`studentclass`.`classid` AS classid,
					SUM(`'.$tabl.'`.`contact`) AS score
				FROM
					`'.$tabl.'`
				RIGHT JOIN
					(SELECT 
						`students`.`id` as id, 
						 CONCAT(`surname`," ",`firstname`," ",`middlename`) as studentname,
						 `itemid` as classid
					FROM 
							`students` 
					RIGHT JOIN 
					 		`'.$tabl.'`
					 ON
					 		`students`.`id` = `'.$tabl.'`.`clientid`
					 WHERE 
					 		`'.$tabl.'`.`grp` = 4 AND `'.$tabl.'`.`termid` = '.$term.'	
					) AS studentclass
				ON 
					`'.$tabl.'`.`clientid` = `studentclass`.`id`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`classid`, `P`.`studentid`
				) AS Q
				GROUP BY 
					`Q`.`classid`
				ORDER BY
					`Q`.`classname`
				';	 
			}		
			elseif($id == 17)
			{
				$sql = '
				SELECT
					`Q`.`classid` AS classid,	
					`Q`.`classname` AS classname,
					COUNT( DISTINCT `Q`.`studentid`) AS studentid,
					AVG(`Q`.`score`) AS score
				FROM
				(SELECT
					`P`.`classid` AS classid,
					(SELECT name FROM `claszs` WHERE `claszs`.`id` = `P`.`classid` LIMIT 1) as classname,
					`P`.`studentid` AS studentid,
					AVG(`P`.`score`) AS score
				FROM
				(SELECT 
					`studentclass`.`id` AS studentid,
					`studentclass`.`studentname` AS studentname,
					`'.$tabl.'`.`itemid` AS subjectid,
					`studentclass`.`classid` AS classid,
					SUM(`'.$tabl.'`.`contact`) AS score
				FROM
					`'.$tabl.'`
				RIGHT JOIN
					(SELECT 
						`students`.`id` as id, 
						 CONCAT(`surname`," ",`firstname`," ",`middlename`) as studentname,
						 (SELECT claszid FROM `claszunits` WHERE `id` = itemid LIMIT 1) AS classid
					FROM 
							`students` 
					RIGHT JOIN 
					 		`'.$tabl.'`
					 ON
					 		`students`.`id` = `'.$tabl.'`.`clientid`
					 WHERE 
					 		`'.$tabl.'`.`grp` = 4 AND `'.$tabl.'`.`termid` = '.$term.'	
					) AS studentclass
				ON 
					`'.$tabl.'`.`clientid` = `studentclass`.`id`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				GROUP BY 
					`P`.`classid`, `P`.`studentid`
				) AS Q
				GROUP BY 
					`Q`.`classid`
				ORDER BY
					`Q`.`classname`
				';	 
			}
			elseif($id == 18)
			{
				$sql = '
				SELECT
					`P`.`classid` AS classid,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid` LIMIT 1) as classname,
					`P`.`subjectid` AS subjectid,
					`P`.`subjectname` AS subjectname,
					`P`.`studentid` AS studentid,
					`P`.`studentname` AS studentname,
					`P`.`score` AS score
				FROM
				(SELECT 
					`studentclass`.`id` AS studentid,
					`studentclass`.`studentname` AS studentname,
					`'.$tabl.'`.`itemid` AS subjectid,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid` LIMIT 1) as subjectname,
					
					`studentclass`.`classid` AS classid,
					SUM(`'.$tabl.'`.`contact`) AS score
				FROM
					`'.$tabl.'`
				RIGHT JOIN
					(SELECT 
						`students`.`id` as id, 
						 CONCAT(`surname`," ",`firstname`," ",`middlename`) as studentname,
						 `itemid` as classid
					FROM 
							`students` 
					RIGHT JOIN 
					 		`'.$tabl.'`
					ON
					 		`students`.`id` = `'.$tabl.'`.`clientid`
					WHERE 
					 		`'.$tabl.'`.`grp` = 4 AND `'.$tabl.'`.`termid` = '.$term.'	
					) AS studentclass
				ON 
					`'.$tabl.'`.`clientid` = `studentclass`.`id`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
				ORDER BY
					`P`.`subjectname`		
				';	 
			}
			elseif($id == 19)
			{
				$sql = '
				SELECT
					`P`.`classid` AS classid,
					(SELECT name FROM `claszs` WHERE `claszs`.`id` = `P`.`classid` LIMIT 1) as classname,
					`P`.`subjectid` AS subjectid,
					`P`.`subjectname` AS subjectname,
					`P`.`studentid` AS studentid,
					`P`.`studentname` AS studentname,
					`P`.`score` AS score
				FROM
				(SELECT 
					`studentclass`.`id` AS studentid,
					`studentclass`.`studentname` AS studentname,
					`'.$tabl.'`.`itemid` AS subjectid,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid` LIMIT 1) as subjectname,
					`studentclass`.`classid` AS classid,
					SUM(`'.$tabl.'`.`contact`) AS score
				FROM
					`'.$tabl.'`
				RIGHT JOIN
					(SELECT 
						`students`.`id` as id, 
						 CONCAT(`surname`," ",`firstname`," ",`middlename`) as studentname,
						 (SELECT claszid FROM `claszunits` WHERE `id` = itemid LIMIT 1) AS classid
					FROM 
							`students` 
					RIGHT JOIN 
					 		`'.$tabl.'`
					 ON
					 		`students`.`id` = `'.$tabl.'`.`clientid`
					 WHERE 
					 		`'.$tabl.'`.`grp` = 4 AND `'.$tabl.'`.`termid` = '.$term.'	
					) AS studentclass
				ON 
					`'.$tabl.'`.`clientid` = `studentclass`.`id`
				WHERE
					`'.$tabl.'`.`grp` = 11 AND
					`'.$tabl.'`.`termid` = '.$query['reportid'].'
				GROUP BY
					`'.$tabl.'`.`clientid`, `'.$tabl.'`.`itemid`) AS P
					ORDER BY
					`P`.`subjectname`	
				
				';	 
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
		}		}
	public function selectAccess($table , $id = NULL, $where  = NULL, $tabl, $orderby  = NULL, $groupby  = NULL)
	{
		//print_r($where);
		$add ='';
		$contact ='';
		if(isset($id) && $id !== NULL)
		{
			$add = 'WHERE `'.$tabl.'`.`id` = '.$id;
		}elseif(isset($where))
		{
			
			if($table == 'accessstudentsubjectmultiple'){
				$contact = $where['contact'];
				unset($where['contact']);
			}
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
				$sql = 'SELECT 
					*,
					(
						SELECT 
							COUNT(`id`)  as number_of_candidate
						FROM 
							`'.$tabl.'` 
						WHERE
							`'.$tabl.'`.`termid` = `P`.`termid` AND
							`'.$tabl.'`.`grp` = 3 AND
							`'.$tabl.'`.`contact` = `P`.`itemid` AND
							`'.$tabl.'`.`itemid` = `P`.`itemid1` AND
							`'.$tabl.'`.`itemid1` = `P`.`clientid` 
						GROUP BY
								termid, itemid, itemid1
						limit 1
					) AS number_of_candidate
				FROM
				(SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT abbrv FROM `claszs` WHERE `claszs`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemnameops,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemabbrv1,
					(SELECT CONCAT(`surname`," ", SUBSTRING(`firstname`, 1, 1)," ", SUBSTRING(middlename, 1, 1)) AS exc FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientabbrv,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid1) AS P';}
		   if($table == 'accessstaffsubjectnum')
			{
				$staff = $where['clientid'];
				$termid = $where['termid'];
				$grp = $where['grp'];
				
				$sql = 'SELECT
				*,
					(
						SELECT 
							COUNT(`id`)  as number_of_candidate
						FROM 
							`'.$tabl.'` 
						WHERE
							`'.$tabl.'`.`termid` = `P`.`termid` AND
							`'.$tabl.'`.`grp` = 3 AND
							`'.$tabl.'`.`contact` = `P`.`itemid` AND
							`'.$tabl.'`.`itemid` = `P`.`itemid1` AND
							`'.$tabl.'`.`itemid1` = `P`.`clientid` 
						GROUP BY
								termid, itemid, itemid1
						limit 1
					) AS num
				FROM
				(SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT abbrv FROM `claszs` WHERE `claszs`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemnameops,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemabbrv1,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname,
					
					(SELECT COUNT(id) FROM `'.$tabl.'` WHERE `'.$tabl.'`.`itemid1` = '.$staff.' AND `'.$tabl.'`.`grp` = 3  LIMIT 1) AS cnt
				FROM 
					`'.$tabl.'`
				WHERE 
					`'.$tabl.'`.`clientid` = '.$staff.' AND 
					`'.$tabl.'`.`termid` = '.$termid.' AND
					`'.$tabl.'`.`grp` = 2  
				ORDER BY itemid1) as P ';
			 }
			if($table == 'accessstaffsubjectnum1')
			{
				$staff = $where['clientid'];
				$grp = $where['grp'];
				$clasz = $where['clasz'];
				$termid = $where['termid'];
				
				$sql = 'SELECT
				*,
					(
						SELECT 
							COUNT(`id`)  as number_of_candidate
						FROM 
							`'.$tabl.'` 
						WHERE
							`'.$tabl.'`.`termid` = `P`.`termid` AND
							`'.$tabl.'`.`grp` = 3 AND
							`'.$tabl.'`.`contact` = `P`.`itemid` AND
							`'.$tabl.'`.`itemid` = `P`.`itemid1` AND
							`'.$tabl.'`.`itemid1` = `P`.`clientid` 
						GROUP BY
								termid, itemid, itemid1
						limit 1
					) AS num
				FROM
				(SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid` LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
					(SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemabbrv1,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`'.$tabl.'`
				WHERE 
					`'.$tabl.'`.`clientid` = '.$staff.' AND 
					`'.$tabl.'`.`termid` = '.$termid.' AND
					`'.$tabl.'`.`grp` = 2 AND 
					`'.$tabl.'`.`itemid` = '.$clasz.'  
				ORDER BY itemid1) AS P ORDER BY clientname';
			 }
			if($table == 'accessstudentclass')
			{
				$sql = '
				SELECT
					 *,
				    `'.$tabl.'`.`id` as cid,
				    `students`.`id` as id,
				    (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as schoolname,
					(SELECT `abbrv` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as abbrv,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname
				FROM 
					`students`
				LEFT JOIN
				`'.$tabl.'` 
					
				ON
					`'.$tabl.'`.`clientid` = `students`.`id`
					'.$add.' ORDER BY surname, firstname, middlename';
			 }
			if($table == 'accessstudentclassfull')
			{
				$sql = '
				SELECT
					 *,
				  `'.$tabl.'`.`id` as cid,
				    `students`.`id` as id,
				    (SELECT abbrv FROM `schools` WHERE `schools`.`id` = `students`.`schoolid`  LIMIT 1) AS abbrv,
				    (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as schoolname,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT GROUP_CONCAT(name) as nm FROM `claszunits` WHERE id IN (SELECT CONCAT(`itemid`) AS cl FROM `'.$tabl.'` WHERE `'.$tabl.'`.`termid` = '.$where['termid'].' AND `'.$tabl.'`.`grp` = '.$where['grp'].' AND `'.$tabl.'`.`clientid` = `students`.`id` )) AS dupli 
					
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
				 	*
				 	FROM
				(SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS staffname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname,
					(SELECT admission_no FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS admission_no,
					(SELECT photo FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS photo
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid) AS P ORDER BY clientname';
			 }
			if($table == 'accessstudentsubjectmultiple')
			{
				$cn = strlen($add) > 0 ? " AND `contact` in (".$contact.") " : " WHERE  `contact` in (".$contact.") ";
				$add .= $cn;
				$sql = '
				 SELECT
				 	*
				 	FROM
				(SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid`  LIMIT 1) AS itemname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS staffname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS clientname,
					(SELECT admission_no FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS admission_no,
					(SELECT photo FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS photo
				FROM 
					`'.$tabl.'` '.$add.' ORDER BY itemid) AS P ORDER BY clientname';
			 }
			if($table == 'assessment')
			{
				$sql = '
				SELECT *
				FROM
				(SELECT
					`P`.`itemid`,
					`P`.`classid`,
					 COUNT(DISTINCT clientid) AS num,
					 GROUP_CONCAT(DISTINCT clientid) AS studentid,
					 (SELECT name FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid`  LIMIT 1) AS itemname1,
					 (SELECT claszid FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid`  LIMIT 1) AS caid,
					 (SELECT abbrv FROM `claszs` WHERE `id` = (SELECT claszid FROM `claszunits` WHERE `claszunits`.`id` = `P`.`classid`  LIMIT 1) limit 1) AS caname,
					 (SELECT abbrv FROM `claszs` WHERE `claszs`.`id` = `P`.`classid`  LIMIT 1) AS itemnameops,
					 (SELECT UPPER(name) as nm FROM `subjects` WHERE `subjects`.`id` = `P`.`itemid`  LIMIT 1) AS itemname,
					 (SELECT abbrv FROM `subjects` WHERE `subjects`.`id` = `P`.`itemid`  LIMIT 1) AS itemabbrv,
					 GROUP_CONCAT(DISTINCT `P`.`clientname`) as clientname,
					 AVG(score) AS score,
					 MAX(score) AS maxscore,
					 MIN(score) AS minscore
				FROM 
					(SELECT
						`Q`.`clientid`,
						`Q`.`itemid`,
						`Q`.`staffid`,
						(SELECT CONCAT(UPPER(surname)," ",UPPER(SUBSTRING(firstname, 0, 1))," ", UPPER(SUBSTRING(middlename, 0, 1)) ) as nm FROM `staffs` WHERE `staffs`.`id` = `Q`.`staffid`  LIMIT 1) AS clientname,
						(SELECT itemid FROM `'.$tabl.'` WHERE  `termid` = '.$where['termid'].' AND grp = 4 AND `clientid` = `Q`.`clientid` LIMIT 1 ) AS classid,
						SUM(`Q`.`score`) as score
					  FROM 
						(SELECT
							*,
							CAST((SELECT maxscore FROM `caunits` WHERE `id` = itemid1 LIMIT 1) AS DECIMAL(10, 6)) * CAST(`contact` AS DECIMAL(10, 6)) AS score
						FROM
							`'.$tabl.'`
						WHERE 
							`grp` = 8 AND
							`termid` = '.$where['termid'].' AND
							`itemid1` IN ('.$where['itemid1'].')
					 	) AS Q
					 GROUP BY clientid, itemid ) AS P
				GROUP BY
					 `P`.`classid`, `P`.`itemid`
				ORDER BY
					`P`.`itemid`, `P`.`classid`) AS R
				ORDER BY `R`.`itemname`
				 ';
			}
			if($table == 'assessmentdetails')
			{
				 $sql ='
				       SELECT
							*,
							(SELECT CONCAT(UPPER(surname)," ", LOWER(firstname)," ", LOWER(middlename) ) as nm FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`clientid`  LIMIT 1) AS studentname,
							(SELECT CONCAT(UPPER(surname)," ", LOWER(firstname)," ", LOWER(middlename) ) as nm FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`staffid`  LIMIT 1) AS staffname,
							(SELECT name as nm FROM `caunits` WHERE `caunits`.`id` = `'.$tabl.'`.`itemid1`  LIMIT 1) AS itemname1,
							(SELECT itemid FROM `'.$tabl.'` WHERE  `termid` = '.$where['termid'].' AND grp = 4 AND `clientid` = `'.$tabl.'`.`clientid` LIMIT 1 ) AS classid,
							CAST((SELECT maxscore FROM `caunits` WHERE `id` = itemid1 LIMIT 1) AS DECIMAL(10, 6)) * CAST(`contact` AS DECIMAL(10, 6)) AS score
						FROM
							`'.$tabl.'`
						WHERE 
							`grp` = 8 AND
							`termid` = '.$where['termid'].' AND
							`itemid` = '.$where['itemid'].' AND
							`itemid1` IN ('.$where['itemid1'].') AND
							`clientid` IN ('.$where['clientid'].')
					 	 ';
			}
			if($table == 'lessonplanreport')
			{
					$sql = '
					SELECT 
					*
					FROM
						(
							SELECT
								 `'.$tabl.'`.`id` as sid,
								 `'.$tabl.'`.`termid` as termid,
								 `'.$tabl.'`.`itemid` as subjectid,
								 `'.$tabl.'`.`itemid1` as claszid,
								 `'.$tabl.'`.`clientid` as clientid,
	(SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`clientid`) as staffname,
	(SELECT `name` as nm from `subjects` WHERE `subjects`.`id` = `'.$tabl.'`.`itemid` LIMIT 1  ) as subjectname,
	(SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `'.$tabl.'`.`itemid1` LIMIT 1  ) as claszname,
	(SELECT `abbrv` as nm from `claszs` WHERE `claszs`.`id` = `'.$tabl.'`.`itemid1` LIMIT 1  ) as claszabbrv
							FROM 
								`'.$tabl.'`
							WHERE
								`termid` = '.$where['termid'].' AND
								`grp` = 3
						) AS P
					LEFT JOIN
						(
							SELECT 
								*,
	(SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `stafflessonplan`.`claszid` LIMIT 1) as claszunitnameops,
	(SELECT `name` as nm from `claszunits` WHERE `claszunits`.`id` = `stafflessonplan`.`claszid` LIMIT 1) as claszunitname
							FROM
								`stafflessonplan`
							LEFT JOIN
								`themes`
							ON
								`stafflessonplan`.`schemeid` = `themes`.`id`
							WHERE
								`termid` = '.$where['termid'].' AND
								`weekid` = "'.$where['weekid'].'"
						) as Q
					ON 
						`P`.`clientid` = `Q`.`staffid` AND 
						`P`.`itemid1` = `Q`.`claszid` AND 
						`P`.`itemid` = `Q`.`subjectid` 
					';
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
		}		}
	public function selectFees($table , $id = NULL, $where  = NULL, $tabl, $tabl1  = NULL, $groupby  = NULL)
	{
		$add ='';
		$add ='';
		$starts ='';
		$ends ='';
		$ids ='';

		if(isset($id) && $id !== NULL && $id > 0)
		{
			$add = 'WHERE `id` = '.$id;
		}elseif(isset($where))
		{

			if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
			if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
			if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
			if(strlen($ids) > 0){unset($where['ids']);}
			if(count($where) > 0){
			$add = parent::wheresClause($where);
			$add .= parent::orderByClause($orderby);
			}
			if(strlen($starts) > 0 && strlen($ends) > 0)
			{

				$wh = ' `'.$tabl.'`.`datepaid` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
				if(strlen($add) > 0)
				{
					$add = $add." AND ".$wh;
				}else
				{
					$add." WHERE ".$wh;
				}
			}
			
			if(strlen($ids) > 0)
			{
				$wh = ' `'.$tabl.'`.`studentid` IN ('.$ids.') ';
				if(strlen($add) > 0)
				{
					$add = $add." AND ".$wh;
				}else
				{
					$add." WHERE ".$wh;
				}
			}
	    }
		try
		{
			if($table == 'studentfees')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `accounts` WHERE `accounts`.`id` = `'.$tabl.'`.`accountid`  LIMIT 1) AS accountname,
					(SELECT name FROM `fees` WHERE `fees`.`id` = `'.$tabl.'`.`feeid`  LIMIT 1) AS feename,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`studentid`  LIMIT 1) AS studentname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`staffid`  LIMIT 1) AS staffname,
					(SELECT `itemid` FROM `'.$tabl1.'` 	WHERE `clientid` = `'.$tabl.'`.`studentid` AND `grp` = 4 LIMIT 1 ) AS classid,
					(SELECT `NAME` FROM `claszunits` WHERE `id` = (SELECT `itemid` FROM `'.$tabl1.'` 	WHERE `clientid` = `'.$tabl.'`.`studentid` AND `grp` = 4  LIMIT 1 ) LIMIT 1) AS classname

				FROM 
					`'.$tabl.'` '.$add.'  ORDER BY datepaid';
			 }
			 if($table == 'studentfeesmo')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `accounts` WHERE `accounts`.`id` = `'.$tabl.'`.`accountid`  LIMIT 1) AS accountname,
					(SELECT name FROM `fees` WHERE `fees`.`id` = `'.$tabl.'`.`feeid`  LIMIT 1) AS feename,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`studentid`  LIMIT 1) AS studentname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`staffid`  LIMIT 1) AS staffname,
					(SELECT `itemid` FROM `'.$tabl1.'` 	WHERE `clientid` = `'.$tabl.'`.`studentid` AND `grp` = 4 LIMIT 1 ) AS classid,
					(SELECT `NAME` FROM `claszunits` WHERE `id` = (SELECT `itemid` FROM `'.$tabl1.'` 	WHERE `clientid` = `'.$tabl.'`.`studentid` AND `grp` = 4  LIMIT 1 ) LIMIT 1) AS classname

				FROM 
					`'.$tabl.'` '.$add.'  ORDER BY datepaid';
			 }
			 elseif($table == 'studentfeess')
			{
				$sql = '
				SELECT
					 *,
					(SELECT name FROM `terms` WHERE `terms`.`id` = `'.$tabl.'`.`termid`  LIMIT 1) AS termname,
					(SELECT name FROM `accounts` WHERE `accounts`.`id` = `'.$tabl.'`.`accountid`  LIMIT 1) AS accountname,
					(SELECT name FROM `fees` WHERE `fees`.`id` = `'.$tabl.'`.`feeid`  LIMIT 1) AS feename,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `students` WHERE `students`.`id` = `'.$tabl.'`.`studentid`  LIMIT 1) AS studentname,
					(SELECT CONCAT(surname," ", firstname," ", middlename ) FROM `staffs` WHERE `staffs`.`id` = `'.$tabl.'`.`staffid`  LIMIT 1) AS staffname
				FROM 
					`'.$tabl.'` '.$add.' ';
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
				return $rows;}	
		}	
		catch (PDOException $e)
		{
			$msg = $db.":";
			$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
			return $msg;
		}		
	}
	public function selectedAttendance($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL )
	{
		try
		{
			$add ='';
			$starts ='';
			$ends ='';
			$ids ='';
			
				if(isset($where))
				{

					if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
					if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
					if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
					if(strlen($ids) > 0){unset($where['ids']);}
					$add = parent::wheresClause($where);
					$add .= parent::orderByClause($orderby);
				
					if(strlen($starts) > 0 && strlen($ends) > 0)
					{
						$wh = ' `'.$table.'`.`dates` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
					if(strlen($ids) > 0)
					{
						$wh = ' `'.$table.'`.`clients` IN ('.$ids.') ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
			    }
			
				$sql = 'SELECT * FROM `'. $table .'` '.$add;
			 
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
		}		}
	public function selectedAttendances($table , $id = NULL, $where  = NULL,  $note, $session =NULL, $term=NULL )
	{
		try
		{
			$add ='';
			$starts ='';
			$ends ='';
			$ids ='';
			if(isset($where['note'])){$starts = $where['note']; unset($where['note']);}
				if(isset($where))
				{
					if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
					if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
					if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
					if(strlen($ids) > 0){unset($where['ids']);}
					$add = parent::wheresClause($where);
				
					if(strlen($starts) > 0 && strlen($ends) > 0)
					{
						$wh = ' `'.$table.'`.`dates` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
					if(strlen($ids) > 0)
					{
						$wh = ' `'.$table.'`.`clients` IN ('.$ids.') ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
			    }
		
			 	if($note == 2)
			 	{
			 		$access = 'access_'.$session;
					$sql = '
						SELECT
							 *,
							(SELECT CONCAT(surname," ",firstname," ",middlename) as name FROM `students` WHERE `students`.`id` = clients LIMIT 1) AS clientname,
							(SELECT CONCAT(admission_no,"::::",photo,"::::",g1_phone1,"::::",g1_phone2,"::::",g2_phone1,"::::",g2_phone2,"::::","::::",g1_email,"::::",g2_email) as name FROM `students` WHERE `students`.`id` = clients LIMIT 1) AS clientdata,
							(SELECT 
								itemid  as nm
							FROM 
								`'.$access.'`
							WHERE 
								`'.$access.'`.`clientid` = `clients` AND  
								`'.$access.'`.`termid` = '. $term .' AND
								`'.$access.'`.`grp` = 2
							LIMIT 1) AS claszunitid
						FROM 
						`'. $table .'` '.$add;
				}
				elseif($note == 1)
			 	{
					$sql = '
						SELECT
							 *
						FROM 
						`'. $table .'` '.$add;
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
		}		}
	public function selectedInventory($table , $id = NULL, $where  = NULL,  $note, $session =NULL, $term=NULL )
	{
		try
		{
			$add ='';
			$starts ='';
			$ends ='';
			$ids ='';

			if(isset($where['note'])){$starts = $where['note']; unset($where['note']);}

			if(isset($where))
			{
					if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
					if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
					if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
					if(strlen($ids) > 0){unset($where['ids']);}
					$add = '';//parent::wheresClause($where);
				
					if(strlen($starts) > 0 && strlen($ends) > 0)
					{
						$wh = ' `'.$table.'`.`datereported` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
					if(strlen($ids) > 0)
					{
						$wh = ' `'.$table.'`.`clients` IN ('.$ids.') ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}
			}
		
			if($note == 1)
			{
			 	 $sql = 'SELECT
							 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `inventorytransactions`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `name` as nm from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) as inventoryname,
						 (SELECT `inventoryid` as cd from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `inventorys` WHERE `id` = (SELECT `inventoryid` as cd from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) LIMIT 1) AS cname	 
						FROM 
						`'. $table .'` '.$add;
			}
			elseif($note == 2)
			{
					$inventoryid = $where['inventoryid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							states,
							SUM(`quantity`) as quantity
						FROM `inventorytransactions` 
						WHERE `inventoryid` = '.$inventoryid.' AND `schoolid` = '.$schoolid.'
						GROUP BY `states`
					';
			}
			elseif($note == 3)
			{
					$inventoryid = $where['inventoryid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							`price` 
						FROM `inventorytransactions` 
						WHERE `states` = 1 AND `inventoryid` = '.$inventoryid.' AND `schoolid` = '.$schoolid.'
						ORDER BY `daterecorded` ASC LIMIT 1
					';
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
		}		}
	public function selectedExpense($table , $id = NULL, $where  = NULL,  $note, $session =NULL, $term=NULL )
	{
		try
		{
			$add ='';
			$starts ='';
			$ends ='';
			$ids ='';

			if(isset($where['note'])){$starts = $where['note']; unset($where['note']);}

			if(isset($where))
			{

					if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
					if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
					if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
					if(strlen($ids) > 0){unset($where['ids']);}
					$add = '';//parent::wheresClause($where);
				
					if(strlen($starts) > 0 && strlen($ends) > 0)
					{

						$wh = ' `'.$table.'`.`daterecorded` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;

						}else
						{
							$add =  $add." WHERE ".$wh;
						}
						
					}

					if(strlen($ids) > 0)
					{
						$wh = ' `'.$table.'`.`clients` IN ('.$ids.') ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}

			}
		
			if($note == 1)
			{
			 	 $sql = 'SELECT
							 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `expensetransactions`.`schoolid` LIMIT 1  ) as schoolname,
						  (SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `expensetransactions`.`staffid` LIMIT 1  ) as staffname,
						  (SELECT `name` as nm from `accounts` WHERE `accounts`.`id` = `expensetransactions`.`accountid` LIMIT 1  ) as accountname,
						 (SELECT `name` as nm from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) as expensename,
						 (SELECT `expenseid` as cd from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `expenses` WHERE `id` = (SELECT `expenseid` as cd from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) LIMIT 1) AS cname	 
						FROM 
						`'. $table .'` '.$add.' ORDER BY `daterecorded` DESC';
			}
			elseif($note == 2)
			{
					$expenseid = $where['expenseid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							states,
							SUM(`quantity`) as quantity
						FROM `expensetransactions` 
						WHERE `expenseid` = '.$expenseid.' AND `schoolid` = '.$schoolid.'
						GROUP BY `states`
					';
			}
			elseif($note == 3)
			{
					$inventoryid = $where['inventoryid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							`price` 
						FROM `inventorytransactions` 
						WHERE `states` = 1 AND `inventoryid` = '.$inventoryid.' AND `schoolid` = '.$schoolid.'
						ORDER BY `daterecorded` ASC LIMIT 1
					';
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
	public function selectedMaintenance($table , $id = NULL, $where  = NULL,  $note, $session =NULL, $term=NULL )
	{
		try
		{
			$add ='';
			$starts ='';
			$ends ='';
			$ids ='';

			if(isset($where['note'])){$starts = $where['note']; unset($where['note']);}

			if(isset($where))
			{

					if(isset($where['starts'])){$starts = $where['starts']; unset($where['starts']);}
					if(isset($where['ends'])){$ends = $where['ends']; unset($where['ends']);}
					if(isset($where['ids'])){$ids = $where['ids']; unset($where['ids']);}
					if(strlen($ids) > 0){unset($where['ids']);}
					$add = '';//parent::wheresClause($where);
				
					if(strlen($starts) > 0 && strlen($ends) > 0)
					{

						$wh = ' `'.$table.'`.`daterecorded` BETWEEN CAST("'.$starts.'" AS DATE) AND CAST("'.$ends.'" AS DATE) ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;

						}else
						{
							$add =  $add." WHERE ".$wh;
						}
						
					}

					if(strlen($ids) > 0)
					{
						$wh = ' `'.$table.'`.`clients` IN ('.$ids.') ';
						if(strlen($add) > 0)
						{
							$add = $add." AND ".$wh;
						}else
						{
							$add." WHERE ".$wh;
						}
					}

			}
		
			if($note == 1)
			{
			 	 $sql = 'SELECT
							 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `maintenancetransactions`.`schoolid` LIMIT 1  ) as schoolname,
						  (SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `maintenancetransactions`.`staffid` LIMIT 1  ) as staffname,
						 (SELECT `name` as nm from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  ) as maintenancename,
						 (SELECT `maintenanceid` as cd from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `maintenances` WHERE `id` = (SELECT `maintenanceid` as cd from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  ) LIMIT 1) AS cname	 
						FROM 
						`'. $table .'` '.$add.' ORDER BY `daterecorded` DESC';
			}
			elseif($note == 2)
			{
					$maintenanceid = $where['maintenanceid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							states,
							SUM(`quantity`) as quantity
						FROM `maintenancetransactions` 
						WHERE `expenseid` = '.$expenseid.' AND `schoolid` = '.$schoolid.'
						GROUP BY `states`
					';
			}
			elseif($note == 3)
			{
					$inventoryid = $where['inventoryid'];
					$schoolid = $where['schoolid'];

					$sql = '
						SELECT  
							`price` 
						FROM `inventorytransactions` 
						WHERE `states` = 1 AND `inventoryid` = '.$inventoryid.' AND `schoolid` = '.$schoolid.'
						ORDER BY `daterecorded` ASC LIMIT 1
					';
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
		}		}
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
		}		}
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
			 if($table == 'accessstudentsubjectmultiple')
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
		}		}
	public function selectSummary($session , $term, $grp)
	{
		
		try
		{
			if($grp == 1)
			{
				$tabl = 'access_'.$session;
				$sql = '
					SELECT
					 `claszunits`.`id` AS id,
					 `claszunits`.`name` AS name,
					  COUNT(`P`.`id`) AS nums,
					  GROUP_CONCAT(`P`.`gender`) AS gender,
					  GROUP_CONCAT(`P`.`house`) AS house,
					  GROUP_CONCAT(`P`.`dob`) AS dob,
					  GROUP_CONCAT(`P`.`religion`) AS religion,
					  GROUP_CONCAT(`P`.`place`) AS place,
					  GROUP_CONCAT(`P`.`soo`) AS soo
					FROM 
					(SELECT 
						`'.$tabl.'`.`id` AS id,
						`'.$tabl.'`.`itemid` AS itemid,
						`students`.`gender` AS gender,
						`students`.`house` AS house,
						`students`.`dob` AS dob,
						`students`.`religion` AS religion,
						`students`.`place` AS place,
						`students`.`soo` AS soo
					FROM
						`'.$tabl.'`
					LEFT JOIN
						`students`
					ON
						`'.$tabl.'`.clientid = `students`.`id`
					WHERE
					 grp = 4 AND 
					 termid = '.$term.' ) AS P
				LEFT JOIN
					`claszunits`
				ON
					`P`.`itemid` = `claszunits`.`id`
				GROUP BY `P`.`itemid` 
				ORDER BY `claszunits`.`name`';
			}
			elseif($grp == 2)
			{
				$tabl = 'fee_'.$session;
				$tabl1 = 'access_'.$session;
				$sql = '
				SELECT
					`R`.`id` AS id,
					`R`.`name` AS name,
					COUNT(`R`.`id`) AS nums,
					SUM( `R`.`pay`) AS pay,
					SUM( `R`.`fee`) AS fee,
					SUM( `R`.`bal`) AS bal,
					SUM( `R`.`bals`) AS bals,
					GROUP_CONCAT(`R`.`bals`) AS BBL,
					`R`.`grp` AS grp
				FROM
				(SELECT
					  `P`.`claszunitid` AS id,
					  `P`.`cname` AS name,
					  `P`.`id` AS studentid,
					  `Q`.`fee` AS fee,
					  `Q`.`pay` AS pay,
					  `Q`.`fee` - `Q`.`pay` AS bal,
					  IF (CAST(`Q`.`pay` AS DECIMAL(10, 6)) > CAST(`Q`.`fee` AS DECIMAL(10, 6)),  CAST(`Q`.`pay` AS DECIMAL(10, 6)) - CAST(`Q`.`fee` AS DECIMAL(10, 6)),  0) AS bals,
					  `Q`.`grp` AS grp
					  
				FROM 
					(
						SELECT 
						`students`.`id` AS id,
						`'.$tabl1.'`.`itemid` AS claszunitid,
						(SELECT name FROM `claszunits` WHERE `'.$tabl1.'`.`itemid` = `claszunits`.`id` LIMIT 1) AS cname
						FROM
							`students`
						RIGHT JOIN
							`'.$tabl1.'`
						ON
							`students`.`id` = `'.$tabl1.'`.`clientid` 
						WHERE
					 	grp = 4 AND 
					 	termid = '.$term.' 
					) AS P
				RIGHT JOIN
					(
						SELECT
							studentid AS studentid,
							grp AS grp,
							IF (grp = "0",  SUM(amount), 0) AS pay,
							IF (grp = "1",  SUM(amount), 0) AS fee
							
						FROM
						  `'.$tabl.'`
						GROUP BY 
							studentid, grp
					) AS Q				
				ON
					`P`.`id` = `Q`.`studentid`) AS R
					GROUP BY `R`.id
				';
			} 
			elseif($grp == 3)
			{
				$tabl = 'fee_'.$session;
				$tabl1 = 'access_'.$session;
				$sql = '
				SELECT
					  `P`.`claszunitid` AS id,
					  `P`.`cname` AS name,
					  COUNT(`P`.`id`) AS nums,
					  SUM( `'.$tabl.'`.`amount`) AS pay,
					  `'.$tabl.'`.`grp` AS grp
					  
				FROM 
					(
					SELECT 
						`students`.`id` AS id,
						`'.$tabl1.'`.`itemid` AS claszunitid,
						(SELECT name FROM `claszunits` WHERE `'.$tabl1.'`.`itemid` = `claszunits`.`id` LIMIT 1) AS cname
					  
					FROM
						`students`
					LEFT JOIN
						`'.$tabl1.'`
					ON
						`students`.`id` = `'.$tabl1.'`.`clientid` 
					WHERE
					 	grp = 4 AND 
					 	termid = '.$term.' 
					) AS P
				RIGHT JOIN
					`'.$tabl.'`
				ON
					`P`.`id` = `'.$tabl.'`.`studentid`
				GROUP BY `P`.`claszunitid`, `'.$tabl.'`.`grp`
				ORDER BY `P`.`cname`';
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
				if($table == 'weeks' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `terms` WHERE `terms`.`id` = `weeks`.`termid` LIMIT 1  ) as termname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'attendances' )
				{
					$sql = '
					SELECT
						 *
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
				if($table == 'expenses' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `expenses`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'expenseunits' )
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `expenses` WHERE `expenses`.`id` = `expenseunits`.`expenseid` LIMIT 1  ) as expensename
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'accounts')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `accounts`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'comments')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `surname` as nm from `staffs` WHERE `staffs`.`id` = `comments`.`staffid` LIMIT 1  ) as staffname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'cbts')
				{
					$sql = '
					SELECT
						 *
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'cbtexams')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `subjects` WHERE `subjects`.`id` = `cbtexams`.`subjectid` LIMIT 1  ) as subjectname,
						(SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `cbtexams`.`claszid` LIMIT 1  ) as claszname,
						(SELECT `name` as nm from `cbts` WHERE `cbts`.`id` = `cbtexams`.`cbtid` LIMIT 1  ) as cbtname
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
				if($table == 'timetables')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `terms` WHERE `terms`.`id` = `timetables`.`termid` LIMIT 1  ) as termname,
						 (SELECT `name` as nm from `sessions` WHERE `sessions`.`id` = `timetables`.`sessionid` LIMIT 1  ) as sessionname
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
						 (SELECT `name` as nm from `units` WHERE `units`.`id` = `subjects`.`unitid` LIMIT 1  ) as unitname,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `subjects`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'subjectsort')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `subjects`.`departmentid` LIMIT 1  ) as departmentname,
						 (SELECT `name` as nm from `units` WHERE `units`.`id` = `subjects`.`unitid` LIMIT 1  ) as unitname,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `subjects`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`subjects` '.$add.' ORDER BY name';
				}
				if($table == 'reports')
				{
					$sql = '
					SELECT
						 *, 
						(SELECT CONCAT(`surname`," ",`firstname`," ", `middlename`) as nm from `staffs` WHERE `staffs`.`id` = `reports`.`staffid` LIMIT 1  ) as staffname,
						(SELECT `name` as nm from `terms` WHERE `terms`.`id` = `reports`.`termid` LIMIT 1  ) as termname,
						(SELECT `name` as nm from `grades` WHERE `grades`.`id` = `reports`.`grade` LIMIT 1  ) as gradename,
						(SELECT `abbrv` as nm from `sessions` WHERE `sessions`.`id` = `reports`.`sessionid` LIMIT 1  ) as sessionabbrv,
						(SELECT `schoolid` as nm from `sessions` WHERE `sessions`.`id` = `reports`.`sessionid` LIMIT 1  ) as schoolid
					FROM 
					`'. $table .'` '.$add.' ORDER BY id';
				}
				if($table == 'themes')
				{
					$sql = '
					SELECT
						 *, 
						(SELECT CONCAT(`surname`," ",`firstname`," ", `middlename`) as nm from `staffs` WHERE `staffs`.`id` = `themes`.`staffid` LIMIT 1  ) as staffname,
						 (SELECT `name` as nm from `subjects` WHERE `subjects`.`id` = `themes`.`subjectid` LIMIT 1  ) as subjectname,
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszname,
						  (SELECT `abbrv` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszabbrv
					FROM 
					`'. $table .'` '.$add.' ORDER BY moduleid';
				}
				if($table == 'themereport')
				{
					$sql = '
					SELECT
						 *,
					(SELECT CONCAT(`surname`," ",SUBSTRING(`firstname`, 1, 1)," ", SUBSTRING(`middlename`,1,1)) as nm from `staffs` WHERE `staffs`.`id` = `themes`.`staffid` LIMIT 1  ) as staffname,
					(SELECT phone1 as nm from `staffs` WHERE `staffs`.`id` = `themes`.`staffid` LIMIT 1  ) as phonenumber,
						 (SELECT `name` as nm from `subjects` WHERE `subjects`.`id` = `themes`.`subjectid` LIMIT 1  ) as subjectname,
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszname,
						  (SELECT `abbrv` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszabbrv
					FROM 
					`themes` 
					WHERE
					`subjectid` IN ('.$where['itemid'].')
					ORDER BY ABS(moduleid)
					';
				}
				
				if($table == 'classfees')
				{
					$sql = '
					SELECT
						 *,
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `classfees`.`claszid` LIMIT 1  ) as claszname,
						 (SELECT `name` as nm from `fees` WHERE `fees`.`id` = `classfees`.`feeid` LIMIT 1  ) as feename
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
				if($table == 'inventorytransactions')
				{
					$sql = '
					SELECT
						 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `inventorytransactions`.`schoolid` LIMIT 1  ) as schoolname,
						  (SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `inventoryetransactions`.`staffid` LIMIT 1  ) as staffname,
						 (SELECT `name` as nm from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) as inventoryname,
						 (SELECT `inventoryid` as cd from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `inventorys` WHERE `id` = (SELECT `inventoryid` as cd from `inventoryunits` WHERE `inventorytransactions`.`inventoryid` = `inventoryunits`.`id` LIMIT 1  ) LIMIT 1) AS cname
					FROM 
					`'. $table .'` '.$add;
				}
				if($table == 'expensetransactions')
				{
					$sql = '
					SELECT
						 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `expensetransactions`.`schoolid` LIMIT 1  ) as schoolname,
						  (SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `expensetransactions`.`staffid` LIMIT 1  ) as staffname,
						  (SELECT `name` as nm from `accounts` WHERE `accounts`.`id` = `expensetransactions`.`accountid` LIMIT 1  ) as accountname,
						 (SELECT `name` as nm from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) as expensename,
						 (SELECT `expenseid` as cd from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `expenses` WHERE `id` = (SELECT `expenseid` as cd from `expenseunits` WHERE `expensetransactions`.`expenseid` = `expenseunits`.`id` LIMIT 1  ) LIMIT 1) AS cname
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
				if($table == 'maintenancetransactions')
				{
					$sql = '
					SELECT
						 *,
						  (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `maintenancetransactions`.`schoolid` LIMIT 1  ) as schoolname,
			(SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `maintenancetransactions`.`staffid` LIMIT 1  ) as staffname,
			(SELECT CONCAT(`surname`," ",`firstname`," ",`middlename`) as nm from `staffs` WHERE `staffs`.`id` = `maintenancetransactions`.`userid` LIMIT 1  ) as username,
						 (SELECT `name` as nm from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  ) as maintenancename,
						 (SELECT `maintenanceid` as cd from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  ) as cid,
						 (SELECT `name` as nm FROM `maintenances` WHERE `id` = (SELECT `maintenanceid` as cd from `maintenanceunits` WHERE `maintenancetransactions`.`maintenanceid` = `maintenanceunits`.`id` LIMIT 1  )		 LIMIT 1) AS cname
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
				if($table == 'claszsort')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `claszs`.`schoolid` LIMIT 1  ) as schoolname
					FROM 
					`claszs` '.$add.' ORDER BY `name`';
				}
				if($table == 'claszunits')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `claszunits`.`claszid` LIMIT 1  ) as claszname
					FROM 
					`'. $table .'` '.$add.' ORDER BY name';
				}
				if($table == 'claszunitsort')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `claszunits`.`claszid` LIMIT 1  ) as claszname
					FROM 
					`claszunits` '.$add.' ORDER BY name';
				}
				if($table == 'admissions')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `admissions`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `abbrv` as nm from `schools` WHERE `schools`.`id` = `admissions`.`schoolid` LIMIT 1  ) as abbrv,
						 (SELECT `signed` as nm from `schools` WHERE `schools`.`id` = `admissions`.`schoolid` LIMIT 1  ) as signed

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
						  (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `staffs`.`departmentid` LIMIT 1  ) as departmentnames,
						 
						 (SELECT `name` as nm from `levels` WHERE `levels`.`id` = `staffs`.`designationid` LIMIT 1  ) as levelname
					FROM 
					`'. $table .'` '.$add.' ORDER BY surname, firstname, middlename';
				}
				if($table == 'allstaffs')
				{
					$sql = '
					SELECT
						`staffs`.`id`,
						 `staffs`.`surname`,
						 `staffs`.`firstname`,
						 `staffs`.`middlename`,
						`staffs`. `employment_no` as numb,
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `staffs`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `abbrv` as nm from `schools` WHERE `schools`.`id` = `staffs`.`schoolid` LIMIT 1  ) as schoolabbrv,
						 (SELECT `abbrv` as nm from `departments` WHERE `departments`.`id` = `staffs`.`departmentid` LIMIT 1  ) as departmentname,
						 (SELECT `name` as nm from `departments` WHERE `departments`.`id` = `staffs`.`departmentid` LIMIT 1  ) as departmentnames,
						 (SELECT `name` as nm from `levels` WHERE `levels`.`id` = `staffs`.`designationid` LIMIT 1  ) as levelname
					FROM 
					`staffs` '.$add.' ORDER BY surname, firstname, middlename';
				}
				if($table == 'students')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `abbrv` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as abbrv
					FROM 
					`'. $table .'` '.$add.' ORDER BY surname, firstname, middlename';
				}
				if($table == 'studentx')
				{
					$sql = '
					SELECT
						 *, 
						 (SELECT `name` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as schoolname,
						 (SELECT `abbrv` as nm from `schools` WHERE `schools`.`id` = `students`.`schoolid` LIMIT 1  ) as abbrv
					FROM 
					`students` 	WHERE id IN ('.$where['ids'].') ORDER BY surname, firstname, middlename';
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
						(`surname` like "'.$ser.'" OR
						`firstname` like "'.$ser.'" OR
						`middlename` like "'.$ser.'" OR
						`employment_no` like "'.$ser.'") AND
						`schoolid` = "'.$where['schoolid'].'"
						 ORDER BY surname, firstname, middlename';
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
						(`surname` like "'.$ser.'" OR
						`firstname` like "'.$ser.'" OR
						`middlename` like "'.$ser.'" OR
						`admission_no` like "'.$ser.'") AND
						`schoolid` = "'.$where['schoolid'].'"
						ORDER BY surname, firstname, middlename';
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
		}	}
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
		}	}
	public function selectess($table , $id = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		try
		{
				
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
				`'. $table .'` WHERE staffid IN ('.$where['staffid'].')';
			}
			if($table == 'themesummary')
			{
				$sql = '
				SELECT
					subjectid,
					claszid,
					termid,
					COUNT(`themes`.`id`) AS num,
					`subjects`.`name` AS subjectname,
					(SELECT `name` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszname,
					(SELECT `abbrv` as nm from `claszs` WHERE `claszs`.`id` = `themes`.`claszid` LIMIT 1  ) as claszabbrv
				FROM 
					`themes`
				LEFT JOIN
					`subjects`
				ON
					`themes`.`subjectid` = `subjects`.`id`
				WHERE
					`subjects`.`typeid` = '.$where['typeid'].'
				
				GROUP BY subjectid, claszid, termid
				';

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
		}	}	
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
			  `contact` varchar(30) NOT NULL DEFAULT 0,
			  `is_active` tinyint(1) NOT NULL,
			  `staffid` int(100) DEFAULT 0,
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
			}}
	public function creatTableFee($tablename)
	{
		$tb = 'fee'.$tablename;
		$sql = "
		CREATE TABLE IF NOT EXISTS `".$tb."` (
			  `id` int(150) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
			  `grp` int(4) NOT NULL,
			  `termid` int(10) NOT NULL,
			  `studentid` int(200) NOT NULL,
			  `accountid` int(10) DEFAULT 0,
			  `feeid` int(100) NOT NULL,
			  `teller` int(40) DEFAULT NULL,
			  `amount` varchar(200) DEFAULT 0,
			  `staffid` int(200) NOT NULL,
			  `datepaid` date NOT NULL,
			  `is_active` tinyint(1) NOT NULL,
			  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
			  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
			}}
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
			}}
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