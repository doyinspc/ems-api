<?php

include '../connect/common.php';
include '../connect/connect.php';
$op = new Db;

$dpt = array(
	'smo'=> 1,
	'social science'=> 2,
	'vocation'=> 3,
	'science'=> 4,
	'art'=> 5,
	'languages'=> 6,
	'admin'=> 7,
	'junior support staff'=> 8
);
$row = 1;
 $rw = array();
if (($handle = fopen("stt.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    //echo "<p> $num fields in line $row: <br /></p>\n";
    
    	echo '<pre>';
    	if(strlen($data[8]) === 10)
    	{
    		$r = '0'.$data[8] ;
    	}elseif(strlen($data[8]) === 11)
    	{
    		$r = $data[8] ;
    	}
    	elseif(strlen($data[8]) > 11)
    	{
    		$r = explode(" ", $data[8]);
    		$r = $r[0];
    	}else{
    		$r = '';
    	}
    	if(strlen($data[22]) === 10)
    	{
    		$r1 = '0'.$data[22] ;
    	}elseif(strlen($data[22]) === 11)
    	{
    		$r1 = $data[22] ;
    	}
    	elseif(strlen($data[22]) > 11)
    	{
    		$r1 = explode(" ", $data[22]);
    		$r1 = $r1[0];
    	}else{
    		$r1 = '';
    	}

    	$db = explode('/', $data[11]);
    	//print_r($db);
        $d = $db[2].'-'.$db[0].'-'.$db[1];
        $db1 = explode('/', $data[13]);
       // print_r($db1);
        $d1 = $db1[2].'-'.$db1[0].'-'.$db1[1];

		$rw[$row]['employment_no'] = strtoupper(trim($data[2]));
		$rw[$row]['email'] = strtolower(trim($data[1]));
		$rw[$row]['phone1'] = $r;
		$rw[$row]['schoolid'] = strtoupper($data[24]);;
		$rw[$row]['surname'] = ucfirst(strtolower($data[4]));
		$rw[$row]['firstname'] = ucfirst(strtolower($data[5]));
		$rw[$row]['middlename'] = ucfirst(strtolower($data[6]));
		$rw[$row]['marital'] = ucfirst(strtolower($data[7]));
		$rw[$row]['gender'] = ucfirst(strtolower($data[10]));
		$rw[$row]['religion'] = ucfirst(strtolower($data[9]));
		$rw[$row]['dob'] = $d;
		$rw[$row]['doe'] = $d1;
		$rw[$row]['departmentid'] = $dpt[strtolower($data[14])];
		$rw[$row]['nin'] = strlen($data[15]) > 5 ? strtolower($data[15]) : '';
		$rw[$row]['tin'] = strlen($data[16]) > 5 ? strtolower($data[16]): '';
		$rw[$row]['nationality'] = ucfirst(strtolower($data[17]));
		$rw[$row]['soo'] = ucfirst(strtolower($data[18])) ;
		$rw[$row]['lga'] = ucfirst(strtolower($data[19]));
		$rw[$row]['address'] = strtoupper($data[11]);
		$rw[$row]['kin1_name'] = ucwords(strtolower($data[20]));
		$rw[$row]['kin1_rel'] = ucfirst(strtolower($data[21]));
		$rw[$row]['kin1_phone1'] = $r1;
		$rw[$row]['kin1_address'] = strtoupper($data[23]);


		//echo $op->insert('staffs', $rw[$row]);
		print_r($rw[$row]);
		$row++;

  }
  fclose($handle);
}
print_r($rw);
$sr = array();
$sr1 = array();
foreach ($rw as $key => $value) 
{
	$nm = array();
	$nm['fullname'] = $value['surname']." ".$value['firstname']." ". $value['middlename'];
	$nm['marital'] = strtoupper($value['marital']);
	$nm['gender'] = $value['gender'];
	$nm['dob'] = $value['dob'];
	$nm['phone1'] = $value['phone1'];
	$nm['area'] = $value['soo']." | ".$value['lga'];
	$nm['pcg'] = $value['address'];
	$sr[$value['employment_no']][] = $nm;
}
foreach ($rw as $key => $value) 
{
	$nm = array();
	$nm['fullname'] = $value['surname']." ".$value['firstname']." ". $value['middlename'];
	$nm['marital'] = strtoupper($value['marital']);
	$nm['gender'] = $value['gender'];
	$nm['dob'] = $value['dob'];
	$nm['phone1'] = $value['phone1'];
	$nm['area'] = $value['soo']." | ".$value['lga'];
	$nm['pcg'] = $value['address'];
	$sr1[$value['employment_no']] = $nm;
}
$nsr = array();
foreach ($sr as $key => $value) 
{
	if(count($value) > 1)
	{
		$nsr[$key] = $value;
	}
}
$nph = array();
foreach ($sr1 as $key => $value) 
{
	if(strlen($value['phone1']) == 0)
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
			<td>phone1</td>
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
					echo '<td style="align:center">'.$value1['marital'].'</td>';
					echo '<td style="align:center">'.$value1['fullname'].'</td>';
					echo '<td style="align:center">'.$value1['phone1'].'</td>';
					echo '<td style="align:center">'.$value1['gender'].'</td>';
					echo '<td style="align:center">'.$value1['dob'].'</td>';
					echo '<td style="align:center">'.$value1['area'].'</td>';
					echo '<td style="align:center">'.$value1['pcg'].'</td>';
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
					echo '<td style="align:center">'.$value1['marital'].'</td>';
					echo '<td style="align:center">'.$value1['fullname'].'</td>';
					echo '<td style="align:center">'.$value1['phone'].'</td>';
					echo '<td style="align:center">'.$value1['gender'].'</td>';
					echo '<td style="align:center">'.$value1['dob'].'</td>';
					echo '<td style="align:center">'.$value1['area'].'</td>';
					echo '<td style="align:center">'.$value1['pcg'].'</td>';
					echo '</tr>';
				
			}

	?>
</tbody>
</table>