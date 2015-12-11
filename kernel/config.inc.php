<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 07.12.15
 * Time: 17:15
 */

 // 2 ðåæèìà - æåñòêèé è ìÿãêèé
 //error_reporting( E_ALL ^ E_NOTICE );
error_reporting( E_ALL ^ E_DEPRECATED ^ E_STRICT  );

// load settings
require_once(dirname(__FILE__).'/define.inc.php');

// LOAD common classes
require_once( KERNEL_PATH.'/adodb/adodb.inc.php');
// require_once( KERNEL_PATH.'/global.inc.php' );
require_once( KERNEL_PATH.'/debug.inc.php' );
require_once( KERNEL_PATH.'/XFramework.class.php' );

session_start();


// ================================================================
//  Set protection mode ... ?! or in kernel ?!
// ================================================================
if (!empty($_SESSION["User"]))
{
    $us = $_SESSION["User"];
    //print_r($us);
    // echo "<hr>".$config_type;
    if (
    (($config_type == 4) && (!$us->IsAdmin()))
    )
    {
        SendUnauthorized();
    }
    $config_type = $us->UserTypeID;
}
else
{

    if (in_array($config_type, array(4)))
    {
        SendUnauthorized();
    }
}

// ================================================================
//  Set protection mode ... ?! or in kernel ?!
// ================================================================

$dbms = 'postgres7';
$dbms = 'mysql';
$db_host = 'localhost';
$dbname = 'agro';
$dbuser = 'agro';
$dbpasswd = 'agro';

$conn = NewADOConnection( $dbms );
$conn->Connect( $db_host, $dbuser, $dbpasswd, $dbname );

$conn->fetchMode = ADODB_FETCH_ASSOC;

//$conn->debug=true;
// $conn->Execute("SET NAMES 'UTF8'");
// $conn->debug=false;
/**/
$main_conn = $conn;

//Create Application
$app = new XFrameWork( $conn, $main_conn, NORMAL_WORK );
// $app->MakeStat();
//echo LANGS;

///$app = new XFrameWork( &$conn, XML_DEBUG );
//$app = new XFrameWork( &$conn, XSLT_DEBUG );

// TODO: defence all items
if (!isset($nostat))
{
    $app->MakeStat();
}



?>