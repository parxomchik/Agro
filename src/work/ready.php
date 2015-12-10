<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 07.12.15
 * Time: 16:38
 */

$config_type= 2;

// в случае авторизации пользователь может быть неавторизированным
if ($_SERVER['REQUEST_URI'] == '/api/login')  {
    $config_type= 1;

}

require_once(dirname(__FILE__)."/../../kernel/web.shm.php");



if (($_SERVER['REQUEST_URI'] == '/api/login') && ($_SERVER['REQUEST_METHOD'] == 'GET')) {

    $app->PageInfo->addObject( new XObjectInfo( 'XMain', '', COMMONLOGIC, 'MainGoods') );
    $app->Run();
    exit();
}


//
if (($_SERVER['REQUEST_URI'] == '/api/login') && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

    $app->PageInfo->addObject( new XObjectInfo( 'XLogin', '', COMMONLOGIC, 'MainGoods') );
    $app->Run();
    exit();

}

//
if (($_SERVER['REQUEST_URI'] == '/api/plat') && ($_SERVER['REQUEST_METHOD'] == 'GET')) {

    $app->PageInfo->addObject( new XObjectInfo( 'XPlat', '', COMMONLOGIC, 'MainGoods') );
    $app->Run();
    exit();

}

?>