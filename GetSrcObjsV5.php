<?php
/*  getSrcObjs - Get Source Object - List the source objects 
 *    Compare HDStoGM43 then add objects in GM43 not in HDS
 *    
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

$s = "SELECT F1.MLFILE, F1.MLNAME as HDSNAME, F1.MLMTXT, F1.MLSEU2, F2.mlname as GEMNAME FROM 
jrmatz.hv5src f1 left join jrmatz.gemsrc f2 on f1.mlname =       
f2.mlname and f1.mlseu2 = f2.mlseu2 ";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
while ($row=db2_fetch_assoc($r)){
    $x['FILE'] = trim($row['MLFILE']);
    $x['HDSNAME'] = trim($row['HDSNAME']);
    $x['GEMNAME'] = trim($row['GEMNAME']);
    $x['TEXT'] = trim($row['MLMTXT']);
    $x['TYPE'] = trim($row['MLSEU2']);
    $data['root'][] = $x;
}

$s = "SELECT F1.MLLIB,F1.MLFILE,f1.MLNAME as GEMNAME, f1.MLMTXT, f1.MLSEU2, f2.mlname as HDSNAME FROM   
jrmatz.gemsrc f1 left join jrmatz.hv5src f2 on f1.mlname =         
f2.mlname and f1.mlseu2 = f2.mlseu2 WHERE not exists (Select * from
jrmatz.hv5src f3 where f3.mlname = f1.mlname and f3.mlseu2 =       
f1.mlseu2) ";
$r = db2_exec($con, $s);
while ($row=db2_fetch_assoc($r)){
    $x['HDSLIB'] = trim($row['MLLIB']);
    $x['FILE'] = trim($row['MLFILE']);
    $x['HDSNAME'] = trim($row['HDSNAME']);
    $x['GEMNAME'] = trim($row['GEMNAME']);
    $x['TEXT'] = trim($row['MLMTXT']);
    $x['TYPE'] = trim($row['MLSEU2']);
    $data['root'][] = $x;
}

$data['success'] = true;
echo $_GET['callback'] . '(' . json_encode($data). ')';
// FILE, HDSNAME, GEMNAME, TEXT, TYPE