<?php
// required headers
//header("Access-Control-Allow-Origin: http://localhost:3000/");
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
	if($cat === 'selectes'){$data = $op->selectes($table, NULL, $query);}
	if($cat === 'staffclass')
	{

		if(isset($query['sessionid']))
		{
			$sessionID = $query['sessionid'];
			unset($query['sessionid']);
			$tabl = 'access_'.$sessionID;
			$data = $op->selectAccess($table, NULL, $query, $tabl);
		}
		
	}
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
	if($cat === 'studentsubject'){$data = $op->selectAccess($table, NULL, $query);}

	if($cat === 'dropdowns')
	{
		$data1 = $op->selectDropdown($query['schoolid'], 1);
		$data2 = $op->selectDropdown($query['schoolid'], 2);
		$data3 = $op->selectDropdown($query['schoolid'], 3);
		$data4 = $op->selectDropdown($query['schoolid'], 4);
		$data5 = $op->selectDropdown($query['schoolid'], 5);
		$data6 = $op->selectDropdown($query['schoolid'], 6);
		$data7 = $op->selectDropdown($query['schoolid'], 7);

		$data = [$data1, $data2, $data3, $data4, $data5, $data6, $data7];
	}
	if($cat === 'schoolaccess')
	{
		$data = $op->selectGroups($query['schools'], 1);
	}
	if($cat === 'dataaccess')
	{
		$session = $query['sessionid'];
		$term = $query['termid'];
		$staff = $query['staffid'];
		$data1 = $op->selectAccessSimple($session, $term, $staff, 1);
		$data2 = $op->selectAccessSimple($session, $term, $staff, 2);
		$data = [$data1, $data2];
	}

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

	unset($_POST['cat']);
	unset($_POST['table']);
	if(isset($_POST['rowzid'])){ unset($_POST['rowzid']);}
	if(isset($_POST['narration'])){unset($_POST['narration']);}
	//LOGIN STUDENT AND STAFF
	if($cat === 'login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$data = $op->selectOne('staffs', NULL, array('employment_no' => $username));
		$data = (array) $data;
		if($data && isset($data['id']) && $data['id'] > 0)
		{
			if(
				$data['passwd'] !== md5($password) && 
				$table === 'staffs' &&
				$data['is_admin'] < '2' &&
				$data['is_active'] === '0' &&
				$data['is_delete'] === '0'
			) 
			{
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
			}
		}
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
	if($cat === 'delete')
	{
		if(isset($_POST['id']))
		{
			$ins = $_POST['id'];
			$sel = $op->selectOne($table, NULL, array('id' =>$ins));
			$dz = jason_encode($sel);
			$td = $op->delete($table, array('id' =>$ins));
		}
	}
	if($cat === 'update')
	{
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
			}
	}
	if($cat === 'updates')
	{
		$sessionID = isset($_POST['sessionid']) ? $_POST['sessionid'] : '';
		if(isset($_POST['sessionid'])){
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
			            "message" => "Error uploading the file!"
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
			        	elseif($table == 'students')
			        	{
			        		$op->update($table, array('photo' =>$upload_name), array('id'=>$ins));
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
			                "message" => "Error uploading the file!"
			            );
			        }
			    }catch(Exception $err)
			    {
			    	 $response = array(
			                "status" => "error",
			                "error" => $err->getMessage(),
			                "message" => "Error uploading the file!"
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
		
		$get_table_info = get_info($op, $table, $ins, NULL, NULL, $tabl);
		$response['data'] = $get_table_info;
		echo json_encode($response);
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
function get_info($op, $table, $id = NULL, $where = NULL, $grp =NULL, $tabl)
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

		if($table == 'cas')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'caunits')
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
		if($table == 'grades')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'gradeunits')
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
		if($table == 'subjects')
		{
			return $data = $op->selected($table, $id);
		}
		if($table == 'themes')
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
		if($table == 'maintenanceunits')
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
?>