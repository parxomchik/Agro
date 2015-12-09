<?php

class XObjectInfo {

    //Public attributes
    var $Name;
    var $Class;
    var $Package;
    var $Template;
    var $BlockType;

    //Protected attributes
    var $_available = false;
    var $_checked = false;
    var $_path = '';
    var $_methods = array();
    var $_params = array();

    //Constructor
    /**
     * @param class string
     * @param name string
     * @param package string
     */
    function XObjectInfo( $class, $name = "",
                          $package = "", $tmpl="", $type="" )
    {

        if ( !empty( $class ) )
        {
            $this->Class = $class;

            // Setup class name
            if ( !empty( $name ) )
            {
                $this->Name = $name;
            }
            else
            {
                $this->Name= $this->Class;
            }


            //Setup class package
            if ( !empty( $package ) )
            {
                $this->Package = $package;
            } else {
                $this->Package = DEFAULT_PACKAGE;
            }


            $this->Template = $tmpl;
            $this->BlockType= $type;

        }
        else
        {
            echo "error";
            DebugLogger( "XObjectInfo: Try create object with empty class name" );
            return;
        }
    }

    /**
     * @retrun string
     * @desc Calc and check full path to class. Return null if class don't exists
     */
    function getFullPath() {

        // ïîëó÷åíèå ïîëíîãî ïóòè (îäèí îðàç)
        if ( !empty( $this->_path ) )
        {
            DebugLogger( "XObjectInfo: Just return fullpath" );
            return $this->_path;
        }
        $full_path = PACKAGES_DIR.'/'.str_replace('.','/',$this->Package).'/'.$this->Class.'.class.php';
        if ( file_exists( $full_path ) ) {
            $this->_path = $full_path;
            return $full_path;
        } else {
            $this->_checked = true;
            $this->_available = false;
            return null;
        }
    }

    /**
     * @retrun boolean
     * @desc Check that object class exists, include class file
     */
    // äîñòóïíîñòü êàòàëîãà
    function IsAvailable() {

        if ( $this->_checked ) {
            return $this->_available;
        }
        $this->_checked = true;

        if ( $path = $this->getFullPath() ) {

            //Include class file if it not already included
            if ( !in_array( $path, get_required_files() ) )
            {
                require_once( $path );
            }

            //Check that needly class exists
            if ( !class_exists( str_replace('.','_',$this->Package).'_'.$this->Class ) )
            {
                WriteLog("Class ".$this->Class." from Package ".$this->Package." not found", LOGFILE_LOGIC);
                return $this->_available = false;
            } else {
                $class = str_replace('.','_',$this->Package).'_'.$this->Class;
                $this->_methods = array_flip( get_class_methods( $class ) );
                unset( $this->_methods[ 'xobject' ] );
                unset( $this->_methods[ 'show' ] );
                unset( $this->_methods[ strtolower($class) ] );
            }

        } else {
            WriteLog("File for Class ".$this->Class." from Package ".$this->Package." not found", LOGFILE_LOGIC);
            return $this->_available = false;
        }
        return $this->_available = true;
    }

    /**
     * @retrun XObject
     * @desc Return new Object or NULL if it's unavailable
     */
    function &createObject() {
        if ( $this->IsAvailable() ) {
            $class = str_replace('.','_',$this->Package).'_'.$this->Class;
            $obj = new  $class( $this->Name, $this->BlockType);
            $obj->setParams( $this->_params );
            return $obj;
        } else {
            return null;
        }
    }

    /**
     * @retrun XObject
     * @desc Return new Object or NULL if it's unavailable
     */
    function IsMethod( $method ) {
        if ( $this->IsAvailable() ) {
            return !empty( $this->_methods[ strtolower( $method ) ] );
        } else return false;
    }

    function setParam( $name, $value ) {
        if ( empty( $name  ) ) {
            class_log( "XObjectInfo", "Try set parameter with empty  name in object ".$this->Name, CONFIGPARSE_LOG);
            return false;
        } else {
            $this->_params[ $name ]= $value;
            return true;
        }
    }

    function deleteParam( $name ) {
        if ( empty( $name ) ) {
            class_log( "XObjectInfo", "Try delete parameter with empty name in object ".$this->Name, CONFIGPARSE_LOG);
            return false;
        } else if ( !array_key_exists( $name , $this->params ) ) {
            class_log( "XObjectInfo", "Try delete unexisten parameter ".$name." in object ".$this->Name, CONFIGPARSE_LOG);
            return false;
        } else {
            unset ( $this->_params);
            return false;
        }
    }

    function getParams() {
        return $this->_params;
    }
//End of class
}

?>