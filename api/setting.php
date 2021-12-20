<?php
error_reporting(0);
// required headers
//header("Access-Control-Allow-Origin: https://www.skoolq.com");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PATCH, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required headers
ini_set('post_max_size', '25M');
ini_set('upload_max_filesize', '40M');
ini_set('max_execution_time', 1200);


include '../connect/connect.php';
$op = new Db;


//GET REQUEST

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
	$queries = array();
	parse_str($_SERVER['QUERY_STRING'], $queries);
	$query = (array) json_decode($queries['data']);
	$cat = $queries['cat'];
	$table = $queries['table'] ;
	$token = isset($queries['token']) ? $queries['token'] : '' ;
	$narration = $queries['narration'] ;
	$data = array();
	$tabl ='';
	
	if($cat === 'all'){$data = $op->select($table);}
	if($cat === 'group'){$data = $op->select($table, NULL, $query);}
	if($cat === 'select'){$data = $op->select($table, NULL, $query);}
	if($cat === 'selected'){$data = $op->selected($table, NULL, $query);}
	if($cat === 'selectedattendance'){$data = $op->selectedAttendance($table, NULL, $query);}
	if($cat === 'selectedin'){$data = $op->selectedAttendance($table, NULL, $query);}
	if($cat === 'selectes'){$data = $op->selectes($table, NULL, $query);}
	if($cat === 'selectess'){$data = $op->selectess($table, NULL, $query);}
	if($cat === 'staffclass'){

		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$grp = isset($query['classgroup']) ? $query['classgroup'] : 0 ;
			if(isset($query['classgroup'])){unset($query['classgroup']);}
			if($grp == 1){
				$cl = $query['contact'];
				$all_unit = $op->select('claszunits', NULL, array('claszid'=>$cl, 'is_active'=>0, 'is_delete'=>0));
				$aid = array();
				$all_unit_array = array();
				foreach ($all_unit as $key => $value) {
					# code...
					$all_unit_array[] = $value->id;
				}
				$query['contact'] = implode(',', $all_unit_array);
				$data = $op->selectAccess('accessstudentsubjectmultiple', NULL, $query, $tabl);
			}else{
				$data = $op->selectAccess($table, NULL, $query, $tabl);
			}
		}
	}
	if($cat === 'classfees'){

		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess($table, NULL, $query, $tabl);
		}	}
	if($cat === 'staffsubject'){$data = $op->selectAccess($table, NULL, $query);}
	if($cat === 'studentclass'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess($table, NULL, $query, $tabl);
		}
	}
	if($cat === 'assessment'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess('assessment', NULL, $query, $tabl);
		}
	}
	if($cat === 'assessmentdetails'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess('assessmentdetails', NULL, $query, $tabl);
		}
	}
	if($cat === 'lessonplanreport'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess('lessonplanreport', NULL, $query, $tabl);
		}
	}
	if($cat === 'theme'){
		if(isset($query['itemid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selected('themereport', NULL, $query, $tabl);
		}
	}
	if($cat === 'studentca')
	{
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectScore($table, NULL, $query, $tabl);
		}
	}
	if($cat === 'studentscore'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectScore($table, NULL, $query, $tabl);
		}}
	if($cat === 'selectreport'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;


			$data1 = $op->selectReport($tabl, 1, $query); //get scores for all subjects
			$data2 = $op->selectReport($tabl, 2, $query); //get class averages for all subjects
			$data3 = $op->selectReport($tabl, 3, $query); //get classparent averages for all subjects
			$data4 = $op->selectReport($tabl, 4, $query); //get class averages for all subjects
			$data5 = $op->selectReport($tabl, 5, $query); //get rank for classparent subjects
			$data6 = $op->selectReport($tabl, 6, $query); //get rank for class subjects
			$data7 = $op->selectReport($tabl, 7, $query); //get student total, average and rank clasparent 
			$data8 = $op->selectReport($tabl, 8, $query); //get student total, average and rank class
			$data9 = $op->selectReport($tabl, 9, $query); //get class spreadsheet
			$data10 = $op->selectReport($tabl, 10, $query); //get class spreadsheet
			$data11 = $op->selectReport($tabl, 11, $query); //get class spreadsheet
			$data12 = $op->selectReport($tabl, 12, $query); //get class spreadsheet
			$data13 = $op->selectReport($tabl, 13, $query); //get class spreadsheet
			$data14 = $op->selectReport($tabl, 14, $query); //get class spreadsheet

			$data =  [$data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10, $data11, $data12, $data13, $data14];


		}}
	if($cat === 'getreport'){
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;


			$data1 = $op->selectReport($tabl, 15, $query); //get scores for all subjects
			$data2 = $op->selectReport($tabl, 16, $query); //get class averages for all subjects
			$data3 = $op->selectReport($tabl, 17, $query); //get classparent averages for all subjects
			$data4 = $op->selectReport($tabl, 18, $query); //get class averages for all subjects
			$data5 = $op->selectReport($tabl, 19, $query); //get class averages for all subjects
			

			$data =  [$data1, $data2, $data3, $data4, $data5 ];


		}}
	if($cat === 'studentfees')
	{
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'fee_'.$sessionID;
			$tabl1 = 'access_'.$sessionID;
			$data = $op->selectFees($table, NULL, $query, $tabl, $tabl1);
		}
	}
	if($cat === 'studentfeesmo')
	{
		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'fee_'.$sessionID;
			$tabl1 = 'access_'.$sessionID;
			$data = $op->selectFees($table, NULL, $query, $tabl, $tabl1);
		}
	}
	if($cat === 'studentfeess'){
	if(isset($query['sessionid']))
	{
		$sessionID = $query['sessionid'];

		unset($query['sessionid']);
		$tabl = 'fee_'.$sessionID;
		$data = $op->selectFees($table, NULL, $query, $tabl, NULL, NULL, NULL);
	}}
	if($cat === 'studentsubject'){$data = $op->selectAccess($table, NULL, $query);}
	if($cat === 'dropdowns')
	{

		$typeid = isset($query['typeid']) ? $query['typeid'] : 0;

		$data1 = $op->selectDropdown($query['schoolid'], 1);
		$data2 = $op->selectDropdown($query['schoolid'], 2, $typeid);
		$data3 = $op->selectDropdown($query['schoolid'], 3, $typeid);
		$data4 = $op->selectDropdown($query['schoolid'], 4);
		$data5 = $op->selectDropdown($query['schoolid'], 5);
		$data6 = $op->selectDropdown($query['schoolid'], 6);
		$data7 = $op->selectDropdown($query['schoolid'], 7);
		$data11 = $op->selectDropdown($query['schoolid'], 11, $typeid);

		$data = [$data1, $data2, $data3, $data4, $data5, $data6, $data7, $data11];
	}
	if($cat === 'dropdownca'){ $data = $op->selectDropdown($query['schoolid'], 8, $query['termid']);}
	if($cat === 'dropdowncas'){ $data = $op->selectDropdown($query['schoolid'], 9, $query['sessionid']);}
	if($cat === 'dropdownca1'){ $data = $op->selectDropdown($query['schoolid'], 10, $query['cas']);}
	if($cat === 'schoolaccess'){ $data = $op->selectGroups($query['schools'], 1);}
	if($cat === 'dataaccess')
	{
		$session = $query['sessionid'];
		$term = $query['termid'];
		$staff = $query['staffid'];
		$data1 = $op->selectAccessSimple($session, $term, $staff, 1);
		$data2 = $op->selectAccessSimple($session, $term, $staff, 2);
		$data = [$data1, $data2];
	}
	if($cat === 'datasummary')
	{
		if(isset($query['sessionid']) && isset($query['termid']) &&  isset($query['typeid']))
		{
			$session = $query['sessionid'];
			$term = $query['termid'];
			$type = $query['typeid'];
			$data = $op->selectSummary($session, $term, $type);
		}
	}
	if($cat === 'dataattendance')
	{
		if(isset($query['sessionid']) && isset($query['termid']))
		{
			$session = $query['sessionid'];
			$term = $query['termid'];

			unset($query['sessionid']);
			unset($query['termid']);

			$query['grp'] = 4;
			$data1 = $op->selectedAttendances($table, NULL, $query, 1, $session, $term);
			$query['grp'] = 2;
			$data2 = $op->selectedAttendances($table, NULL, $query, 2, $session, $term);

			$data = [$data1, $data2];
		}
	}
	if($cat === 'datainventory'){$data = $op->selectedInventory($table, NULL, $query, 1);}
	if($cat === 'forminventory'){
		$data1 = $op->selectedInventory($table, NULL, $query, 2);
		$data2 = $op->selectedInventory($table, NULL, $query, 3);
		$data =[$data1, $data2];
	}
	if($cat === 'dataexpense'){$data = $op->selectedExpense($table, NULL, $query, 1);}
	if($cat === 'datamaintenance'){$data = $op->selectedMaintenance($table, NULL, $query, 1);}

	$data = (array) $data;
	if(is_array($data))
	{
		// set response code - 200 OK
    	http_response_code(200);
		echo json_encode($data);
	}
	else
	{
	    // set response code - 404 Not found
	    http_response_code(404);
	    echo json_encode(array("message" => $data));
	}	
}

///CONSTANTS
$response = array();
$upload_dir = 'uploads/';
$upload_dir1 = 'question/';
$server_url = 'http://127.0.0.1:8000/ems/api/index.php';
//POST AND FILES REQUEST
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$cat = isset($_POST['cat']) ? $_POST['cat'] : null;
	$table = isset($_POST['table']) ? $_POST['table'] : null;
	$staffID = isset($_POST['rowzid']) ? $_POST['rowzid'] : null;
	$locateID = isset($_POST['locateid']) ? $_POST['locateid'] : null;
	$dz  = '';
	$tabl ='';
	$tabl1 ='';

	unset($_POST['cat']);
	unset($_POST['table']);
	if(isset($_POST['rowzid'])){ unset($_POST['rowzid']);}
	if(isset($_POST['narration'])){unset($_POST['narration']);}
	//LOGIN STUDENT AND STAFF
	if($cat === 'login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$datax = $op->selectOne('staffs', NULL, array('employment_no' => $username));
		$datax = (array) $datax;
		//print_r($datax);
		if($datax && isset($datax['id']) && $datax['id'] > 0)
		{
			if(
				$datax['passwd'] === md5($password) && 
				$table === 'staffs' &&
				$datax['is_admin'] < '2' &&
				$datax['is_active'] === '0' &&
				$datax['is_delete'] === '0'
			) 
			{
				$data = $op->selected('staffs', $datax['id']);
				$response = array(
				"status" => "success",
            	"error" => false,
            	"data"=>$data,
            	"message" => "Login Successful",
            	"url"=>$server_url
				);
				http_response_code(200);
				echo json_encode($response);
			}
			else
			{
				$response = array(
				"status" => "error",
            	"error" => true,
            	"data"=>'',
            	"message" => "Login Failed: Wrong Data",
            	"url"=>$server_url
				);
				http_response_code(404);
				echo json_encode($response);
			}
		}else
			{
				$response = array(
				"status" => "error",
            	"error" => true,
            	"data"=>$data,
            	"message" => "Login Failed: Wrong usernae",
            	"url"=>$server_url
				);
				http_response_code(404);
				echo json_encode($response);
			}
	}
	if($cat === 'insert')
	{
		if($table == 'staffs')
		{
			if(isset($_POST['passwd']))
			{
				$ps = md5($_POST['passwd']);
				$_POST['passwd'] = $ps;
			}else
			{
				//$ps = md5($_POST['passwd']);
				//$_POST['passwd'] = $ps;
			}
			
		}
		if(isset($_POST['id']))
		{
			$ins = $_POST['id'];
			unset($_POST['id']);
			if(isset($_POST['files'])){unset($_POST['files']);}
			$td = $op->update($table, $_POST, array('id' =>$ins));
		}else
		{
			if(isset($_POST['files'])){unset($_POST['files']);}
			 $ins = $op->insert($table, $_POST);
			if($ins > 0 & $table =='sessions')
			{
				$nm = '_'.$ins;
				$op->creatTableAccess($nm);
				$op->creatTableFee($nm);
			}
		}
	}
	if($cat === 'updatestudent')
	{
		if(isset($_POST['id']) && isset($_POST['cid']) && isset($_POST['sessionid']))
		{
			$ins = $_POST['cid'];
			$cid = $_POST['id'];
			$sessionID = $_POST['sessionid'];

			unset($_POST['id']);
			unset($_POST['cid']);
			unset($_POST['sessionid']);


			if(isset($_POST['links']))
			{
				$lks = $_POST['links'];
				unset($_POST['links']);
			}else{
				$lks='';
			}	

			if(isset($_POST['files'])){unset($_POST['files']);}
			$td = $op->update('students', $_POST, array('id' =>$cid));
			$tabl = 'access_'.$sessionID;
		}
		$table = 'accessstudentclass';
	}
	if($cat === 'inserts')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tabl = 'access_'.$sessionID;
		if(isset($_POST['id']))
		{
			$ins = $_POST['id'];
			unset($_POST['id']);
			$td = $op->update($tabl, $_POST, array('id' =>$ins));
		}else
		{
			$ins = $op->insert($tabl, $_POST);
		}
	}
	if($cat === 'duplicateclassstaff')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		$osessionID = isset($_POST['osessionid']) ? $_POST['osessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tablenew = 'access_'.$sessionID;
		$tableold = 'access_'.$osessionID;
		
		$old_term = $_POST['otermid'];
		$new_term = $_POST['termid'];

		//GET ALL OLD CLASS
		$arr = array();
		$arr['grp'] = 1;
		$arr['termid'] = $old_term;

		$old_records = $op->select($tableold, NULL, $arr);
		$r = array();
		
		foreach ($old_records as $key => $value) {
			# code...
				$nrr = array();
				$nrr['grp'] = $value->grp;
				$nrr['termid'] = $new_term;
				$nrr['itemid'] = $value->itemid;
				$nrr['itemid1'] = $value->itemid1;
				$nrr['clientid'] = $value->clientid;
				$nrr['staffid'] = $value->staffid;
				$nrr['contact'] = $value->contact;
				$nrr['checker'] = $value->grp.'_'.$new_term.'_'.$value->clientid.'_'.$value->itemid;

				$crr = array();
				$crr['grp'] = $value->grp;
				$crr['termid'] = $new_term;
				$crr['itemid'] = $value->itemid;
				$crr['itemid1'] = $value->itemid1;
				$crr['clientid'] = $value->clientid;

				$chk = $op->selectOne($tablenew, NULL, $crr);
				if(isset($chk) &&  $chk->id > 0)
				{
					$up1 = $op->update($tablenew, $nrr, array('id'=>$chk->id));
					$up = $chk->id;
				}else
				{
					$up = $op->insert($tablenew, $nrr );
				}
				
				$r[] = $up;
		}
		//print_r($r);
		$r1 = sizeof($r);
		$response = array(
				"data" => array(),
	            "status" => "success",
	            "error" => false,
	            "message" => $r1
		         );
		http_response_code(200);
		echo json_encode($response);
	}
	if($cat === 'duplicateclassstudent')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		$osessionID = isset($_POST['osessionid']) ? $_POST['osessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tablenew = 'access_'.$sessionID;
		$tableold = 'access_'.$osessionID;
		
		$old_term = $_POST['otermid'];
		$new_term = $_POST['termid'];

		//GET ALL OLD CLASS
		$arr = array();
		$arr['grp'] = 4;
		$arr['termid'] = $old_term;

		$old_records = $op->select($tableold, NULL, $arr);
		$r = array();
		
		foreach ($old_records as $key => $value) {
			# code...
				$nrr = array();
				$nrr['grp'] = $value->grp;
				$nrr['termid'] = $new_term;
				$nrr['itemid'] = $value->itemid;
				$nrr['itemid1'] = $value->itemid1;
				$nrr['clientid'] = $value->clientid;
				$nrr['contact'] = $value->contact;
				$nrr['checker'] = $value->grp.'_'.$new_term.'_'.$value->clientid.'_'.$value->itemid;

				$crr = array();
				$crr['grp'] = $value->grp;
				$crr['termid'] = $new_term;
				$crr['itemid'] = $value->itemid;
				$crr['itemid1'] = $value->itemid1;
				$crr['clientid'] = $value->clientid;

				$chk = $op->selectOne($tablenew, NULL, $crr);
				if(isset($chk) &&  $chk->id > 0)
				{
					$up1 = $op->update($tablenew, $nrr, array('id'=>$chk->id));
					$up = $chk->id;
				}else
				{
					$up = $op->insert($tablenew, $nrr );
				}
				
				$r[] = $up;
		}
		//print_r($r);
		$r1 = sizeof($r);
		$response = array(
				"data" => array(),
	            "status" => "success",
	            "error" => false,
	            "message" => $r1
		         );
		http_response_code(200);
		echo json_encode($response);
	}
	if($cat === 'duplicateclassstaffsubject')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		$osessionID = isset($_POST['osessionid']) ? $_POST['osessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tablenew = 'access_'.$sessionID;
		$tableold = 'access_'.$osessionID;
		
		$old_term = $_POST['otermid'];
		$new_term = $_POST['termid'];

		//GET ALL OLD CLASS
		$arr = array();
		$arr['grp'] = 2;
		$arr['termid'] = $old_term;

		$old_records = $op->select($tableold, NULL, $arr);
		$r = array();
		
		foreach ($old_records as $key => $value) {
			# code...
				$nrr = array();
				$nrr['grp'] = $value->grp;
				$nrr['termid'] = $new_term;
				$nrr['itemid'] = $value->itemid;
				$nrr['itemid1'] = $value->itemid1;
				$nrr['clientid'] = $value->clientid;
				$nrr['staffid'] = $value->staffid;
				$nrr['contact'] = $value->contact;
				$nrr['checker'] = $value->grp.'_'.$new_term.'_'.$value->clientid.'_'.$value->itemid;

				$crr = array();
				$crr['grp'] = $value->grp;
				$crr['termid'] = $new_term;
				$crr['itemid'] = $value->itemid;
				$crr['itemid1'] = $value->itemid1;
				$crr['clientid'] = $value->clientid;

				$chk = $op->selectOne($tablenew, NULL, $crr);
				if(isset($chk) &&  $chk->id > 0)
				{
					$up1 = $op->update($tablenew, $nrr, array('id'=>$chk->id));
					$up = $chk->id;
				}else
				{
					$up = $op->insert($tablenew, $nrr );
				}
				
				$r[] = $up;
		}
		//print_r($r);
		$r1 = sizeof($r);
		$response = array(
				"data" => array(),
	            "status" => "success",
	            "error" => false,
	            "message" => $r1
		         );
		http_response_code(200);
		echo json_encode($response);
	}
	if($cat === 'duplicateclassstudentsubject')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		$osessionID = isset($_POST['osessionid']) ? $_POST['osessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tablenew = 'access_'.$sessionID;
		$tableold = 'access_'.$osessionID;
		
		$old_term = $_POST['otermid'];
		$new_term = $_POST['termid'];

		//GET ALL OLD CLASS
		$arr = array();
		$arr['grp'] = 2;
		$arr['termid'] = $old_term;

		$old_records = $op->select($tableold, NULL, $arr);
		$r = array();
		
		foreach ($old_records as $key => $value) {
			# code...
				$nrr = array();
				$nrr['grp'] = $value->grp;
				$nrr['termid'] = $new_term;
				$nrr['itemid'] = $value->itemid;
				$nrr['itemid1'] = $value->itemid1;
				$nrr['clientid'] = $value->clientid;
				$nrr['staffid'] = $value->staffid;
				$nrr['contact'] = $value->contact;
				$nrr['checker'] = $value->grp.'_'.$new_term.'_'.$value->clientid.'_'.$value->itemid;

				$crr = array();
				$crr['grp'] = $value->grp;
				$crr['termid'] = $new_term;
				$crr['itemid'] = $value->itemid;
				$crr['itemid1'] = $value->itemid1;
				$crr['clientid'] = $value->clientid;

				$chk = $op->selectOne($tablenew, NULL, $crr);
				if(isset($chk) &&  $chk->id > 0)
				{
					$up1 = $op->update($tablenew, $nrr, array('id'=>$chk->id));
					$up = $chk->id;
				}else
				{
					$up = $op->insert($tablenew, $nrr );
				}
				
				$r[] = $up;
		}
		//print_r($r);
		$r1 = sizeof($r);
		$response = array(
				"data" => array(),
	            "status" => "success",
	            "error" => false,
	            "message" => $r1
		         );
		http_response_code(200);
		echo json_encode($response);
	}
	if($cat === 'insertfee')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		if(isset($_POST['schoolid']))
		{
			unset($_POST['schoolid']);
		}
		$tabl = 'fee_'.$sessionID;
		$tabl1 = 'access_'.$sessionID;
		if(isset($_POST['id']))
		{
			$ins = $_POST['id'];
			unset($_POST['id']);
			$td = $op->update($tabl, $_POST, array('id' =>$ins));
		}else
		{
			$ins = $op->insert($tabl, $_POST);
		}
	}
	if($cat === 'insertsetfee')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		
		
		$tabl = 'fee_'.$sessionID;
		$tabl1 = 'access_'.$sessionID;
		$term = $_POST['termid'];
		$clasz = $_POST['claszid'];
		$student = $_POST['studentid'];
		$staff = $_POST['staffid'];
		$select_class = $op->select('classfees', NULL, array('termid'=>$term, 'claszid'=>$clasz));
		
		foreach ($select_class as $key => $value) {
			# confirm if the students has fee already set if so just update amount
			$fee_set = $op->selectOne($tabl, NULL, array('grp'=>1, 'studentid'=>$student, 'termid'=>$term,'feeid'=>$value->feeid));
			if(isset($fee_set) && $fee_set->id > 0 )
			{
				$op->update($tabl, array('amount'=>$value->amount), array('id'=>$fee_set->id));
			}else
			{
				 $op->insert($tabl, 
					array(
						'grp'=>1, 
						'termid'=>$term,
						'studentid'=>$student, 
						'staffid'=>$staff,
						'accountid'=>0,
						'feeid'=>$value->feeid, 
						'amount'=>$value->amount,
						'datepaid'=>date('now')
					)
				);
			}

			
			
			
		 }
		 	$data = $op->selectFees('studentfees', NULL, array('studentid'=>$student, 'termid'=>$term ), $tabl, $tabl1);
			
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully",
	            "data" => $data
	          );
			
				http_response_code(200);
				echo json_encode($response);
		}	
	}
	if($cat === 'insertsetfees')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		
		
		$tabl = 'fee_'.$sessionID;
		$tabl1 = 'access_'.$sessionID;
		$term = $_POST['termid'];
		$clasz = $_POST['claszid'];
		$student = $_POST['studentid'];
		$staff = $_POST['staffid'];
		$amount = $_POST['amount'];
		$fee = $_POST['feeid'];

			# confirm if the students has fee already set if so just update amount
			$fee_set = $op->selectOne($tabl, NULL, array('grp'=>1, 'studentid'=>$student, 'termid'=>$term, 'feeid'=>$fee));
			if(isset($fee_set) && $fee_set->id > 0 )
			{
				$op->update($tabl, array('amount'=>$amount), array('id'=>$fee_set->id));
			}else
			{
				 $op->insert($tabl, 
					array(
						'grp'=>1, 
						'termid'=>$term,
						'studentid'=>$student, 
						'staffid'=>$staff,
						'accountid'=>0,
						'feeid'=>$fee, 
						'amount'=>$amount,
						'datepaid'=>date('now')
					)
				);
			}

			
		 	$data = $op->selectFees('studentfees', NULL, array('studentid'=>$student, 'termid'=>$term ), $tabl, $tabl1);
			
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully",
	            "data" => $data
	          );
			
			http_response_code(200);
			echo json_encode($response);
		}	
	}
	if($cat === 'insertsubject')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
			//get data sent
			$tabl = 'access_'.$sessionID;
			$termid = $_POST['termid'];
			$claszparent = $_POST['claszid'];
			$claszgroup = $_POST['claszgroup'];
			$subject = $_POST['subjectid'];
			$staff = $_POST['staffid'];
			$grp = $_POST['grp'];


			if($claszgroup == 0){
				$clasz = $_POST['claszid'];
				$fin = array();
			    $fin['termid'] = $termid;
				$fin['grp'] = 4;
				$fin['itemid'] = $clasz;

				#get all students from the class
				$students_array = $op->select($tabl, NULL, $fin);
				//loop students
				foreach ($students_array as $key => $value) 
				{
					# confirm subject has not been recorded for students
					$chk = array();
					$chk['termid'] = $termid;
					$chk['grp'] = 3;
					$chk['itemid'] = $subject;
					$chk['clientid'] = $value->clientid;

					$checks = $op->selectOne($tabl, NULL, $chk);
					
					if(isset($checks) && is_array($checks) &&  $checks->id > 0)
					{
						#record availble edit
						$chk1 = array();
						$chk1['itemid1'] = $staff;
						$chk1['contact'] = $clasz;
						$up = $op->update($tabl, $chk1, array('id'=>$checks->id));

					}else
					{
						#record not available insert
						$chk1 = array();
						$chk1['termid'] = $termid;
						$chk1['grp'] = 3;
						$chk1['itemid'] = $subject;
						$chk1['itemid1'] = $staff;
						$chk1['clientid'] = $value->clientid;
						$chk1['contact'] = $clasz;
						$chk1['checker'] = '3'.'_'.$termid.'_'.$value->clientid.'_'.$subject;
						$in = $op->insert($tabl, $chk1);
					}
				}

				 $query = array();
	             $query['termid'] = $termid;
	             $query['itemid'] = $subject;
	             $query['contact'] = $clasz;
	             $query['itemid1'] = $staff;
	             $query['grp'] = '3';

			     $data = $op->selectAccess('accessstudentsubject', NULL, $query, $tabl);
				 $response = array(
		            "status" => "success",
		            "error" => false,
		            "message" => "File uploaded successfully",
		            "data" => $data
		         );
		
			   http_response_code(200);
			   echo json_encode($response);
			 }
		
			elseif($claszgroup == 1){
				
				//get all class unit
				$all_unit_array = array();
				$all_unit = $op->select('claszunits', NULL, array('claszid'=>$claszparent, 'is_active'=>0, 'is_delete'=>0));
				//loop over class to get students
				
				foreach ($all_unit as $key => $value) {
					# code...
					$clasz = $value->id;
					$all_unit_array[] = $clasz;
					$fin = array();
				    $fin['termid'] = $termid;
					$fin['grp'] = 4;
					$fin['itemid'] = $clasz;

					#get all students from the class
					$students_array = $op->select($tabl, NULL, $fin);
					//loop students
					foreach ($students_array as $key => $value) 
					{
						# confirm subject has not been recorded for students
						$chk = array();
						$chk['termid'] = $termid;
						$chk['grp'] = 3;
						$chk['itemid'] = $subject;
						$chk['clientid'] = $value->clientid;

						$checks = $op->selectOne($tabl, NULL, $chk);
						
						if(isset($checks) && is_array($checks) &&  $checks->id > 0)
						{
							#record availble replcace teacher
							$chk1 = array();
							$chk1['itemid1'] = $staff;
							$chk1['contact'] = $clasz;
							$up = $op->update($tabl, $chk1, array('id'=>$checks->id));

						}else
						{
							#record not available insert
							$chk1 = array();
							$chk1['termid'] = $termid;
							$chk1['grp'] = 3;
							$chk1['itemid'] = $subject;
							$chk1['itemid1'] = $staff;
							$chk1['clientid'] = $value->clientid;
							$chk1['contact'] = $clasz;
							$chk1['checker'] = '3'.'_'.$termid.'_'.$value->clientid.'_'.$subject;
							$in = $op->insert($tabl, $chk1);

						}
					}
				 }

				 $query = array();
	             $query['termid'] = $termid;
	             $query['itemid'] = $subject;
	             $query['contact'] = implode(",", $all_unit_array);
	             $query['itemid1'] = $staff;
	             $query['grp'] = '3';

			     $data = $op->selectAccess('accessstudentsubjectmultiple', NULL, $query, $tabl);
				 $response = array(
		            "status" => "success",
		            "error" => false,
		            "message" => "File uploaded successfully",
		            "data" => $data
		         );
		
			   http_response_code(200);
			   echo json_encode($response);
			 }else{
			 	$response = array(
		            "status" => "success",
		            "error" => false,
		            "message" => "File uploaded successfully",
		            "data" => 'None'
		         );
		
			   http_response_code(200);
			   echo json_encode($response);
			 }
		
		}		
	}
	if($cat === 'insertscore')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			if(isset($_POST['sessionid']))
			{
				unset($_POST['sessionid']);
				//get data sent
				$tabl = 'access_'.$sessionID;
				$termid = $_POST['termid'];
				$subject = $_POST['subjectid'];
				$student = $_POST['studentid'];
				$ca = $_POST['caid'];
				$staff = $_POST['staffid'];
				$grp = $_POST['grp'];
				$score = (string) $_POST['score'];

				$fin = array();
			    $fin['termid'] = $termid;
				$fin['grp'] = 8;
				$fin['itemid'] = $subject;
				$fin['itemid1'] = $ca;
				$fin['clientid'] = $student;

				#get all students from the class
				$checks = $op->selectOne($tabl, NULL, $fin);

				//print_r($checks);
				if(isset($checks)  &&  $checks->id > 0)
				{
					#record availble edit
					$chk1 = array();
					$chk1['staffid'] = $staff;
					$chk1['contact'] = $score;
					$up = $op->update($tabl, $chk1, array('id'=>$checks->id));
					$in = $checks->id;

				}else
				{
					#record not available insert
					$chk1 = array();
					$chk1['termid'] = $termid;
					$chk1['grp'] = 8;
					$chk1['itemid'] = $subject;
					$chk1['itemid1'] = $ca;
					$chk1['clientid'] = $student;
					$chk1['contact'] = $score;
					$chk1['staffid'] = $staff;
					$chk1['checker'] = '8'.'_'.$termid.'_'.$student.'_'.$subject.'_'.$ca;

					$in = $op->insert($tabl, $chk1);
				}
				//loop students
				
		     //$data = $op->selectOne($tabl, NULL, array('id'=>$in));
			if($in > 0)
			{
		     $data = $op->selectScore('accessstudentscore',  $in, NULL, $tabl);
			 $response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully",
	            "data" => $data
	         );
	
		   	http_response_code(200);
		   	echo json_encode($response);
			}
		}			
	}
	if($cat === 'saveca')
	{
		
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			if(isset($_POST['sessionid']))
			{
				unset($_POST['sessionid']);
				//get data sent
				$tabl = 'access_'.$sessionID;
				$reportid = $_POST['reportid'];
				$subject = $_POST['subjectid'];
				//$staff = $_POST['staffid'];
				$grp = 11;
				$data = json_decode($_POST['data']);
				$store = array();

				foreach($data as $k => $v)
				{
					foreach ($v as $k1 => $v1) {
						# code...
						$arr= array();
						$arr['grp'] = $grp; //group
						$arr['termid'] = $reportid; //report id
						$arr['itemid'] = $subject; // subjectid
						$arr['itemid1'] = $k1; //caid
						$arr['clientid'] = $k; //student id
						$arr['contact'] = $v1;   // score
						$arr['checker'] = '11'.'_'.$reportid.'_'.$subject.'_'.$k.'_'.$k1;

						$prr = array();
						$prr['grp'] = $grp; //group
						$prr['termid'] = $reportid; //report id
						$prr['itemid'] = $subject; // subjectid
						$prr['itemid1'] = $k1; //caid
						$prr['clientid'] = $k; 


						$sav = $op->selectOne($tabl, NULL, $prr);
						if(isset($sav) && $sav->id > 1)
						{
							//update
							$ind = $op->update($tabl, $arr, array('id'=>$sav->id));
							$store[] = $sav->id;

						}else
						{
							//insert
							$ind = $op->insert($tabl, $arr);
							$store[] = $ind;
						}
					}
				}
				
			if(count($store) > 0)
			{
			 $ind = implode(",", $store);
		     $data = $op->selectScore('accessstudentca', NULL, array('idx' =>$ind),  $tabl);
			 $response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully",
	            "data" => $data
	         );
	
		   	http_response_code(200);
		   	echo json_encode($response);
			}
		}			
	}
	if($cat === 'savecadelete')
	{
		
		    $sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			if(isset($_POST['sessionid']))
			{
				unset($_POST['sessionid']);
				//get data sent
				$tabl = 'access_'.$sessionID;
				$reportid = $_POST['reportid'];
				$subject = $_POST['subjectid'];
				//$staff = $_POST['staffid'];
				$grp = 11;
				$data = json_decode($_POST['data']);
				$store = array();
				$stds = array();

				foreach($data as $k => $v)
				{
					$stds[] = $k;
					foreach ($v as $k1 => $v1)
					{
						# code...
						$arr= array();
						$arr['grp'] = $grp; //group
						$arr['termid'] = $reportid; //report id
						$arr['itemid'] = $subject; // subjectid
						$arr['itemid1'] = $k1; //caid
						$arr['clientid'] = $k; //student id
						$arr['contact'] = $v1;   // score
						$arr['checker'] = '11'.'_'.$reportid.'_'.$subject.'_'.$k.'_'.$k1;

						$prr = array();
						$prr['grp'] = $grp; //group
						$prr['termid'] = $reportid; //report id
						$prr['itemid'] = $subject; // subjectid
						$prr['itemid1'] = $k1; //caid
						$prr['clientid'] = $k; 


						$sav = $op->selectOne($tabl, NULL, $prr);
						
						if(isset($sav) && $sav->id > 1)
						{
							//update
							$ind = $op->delete($tabl, array('id'=>$sav->id));
							

						}
					}
				}
				//print_r($stds);
				$std = implode(",", $stds);
				//print_r($std);
				$response = array(
				"data" => array("reportid"=>$reportid, "subjectid"=>$subject, "data"=>$std),
	            "status" => "success",
	            "error" => false,
	            "message" => "deleted successfully"
		         );
				http_response_code(200);
				echo json_encode($response);
		}			
	}
	if($cat === 'insertscoreheader')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
			//get data sent
			$tabl = 'access_'.$sessionID;
			$termid = $_POST['termid'];
			$student = $_POST['studentid'];
			$ca = $_POST['caid'];
			$staff = $_POST['staffid'];
			$grp = 7;

			$fin = array();
		    $fin['termid'] = $termid;
			$fin['grp'] = 7;
			$fin['itemid'] = $subject;
			$fin['itemid1'] = $ca;
			$fin['clientid'] = $staff;

			#get all students from the class
			$checks = $op->selectOne($tabl, NULL, $fin);
			if(isset($checks) && is_array($checks) &&  $checks->id > 0)
			{
				#record availble edit
				$chk1 = array();
				//$chk1['staff'] = $staff;
				$chk1['contact'] = $score;
				$up = $op->update($tabl, $chk1, array('id'=>$checks->id));
				$in = $checks->id;

			}else
			{
				#record not available insert
				$chk1 = array();
				//$chk1['staff'] = $staff;
				$chk1['termid'] = $termid;
				$chk1['grp'] = 7;
				$chk1['itemid'] = $subject;
				$chk1['itemid1'] = $ca;
				$chk1['clientid'] = $staff;
				$chk1['contact'] = $score;
				$chk1['checker'] = '7'.'_'.$termid.'_'.$staff.'_'.$subject.'_'.$ca;  
				$in = $op->insert($tabl, $chk1);
			}
				//loop students
				
		     $data = $op->selectOne($tabl, NULL, array('id'=>$in));
			 $response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully",
	            "data" => $data
	         );
	
		   http_response_code(200);
		   echo json_encode($response);
		}			
	}
	if($cat === 'delete')
	{
		if(isset($_POST['id']))
		{
			$ins1 = $_POST['id'];
			$sel = $op->selectOne($table, NULL, array('id' =>$ins));
			$dz = json_encode($sel);
			$td = $op->delete($table, array('id' =>$ins1));
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully"
	          );
			http_response_code(200);
			echo json_encode($response);
		}
	}
	if($cat === 'deletes')
	{
		if(isset($_POST['id']) && isset($_POST['sessionid']))
		{
			$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			unset($_POST['sessionid']);
			
			$tabl = 'access_'.$sessionID;
			$ins1 = $_POST['id'];
			$sel = $op->selectOne($tabl, NULL, array('id' =>$ins1));
			$dz = json_encode($sel);
			$td = $op->delete($tabl, array('id' =>$ins1));
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "deleted successfully"
	          );
			http_response_code(200);
			echo json_encode($response);
		}
	}
	if($cat === 'deletefee')
	{
		if(isset($_POST['id']) && isset($_POST['sessionid']))
		{
			$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			unset($_POST['sessionid']);
			
			$tabl = 'fee_'.$sessionID;
			$ins1 = $_POST['id'];
			$sel = $op->selectOne($tabl, NULL, array('id' =>$ins1));
			$dz = json_encode($sel);
			$td = $op->delete($tabl, array('id' =>$ins1));
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "deleted successfully"
	          );
			http_response_code(200);
			echo json_encode($response);
		}
	}
	if($cat === 'deletescore')
	{
		if(isset($_POST['id']) && isset($_POST['sessionid']))
		{
			$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			unset($_POST['sessionid']);
			$tabl = 'access_'.$sessionID;
			$ins1 = $_POST['id'];
			$sel = $op->selectOne($tabl, NULL, array('id' =>$ins1));
			$dz = json_encode($sel);
			$td = $op->delete($tabl, array('id' =>$ins1));
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "deleted successfully"
	          );
			http_response_code(200);
			echo json_encode($response);
		}
	}
	if($cat === 'update')
	{
		if($table == 'staffs')
		{
			if(isset($_POST['passwd']))
			{
				$ps = md5($_POST['passwd']);
				$_POST['passwd'] = $ps;
			}	
		}
		if($table == 'students')
		{
			if(isset($_POST['links']))
			{
				$lks = $_POST['links'];
				unset($_POST['links']);
			}else{
				$lks='';
			}	
		}
		$ins = $_POST['id'];
		unset($_POST['id']);
		if(isset($_POST['files'])){unset($_POST['files']);}
		if(count($_POST) > 0)
		{
			$td = $op->update($table, $_POST, array('id' =>$ins));
		}
		if($ins > 0 & $table =='sessions')
		{
				$nm = '_'.$ins;
				$op->creatTableAccess($nm);
				$op->creatTableFee($nm);
		}
	}
	if($cat === 'updatestudentclasss')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		$tabl = 'access_'.$sessionID;
		if(isset($_POST['id']))
		{
			$ins = $_POST['id'];
			unset($_POST['id']);
			$td = $op->update('students', $_POST, array('id' =>$ins));
		}
	}
	if($cat === 'updatepassword')
	{
		$pss = new_password();
		$ps = md5($pss);
		$_POST['passwd'] = $ps;
		$ins = $_POST['id'];
		$mname = $_POST['fname'];
		$msend = $_POST['email'];
		$memp = $_POST['memp'];

		unset($_POST['id']);
		unset($_POST['fname']);
		unset($_POST['email']);
		unset($_POST['memp']);

		if(isset($_POST['files'])){unset($_POST['files']);}
		if(count($_POST) > 0){$td = $op->update($table, array('passwd'=>$ps), array('id' =>$ins));}

		send_password($msend, $mname, $memp, $pss);
	}
	if($cat === 'updates')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		
		$tabl = 'access_'.$sessionID;
		$ins = $_POST['id'];
		unset($_POST['id']);
		if(count($_POST) > 0)
		{
			$td = $op->update($tabl, $_POST, array('id' =>$ins));
		}
	}
	if($cat === 'updatefee')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid']))
		{
			unset($_POST['sessionid']);
		}
		
		$tabl = 'fee_'.$sessionID;
		$ins = $_POST['id'];
		unset($_POST['id']);
		if(count($_POST) > 0)
		{
			$td = $op->update($tabl, $_POST, array('id' =>$ins));
		}
	}
	if($cat === 'updateterm')
	{
		if(isset($_POST['schoolid']))
		{
			$schoolID = isset($_POST['schoolid']) ? $_POST['schoolid'] : '';
			$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			unset($_POST['sessionid']);
			//GET ALL SESSIONS
			$variable = $op->select('sessions', NULL, array('schoolid'=>$schoolID));
			foreach ($variable as $key => $value) {
				$op->update('terms', array('is_active'=>0), array('sessionid'=>$value->id));
			}
			
			$op->update('terms', array('is_active'=>1), array('id'=>$id));
			$data = $op->select('terms', NULL, array('sessionid'=>$sessionID));
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "dates changed",
	            "data" => $data
	          );
			
				http_response_code(200);
				echo json_encode($response);
		}
	}
	if($cat === 'send')
	{
		$fullname = $_POST['fullname'];
		$email = $_POST['email'];
		$tid = $_POST['id'];
		$locationid = $_POST['locationid'];
		$arr = array();
		$arr['fullname'] = $fullname;
		$arr['channel'] = $email;
		$arr['tid'] = $tid;
		$arr['locationid'] = $locationid;
		$ins = $op->insert($table, $arr);
		if($ins > 1)
		{
			send_guest_request($email, $ins, $fullname);
		}	
	}
	if($cat === 'confirm')
	{
		
			$sel = $op->selectOne($table, NULL, $_POST);
			if(isset($sel) && $sel->id > 0)
			{
					$ins = $sel->id;	
			}else 
			{
				if(isset($_POST['files'])){unset($_POST['files']);}
				$ins = $op->insert($table, $_POST);
			}
	}
	//STORE QUESTION DATA
	if($cat === 'pending')
	{

		if($_FILES && isset($_FILES) && isset($_FILES['files']))
		{
			$file_name = $_FILES["files"]["name"];
		    $file_tmp_name = $_FILES["files"]["tmp_name"];
		    $file_size = $_FILES["files"]["size"];
		    $error = $_FILES["files"]["error"];
		    $file_arr = explode(".", $file_name);
		    $ext = end($file_arr);
		    if($error > 0)
		    {
		        $response = array(
		            "status" => "error",
		            "error" => true,
		            "message" => "Error uploading the file!3"
		        );
			}else 
			{
				$random_name = rand(1000, 1000000);
		        $upload_name = $upload_dir1."img".$_POST['id']."_".strtolower($random_name).".".$ext;
		        try{
		        	if(move_uploaded_file($file_tmp_name, $upload_name)) 
			        {
			            $response = array(
			                "status" => "success",
			                "error" => false,
			                "message" => "File uploaded successfully",
			                "url" => $upload_name
			              );
			        }else
			        {
			            $response = array(
			                "status" => "error",
			                "error" => true,
			                "message" => "Error uploading the file!1"
			            );
			        }

		        }catch(Exception $err)
		        {
		        	$response = array(
			                "status" => "error",
			                "error" => $err->getMessage(),
			                "message" => "Error uploading the file!1"
			            );
		        }
		        

			}
		}else
		{
			$response = array(
	            "status" => "error",
	            "error" => true,
	            "message" => "Error uploading the file!2"
			 );
		}
		echo json_encode($response);
	}
	if(isset($ins) && $ins > 0)
	{
		//lodge_data($op1, $staffID, $table, $cat, $ins, $locateID);
		if($_FILES && isset($_FILES) && isset($_FILES['files']))
		{
		    $file_name = $_FILES["files"]["name"];
		    $file_tmp_name = $_FILES["files"]["tmp_name"];
		    $file_size = $_FILES["files"]["size"];
		    $error = $_FILES["files"]["error"];
		    $file_arr = explode(".", $file_name);
		    $ext = end($file_arr);
		   

		    if($error > 0)
		    {
			        $response = array(
			            "status" => "error",
			            "error" => true,
			            "message" => "Error uploading the file5!"
			        );
			}else 
			{
			        $random_name = rand(1000, 1000000);
			        $upload_name = $table."/".$table."_".$ins."_".strtolower($random_name).".".$ext;
			        try{
			        if(move_uploaded_file($file_tmp_name , $upload_name)) 
			        {
			        	$arr = array();
			        	if($table == 'course_materials')
			        	{
			        		$op->update($table, array('links' =>$upload_name, 'sizes'=>$file_size), array('id'=>$ins));
			        	}
			        	elseif($table == 'staffs' )
			        	{
			        		$op->update($table, array('photo' =>$upload_name), array('id'=>$ins));
			        	}
			        	elseif($table == 'staffeducations' || $table == 'staffprofessionals' || $table == 'schools')
			        	{
			        		$op->update($table, array('links' =>$upload_name), array('id'=>$ins));
			        	}
			        	elseif($table == 'students' && $lks != "")
			        	{
			        		$op->update($table, array('photo' =>$upload_name, strval($lks)=>$upload_name), array('id'=>$ins));
			        	}
			        	elseif($table == 'accessstudentclass' && isset($lks) && $lks != "")
			        	{
			        		$op->update('students', array('photo' =>$upload_name, strval($lks)=>$upload_name), array('id'=>$ins));
			        	}
			        	
			            $response = array(
			                "status" => "success",
			                "error" => false,
			                "message" => "File uploaded successfully",
			                "url" => ''
			              );
			        }else
			        {
			            $response = array(
			                "status" => "error",
			                "error" => true,
			                "message" => "Error uploading the file1!"
			            );
			        }
			    }catch(Exception $err)
			    {
			    	 $response = array(
			                "status" => "error",
			                "error" => $err->getMessage(),
			                "message" => "Error uploading the file3!"
			            );
			    }
			 }  
		}else
		{
			
			$response = array(
	            "status" => "success",
	            "error" => false,
	            "message" => "File uploaded successfully"
	          );		  
		}
		
		$get_table_info = get_info($op, $table, $ins, NULL, NULL, $tabl, $tabl1);
		if(is_object($get_table_info))
		{
			$response['data'] = $get_table_info;
			http_response_code(200);
			echo json_encode($response);
		}else
		{
			$response = array(
			             "status" => "error",
			              "error" => $get_table_info,
			              "message" => "Error uploading the file4!"
			            );
			http_response_code(404);
			echo json_encode($response);
		}	
	}
}
function lodge_data($op, $staffID, $table, $cat, $ins, $locate, $dz=null)
{
	//PREPARE DATA
	$arr = array();


	$arr['staffid'] = $staffID;
	$arr['tables'] = $table;
	$arr['actions'] = $cat;
	$arr['ids'] = $ins;
	$arr['locates'] = $locate;
	$arr['data'] = $dz;

	//$op->insert('user_transactions', $arr);
}
function new_password()
{
	$a  = chr(rand(65, 90));
			$b  = chr(rand(65, 90));
			$c  = chr(rand(65, 90));
			$d  = chr(rand(65, 90));
			$e  = chr(rand(65, 90));
			$f  = chr(rand(65, 90));
			$g  = chr(rand(65, 90));
			$h  = chr(rand(65, 90));
			$i  = chr(rand(65, 90));
			$j  = chr(rand(65, 90));
			return $pass = $a.$b.$c.$d.$e.$f.$g.$h.$i.$j;
}
function send_password($msend, $mname, $memp, $mpass)
{
	
	$to = $msend;
	$subject = 'SIL EDUCATION LOGIN';
	$from = 'info@silems.com';
	
	 
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	 
	// Create email headers
	$headers .= 'From: StreSERT Integrated Ltd. SMS <'.$from.">\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	
	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= '<img src="https://www.silhms.com/hms/logo.png" alt="SILEDUCATION | SCHOOL" height="100px" /><br/>';
	$message .= '<h2 style="color:#000;">Hello '.$mname.'!</h2>';
	$message .= '<p style="color:#080;font-size:14px;">Welcome to <b>StreSERT Integrated Limited School Management Solution</b>. You have been successfully registered on the platform. Find below your login details.Login and complete you profile details (Bio-data, Education, Professional and Work History. You can do that by clicking PROFILE on the sidebar menu and then selecting the item from your menu drop-down button.)</p><p>You will require soft copies of your educational & professional certificates to complete the update. its advisable to use a computer to complete the process</p>';
	$message .= '<h3 style="color:#000;">Staff ID '.$memp.'</h3>';
	$message .= '<h3 style="color:#000;">Password '.$mpass.'</h3><br/>';
	$message .= '<h4>Visit <a href="https://www.skoolq.com/ems/#/login">https://www.skoolq.com/ems/#/login</a>  to login</h4><br/>';
	$message .= '<h4>For help ? please call 08136184913</h4>';
	$message .= '<h4>Thank You</h4>';
	$message .= '</body></html>';
	 
	try{@mail($to, $subject, $message, $headers);}catch(Exception $e) {
  	//	echo 'Message: ' .$e->getMessage();
	}
}
function get_info($op, $table, $id = NULL, $where = NULL, $grp =NULL, $tabl =NULL, $tabl1=NULL)
{
	if(isset($id) && $id !== NULL)
	{ 
		
		if($table == 'schools')
		{
			return $data = $op->selectOne($table, NULL, array('id' => $id));
		}
		if($table == 'sessions')
		{
			return $data = $op->selectOne($table, NULL, array('id' => $id));
		}
		if($table == 'terms')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'staffs')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'students')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'attendances')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'cas')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'caunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'cbts')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'cbtexams')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'claszs')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'claszunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'departments')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'units')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'notices')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'fees')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'accounts')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'grades')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'gradeunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'expenses')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'expenseunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'expensetransactions')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'comments')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'offices')
		{
			return $data = $op->selected($table, $id);
		}

		if($table == 'jobs')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'levels')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'weeks')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'subjects')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'themes')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'timetables')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'reports')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'inventorys')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'maintenances')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'inventoryunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'inventorytransactions')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'maintenanceunits')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'maintenancetransactions')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'classfees')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'staffeducations')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'staffexperiences')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'staffprofessionals')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'staffleaves')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'staffjobs')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'staffAccess')
		{
			return $data = $op->selectes($table, $id);
		}
		if($table == 'studentfees')
		{
			return $data = $op->selectFees($table, $id, NULL, $tabl, $tabl1);
		}
		if($table == 'accessstaffclass')
		{
			return $data = $op->selectAccess($table, $id, NULL, $tabl);
		}
		if($table == 'accessstaffsubject')
		{
			return $data = $op->selectAccess($table, $id, NULL, $tabl);
		}
		if($table == 'accessstudentclass')
		{
			return $data = $op->selectAccess($table, $id, NULL, $tabl);
		}
		if($table == 'accessstudentsubject')
		{
			return $data = $op->selectAccess($table, $id, NULL, $tabl);
		}
	}
	elseif(isset($where))
	{
		return $data = $op->selected($table, $id, $where);
	}
}
//send_password('doyinspc2@gmail.com', 'Ade', '1111','12222');
?>