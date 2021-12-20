<?php

include '../connect/common.php';
include '../connect/connect.php';
$op = new Db;

echo '<pre>';
$all = $op->select('staffs', NULL, array('schoolid'=>1));

// foreach ($all as $key => $value) {
	
// 	echo $adm = $value->admission_no;
  
// 	$src = 'pht/'.$adm.'.jpg';
// 	$addr = $value->id.'_'.$adm.'_'.'.jpg';
// 	$dest = 'passport/'.$addr;

// 	if(copy($src, $dest))
// 	{
// 		$op->update('students', array('photo1'=>$addr), array('id'=>$value->id));
// 	}


// }
$row = array();

foreach ($all as $key => $value) {

      $rw = array();

      $rw['empid'] = $value->id;
      $rw['firstname'] = $value->firstname;
      $rw['lastname'] = $value->surname;
      $rw['gender'] = $value->gender;
      $row[] = $rw;

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

<!-- <table width="100%" border="1px">
	<?php
		//foreach ($row as $key => $value) {
			# code...

			// echo '<tr>';
			// echo '<td>'.$value['empid'].'</td>';
			// echo '<td>'.$value['firstname'].'</td>';
			// echo '<td>'.$value['lastname'].'</td>';
			// echo '<td>'.$value['gender'].'</td>';
			// echo '</tr>';

		}


	?>
</table> -->