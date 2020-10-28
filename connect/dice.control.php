<?php
include("dice.php");

$db = $_POST['db'];
unset($_POST['db']);
if($_POST['id'] == 0 || strlen($_POST['id']) == 0){
	$_POST['id'] = NULL;
	}
$_POST['id'] = '6';
$_POST['sat'] = 'tread';
foreach($_POST as $k => $v)
{
    if(!is_numeric($v))
    {
		if(!is_null($v))
		{
		$insert_array[] = "`$k` = '$v'";
		}
		else
		{
        $insert_array[] = "`$k` = NULL";
		}
    }
	
    else
    {
        $insert_array[] = "`$k` = '$v'";
    }
}
//print_r($insert_array);
echo $final_string = implode(',', $insert_array);

$rep = insert($db,$final_string);

?>