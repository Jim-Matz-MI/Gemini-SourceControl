<?php
 
/* GConB - Get Connection
 * Setups up connection string for the production environment including the library list used.
 *
 */
date_default_timezone_set('America/Chicago');


    $user = 'WEBOE';
    $con = db2_connect("*LOCAL", "$user", "$user");
    
    //var_dump($con);
 