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
	'admin'=> 7
);
$row = 1;
 $rw = array();
if (($handle = fopen("admission.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    //echo "<p> $num fields in line $row: <br /></p>\n";
    
    	echo '<pre>';
    	
    	$db = explode(' ', $data[1]);
    	//print_r($db);
        

		$rw[$row]['surname'] = strtoupper(trim($db[0]));
		$rw[$row]['firstname'] = strtoupper(trim($db[1]));
		$rw[$row]['middlename'] = strtoupper(trim($db[2]));
		$rw[$row]['cclass'] = $data[10];
		$rw[$row]['schoolid'] = $data[9];
		$rw[$row]['session'] = '2020';
		$rw[$row]['address'] = $data[2];
		$rw[$row]['status'] = $data[8];


		echo $op->insert('admissions', $rw[$row]);
		$row++;

  }
  fclose($handle);
}
print_r($rw);
$sr = array();
$sr1 = array();

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

