<?php

return [
    'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=localhost;dbname=mds_demo',
    'dsn' => 'mysql:host=localhost;dbname=thongke',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

// return [
//     'class' => 'yii\db\Connection' ,
//     'dsn' => 'oci:dbname=//10.51.188.23:1521/OMCQNM', // Oracle
//     'username' => 'OMC_NEW',
//     'password' => 'omcdata#1',
//     'charset' => 'utf8' ,
// ];