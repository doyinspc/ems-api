<?php
require_once('connect.php');
require_once('class.student.php');
require_once('class.sendmail.php');

$message = new SimpleMail;
$message-> setToAddress('doyinspc2@yahoo.com');
$message-> setFromAddress('doyinspc2@gmail.com');
$message-> setCCAddress('friend@example.com');
$message-> setSubject('Testing Multipart Email');
$message-> setTextBody('This is the plain text portion of the email!');
$message-> setHTMLBody(' < html > < p > This is the < b > HTML portion < /b > of the email! < /p > < /html > ');
if ($message-> send()) {
echo 'Multi-part mail sent successfully!';
} else {
echo 'Sending the multi-part mail failed!';
}

$con = new Connect;
$a = "`id` = 51, `surname` = 'onoja'";
$b = "`id` = 1229";
$st1 = $con->insert('studentbio_db', $a);
$st2 = $con->construct();
$st3 = $con->delete('studentbio_db', $b);
$sth = $st2->query ("SELECT surname, id FROM studentbio_db");
while ($row = $sth->fetch (PDO::FETCH_NUM))
printf ("Name: %s, Category: %s\n</br>", $row[0], $row[1]);
$con = NULL;
new 
?>