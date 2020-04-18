<?php
/*  getCmpSrc- Load the SRCFIL and return the values
 *
 * Test source Change
 *
 *
 *
 */
error_reporting(E_ALL);

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json");
ini_set('memory_limit', '-1');
ini_set('time_limit', '180');
ignore_user_abort();



include "Access.php";


 


$s = "SELECT cast(srcdif as char(220) ccsid 37) as SRCDIF FROM JRMATZ.SRCDIF";
$r = db2_exec($con, $s);
$data['SQL'] = $s;
while ($row = db2_fetch_assoc($r)){
 
    $data['root'][]= $row;
    
}
$data['success'] = true;
echo $_GET['callback'] . '(' . json_encode($data). ')';