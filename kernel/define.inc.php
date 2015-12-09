<?php
// ========================================================
//  INTERGRATION SETTINGS
// ========================================================

define( 'KERNEL_PATH', dirname(__FILE__).'' );

//FrameWork work modes.
define( 'NORMAL_WORK', 0 );
define( 'XML_DEBUG', 1 );
define( 'XSLT_DEBUG', 2 );
define( 'ROBOTWORK', 3 );

define('COMMONLOGIC', 'logic');
define('COMMONENTITY', 'entity');

//Entity modes
define( 'READ_MODE', 1 );
define( 'CHANGE_MODE', 2 );
define( 'DELETE_MODE', 4 );
define( 'NEW_MODE', 8 );

//
function SendUnauthorized()
{
    header('HTTP/1.1 401 Unauthorized', true, 401);
    //
    echo json_encode(
        array(
            'status'   => 'ERROR',
            'messages' => 'Ваш IP адрес '.$_SERVER['REMOTE_ADDR'].' не разрешен'
        )
    );

    exit();
}

define('ADMINLOGIC', 'adminlogic');

define( 'BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].'/' );
define( 'SAFEBASE_URL', 'http://'.$_SERVER['SERVER_NAME'] );
define('ITEMPERPAGE', 30);
define( 'PACKAGES_DIR', dirname(__FILE__).'' );
define( 'ENTITY_PATH', dirname(__FILE__).'' );
define( 'GALLERYIMAGEPATH', dirname(__FILE__).'/../images/gallery' );
define( 'GOODIMAGEPATH', dirname(__FILE__).'/../images/good' );
define( 'MODELIMAGEPATH', dirname(__FILE__).'/../images/model' );
define( 'IMPORTPATH', dirname(__FILE__).'/../images/import' );
define( 'WATERMARK', dirname(__FILE__).'/../images/logo.jpg' );

define('LANGS', 'ru_ua');
//
define( 'LOGFILE_STAT', dirname(__FILE__).'/../log/stat.log');
define( 'LOGFILE_XSLT', dirname(__FILE__).'/../log/xslt-error.log');
define( 'LOGFILE_GENERAL', dirname(__FILE__).'/../log/general-error.log');
define( 'LOGFILE_LOGIC', dirname(__FILE__).'/../log/logic-error.log');
define( 'LOGFILE_ENITY', dirname(__FILE__).'/../log/entity-error.log');

/*
// ========================================================
//  Application settings
// ========================================================
define( 'CANRULEOPTIMA', 1);
define( 'CREDITLEVEL_PRICE', 300);

// ========================================================
//  Global settings
// ========================================================

// àäìèíñêàÿ ëîãèêà

define( 'KERNEL_TMPL_PATH', dirname(__FILE__).'/../template' );
define('ADMIN_TMPL_PATH', dirname(__FILE__).'/../admin_template' );

define( 'KAPTCHA_PATH', dirname(__FILE__).'/../../common/kaptcha' );
define( 'HTMLHEAD', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">' );

//define( 'HTMLHEAD', '<html>' );

if (!in_array($config_type, array(3,4)))
{
    define('BASE_TMPL_PATH', dirname(__FILE__).'/../template' );
}
else
{
    define('BASE_TMPL_PATH', dirname(__FILE__).'/../admin_template' );
}

define('COMMON_TMPL_PATH', dirname(__FILE__).'/../../kernel/template' );
define('DEVELOPERLOG_PATH', dirname(__FILE__).'/../www' );
define('BLOCK_PATH', dirname(__FILE__).'/../block' );
define('SITEENCODING', 'utf-8' );

define( 'EMAILSENDER' , 'info@xzone.com.ua');


// ========================================================
//  Site settings
// ========================================================
define('MAINITEMS', 12);

// ========================================================
//  Ðàçðåøàòü ïðîäàâàòü íå çàðåãëåííûì
// ========================================================
define('ALLOWADDUNREGISTER', 1);


// ====================================================
//   Ïóòü ê õðàíèëèùó áàííåðîâ
// ====================================================
define('BANNER_PATH', dirname(__FILE__).'/../www/banners' );
// ====================================================
//   íàçâàíèå äëÿ àäìèíêè
// ====================================================
define('ADMINTITLE', 'Ïàíåëü óïðàâëåíèÿ ñàéòîì ìàãàçèíà' );

// ====================================================
//   Ïîêàçûâàòü âñå òîâàðû
// ====================================================
// define('LOCAL_SHOWALLGOODS', 1);
// ====================================================
//   Ðàçðåøàòü ïðîâîäèòü çàêàç ïî âñåì òîâàðàì
// ====================================================
// define('LOCAL_NOCHECKAVAILABLE', 1);
*/

?>
