<?php
require_once( 'XEntity.class.php' );

class XEntityCache {
    /**
     * ßçûê ìîäåëè
     *
     * @var unknown_type
     */
    var $Lang;

    /**
     * Ñîåäèíåíèå
     *
     * @var ADOConnection
     */
    var $_conn;

    /**
     * Ãëàâíîå ñîåäèíåíèå
     *
     * @var ADOConnection
     */
    var $_main_conn;

    var $_entity = array();

    function XEntityCache( $conn, $mainconn, $lang )
    {
        $this->_conn = $conn;
        $this->_main_conn = $mainconn;
        $this->Lang = $lang;
    }

    function &getEntity( $class, $key, $params = array() , $mode = READ_MODE, $package = DEFAULT_PACKAGE )
    {
        // ñìîòðèì ðåæèì ñêà÷èâàíèÿ (ìîæåò èñïîëüçîâàòüñÿ ïîòîì äëÿ ïðàâ)
        $mode = intval( $mode );
        if ( !$mode )
        {
            WriteLog('Invalid mode of entity '.$class.' in mode '.$mode, LOGFILE_ENITY );
            return;
        }

        // èùåì ñóùíîñòü â êåøå (äëÿ óáûñòðåíèÿ è îáñåïå÷åíèÿ ïîâòîðíîãî ÷òåíèÿ)
        $hash = md5 ( $package.$class.$mode.serialize( $params ).serialize( $key )  );
        if ( !empty( $this->_entity[ $hash ] ) )
        {
            return $this->_entity[ $hash ] [ 'entity' ];
        } else
        { //Try build new entity
            if ( empty ( $class ) ) {
                return null;
            }

            if ( empty ( $package ) ) {
                $package = DEFAULT_PACKAGE;
            }
            $class .= "Entity";

            $full_path = PACKAGES_DIR.'/'.str_replace('.','/',$package).'/'.$class.'.class.php';
            if ( file_exists( $full_path ) )
            {
                if ( !in_array( $full_path, get_required_files() ) ) {
                    require_once( $full_path );
                }

                //Check that needly class exists
                if ( !class_exists( str_replace('.','_',$package).'_'.$class ) ) {
                    class_log( 'XEntityCache', 'Class '.$class.'  not found ', ENTITYCACHE_LOG );
                    return null;
                } else {
                    $full_class = str_replace('.','_',$package).'_'.$class;
                    $methods = array_flip( get_class_methods( $full_class ) );
                    //If called class not derived from entity return null
                    if ( !in_array( 'xentity' , $methods ) ) {
                        return null;
                    }
                }
                return $this->_createEntity( $full_class, $key, $params, $mode, $hash );
            } else {
                $sth = null;
                return $sth;
            }
        }
    }

    function callSave( $hash ) {
        if ( array_key_exists( $hash, $this->_entity ) ) {
            if ( $this->_entity[ $hash ][ 'mode' ] & (CHANGE_MODE | NEW_MODE) ) {
                return $this->_entity[ $hash ][ 'entity' ]->Save();
            }
        }
        return false;
    }

    function callDelete( $hash ) {
        if ( array_key_exists( $hash, $this->_entity ) )
        {
            if ( $this->_entity[ $hash ][ 'mode' ] & DELETE_MODE ) {
                return $this->_entity[ $hash ][ 'entity' ]->Delete();
            }
        }
        return false;
    }

    function callDeleteImage( $hash ) {
        if ( array_key_exists( $hash, $this->_entity ) ) {
            if ( $this->_entity[ $hash ][ 'mode' ] & DELETE_MODE ) {
                return $this->_entity[ $hash ][ 'entity' ]->DeleteImage();
            }
        }
        return false;
    }

    function &_createEntity ( $class, $key, $params, $mode, $hash ) {
        global $app; //Get link to framework
        $lang = $app->User->Lang;
        if ( empty( $lang ) ) {
            $lang = $this->Lang;
        }
        $obj = new $class( $this->_conn, $this->_main_conn, $lang, $hash );
        $this->_entity[ $hash ][ 'class' ] = $class;
        $this->_entity[ $hash ][ 'mode' ] = $mode;
        $this->_entity[ $hash ][ 'params' ] = $params;
        $this->_entity[ $hash ][ 'entity' ] = &$obj;
        $obj->Load( $key, $params, $mode );
        return $obj;

    }

}
?>