<?php
require_once('class.dbcontrol.php');

class DB extends DbControl{
	var $host = "localhost";
	var $db = "elimstaff";
	var $user = "root";
	var $pass = "";
	public function chrr($a, $b){
		if($b == 0){
			return ucwords(strtolower($a));
			}
		
		}
	public function getYears($d){
	 if(($d != "0000-00-00") && ($d != NULL)) {
        $date1 = date_create($d);
		$date2 = date_create(date('Y-m-d'));
        $diff = date_diff($date1,$date2);
        return $diff->format("%y yrs, %m m ");
	 }
	 else{
		 return "--.--";
		 }
 	}
	public function maincheck($a, $b){
			if(isset($a) && strlen($a)>0){
				return $a;
				}
			else{
				header('location:'.$b.'');
				}
		}
	public function ect($a, $b){
		return $a;
		}
	public function dct($a, $b){
		return $a;
		}
	 public  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public  function encode($value, $e){ 
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $e, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }

    public function decode($value, $e){
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $e, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
	public function construct(){
		
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
		
		return $dbh;
	}
	protected function array_to_pdo_params($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = "`$name` = ?";
 		 }
  		return implode(', ', $temp);
	}
	protected function array_to_pdo_key($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = "`$name` ";
 		 }
  		return "( ".implode(', ', $temp)." )";
	}
	public function object_to_array($data) 
		{
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
	public function array_or_array($d,$e){
	if(is_array($e)){
		$i =$e;
	}else{
	$i = explode(',',$e);
	}
	$item = '(';
	foreach($i as $ii){
		$item .= $d."='".$ii."' OR "; 
		}
	return substr($item,0,-3).')';
	}	
	protected function array_to_pdo_place($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = " ? ";
 		 }
  		return "( ".implode(', ', $temp)." )";
	}
	protected function array_to_pdo_value($array) {
 	 $temp = array();
  		foreach (array_keys($array) as $name) {
   			 $temp[] = " :$name ";
 		 }
  		return "( ".implode(', ', $temp)."  )";
	}
	public function studentSession($e){
		$se = $this->select('session');
			$details = array();
			foreach($se as $ses){
				$sc = 'student_class'.$ses->id;
				$ss = 'student_subject'.$ses->id;
				//get class
				$sc1 = $this->selectOne($sc, NULL, array('studentID'=>$e));
				$ss1 = $this->select($ss, NULL, array('studentID'=>$e));
					if(isset($sc1) && !isset($ss1)){
						$details[$ses->id] = array($ses->name, $sc1, NULL);
					}
				    elseif(!isset($sc1) && isset($ss1) && is_array($ss1)){
						$details[$ses->id] = array($ses->name,NULL, $ss1);
						}
					elseif(isset($sc1) && isset($ss1) && is_array($ss1)){
						$details[$ses->id] = array($ses->name,$sc1, $ss1);
						}
				}
			return $details;
		}
	public function studentSemester($e){
					//	get semesters
					$semesters = $this->select('semester', NULL, NULL, array('id desc'));
					$details2 = array();
					foreach($semesters as $semester){
						$get_session = $this->selectOne('session', NULL, array('id'=>$semester->session));
						$s = array();
						$s[] = 'student_class'.$semester->id;
						$s[] = 'student_subject'.$semester->id;
						$s[] = 'student_behavior'.$semester->id;
						$s[] = 'student_health'.$semester->id;
						$s[] = 'student_fee'.$semester->id;
						$s[] = 'student_money'.$semester->id;
						$s[] = 'student_result'.$semester->id;
						//foreach semester get cas
						$deta = array();
						$dbh = $this->construct();
						foreach($s as $sr){
							 $sr1 = $this->ifTableExist($e, $sr, $dbh);
							 $deta[] = $sr1;
						}
						$cas = $this->select('ca', null, array('semesterID'=>$semester->id));
						$all_scores = array();
						$all_subjects = array();
						if(in_array('student_result'.$semester->id, $deta)){
						foreach($cas as $ca){
						$get_all_subjects = $this->select('student_result'.$semester->id, NULL, array('caID'=>$ca->id, 'studentID'=>$e));
								$subject_score = array();
								foreach($get_all_subjects as $j){
									$subject_score[$j->subjectID] = $j->score; 
									$all_subjects[] = $j->subjectID;
									}
								if(count($subject_score) > 0){$all_scores[$ca->id] = $subject_score;}
							}
						}
						$name_file = array($semester->name, $get_session->name, $get_session->id);
						$details2[$semester->id] = array($name_file, $cas, $all_scores, array_unique($all_subjects), $deta);
						$deta = array();
						}
					
							
				
			return $details2;
		}
public function studentSemesterP($e){
					//	get semesters
					$semesters = $this->select('semester', NULL, NULL, array('id desc'));
					$details2 = array();
					foreach($semesters as $semester){
						$get_session = $this->selectOne('session', NULL, array('id'=>$semester->session));
						$s = array();
						$s[] = 'student_class'.$semester->id;
						$s[] = 'student_subject'.$semester->id;
						$s[] = 'student_behavior'.$semester->id;
						$s[] = 'student_health'.$semester->id;
						$s[] = 'student_fee'.$semester->id;
						$s[] = 'student_money'.$semester->id;
						$s[] = 'student_result'.$semester->id;
						//foreach semester get cas
						$deta = array();
						$dbh = $this->construct();
						foreach($s as $sr){
							 $sr1 = $this->ifTableExist($e, $sr, $dbh);
							 $deta[] = $sr1;
						}
						
						
						$name_file = array($semester->name, $get_session->name, $get_session->id);
						$details2[$semester->id] = array($name_file, $cas, $all_scores,NULL, $deta);
						$deta = array();
						}
					
							
				
			return $details2;
		}	
	public function ifTableExist($a, $b, $c){
		try {
		 $result = $c->query("SELECT $a FROM $b LIMIT 1");
		} 
		catch (Exception $e) {
			return FALSE;
		}
    	return $b;
	}
	public function select($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
		}
		
	}
	
	public function selectIN($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
		}
		
	}


public function studentPosition($semester, $classgroup  = NULL, $class  = NULL )
	{
		$table_result= 'student_result'.$semester;
		$table_class= 'student_class'.$semester;
		if(isset($classgroup)){
			$add = 'WHERE classID = '.$classgroup.' ';
		}
		if(isset($class)){
			$add = 'WHERE student_class = '.$class.' ';
		}
		
		
		try
		{
$sql = 'SELECT
			q.studentID AS sub, 
			AVG(q.score) AS sc
				FROM 
				(SELECT 
					
					studentID,
					subjectID,
					classID,					
					sum(score) as score
				FROM 
					`'. $table_result .'`
				  	 '.$add.'
					 GROUP BY studentID, subjectID, classID
				) as q 
				
				GROUP BY q.studentID
				';
				
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
		}
		
	}
	
	
	public function selectMax($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
		$sql = 'SELECT MAX('.$columns.') as '.$columns.' FROM `'. $table .'` '.$add;
				
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
		}
		
	}
	
	public function selectjoin($firsttable = NULL, $secondtable = NULL, $ontable, $jointype, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL, $otherwhere  = NULL)
	{
		$add = parent::whereClause($where);
		//if(isset($otherwhere)){$add .= $add." AND ".$otherwhere." ";}
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
	 $sql = 'SELECT '. $col .' FROM `'. $firsttable .'` '.$jointype.' `'.$secondtable.'` ON '.$ontable.' '.$add;
				
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
		}
		
	}
	
	
	
	public function selectOne($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
		}
		
	}
	
	public function selectRaw($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		$add = $where;
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
		}
		
	}
	public function selectRawAll($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		$add = $where;
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
		}
		
	}
	
	public function selectStudent($session, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		$C = 'student_class'.$session;
		$D = 'student_subject'.$session;
		$add = $where;
		$add .= parent::orderByClause($orderby);
		try
		{
	  $sql = 'SELECT 
	   			*, 
				(SELECT (GROUP_CONCAT(CONCAT(subjectID))) AS sub FROM `'.$D.'` WHERE studentID = students.id GROUP BY studentID) AS subs  
	   		  FROM 
			  	`students` 
			  LEFT JOIN 
			  	(SELECT '.$C.'.studentID AS a, datas.id AS classID, datas.name AS className FROM '.$C.' LEFT JOIN datas ON '.$C.'.`classID`= `datas`.`id`) AS J
			  ON 
			  	`students`.`id` = J.a
			   '.$add
			  ;
				
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
		}
		
	}
	
	public function studentCA($studentDB, $caDB, $ca, $class=NULL, $classgroup=NULL, $subject=NULL, $student= NULL)
	{
	if(isset($student)){
		$st[] = $this->array_or_array('studentID', $student);
		}
	if(isset($subject)){
		$st[] = $this->array_or_array('subjectID',$subject);
	}
	if(isset($class)){
		$st[] = $this->array_or_array('classID',$class);
	}
	if(isset($classgroup)){
		$st[] = $this->array_or_array('classname',$classgroup);
	}
	if(isset($ca)){
		$st[] = $this->array_or_array('caID',$ca);
	}	
	$stnum = count($st);
	
	if($stnum > 0){
	$WHERE =  'WHERE ';
		foreach($st as $s){
			$WHERE .= $s.' AND';		
		}
		$WHERE =  substr($WHERE,0,-3);
	}
	
	
		try
		{
    	$sql = '
			SELECT  
			 CONCAT(surname," ",firstname," ",middlename) as names,
			 '.$studentDB.'.id as studentID,
			 '.$caDB.'.id as cid,
			 username as username,
			 score as score,
			 caID as caID,
			 subjectID as subjectID,
			 classID as classID,
			 (SELECT pid FROM datas WHERE id = classID) as classname
			FROM '.$studentDB.' 
			LEFT JOIN '.$caDB.' 
			ON 
				'.$studentDB.'.id = '.$caDB.'.studentID				
				'.$WHERE; 
				
				
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
		}
		
	}
	
	public function selectRawAlls($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
		$add = $where;
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
		}
		
	}
	
	public function sessionGroup($table, $columns, $groupby= NULL, $where  = NULL, $otherWhere= NULL)
	{
		$add = $this->whereClause($where);
		 
			if(isset($table)){
				if(!isset($group)){
					$group = '';
					}
				else{
					$group = 'GROUP BY '.$group;
					}
			}
			
			if(isset($otherWhere)){
				if(!isset($group)){
					$group = '';
					}
				else{
					if(strlen($add) == 0){
						$add = 'WHERE '.$otherWhere;
						}
					else{
						$add = $add.' AND '.$otherWhere;
					}
					}
				}	
					
	    $sql = 'SELECT (GROUP_CONCAT(`'.$columns.'`)) AS num FROM `'.$table.'` '. $add. $group;
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			return $row;			
			
		}
		
	public function staffSubject($semester, $staffID, $where  = NULL, $otherWhere= NULL)
	{
		$ssub = 'student_subject'.$semester;
		$scl = 'student_class'.$semester;
		$add = $this->whereClause($where);
					
	    echo $sql = 'SELECT 
				'.$ssub.'.id as subjectID,
				'.$ssub.'.subjectID as subject,
				'.$ssub.'.staffID as staff,
				'.$scl.'.classID as classz
					
				FROM 
					'.$ssub.' 
				LEFT JOIN 
					'.$scl.' 
				ON 
					'.$ssub.'.studentID = '.$scl.'.studentID
				WHERE
					'.$ssub.'.staffID = '.$staffID.'';
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ)){
				array_push($rows, $row);
			}
			return $rows;			
			
		}
		
	public function staffSubjectGroup($semester, $staffID, $where  = NULL, $otherWhere= NULL)
	{
		$ssub = 'student_subject'.$semester;
		$scl = 'student_class'.$semester;
		$add = $this->whereClause($where);
					
	    $sql = 'SELECT DISTINCT(classz) as classID, subject, COUNT(student) as student  FROM
				(SELECT 
				'.$ssub.'.id as subjectID,
				'.$ssub.'.subjectID as subject,
				'.$ssub.'.studentID as student,
				'.$ssub.'.staffID as staff,
				'.$scl.'.classID as classz
					
				FROM 
					'.$ssub.' 
				LEFT JOIN 
					'.$scl.' 
				ON 
					'.$ssub.'.studentID = '.$scl.'.studentID
				WHERE
					'.$ssub.'.staffID = '.$staffID.') as Q GROUP BY Q.classz, Q.subject';
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ)){
				array_push($rows, $row);
			}
			return $rows;			
			
		}
		
	public function staffSubjectStudent($semester, $staffID = NULL, $subjectID = NULL, $classID = NULL)
	{
		$ssub = 'student_subject'.$semester;
		$scl = 'student_class'.$semester;
					
		$other = '';
		$other1 = '';
		if(isset($staffID) || isset($subjectID)){
		$other .= ' WHERE ';
		if(isset($staffID)){$other .= '`'.$ssub.'`.`staffID` = '.$staffID; }
		if(isset($staffID) && isset($subjectID)){$other .= ' AND '; }
		if(isset($subjectID)){$other .= '`'.$ssub.'`.`subjectID` = '.$subjectID; }	
		}
		if(isset($classID)){
		 $other1 =  'WHERE `Q`.`classz` = '.$classID;
		}
		
	    $sql = 'SELECT * FROM
				(SELECT 
				'.$ssub.'.id as subjectID,
				'.$ssub.'.subjectID as subject,
				'.$ssub.'.studentID as student,
				'.$ssub.'.staffID as staff,
				'.$scl.'.classID as classz,
				(SELECT CONCAT(surname," ",firstname," ",middlename) as names FROM students WHERE id = '.$ssub.'.studentID) as name,
				(SELECT username as username FROM students WHERE id = '.$ssub.'.studentID) as username
				FROM 
					'.$ssub.' 
				JOIN 
					'.$scl.' 
				ON 
					'.$ssub.'.studentID = '.$scl.'.studentID
				
					 '.$other.') 
					 as Q 
				 
				   '.$other1.' ORDER BY Q.name';
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ)){
				array_push($rows, $row);
			}
			return $rows;			
			
		}
	
	public function select_json($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
				
				//$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			//{
				//array_push($rows, $row);
			//}
			return $rows;
		}
		catch (PDOException $e)
		{
		$msg = $db.":";
		$msg .=  ("getMessage(): " . $e->getMessage() . "\n");
		return $msg;
		}
		
	}
	
	public function selectn($table = NULL, $columns  = NULL, $where  = NULL,  $orderby  = NULL, $groupby  = NULL)
	{
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
				
			while ($row = $sth->fetch(PDO::FETCH_NUM)){
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
		
	public function insert($table, $column)
	{
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
		
	public function update($table, $column, $where)
	{
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
		
	public function delete($table, $where)
	{
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
		
	public function convert_array($array)
	{
		$text = '[';
		foreach($array as $k => $v){
			$text .= '{';
			$text .= 'value:';
			$text .= '"'.$v[0].'"';
			$text .= ',';
			$text .= 'label:';
			$text .= '"'.$v[1].'"';
			$text .= '},';
			
			}
		$text = substr($text, 0, -1);
		$text .= ']';
		
		return $text;
	}
	
	public function convert_table($array, $table)
	{
		 $text ='';
		// echo '<pre>';		
		foreach($array as $k => $v){
		
			$text .= '<tr>';
			foreach($v as $k1 => $v1){
				if($k1 == 'id'){
				$ar = $v1;
				}
		unset($array['id']);
				$text .= '<td>';
				$text .= $v1;
				$text .= '</td>';
			}
			$text .= '<td align="center"><a href = "form.php?Action=Edit&na='. $table .'&id='.$ar.'">';
			$text .= 'Edit';
			$text .= '</a></td>';
			$text .= '<td align="center"><a href = "form.php?Action=Delete&na='. $table .'&id='. $ar .'">';
			$text .= 'Delete';
			$text .= '</a></td>';
			$text .= '</tr>';
			}
		return $text;
	}
	
	public function ranking($table, $class, $subject, $caid, $score)
	{
		if(isset($caid)){
					
					$groups = $this->stringCA($caid);
					$add = ' AND '.$groups;
				
				}	

		
	$sql = 'SELECT id, score, rank
			  FROM
			(
			  SELECT id, score, @n := IF(@g = score, @n, @n + 1) rank, @g := score
				FROM 
				(SELECT
					studentID as id, 
					SUM(score) as score
				FROM
					'.$table.'
				WHERE
					classID = "'.$class.'"
					AND
					subjectID = "'.$subject.'"
					'.$add.'
				GROUP BY studentID
				) AS R
				, (SELECT @n := 0) i
			   ORDER BY score DESC
			) q
			 WHERE score = "'.$score.'"';
	}
	public function	addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
  	public function	checkInvasion($num, $type) {
    return $num;
  	}
	public function	get_table($name, $session, $active) {
		$table = NULL;
		if($name == 'result'){
			$table = $name.$session;
			}
		elseif($active != 1 && $name != 'result'){
			$table = $name.$session;
			}
			else{
			$table = $name;	
				}
				
		return $table;
		
    return $num;
  	}
	public function sessionStudentIDs($table = NULL, $group = NULL, $where = NULL){
		$add = parent::whereClause($where);
			if(isset($table)){
				if(!isset($group)){
					$group = '';
					}
				else{
					$group = 'GROUP BY '.$group;
					}
					
		$sql = 'SELECT (GROUP_CONCAT(`'.$table.'`.`studentID`)) AS studentNum
					FROM `'.$table.'` 
					LEFT JOIN studentbio_db 
					ON `'.$table.'`.studentID = studentbio_db.id '.$add.' '.$group;
				$rows = array();
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
				while ($row = $sth->fetch(PDO::FETCH_NUM)){
				array_push($rows, $row);
				}
			return $rows;			
			}
		}
	public function classMaker($val, $class_table = NULL){
		if(is_array($val)){
		$text = ' AND (';
		foreach($val as $k => $v){
			$text .= $class_table.'.classID = '.$k.' OR ';
			}
		$tex .= substr($text, 0, -3);
		$te .= $tex.')';
		return $te;
		}
	}
	public function subjectAverage($table, $class, $subject, $caid){
		if(isset($caid)){
					
					$groups = $this->stringCA($caid);
					$add = ' AND '.$groups;
				
				}	
		
		$sql = 'SELECT 
					AVG(Q.sc) as sc
				FROM
				(SELECT
					studentID as std, 
					SUM(score) as sc
				FROM
					'.$table.'
				WHERE
					classID = "'.$class.'"
					AND
					subjectID = "'.$subject.'"
					'.$add.'
				GROUP BY studentID) AS Q
				';
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			return $row;
		
	}
	public function classAverage($table, $class, $subject, $caid){
		
		echo $sql = 'SELECT 
					AVG(Q.sc)
				FROM
				(SELECT
					studentID as std, 
					SUM(score) as sc
				FROM
					'.$table.'
				WHERE
					classID = '.$class.'
				AND
					'.$caid.'
				GROUP BY studentID
				) AS Q
				';
				$dbh = $this->construct();	
				$sth = $dbh->query($sql);
			while ($row = $sth->fetch(PDO::FETCH_OBJ))
			return $row;
		
	}
	public function loadFile($type, $id, $place, $folder, $max ,$replace= NULL, $extend = NULL ){
	$max_size = $max * 1024;
	$picture = array('png','gif','jpg','JPG', 'PNG', 'GIF');
	$document = array('pdf','doc','docx', 'png','gif','jpg');
	($type == 0)?$types = $picture: $types = $document;
	$f_name = $_FILES[$place]["name"];
	$f_size = $_FILES[$place]["size"];
	$f_exts = explode(".", $f_name);	
	$f_ext = end($f_exts);
	//Check extention
	if(file_exists($_FILES[$place]['tmp_name']) && is_file($_FILES[$place]['tmp_name'])){ 
		if(in_array($f_ext, $types)){
			//check size
			if($f_size < $max_size){
				//check if file exist
				$new_name = $id.$extend.".".$f_ext;
				$new_path = $folder.'/'.$new_name;
				if(file_exists($new_path))
				{
					if($replace === 1){
						$new_paths = $new_path;
						unset($new_path);
						$isMove = move_uploaded_file ($_FILES[$place]['tmp_name'], $new_paths);
						}
					else{
						$isMove = true;
						}
					
				}
				else
				{
					$isMove = move_uploaded_file ($_FILES[$place]['tmp_name'], $new_path);
				}
				
			}
			else{
				$error ="Maximum Upload file size exceeded !!!";
				}
			
		}else{
			$error = 'Wrong Format!!!';
		}
		if(isset($isMove)){ return array(0, $folder.'/'.$new_name);}else{return array(1, $error);}
	}
	else{return array(1, 'No. File Uploaded');}
		}
	public function createdbtable($table, $fields)
		{		
		
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
				}
		}
	public function createTableSemester($id)
		{
			$sc_table = 'student_class'.$id;
			$ss_table = 'student_subject'.$id;
			$sa_table = 'student_apprisal'.$id;
			$sb_table = 'student_behavior'.$id;
			$sr_table = 'student_result'.$id;
			$sf_table = 'student_fee'.$id;
			$sm_table = 'student_money'.$id;
			$sh_table = 'student_health'.$id;
			$sca_table = 'student_ca'.$id;
			//students class
				$sc = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
			//students subject
				$ss = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'subjectID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
			//students apprisal
				$sa = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'commentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'comments', 'refname'=>'id')
				);
				//students behaviour
				$sb = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'behaviorID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'behavior', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'action', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'effectivedate', 'PK'=>false, 'type'=>'DATE', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				);
				//student result
				$sr = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'caID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'ca', 'refname'=>'id'),
				array('name'=>'subjectID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'score', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>10, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
				//student fees
				$sf = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'feeID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'methodID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'accountID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'amount', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'teller', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'description', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);	
				//student money
				$sm = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'amount', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'teller', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'type', 'PK'=>false, 'type'=>'INT', 'num'=>2, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'datecreated', 'PK'=>false, 'type'=>'TIMESTAMP', 'num'=>2, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);	
				//students health
				$sh = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'healthID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'health', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'action', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'effectivedate', 'PK'=>false, 'type'=>'DATE', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
				//students ca
				$sca = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'subjectID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'caID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'ca', 'refname'=>'id'),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'data1', 'PK'=>false, 'type'=>'longtext', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'data2', 'PK'=>false, 'type'=>'longtext', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
			   $this->createdbtable($sc_table, $sc);
			   $this->createdbtable($ss_table, $ss);
			   $this->createdbtable($sa_table, $sa);
			   $this->createdbtable($sb_table, $sb);
			   $this->createdbtable($sr_table, $sr);
			   $this->createdbtable($sf_table, $sf);
			   $this->createdbtable($sm_table, $sm);
			   $this->createdbtable($sh_table, $sh);
			   $this->createdbtable($sca_table, $sca);
			}
	public function createTableSemesters($id)
		{
			$sc_table = 'exam_level'.$id;
			$ss_table = 'exam_registry'.$id;
			$sr_table = 'exam_score'.$id;
			$sd_table = 'exam_data'.$id;
			//students levels
				$sc = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id')
				);
			//students course
				$ss = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'staffID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'staffs', 'refname'=>'id'),
				array('name'=>'subjectID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id')
				);
			
				//student score
				$sr = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'studentID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'students', 'refname'=>'id'),
				array('name'=>'examID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'question', 'PK'=>false, 'type'=>'LONGTEXT', 'num'=>NULL, 'FK'=>FALSE, 'ref'=>'', 'refname'=>''),
				array('name'=>'answers', 'PK'=>false, 'type'=>'LONGTEXT', 'num'=>NULL, 'FK'=>FALSE, 'ref'=>'', 'refname'=>''),
				array('name'=>'answer', 'PK'=>false, 'type'=>'LONGTEXT', 'num'=>NULL, 'FK'=>FALSE, 'ref'=>'', 'refname'=>''),
				array('name'=>'timex', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>14, 'FK'=>FALSE, 'ref'=>'', 'refname'=>''),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'score', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>10, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'progress', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>10, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'extra', 'PK'=>false, 'type'=>'INT', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'firstlog', 'PK'=>false, 'type'=>'DATETIME', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'lastlog', 'PK'=>false, 'type'=>'DATETIME', 'num'=>NULL, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
				
				//student score
				$sd = array(
				array('name'=>'id', 'PK'=>true, 'type'=>'INT', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'courseID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'classID', 'PK'=>false, 'type'=>'INT', 'num'=>6, 'FK'=>true, 'ref'=>'datas', 'refname'=>'id'),
				array('name'=>'noq', 'PK'=>false, 'type'=>'int', 'num'=>10, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'timex', 'PK'=>false, 'type'=>'int', 'num'=>10, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'mode', 'PK'=>false, 'type'=>'int', 'num'=>2, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'maxScore', 'PK'=>false, 'type'=>'int', 'num'=>6, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'display', 'PK'=>false, 'type'=>'int', 'num'=>2, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'type', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>100, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'score', 'PK'=>false, 'type'=>'int', 'num'=>2, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'password', 'PK'=>false, 'type'=>'VARCHAR', 'num'=>15, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'active', 'PK'=>false, 'type'=>'BOOLEAN', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>''),
				array('name'=>'question', 'PK'=>false, 'type'=>'LONGTEXT', 'num'=>false, 'FK'=>false, 'ref'=>'', 'refname'=>'')
				);
				
				
			   $this->createdbtable($sc_table, $sc);
			   $this->createdbtable($ss_table, $ss);
			   $this->createdbtable($sr_table, $sr);
			   $this->createdbtable($sd_table, $sd);
			  
			}

	public function getData($username, $password){
	$d = $this->selectOne('staffs', NULL, array('empno'=>$username));
	if (count($d) == 1){
		//print_r($d);
	if ($password==$d->password){
				session_start();
				$_SESSION['a'] =array();
				$_SESSION['a']['empno'] = $d->username;
				$_SESSION['a']['password'] = $d->password;
				$_SESSION['a']['id'] = $d->id;
$_SESSION['a']["fullname"] = ucfirst(strtolower($d->surname)).", ".ucfirst(strtolower($d->middlename))." ".ucfirst(strtolower($d->firstname));  
				$_SESSION['a']['dob'] = $d->dob;
				$_SESSION['a']['phone'] = $d->phone_no;
				$_SESSION['a']['email'] = $d->email;
				$_SESSION['a']['house'] = $d->house_add;
				$_SESSION['a']['country'] = ucfirst($d->nationality);
				$_SESSION['a']['rank1'] = $d->rank1;
				$_SESSION['a']['rank2'] = $d->rank2;
				$_SESSION['a']['sofo'] = ucfirst($d->sofo);
				//passport if no pass port set to default
				if(file_exists($d->passport)){
				$_SESSION['a']['passport'] = $d->passport;
				$_SESSION['a']['passport1'] = $d->passport;	
				}
				else{
				$_SESSION['a']['passport'] = 'resources/images/personal.png';
					}
				//qualification	:if no qualification set to null				
				if(isset($_SESSION['a'])){
				header ("Location: ../staffs/staff.php");
					}
				else{
		header ("Location: ../index.php");
		
					}
	}
	else{
		header ("Location: ../index.php");
		}
	
	}else{ $message = "Invalid Data"; header ("Location: ../index.php?msg=".$message);} 
		
		}
	public function getS($username, $password){
	$d = $this->selectOne('students', NULL, array('username'=>$username));
	if (count($d) == 1){
		//print_r($d);
		$ph[] = $d->g1_phone1;
		$ph[] = $d->g1_phone2;
		$ph[] = $d->g2_phone1;
		$ph[] = $d->g2_phone2;
	if (strlen($password) >10 && in_array($password,$ph)){
				session_start();
				$_SESSION['a'] =array();
				
				$_SESSION['a']['id'] = $d->id;
$_SESSION['a']["fullname"] = ucfirst(strtolower($d->surname)).", ".ucfirst(strtolower($d->middlename))." ".ucfirst(strtolower($d->firstname));  
				$numb = $d->id * 12121212121212;
				//qualification	:if no qualification set to null				
				if(isset($_SESSION['a'])){
				header ("Location: ../students/report_student.php?id =".$numb);
					}
				else{
		header ("Location: ../index.php");
		
					}
	}
	else{
		header ("Location: ../index.php");
		}
	
	}else{ $message = "Invalid Data"; header ("Location: ../index.php?msg=".$message);} 
		
		}
	public function getStudentData($username, $password){
	$d = $this->selectOne('students', NULL, array('username'=>$username));
	if (count($d) == 1){
		
	//if ($d->password != $password)
	if (isset($username)){
		//confirm students login
	$ip = $_SERVER['REMOTE_ADDR'];
	$host = $_SERVER['REMOTE_HOST']; 
	$lg = $this->selectOne('exam_login', NULL, array('studentID'=>$d->id)); 
	if(isset($lg))
	{
		//using the same system
		if($lg->hostIP ==  $ip && $lg->host == $host){

		return $msg = '<i class="label label-danger">You already have an active session or You did not properly Logout of the previous session. Kindly see the administrator</i>';
			header ("Location: ../../exam_login/index.php?msg=".$msg);	
		}
		else{
	return $msg = '<i class="label label-danger">You already have an active session or You did not properly Logout of the previous session. Kindly see the administrator</i>';
			header ("Location: ../../exam_login/index.php?msg=".$msg);
		}
		
	}
	else
	{
		$log_details = array('studentID' => $d->id, 'hostIP' => $ip, 'host' =>$host);
		$intg = $this->insert('exam_login', $log_details);
		
		if(ctype_digit($intg) && $intg > 0){

			$regis = true;
		}
		else{
	return $msg = '<i class="label label-danger">Login Registration Error. Kindly see the administrator</i>';
			header ("Location: ../../exam_login/index.php?msg=".$msg);
		}
		
	}
	
	
	if(isset($regis)){
				session_start();
				$_SESSION['a'] = array();
				$_SESSION['a']['id'] = $d->id;
$_SESSION['a']["fullname"] = ucfirst(strtolower($d->surname)).", ".ucfirst(strtolower($d->middlename))." ".ucfirst(strtolower($d->firstname));  
				$dn = $this->selectOne('semester', NULL, array('active'=>1));
				
				//$pn = $this->selectOne('datas', NULL, array('id'=>$d->programme));
				//$pn1 = $this->selectOne('datas', NULL, array('id'=>$pn->name));
				$_SESSION['a']['class'] = 'STUDENT';	
				$_SESSION['a']['matric'] = $username;
				$_SESSION['a']['passport'] = $d->passport;
				$_SESSION['a']['ip'] = $ip;
				$_SESSION['a']['host'] = $host;			
				//qualification	:if no qualification set to null				
				
	}
	}
	else{
		return $message = "Invalid Data"; 
		header ("Location: ../../exam_login/index.php?msg=".$message);
		}
	
	}
	else{ 
	return $message = "Invalid Data"; header("Location: ../../exam_login/index.php?msg=".$message);} 
		
		}
	public function getStaffData($username, $password){
	$d = $this->selectOne('staffs', NULL, array('empno'=>$username));
	if (count($d) == 1){
		//print_r($d);
	if ($d->password = $password){
				session_start();
				$_SESSION['x'] = array();
				$_SESSION['x']['id'] = $d->id;
$_SESSION['x']["fullname"] = ucfirst(strtolower($d->surname)).", ".ucfirst(strtolower($d->middlename))." ".ucfirst(strtolower($d->firstname));  
				$dn = $this->selectOne('datas', NULL, array('id'=>$d->department));
				$_SESSION['x']['dept'] = $dn->name;
				$_SESSION['x']['cat'] = $d->category;	
				$_SESSION['x']['empno'] = $username;
				$_SESSION['x']['passport'] = $d->passport;
				
				header ('Location: ../exam_staff/WelcomePage.php');			
				//qualification	:if no qualification set to null				
				
				
	}
	else{
		header ("Location: ../exam_login/staff.php");
		}
	
	}else{ $message = "Invalid Data"; header ("Location: ../exam_login/staff.php?msg=".$message);} 
		
		}
}




?>