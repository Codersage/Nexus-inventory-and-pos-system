<?php
$serverName =    'SAGECODER';
 

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"inventory" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
    // echo "lets get that bread";
}else{
     echo "Connection could not be established.<br />";
 die( print_r( sqlsrv_errors(), true));
}


?>




