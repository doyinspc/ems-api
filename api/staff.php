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
include '../connect/connectstaff1.php';
$op = new Db;
$op1 = new Db1;

//GET REQUEST

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
	$queries = array();
	parse_str($_SERVER['QUERY_STRING'], $queries);
	$query = (array) json_decode($queries['data']);
	$cat = $queries['cat'];
	$table = $queries['table'] ;
	$token = $queries['token'] ;
	$narration = $queries['narration'];
	$data = array();
	
	if($cat === 'all'){$data = $op->select($table);}
	if($cat === 'group'){$data = $op->select($table, NULL, $query);}
	if($cat === 'select'){$data = $op->selectStaff($table, NULL, $query);}

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

	unset($_POST['cat']);
	unset($_POST['table']);
	if(isset($_POST['rowzid'])){ unset($_POST['rowzid']);}
	if(isset($_POST['narration'])){unset($_POST['narration']);}

	if($cat === 'insert')
	{
		if($table == 'staffs')
		{
			$ps = md5($_POST['passwd']);
			$_POST['passwd'] = $ps;
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
			echo $ins = $op->insert($table, $_POST);
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
		if($ins > 1){
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
		lodge_data($op1, $staffID, $table, $cat, $ins, $locateID);
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
			        $upload_name = $upload_dir.$table."_".$ins."_".strtolower($random_name).".".$ext;
			        try{
			        if(move_uploaded_file($file_tmp_name , $upload_name)) 
			        {
			        	$arr = array();
			        	if($table == 'course_materials')
			        	{
			        		$op->update($table, array('links' =>$upload_name, 'sizes'=>$file_size), array('id'=>$ins));
			        	}
			        	elseif($table == 'staffs' || $table == 'students')
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
		$get_table_info = get_info($op, $table, $ins);
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
function get_info($op, $table, $id = NULL, $where = NULL)
{
	if(isset($id) && $id !== NULL)
	{ 
		
		if($table == 'sessions')
		{
			return $data = $op->selectOne($table, NULL, array('id' => $id));
		}
		if($table == 'room_types')
		{
			return $data = $op->selectroom($table, 2, $id);
		}
	}
}
?>