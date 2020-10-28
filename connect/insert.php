<?php
function insert($db_name, $c_array){
	try
		{
			$detail = "INSERT INTO `". $db_name ."` SET ". $c_array ." ON DUPLICATE KEY UPDATE ". $c_array ."";
			$dbh = connect();
			$sth = $dbh->prepare($detail);
			$sth->execute ();
		}
		catch (PDOException $e)
		{
			print ("The statement failed.\n");
			print ("getCode: ". $e->getCode () . "\n");
			print ("getMessage: ". $e->getMessage () . "\n");
		}
	
}

?>