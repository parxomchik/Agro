<?php

class XApplyGroupPolicy {

    //Protected attributes
    var $_allow = array();
    var $_deny = array() ;
    var $_groups = array() ;

    //Constructor
    function setGroups( $groups ) {
        if ( !is_array( $groups ) or sizeof( $groups ) ==0) {
            $this->_groups = array( VISITORS_GROUP => 'Visitors' );
        } else {
            $this->_groups = $groups;
        }
    }

    //Public methods
    function setAllow( $groups ) {
        if ( !is_array( $this->_groups ) ) {
            return false;
        } else {
            foreach ($groups as $group ) {
                if ( $key = array_search( $group, $this->_groups) and !in_array( $key, $this->_deny) ) {
                    $this->_allow[] = $key;
                }
            }
            array_unique( $this->_allow );
            return sizeof ( $this->_allow );
        }
    }

    function setDeny( $groups ) {
        if ( !is_array( $this->_groups ) ) {
            return false;
        } else {
            foreach ($groups as $group ) {
                if ( $key = array_search( $group, $this->_groups) ) {
                    $this->_deny[] = $key;
                    if ( $allow = array_search( $key, $this->_allow ) ) {
                        unset( $this->_allow[ $allow ] );
                    }
                }
            }
            array_unique( $this->_deny );
            array_unique( $this->_allow );
            return sizeof ( $this->_deny );
        }
    }

    function getAllow() {
        return $this->_allow;
    }

    function getDeny() {
        return $this->_deny;
    }

}
?>