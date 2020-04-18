<?php
/*  getCmpSrc- Load the SRCFIL and return the values
 *     
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


$path = '/usr/local/zendphp7/share/ToolKitAPI';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once("ToolkitService.php");
$extension='ibm_db2';
$pw = 'WEBOE';
$usr = 'WEBOE';
$db = 'ERPDC';
try {
    $ToolkitServiceObj = ToolkitService::getInstance($db, $usr, $pw);
}
catch (Exception $e) {
    echo  $e->getMessage(), "\n";
    exit();
}
//$ToolkitServiceObj->setToolkitServiceParams(array('InternalKey'=>"/tmp/$user"));
$ToolkitServiceObj->setToolkitServiceParams(array('InternalKey'=>"/tmp/$usr",
    'debug'=>true,
    'plug' => "iPLUG32K"));

$mbr = $_GET['M'];
$fil = $_GET['F'];
$lib = $_GET['L'];
 



$param[] = $ToolkitServiceObj->AddParameterChar('both', 10, 'FIL', 'FIL', trim($fil));
$param[] = $ToolkitServiceObj->AddParameterChar('both', 20, 'MBR', 'MBR', trim($mbr));
$param[] = $ToolkitServiceObj->AddParameterChar('both', 20, 'LIB', 'LIB', trim($mbr));


$result  = $ToolkitServiceObj->PgmCall('CHKSRCCMPR', "ULIB", $param, null, null);
$s = "Select * from jrmatz.srcd where FIL='$fil'";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);
$gem = $row['GEM'];
$hds = $row['HDS'];
$srclen = $row['SRC'];
$srcst = $row['SRCS'];

$s = "SELECT cast(srcdif as char(220) ccsid 37) as SRCDIF FROM JRMATZ.SRCDIF";
$r = db2_exec($con, $s);

while ($row = db2_fetch_assoc($r)){
    $src = $row['SRCDIF'];

    $x['SRCDIF'] = $src;
    $cid = substr($src,0,3);
    
    $bgc = '';
    $tt = '';
    if (trim($cid) == 'D'){
        $bgc = "background-color: coral;";
        $tt = 'Line Removed';
    }
    if (trim($cid) == 'I'){
        $bgc = "background-color: #99ff99;";
        $tt = "Line Inserted";
    }
    if (trim($cid) == 'RN'){
        $bgc = "background-color: #ff80df;";
        $tt = "Gemini Line was Reformated";
    }
    if (trim($cid) == 'RO'){
        $bgc = "background-color: #ff4dd2;";
        $tt = "Harris    Line was Reformated";
    }
    if (trim($cid) == 'IM'){
        $bgc = "background-color: #9999ff;";
        $tt = "Line moved in the Gemini file that also appears in the Harris file";
    }
    if (trim($cid) == 'DM'){
        $bgc = "background-color: #4d4dff;";
        $tt = "Line moved in the Harris file that also appears in the Gemini file";
    }
    
    $gemlin = substr($src, $gem,7);
    $hdslin = substr($src,$hds,7);
    $x['CID'] =  "<div   style='font-family: monospace; $bgc'>$cid</div>";
 // echo  "<div style='font-family: monospace; $bgc'><pre>".$src. "</pre></div>";
    $x['GEMLIN'] =  "<div style='font-family: monospace; $bgc'>".substr($src, $gem,7). "</div>";
        $x['HDSLIN'] = "<div style='font-family: monospace; $bgc'>".substr($src,$hds,7) . "</div>";
        $x['SRC'] = "<div style='font-family: monospace; $bgc'><pre>".substr($src, $srcst,$srclen) . "</pre></div>";
        $x['TOOLTIP'] = $tt;
    
    if (is_numeric($gemlin) || is_numeric($hdslin)){
        
        
    $data['root'][]= $x;
    }
    
}
$data['success'] = true;
echo $_GET['callback'] . '(' . json_encode($data). ')';

// CID, GEMLIN, HDSLIN, SRC