<?php
//
//   data/time,
//    ip
//
//
//
//Debug levels define
define( 'CONFIGPARSE_LOG', 1 );
define( 'XSLTBUILD_LOG', 2 );
define( 'USER_LOG', 4 );
define( 'ENTITYCACHE_LOG', 8 );
define( 'ACTIONS_LOG', 16 );
define( 'PERFOMANCE_LOG', 32 );

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    $message = "";
    switch ($errno) {
        case FATAL:
            $message = "  Fatal error in line ".$errline." of file ".$errfile."";
            break;
        case ERROR:
            $message = "<b>ERROR</b> [$errno] $errstr in line ".$errline." of file ".$errfile."";
            break;
        case WARNING:
            $message = "<b>WARNING</b> [$errno] $errstr in line ".$errline." of file ".$errfile."";
            break;
        default:
            $message = "Unkown error type: [$errno] $errstr in line ".$errline." of file ".$errfile."";
            break;
    }

    WriteLog($message, LOGFILE_GENERAL);
}

function WriteLog($message, $file)
{
    if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
    {
        $fp = fopen($file, "a");
        fwrite($fp, strftime("<%D:%M %H:%M:%S>"). "\n");
        fwrite($fp, $message. "\n");
        fclose($fp);
    }
}

//
//
function create_string(){
    $field_list = array("Date", "Page");
    $allow_list = array("1", "1");

    $element_list = array();
    for ($i=0; $i<sizeof($field_list); $i++){
        if ($allow_list[$i] == "1")
            $element_list[] = get_element($i);
    }
    return join("\t", $element_list);
}

function get_element($type){
    $value = "";
    switch ($type) {
        case '0':
            $value = strftime("<%D:%M %H:%M:%S>");
            break;
        case '1':
            $value = $_SERVER['REQUEST_URI'];
            break;
        case '2':
            $value = $_SERVER['REMOTE_ADDR'];
            break;
        default:
            ;
    }
    return $value;
}

function DebugLogger( $message ) {
    //print $message;
//        error_log($message,1);
}
?>