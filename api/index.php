<?php
// required headers
//header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
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
	$token = $queries['token'] ;
	$data = array();
	
	if($cat === 'all'){$data = $op->select($table);}
	if($cat === 'group'){$data = $op->select($table, NULL, $query);}
	if($cat == 'select'){$data = get_info($op, $table, NULL, $query);}

	if($cat === 'categoryroomall'){$data = $op->selectroom($query, 0);}
	if($cat === 'categoryinventoryall'){$data = $op->selectinventory($query, 0);}
	if($cat === 'categorymaintenanceall'){$data = $op->selectmaintenance($query, 0);}
	if($cat === 'categoryuserall'){$data = $op->selectuser($query, 0);}

	if($cat === 'roomall'){$data = $op->selectroom($query, 1);}
	if($cat === 'inventoryall'){$data = $op->selectinventory($query, 1);}
	if($cat === 'maintenanceall'){$data = $op->selectmaintenance($query, 1);}
	if($cat === 'userall'){$data = $op->selectuser($query, 1);}

	if($cat === 'categoryroom'){$data = $op->selectroom($query, 3);}
	if($cat === 'categoryinventory'){$data = $op->selectinventory($query, 3);}
	if($cat === 'categorymaintenance'){$data = $op->selectmaintenance($query, 3);}
	if($cat === 'categoryuser'){$data = $op->selectuser($query, 3);}

	if($cat === 'inventorytransaction'){$data = $op->selectinventory($query, 4);}
	if($cat === 'maintenancetransaction'){$data = $op->selectmaintenance($query, 4);}
	if($cat === 'roomtransaction'){$data = $op->selectroom($query, 4);}
	if($cat === 'guestroomtransaction'){$data = $op->selectroom($query, 6);}
	if($cat === 'guestroomtransactionsummary'){$data = $op->selectroom($query, 7);}
	if($cat === 'usertransaction'){$data = $op->selectuser($query, 4);}

	if($cat === 'inventorytransactionsummary'){$data = $op->selectinventory($query, 5);}
	if($cat === 'maintenancetransactionsummary'){$data = $op->selectmaintenance($query, 5);}
	if($cat === 'roomtransactionsummary'){$data = $op->selectroom($query, 5);}
	if($cat === 'usertransactionsummary'){$data = $op->selectuser($query, 5);}
	
	if($cat === 'maint1'){$data = $op->selectmaintenance($query, 6);}
	if($cat === 'maint2'){$data = $op->selectmaintenance($query, 7);}
	if($cat === 'maint3'){$data = $op->selectmaintenance($query, 8);}

	if($cat === 'roommaihis'){$data = $op->selectroom($query, 3);}
	if($cat === 'roomaista'){$data = $op->selectroom($query, 4);}
	if($cat === 'roominvsta'){$data = $op->selectroom($query, 6);}

	if($cat === 'roomsta')
	{
		$dates = $query['currentdate'];
		$location = $query['locationid'];
		$data1 = $op->selectroomstatistics($dates, 1, $location);//get all bookings for that day
		$data2 = $op->selectroomstatistics($dates, 4, $location);//get all rooms
		$data3 = $op->selectroomstatistics($dates, 5, $location);//get all rooms
		$data4 = $op->selectroomstatistics($dates, 6, $location);//get all rooms out of order
		$data5 = $op->selectroomstatistics($dates, 1, $location);//get all rooms occupied
		$data6 = $op->selectroomstatistics($dates, 1, $location);//get all rooms vacated
		$data =  [$data1, $data2, $data3, $data4, $data5, $data6];
	}
	if($cat === 'roomana')
	{
		$location = $query['locationid'];
		$data1 = $op->selectroomstatistics($query, 2, $location); //get all bookins for that day
		$data2 = $op->selectroomstatistics($query, 3, $location);//get all rooms
		$data3 = $op->selectroomstatistics($query, 2, $location);//get all rooms
		$data =  [$data1, $data2, $data3];
	}
	if($cat === 'mainana')
	{
		
		$data1 = $op->selectmaintenancestatistics($query, 1);
		$data2 = $op->selectmaintenancestatistics($query, 2);
		$data3 = $op->selectmaintenancestatistics($query, 3);
		$data4 = $op->selectmaintenancestatistics($query, 4);
		$data =  [$data1, $data2, $data3, $data4];
	}
	if($cat === 'invana')
	{
		
		$data1 = $op->selectmaintenncestatistics($query, 1);
		$data2 = $op->selectmaintenncestatistics($query, 2);
		$data3 = $op->selectmaintenncestatistics($query, 3);
		$data4 = $op->selectmaintenncestatistics($query, 4);
		$data =  [$data1, $data2, $data3, $data4];
	}
	if($cat === 'userana')
	{
		
		$data1 = $op->selectuserstatistics($query, 1);
		$data2 = $op->selectuserstatistics($query, 2); 
		$data =  [$data1, $data2];
	}

	if($cat == 'update')
	{
		$id = $queries['id'];
		$dt = $op->update($table, $query, array('id'=>$id));
		$data = $op->selectOne($table, NULL, array('id'=>$id));
	}
	if($cat == 'insert')
	{
		$id = $op->insert($table, $query);
		$data = $op->selectOne($table, NULL, array('id'=>$id));
	}
	if($cat == 'deleter'){
		$data = $op->delete($table, $query);
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
$server_url = 'http://127.0.0.1:8000/admission/api/index.php';
//POST AND FILES REQUEST
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$cat = isset($_POST['cat']) ? $_POST['cat'] : null;
	$table = isset($_POST['table']) ? $_POST['table'] : null;
	unset($_POST['cat']);
	unset($_POST['table']);

	//LOGIN STUDENT AND STAFF
	if($cat === 'login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$data = $op->selectOne($table, NULL, array('username' => $username));
		$data = (array) $data;
		if($data && isset($data['id']) && $data['id'] > 0)
		{
			if($data['phone'] === $password && $table === 'students')
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
			}elseif($data['password'] === $password && $table === 'staffs')
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
            	"message" => "Login Failed",
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
            	"message" => "Login Failed",
            	"url"=>$server_url
				);
				http_response_code(404);
				echo json_encode($response);
			}
	}
	//INSERT
	if($cat === 'insert')
	{
		if($table == 'user_types')
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
			$ins = $op->insert($table, $_POST);
		}

		//print_r($ins);
			
	}
	//UPDATE
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
	//CONFITM BEFORE INSERT
	if($cat === 'confirm')
	{
		if($table === 'course_scores')
		{
			$asr = array();
			$asr['studentId'] = $_POST['studentId'];
			$asr['materialId'] = $_POST['materialId'];
			$asr['qid'] = $_POST['qid'];
			$sel = $op->selectOne($table, NULL, $asr);
			if(isset($sel) && $sel->id > 0)
			{
				$ins = $sel->id;	
			}else 
			{
				if(isset($_POST['files'])){unset($_POST['files']);}
				$ins = $op->insert($table, $_POST);
			}

		}
		else
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
	//IF ID ISSET
	if(isset($ins) && $ins > 0)
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

function get_info($op, $table, $id = NULL, $where = NULL)
{
	if(isset($id) && $id !== NULL)
	{ 
		
		if($table == 'room_categorys')
		{
			return $data = $op->selectroom($table, 0, $id);
		}
		if($table == 'room_types')
		{
			return $data = $op->selectroom($table, 2, $id);
		}
		if($table == 'room_transactions')
		{
			return $data = $op->selectroom($table, 4, $id);
		}
		if($table == 'room_maintenance')
		{
			return $data = $op->selectroom($table, 8, $id);
		}
		if($table == 'inventory_categorys')
		{
			return $data = $op->selectinventory($table, 0, $id);
		}
		if($table == 'inventory_types')
		{
			return $data = $op->selectinventory($table, 2, $id);
		}
		if($table == 'inventory_transactions')
		{
			return $data = $op->selectinventory($table, 4, $id);
		}
		if($table == 'maintenance_categorys')
		{
			return $data = $op->selectmaintenance($table, 0, $id);
		}
		if($table == 'maintenance_types')
		{
			return $data = $op->selectmaintenance($table, 2, $id);
		}
		if($table == 'maintenance_transactions')
		{
			return $data = $op->selectmaintenance($table, 4, $id);
		}
		if($table == 'user_categorys')
		{
			return $data = $op->selectuser($table, 0, $id);
		}
		if($table == 'user_types')
		{
			return $data = $op->selectuser($table, 2, $id);
		}
		if($table == 'user_transactions')
		{
			return $data = $op->selectuser($table, 4, $id);
		}

	}elseif(isset($where))
	{
		return $data = $op->selected($table, $id, $where);
	}
	
}


?>