<?php

include '../connect/common.php';
include '../connect/connect.php';
$op = new Db;

echo '<pre>';
$all = $op->select('students');

foreach ($all as $key => $value) {
	
	echo $adm = $value->admission_no;
  
	$src = 'pht/'.$adm.'.jpg';
	$addr = $value->id.'_'.$adm.'_'.'.jpg';
	$dest = 'passport/'.$addr;

	if(copy($src, $dest))
	{
		$op->update('students', array('photo1'=>$addr), array('id'=>$value->id));
	}


}
// $term = 3;
// $grp = 3;
// foreach ($all as $key => $value) {
	
// 	echo $adm = strtoupper($value->cclass);
// 	$cl = $op->selectOne('claszunits', NULL, array('name'=>$adm));
// 	if($cl->id > 0){
// 	$arr = array();
// 	$arr['clientid'] = $value->id;
// 	$arr['grp'] = $grp;
// 	$arr['termid'] = $term;
// 	$arr['itemid'] = $cl->id;
// 	$arr['checker'] = $term.'_'.$grp.'_'.$cl->id.'_'.$value->id;
// 	print_r($arr);
// 	$op->insert('access', $arr);
//   	}
	


// }


?>