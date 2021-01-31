<?php
$serverName =    /*'SAGECODER';*/'SQL5097.site4now.net';
 

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"DB_A6C4FE_sagecoder","UID"=>"DB_A6C4FE_sagecoder_admin", "PWD"=>"Badbush5" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
    // echo "lets get that bread";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}


?>




