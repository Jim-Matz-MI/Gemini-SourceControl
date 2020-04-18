<?php

/*getSchemas - Get a list of all libs/schema's and show a check next to the ones that are in the setup
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


if (isset($_GET['records'])){
    $recs = json_decode($_GET['records'], true);
    $val = $recs['VAL'];
    $sel = $recs['SEL'];
    
    if ($sel){
        $s = "Insert into SRCCON.SCSCHEMA values('$val') with NC";
    } else {
        $s =  "Delete from SRCCON.SCSCHEMA where SELSCHEMA = '$val' with NC";
    }
    db2_exec($con, $s);
}



$s = "SELECT *  FROM QSYS2.SYSSCHEMAS
LEFT JOIN SRCCON.SCSCHEMA ON SELSCHEMA = SCHEMA_NAME  order by SCHEMA_NAME  ";
$r = db2_exec($con, $s);
$data['SQL'] = $s;
$data['SQLMSG'] = db2_stmt_errormsg();
while($row = db2_fetch_assoc($r)){
    if (trim($row['SELSCHEMA']) !== ''){
        $x['SEL'] = true;
    } else $x['SEL'] = false;
    $x['VAL'] = $row['SCHEMA_NAME'];
    $x['TEXT'] = trim($row['SCHEMA_TEXT']) . '(' . trim($row['SCHEMA_NAME']) .')' ;
    $data['root'][] = $x;
}


$data['success'] = true;
echo $_GET['callback'] . '(' . json_encode($data). ')';