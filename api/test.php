<?php

include '../connect/common.php';
include '../connect/connect.php';
$op = new Db;


$row = 1;
 $rw = array();
if (($handle = fopen("combine.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    //echo "<p> $num fields in line $row: <br /></p>\n";
    
    	echo '<pre>';
    	if(strlen($data[13]) === 10)
    	{
    		$r = '0'.$data[13] ;
    	}elseif(strlen($data[13]) === 11)
    	{
    		$r = $data[13] ;
    	}
    	elseif(strlen($data[13]) > 11)
    	{
    		$r = explode(" ", $data[13]);
    		$r = $r[0];
    	}else{
    		$r = '';
    	}
    	$db =isset($data[7]) ? explode('/', $data[7]) : explode('/', '20/11/2020');
    	//print_r($db);
        $d = $db[2].'-'.$db[1].'-'.$db[0];
       
		$rw[$row]['admission_no'] = strlen($data[1]) == 4 ? trim($data[1]) : 'NONE';
		$rw[$row]['surname'] = ucfirst(strtolower($data[2]));
		$rw[$row]['firstname'] = ucfirst(strtolower($data[3]));
		$rw[$row]['middlename'] = ucfirst(strtolower($data[4]));
		$rw[$row]['gender'] = ucfirst(strtolower($data[5]));
		$rw[$row]['religion'] = trim(ucfirst(strtolower($data[6])));
		$rw[$row]['dob'] = $d;
		$rw[$row]['schoolid'] = 1;
		$rw[$row]['soo'] = trim(ucfirst(strtolower($data[8])));
		$rw[$row]['lga'] = ucfirst(strtolower($data[9]));
		$rw[$row]['cclass'] = strtolower($data[10]);
		//$rw[$row]['g1_name'] = ucwords(strtolower($data[11]));
		//$rw[$row]['g1_rel'] = ucfirst(strtolower($data[12]));
		//$rw[$row]['g1_phone'] = $r;
		//$rw[$row]['g1_email'] = strlen($data[14]) > 5 ?  strtolower($data[14]): '';
		//$rw[$row]['g1_place'] = $data[15];
		//$rw[$row]['g1_address'] = $data[16];

	echo $op->insert('students', $rw[$row]);
		$row++;

  }
  fclose($handle);
}

$sr = array();
$sr1 = array();
foreach ($rw as $key => $value) 
{
	$nm = array();
	$sr[$value['admission_no']][] = $value;
}
// foreach ($rw as $key => $value) 
// {
// 	$nm = array();
// 	$nm['fullname'] = $value['surname']." ".$value['firstname']." ". $value['middlename'];
// 	$nm['cclass'] = strtoupper($value['cclass']);
// 	$nm['gender'] = $value['gender'];
// 	$nm['dob'] = $value['dob'];
// 	$nm['g1_phone'] = $value['g1_phone'];
// 	$nm['area'] = $value['soo']." | ".$value['lga'];
// 	$nm['pcg'] = $value['g1_name'];
// 	$sr1[$value['admission_no']] = $nm;
// }
$nsr = array();
foreach ($sr as $key => $value) 
{
	if(count($value) > 1)
	{
		$nsr[$key] = $value;
	}else
	{
		$psr[] = $value[0];
	}
}

//print_r($psr);
$nph = array();
foreach ($sr1 as $key => $value) 
{
	if(strlen($value['g1_phone']) == 0)
	{
		$nph[$key] = $value;
	}
}
?>
<h4>STUDENTS DATA : NONE OR DUPLICATE ADMISSION NUMBER</h4>
<table width='100%' border="1px">
	<thead>
		<tr>
			<td width='50px'>ADMIN.</td>
			<td>CLASS</td>
			<td>FULLNAME</td>
			<td>PHONE</td>
			<td>GENDER</td>
			<td>BIRTH</td>
			<td>SOO/LGA</td>
			<td>PRIMARY CARE GIVER</td>
		</tr>
	</thead>
	<tbody>
	<?php
			foreach ($nsr as $key => $value) 
			{
				foreach ($value as $key1 => $value1)
				{
					echo '<tr>';
					echo '<td style="align:center"><b>'.$key.'</b></td>';
					echo '<td style="align:center">'.$value1['cclass'].'</td>';
					echo '<td style="align:center">'.$value1['surname']." ".$value1['firstname']." ".$value1['middlename'].'</td>';
					echo '<td style="align:center">'.$value1['g1_phone'].'</td>';
					echo '<td style="align:center">'.$value1['gender'].'</td>';
					echo '<td style="align:center">'.$value1['dob'].'</td>';
					echo '<td style="align:center">'.$value1['religion'].'</td>';
					echo '<td style="align:center">'.$value1['g1_name'].'</td>';
					echo '</tr>';
				}
			}

	?>
</tbody>
</table>

<h4>STUDENTS DATA : NO PHONE NUMBERS</h4>
<table width='100%' border="1px">
	<thead>
		<tr>
			<td width='50px'>ADMIN.</td>
			<td>CLASS</td>
			<td>FULLNAME</td>
			<td>PHONE</td>
			<td>GENDER</td>
			<td>BIRTH</td>
			<td>SOO/LGA</td>
			<td>PRIMARY CARE GIVER</td>
		</tr>
	</thead>
	<tbody>
	<?php
			foreach ($nph as $key1 => $value1) 
			{
				
					echo '<tr>';
					echo '<td style="align:center"><b>'.$key1.'</b></td>';
					echo '<td style="align:center">'.$value1['cclass'].'</td>';
					echo '<td style="align:center">'.$value1['fullname'].'</td>';
					echo '<td style="align:center">'.$value1['g1_phone'].'</td>';
					echo '<td style="align:center">'.$value1['gender'].'</td>';
					echo '<td style="align:center">'.$value1['dob'].'</td>';
					echo '<td style="align:center">'.$value1['area'].'</td>';
					echo '<td style="align:center">'.$value1['pcg'].'</td>';
					echo '</tr>';
				
			}

	?>
</tbody>
</table>