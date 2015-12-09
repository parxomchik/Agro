<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 07.12.15
 * Time: 17:13
 */



class XEntity {

    var $Key;
    var $mode;
    var $Lang;
    var $Hash;//Hash of XEntity Parametras
    var $Loaded = 0;

    /**
     * Enter description here...
     *
     * @var ADOConnection
     */
    var $_conn; //Database connection

    /**
     * Ãëàâíîå ñîåäèíåíèå
     *
     * @var ADOConnection
     */
    var $_main_conn;

    function XEntity( $conn, $mainconn, $lang, $hash ) {
        $this->_conn = $conn;
        $this->_main_conn = $mainconn;
        $this->Lang = $lang;
        $this->Hash = $hash;
    }

    //Prototype of Entity Load function
    function Load( $key, $params = array(), $mode = READ_MODE ) {
        return;
    }

    function Save( $mode = READ_MODE ) {
        return;
    }

    function Delete ( $params=array() ) {
        return;
    }

    function DeleteImage ( $params=array() ) {
        return;
    }
}
?>