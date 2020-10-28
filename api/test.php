<?php

include '../connect/common.php';
include '../connect/connect.php';
$op = new Db;


// $name = 'jhmesu hamrod';

// $ph =  array(
// 		'j'=>0,
// 		'h'=>1,
// 		'm'=>2,
// 		'e'=>3,
// 		's'=>4,
// 		'u'=>5,
// 		'h'=>6,
// 		'a'=>7,
// 		'm'=>8,
// 		'r'=>9,
// 		'o'=>0,
// 		'd'=>1,

// 	);
// $start = strtotime('2015-01-01');
// $end = strtotime('2022-12-30');
// $lodge = array(1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1);

// for($i = 0; $i < 15000; $i++)
// {
// 	$names  = str_shuffle($name); 
// 	$ph1 =  str_split(trim($names));
// 	$ph2 = implode("",array_intersect($ph, array_flip($ph1)));	
// 	$phone = '080'.substr($ph2, 0, 9);
// 	$arr = array();
// 	$roo =  rand(1, 30);
// 	$booking = rand($start, $end);
// 	$getlode = rand(0, 12);
// 	$get_num = $lodge[$getlode];
// 	$get_date = $get_num === 1 ? date("Y-m-d h:i:s", $booking) : '';

// 	$space = rand(1000000, 1000000000);
// 	$arr['roomid'] = $roo;
// 	$arr['guestno'] = rand(1, 4);
// 	$arr['transaction_date'] = date("Y-m-d h:i:s", $booking);
// 	$arr['endbook'] = $get_date;
// 	$arr['date_created'] = date("Y-m-d h:i:s", $booking - $space);
// 	$arr['is_paid'] = $get_num;
// 	$arr['is_lodged'] = $get_num;
// 	$arr['fullname'] = $names;
// 	$arr['phone'] = $phone;
// 	$arr['duration'] = $space;
// 	$arr['idtype'] = $phone;
// 	$arr['idnumber'] = $ph2;
// 	$arr['checker'] = date("Y-m-d", $booking - $space).':::'.$roo;
	
// 	try {
// 		$op->insert('room_transactions', $arr);
// 	} catch (Exception $e) {
// 		print_r($e);
// 	}
	

// }



$name = 'jhmesu hamrod';

$ph =  array(
		'j'=>0,
		'h'=>1,
		'm'=>2,
		'e'=>3,
		's'=>4,
		'u'=>5,
		'h'=>6,
		'a'=>7,
		'm'=>8,
		'r'=>9,
		'o'=>0,
		'd'=>1,

	);
$start = strtotime('2015-01-01');
$end = strtotime('2022-12-30');
$lodge = array(1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 1, 0, 1);

for($i = 0; $i < 4000; $i++)
{
	$names  = str_shuffle($name); 
	$ph1 =  str_split(trim($names));
	$ph2 = implode("",array_intersect($ph, array_flip($ph1)));	
	$phone = '080'.substr($ph2, 0, 9);
	$arr = array();
	$roo =  rand(1, 24);
	$roo1 =  rand(1, 30);
	$booking = rand($start, $end);
	$getlode = rand(0, 12);
	$get_num = $lodge[$getlode];
	$space = rand(1000000, 1000000000);
	$get_date = $get_num === 1 ? date("Y-m-d h:i:s", $booking + $space) : '';

	
	$arr['maintenanceid'] = $roo;
	$arr['status'] = rand(1, 4);
	$arr['transaction_date'] = date("Y-m-d h:i:s", $booking);
	$arr['date_completed'] = $get_date;
	$arr['date_created'] = date("Y-m-d h:i:s", $booking);
	$arr['is_completed'] = $get_num;
	$arr['resolutiontime'] = $space;
	$arr['location'] = $roo1;
	
	try {
		echo $op->insert('maintenance_transactions', $arr);
	} catch (Exception $e) {
		print_r($e);
	}
	

}


?>
