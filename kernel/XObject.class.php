<?php

/**
 * Áàçîâûé îáúåêò îò êîòîðîãî íàñëåäóþòñÿ ëîãèêè
 *
 */
class XObject {

    /**
     * Èìÿ îáúåêòà
     *
     * @var unknown_type
     */
    var $Name;
    /**
     * Òèï îáúåêòà
     *
     * @var unknown_type
     */
    var $Type;

    //Protected attributes
    var $_conn;
    var $_params = array ();

    /**
     * Êîíñòðóêòîð îáúåêòà
     *
     * @param unknown_type $name
     * @param unknown_type $type
     * @return XObject
     */
    function XObject( $name, $type ) {
        $this->Name = $name;
        $this->Type = $type;
    }

    /**
     * Ìåòîä ïîêàçà ëîãèêè (ïåðåîïðåäåëÿåòñÿ â ïîòîìêàõ)
     *
     * @param unknown_type $tmpl
     */
    function Show( $tmpl ) {

        //print "<br> <b>Name:</b> ".$this->Name." <b>Class:</b> ".get_class($this)."</br>";
    }

    // special function for compatinbility
    function Optima()
    {

    }

    /**
     * Enter description here...
     *
     * @param unknown_type $params
     */
    function setParams( &$params ) {
        if ( is_array( $params ) ) {
            $this->_params = $params;
        } else {
            $this->_params = array();
        }
    }



}
?>