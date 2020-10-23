<?php

// This is the database connection configuration.
return array(
    'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
    'connectionString' => 'mysql:host=localhost;dbname=oviklo_wms',
    #'connectionString' => 'mysql:host=localhost;dbname=oviklo_wms',
    'emulatePrepare' => true,
    #'username' => 'oviklo_wms',
    'username' => 'root',
    #'password' => 'Y[Qm83Fh$pCr',
    'password' => '',
    'charset' => 'utf8',
);
