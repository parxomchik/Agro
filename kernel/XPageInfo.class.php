<?php
//Special defines
define( 'PREACTION', 1 );
define( 'POSTACTION', 2 );


class XPageInfo extends XApplyGroupPolicy {

    //Public attributes
    var $Redirect;
    var $IsImage = false;

    //Protected attributes
    var $_objects = array();
    var $_actions = array();
    var $_preactions = array();
    var $_postactions = array();

    var $_templates = array();
    var $_xslt =false;
    var $_usecache = false;
    //Constructor
    function XPageInfo() {
        //Add funcs templates
    }

    //Public methods

    // äîáàâëåíèå îáúåêòà
    function addObject( &$object )
    {
        // åñëè îáúåêòà íåò, òî âñå
        if ( empty( $object ) )
            return false;


        // ìîæíî äîáàâëÿòü òîëüêî áàçîâûé îáúåêò
        // PATCH - ïî÷åìó-òî ÐÍÐ5 äàâàëî îøèáêó íà ðîâíîì ìåñòå
        if (  strtolower(get_class( $object)) != strtolower("XObjectInfo")  )
        {
            echo get_class( $object)."<hr/>";
            return false;
        }


        // äîáàâëÿåì â ìàññèâ îáúåêòîâ   äàííûé îáúåêò
        $this->_objects[ $object->Name ] = &$object;

        // äëÿ îòëàäêè
        // echo $object->Name."<hr/>";
        return true;
    }

    function createObject( $name ) {
        if ( empty( $name ) ) return null;
        if ( !empty( $this->_objects[ $name ] ) ) {
            return $this->_objects[ $name ]->createObject();
        } else return  null;
    }

    function getObjectNames() {
        $result = array();
        if ( !is_array( $this->_objects ) ) return $result;
        foreach ($this->_objects as $key => $val ) {
            if ( $this->_objects[ $key ]->IsAvailable() ) {
                $result[] = $key;
            }
        }
        return $result;
    }

    function addAction( &$action ) {

        if ( empty( $action ) ) return false;

        // PATCH
        if (  strtolower(get_class( $action)) != strtolower("XActionInfo")  )
            return false;



        //Check objects in steps
        $action->setGroups( $this->_groups );
        $action->First();
        while( $next = $action->Next() )
        {
            if ( empty( $this->_objects[ $next->Object ] ) ) {
                //Object unavailable
                $action->Delete();
            }
            /*
            else if ( !$this->_objects[ $next->Object ]->IsMethod( $next->Method ) ){
            // } else if ( !isset($this->_objects[ $next->Object ]->_methods[ $next->Method ]) ){
            //print_r($next);
            print_r($this->_objects[ $next->Object ]);

            //Method unavailable
            echo "here2";
            $action->Delete();


            }
            */
        }


        //Add action to Actions list
        $this->_actions[ $action->Name ] = &$action;
        return true;
    }

    // óñòàíîâêà ïàðàìåòðîâ îáúåêòó
    function setParamToObject( $object, $name, $value ) {
        if ( empty( $object ) )
        {
            // class_log( "XPageInfo", "Try set param to object with empty name", CONFIGPARSE_LOG );
            return false;
        } else if ( !array_key_exists( $object, $this->_objects) )
        {

            // class_log( "XPageInfo", "Try set param to unexisten object ".$object, CONFIGPARSE_LOG );
            return false;
        } else {
            return $this->_objects[ $object ]->setParam( $name, $value );
        }
    }

    function deleteParamFromObject( $object, $name, $value ) {
        if ( empty( $object ) ) {
            //                        class_log( "XPageInfo", "Try delete param from object with empty name", CONFIGPARSE_LOG );
            return false;
        } else if ( !array_key_exists( $object, $this->_objects) ) {
            // class_log( "XPageInfo", "Try delete param from unexisten object ".$object, CONFIGPARSE_LOG );
            return false;
        } else {
            return $this->_objects[ $object ]->deleteParam( $name, $value );
        }
    }

    function addPreAction( $name ) {
        return $this->_addSpecAction( $name, PREACTION );
    }

    function addPostAction( $name ) {
        return $this->_addSpecAction( $name, POSTACTION );
    }

    function getPreSteps( $groups ) {
        return $this->_getRunSteps( $groups, PREACTION );
    }

    function getPostSteps( $groups ) {
        return $this->_getRunSteps( $groups, POSTACTION );
    }

    function getActionSteps( $name, $groups ) {
        return $this->_getRunSteps( $groups, false, $name );
    }

    function addTemplate( &$template )
    {
        global $app;
        $lang = $app->User->Lang;
        if ( empty( $template ) ) return false;

        // PATCH PHP5
        if (  strtolower(get_class( $template)) != strtolower("XTemplateInfo")  )
            return false;
        $template->Lang = $lang;
        switch ( $template->Type) {
            case MAIN_TMPL:
            case MAINADMIN_TMPL:
            case MAINCOMMON_TMPL:
                $this->_templates[ 'main' ][ $template->Name ] = &$template;
                break;
            case BLOCK_TMPL:
                $this->_templates[ 'block' ][ $template->Name ] = &$template;
                break;
            case FORM_TMPL:
                $this->_templates[ 'form' ][ $template->Name ] = &$template;
                break;
            case SUB_TMPL:
                $this->_templates[ 'sub' ][ $template->Name ] = &$template;
                break;
            case STANDART_TMPL:
            case COMMON_TMPL:
            case ADMIN_TMPL:
                //default:
                $this->_templates[ 'standart' ][ $template->Name ] = &$template;
                break;
        }

        return true;
    }



    function setCache( $flag = true ) {
        $this->_usecache = (bool)$flag;
    }

    function getCache() {
        return  $this->_usecache;
    }

    //Protected methods
    function _addSpecAction( $name, $mode = PREACTION ) {
        if ( !empty( $this->_actions[ $name ] ) ) {
            switch ( $mode ) {
                case PREACTION: $this->_preactions[] = $name; break;
                case POSTACTION: $this->_postactions[] = $name; break;
            }
        }
    }

    function _getRunSteps( $groups, $mode, $name = "" ) {
        $result = array();

        // echo "here";
        if ( empty( $name ) )
        { //Can't be only spesial step
            $arr = array();
            switch ( $mode ) {
                case PREACTION:
                    $arr = &$this->_preactions;
                    break;
                case POSTACTION:
                    $arr = &$this->_postactions;
                    break;
            }
        } else {
            $arr[] = $name;
        }
        $num = sizeof( $arr);
        for ( $i=0; $i<$num; $i++ ) {
            if ( !empty($this->_actions[ $arr[ $i ] ]) )
            {
                if ( !$this->_checkGroupPolicy( $this->_actions[ $arr[ $i ] ]->getAllow(),
                    $this->_actions[ $arr[ $i ] ]->getDeny(), $groups ) )
                    return $result;
                $this->_actions[ $arr[ $i ] ]->First();
                while( $next = $this->_actions[ $arr[ $i ] ]->Next() )
                {
                    $result[] = $next;
                }
            }
        }
        return $result;
    }

    function _makeXSLTInclude( &$main_xsl )
    {
        $xpath = xpath_new_context( $main_xsl );
        $res = xpath_eval( $xpath,"//include" );
        $num = sizeof( $res->nodeset );
        $needReCall = false;

        for ( $i=0; $i<$num; $i++ )
        {
            $type = $res->nodeset[$i]->get_attribute("type");

            if ( $type != 'subtemplate' )
            {
                /*
                $dom = new DomDocument("1.0");
                $node = $dom->createElement("xsl:call-template", "");
                /*
                = new DomElement( "xsl:call-template", "",
                "http://www.w3.org/1999/XSL/Transform");
                // $dom->appendChild($node);

                $node->setAttribute( "name", "InsertBlock"  );
                $param = $dom->createElement("xsl:with-param");
                $node->appendChild($param);
                */
                // ñîçäàåì ýëåìåíò
                // $dom =  domxml_new_doc("1.0");
                $dom =   $this->_xslt ;
                $node = $this->_xslt->create_element("xsl:call-template", "");
                $node->set_attribute( "name", "InsertBlock"  );

                //
                // $param = $dom->createElement("xsl:with-param", "");
                // $param = $node->appendChild($param);
                $param = $node->new_child("xsl:with-param");


                if ( empty( $type ) || $type == 'default' )
                {
                    $param->set_content($res->nodeset[$i]->get_attribute("object")) ;
                }
                else if ( $type == 'condition' )
                {
                    $childs = $res->nodeset[$i]->child_nodes();
                    //print_r( $childs );
                    if ( is_array( $childs ) ) {
                        $cnum = sizeof( $childs );
                        for ( $j=0; $j<$cnum; $j++ ) {
                            $clone = $childs[$j]->clone_node(true);
                            //$clone->set_namespace( $childs[$j]prefix();
                            $param->append_child( $clone );
                        }
                        //foreach ( $childs as $child ) {
                        //}
                    }
                }
                $param->set_attribute( "name", "id" );
                $res->nodeset[$i]->replace_node( $node );
            }
            // if ( $type != 'subtemplate' )
            // òî åñòü åñëè ýòî ïîäøàáëîí
            else
            {
                $name = $res->nodeset[$i]->get_attribute("name");
                if ( isset($this->_templates['sub']) && array_key_exists( $name, $this->_templates[ 'sub' ] ) )
                {
                    $nodes = $this->_templates[ 'sub' ][ $name ]->getText();
                    if ( is_array( $nodes ) )
                    {
                        $nnum = sizeof( $nodes );
                        for ( $j=0; $j <$nnum; $j++ )
                        {
                            //print_r( $nodes[$j] );
                            // TODO: analyze it nafig

                            // echo $res->nodeset[$i]->get_content();
                            //print_r($res->nodeset[$i]);
                            /*

                            echo $j."of $nnum vs $i<hr/>";
                            print_r($nodes[$j]);
                            echo "<hr/>";
                            print_r($res->nodeset[$i]);
                            echo "<hr/>";
                            print_r($res->nodeset[$i]);
                            echo "<hr/>";
                            */
                            // echo $res->nodeset[$i]->get_content();

                            // $res->nodeset[$i]->insert_before( $nodes[$j], $res->nodeset[$i]);
                            $res->nodeset[$i]->append_sibling( $nodes[$j]);
                            // if (in_array($))
                            /*
                            if ($res->nodeset[$i]->get_content() != "")
                            $res->nodeset[$i]->insert_before( $nodes[$i], $res->nodeset[$i] );
                            else
                            $res->nodeset[$i]->append_child( $nodes[$j], $res->nodeset[$i]  );
                            */
                            /*
                            if ( $nodes[$j]->type == 1 ) {
                            $this->_makeXSLTInclude( $nodes[$j] );
                            }*/
                        }
                    }
                }
                $p = $res->nodeset[$i]->parent_node();
                //print_r( $p );
                $p->remove_child( $res->nodeset[$i] );
                $needReCall =true;

            }
        }
        /**/
        if ( $needReCall ) {
            $this->_makeXSLTInclude( $main_xsl );
        }
        /**/
    }

    function _addNodes( &$node, &$nodes ) {
        if ( !is_array( $nodes ) ) return;
        $num = sizeof( $nodes );
        for ( $i = 0; $i<$num; $i++ ) {
            $node->append_child( $nodes[$i] );
        }
    }

    function _checkGroupPolicy( $allow, $deny, $groups ) {
        return true;
        /*
        if ( array_intersect( $deny, $groups ) ) {
        return false;
        } else if ( array_intersect( $allow, $groups ) ) {
        return  true;
        }
        return false;
        */
    }

}
?>