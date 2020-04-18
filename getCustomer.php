<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json");

include "../GConB.php";

$cust = $_GET['CUST'];

$s = "Select * from HDCUST where CMCUST = $cust";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);
if (trim($row['CMCNA1']) !== ''){
    $custisvalid = true;
} else {
    $custisvalid = false;
}


$blto = $row['CMBLTO'];
if ($blto !== $cust){
    $s2 = "Select * from hdcust where CMCUST = $blto";
    $r2 = db2_exec($con, $s2);
    $row2 = db2_fetch_assoc($r2);
    $bltoname = $row2['CMCNA1'];
    $bltoissame = false;
} else {
    $bltoname = $row['CMCNA1'];
    $bltoissame = true;
}

$s3 = "Select * from hdcust_ext where CMCUST = $cust and FLD = 'SLSCAT'";
$r3 = db2_exec($con, $s3);
$row3 = db2_fetch_assoc($r3);

if (trim($row3['FLDVAL'])!== ''){
    $salescat = $row3['FLDVAL'];
} else {
    $salescat = '';
}
if ($row['CMMPTY'] == 60){
    $top60 = 'Top 60 Account';
} else {
    $top60 = '';
}

$x['CUSTISVALID'] = $custisvalid;
$x['CMCUST'] = $cust;
$x['CMBLTO'] = $blto;
$x['NAME'] = $row['CMCNA1'];
$x['BILLTOISSAME'] = $bltoissame;
$x['BILTONAME'] = $bltoname;
$x['ADR1'] = $row['CMCNA2'];
$x['ADR2'] = $row['CMCNA3'];
$x['ADR3'] = $row['CMCNA4'];
$x['CITY'] = $row['CMCCTY'];
$x['ST']   = $row['CMST'];
$x['ZIP']  = $row['CMZIP'];
$x['TOP60'] = $top60;
$x['SLSCAT'] = $salescat;
$x['LOC'] = $row['CMLOC#'];
$x['SLP'] = $row['CMSLSM'];
$x['CLS'] = $row['CMCCLS'];
$x['SVIA'] = $row['CMSV'];
$x['TERMS'] = $row['CMCTRM'];
$x['REGN'] = $row['CMCRGN'];
$x['POREQ'] = $row['CMRREF'];
$x['STTAXC'] = $row['CMCTXC'];
$x['CNTYTAXC'] = $row['CMCNTC'];
$x['CITYTAXC'] = $row['CMCTTC'];
$x['LOCTAXC1'] = $row['CMCLOC1'];
$x['LOCTAXC2'] = $row['CMCLOC2'];
$x['LOCTAXC3'] = $row['CMCLOC3'];
$x['WHS'] = $row['CMWH#'];

//CUSTISVALID, CMCUST, CMBLTO, NAME, BILLTOISSAME, BILTONAME, ADR1, ADR2,
//ADR3, CITY, ST, ZIP, TOP60, SLSCAT, LOC, SLP, CLS, SVIA, TERMS, REGN, POREQ, 
//STTAXC, CNTYTAXC, CITYTAXC,LOCTAXC1, LOCTAXC2,LOCTAXC3, WHS 

            