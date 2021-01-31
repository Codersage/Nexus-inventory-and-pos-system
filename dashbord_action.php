<?php
header('Content-Type: application/json');
session_start();
$uid=$_SESSION['UID'];
require('connection.php');
$sql="select * FROM  dbo.salesdata ('$uid')";
$result=sqlsrv_query($conn,$sql);

$data = array();
while($row=sqlsrv_fetch_array($result)){

  $data[] = $row;
}

sqlsrv_close($conn);

echo json_encode($data);

?>