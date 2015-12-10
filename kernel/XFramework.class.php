<?php

header("Expires: 0");

require_once( 'XUtils.class.php' );
// require_once( 'XStepInfo.class.php' );
require_once( 'XApplyGroupPolicy.class.php' );
// require_once( 'XActionInfo.class.php' );
require_once( 'XObjectInfo.class.php' );
require_once( 'XObject.class.php' );
// require_once( 'XTemplateInfo.class.php' );
require_once( 'XPageInfo.class.php' );
require_once( 'XEntityCache.class.php' );
require_once( 'XUser.class.php' );
// require_once( 'XCache.class.php' );
// require_once( 'XForm.class.php' );


/**
 * Ãëàâíîå ÿäðî ñèñòåìû
 *
 */
class XFrameWork
{

    /**
     * Âíóòðåííèé íîìåð ñåññèè
     *
     * @var unknown_type
     */
    private $_session_id = 0 ;

    /**
     * Ìàññèâ ìåòîê
     *
     * @var unknown_type
     */
    private $_timeMarks = array();
    /**
     * Îáúåêò íàêîïëåíèÿ ëîãèêàìè
     *
     * @var XPageInfo
     */
    var $PageInfo;

    /**
     * Îáúåêò ïîëüçîâàòåëÿ
     *
     * @var XUser
     */
    var $User =  null;

    /**
     * Êåø ñóùíîñòåé
     *
     * @var XEntityCache
     */
    var $EntityCache;


    var $Request = array();

    var $Dictionary;

    /**
     * Enter description here...
     *
     * @var ADOConnection
     */
    var $_conn;
    /**
     * Enter description here...
     *
     * @var ADOConnection
     */
    var $_main_conn;

    //Protected attributes
    var $_objects = array();
    var $_tree;
    var $_mode;
    var $_action;
    var $_forms;
    var $_startTime;
    var $_messages = array();
    var $_vars = array();
    var $DefaultCurrency;
    var $DefaultCurrencySign;
    var $DefaultCurrencyRate;
    var $Langs = array("en", "ru", "ua");
    var $Language = "ua";

    var $_page = 0;

    // ñïèñîê ÿäðà
    var $KernelInfo = array();

    //Constructor
    var $_root;
    var $TotalStart = 0;

    /*
     * Áëîê ê êîòîðîìó ïðèêðó÷èâàòü îïòèìèçàöèþ
     */
    var $OptimaBlock = "maingoods";
    /*
     * Áëîê ê êîòîðîìó ïîñûëàòü îïòèìèçàöèþ
     */
    var $OptimaTarget = "optimizer";
    /*
     * ïðàâèëî îïòèìèçàöèè
     * @var kernel_entity_XOptimaRuleEntity
     */
    var $CurrentOptimaRule = null;

    var $LastAddedSubitemID = 0;

    // ñïèñîê ôàéëîâûõ ìåíåäæåðîâ
    var $FileManagers = array();

    //
    //
    //
    function XFrameWork( &$conn, &$main_conn, $mode = NORMAL_WORK )
    {
        $this->SetTimeMark('start');

        // óñòàíàâàëèàåì ñîåäèíåíèå
        $this->_conn = &$conn;
        $this->_main_conn = &$main_conn;

        // ñîçäàåì ãðóïïû
        $groups= array();
        $groups[1] = "visitors";
        $groups[2] = "clients";
        // èíèöèàëèçèðóåì îáúåêòû
        $this->PageInfo = new XPageInfo( );
        $this->PageInfo->setGroups( $groups );
        $this->EntityCache = new XEntityCache( $this->_conn, $this->_main_conn, 'en' );

            //Prepare XML tree for work
            $this->_tree = array();

            //Init user
            if ( !empty( $_SESSION[ 'User' ] ) ) {
                $this->User = &$_SESSION[ 'User' ];
            } else {
                $this->User = new XUser();
                $_SESSION[ 'User'] = &$this->User;
                $this->User->setConn( $this->_conn );

            }

        // снятие статистики
        $this->MarkAttendance();
            // óñòàíîâèòü ðåæèìû ïî íàñòðîéêàì
            // $this->SetModes();


        // $this->Dictionary = new XDictionary( $this->User->Lang, $this->_conn );
    }

    // óñòàíîâêà ðåæèìîâ
    function SetModes()
    {
    }




    /**
     * Ïðîâåðÿåì íà íàëè÷èå ïîâòîðà â ëîãèíå
     *
     */
    function CheckLoginExists($newusername)
    {
        $query = "select count(*) as cnt
        	            from client
        	          where username='".$this->ProtectString($newusername)."'
        	            and client_id <> ".intval($this->User->UserID)."
        	          ";
        $res = $this->_main_conn->Execute($query);
        $row = $res->FetchRow();
        return $row['cnt'];
    }

    /**
     * Ïðîâåðÿåì íà íàëè÷èå ïîâòîðà â ëîãèíå
     *
     */
    function CheckEmailExists($newemail)
    {
        $query = "select count(*) as cnt
        	            from client
        	          where email='".$this->ProtectString($newemail)."'
        	            and client_id <> ".intval($this->User->UserID)."
        	          ";
        $res = $this->_main_conn->Execute($query);
        $row = $res->FetchRow();
        return $row['cnt'];
    }

    //Public methods
    function Run( $mymode = "usual" )
    {
        /*
        // âêëþ÷åíèå ïðàâ íà ïðîñìîòð òîé èëè èíîé ñòðàíèöû
        if ( !$this->_checkGroupPolicy( $this->PageInfo->getAllow(), $this->PageInfo->getDeny() ) )
        {
           // TODO: óáðàòü ýòî
           echo "íåäîñòóïíîñòü ñòðàíèöû èç-çà ïðàâ";
           header( "Location: ".$this->PageInfo->Redirect );
           exit;
        }
        */

        // Создание объектов
        $this->_createObjects();

        // Отработка действий заранее
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            if ( !empty( $_GET[ 'ACTION' ] ) ) {
                $this->_action=trim( $_GET['ACTION'] );
            } else {
                $this->_action = null;
            }
            $this->Request = &$_GET;
        } else {
            if ( !empty( $_POST[ 'ACTION' ] ) ) {
                $this->_action=trim( $_POST['ACTION'] );
            } else {
                $this->_action = null;
            }
            $this->Request = &$_POST;
        }
        /*
        // TODO: получение ключей для UTF8
        if ((SITEENCODING != 'utf-8' ) && (!defined('ADMINPANEL')))
        {
            foreach ($this->Request as $key=>$value)
            {
                if (is_array($value)) continue;

                $this->Request[$key] = iconv(SITEENCODING, "utf-8", $value);

            }
        }
        */

        //print_r( $this->Request );
        // unset($this->Request[ 'ACTION' ]);

        //Run pre actions
        $steps = $this->PageInfo->getPreSteps( $this->User->getGroups() );
        // print_r($steps);
        $this->_runSteps( $steps );

        if ( !empty( $this->_action ) ) {
            $steps = $this->PageInfo->getActionSteps( $this->_action,$this->User->getGroups() );
            $this->_runSteps( $steps );
        }

        //Run post actions
        $steps = $this->PageInfo->getPostSteps( $this->User->getGroups() );
        $this->_runSteps( $steps );

        $this->_callShow();

        $this->_showOutput( $mymode );
        $tt = preg_split( "/\ /", microtime());
        $workTime = intval( $tt[1] ) + doubleval( $tt[0] ) - $this->_startTime;

        WriteLog("Work time - ". $workTime ."s", LOGFILE_STAT);
    }

    //Get form
    function &getForm( $name, $method = "POST", $page="/", $action="" ) {
        if ( !empty( $this->_forms[ $name] ) ) {
            return $this->_forms[ $name];
        }
        else if ( empty( $name ) ) {
            return null;
        } else {
            $form = new XForm( $name, $method, $page, $action );
            $this->_forms[ $name] = &$form;
            return $this->_forms[ $name];
        }
    }

    /* Äëÿ ìóëüòè ÿçûêîâîé âåðñèè åñëè áóäåò àïãðåéä */
    function setLang( $lang )
    {
        $this->User->setLang($lang);
        unset( $this->Dictionary );
        $this->Dictionary = new XDictionary( $this->User->Lang, $this->_conn );
    }


    // îïåðàöèÿ ëîãà
    function sendMessage( $object, $name, $message )
    {
        if ( empty($object) ) {
            // class_log("XFrameWork", "Try send message to null object",ACTIONS_LOG);
            echo "Try send message to null object";
            return false;
        }
        if ( !array_key_exists( $object, $this->_objects ) ) {
            // class_log("XFrameWork", "Try send message to unexisting object",ACTIONS_LOG);
            echo "Try send message to unexisting object";
            return false;
        }
        if ( empty($name) || empty($message) ) {
            // class_log("XFrameWork", "Invalid message format", ACTIONS_LOG);
            return false;
        }
        $this->_messages[$object][] = array( 'name'=>$name, 'message'=>$message );
    }

    function getMessages( $object ) {
        if ( array_key_exists($object, $this->_objects) && array_key_exists($object, $this->_messages)) {
            return $this->_messages[$object];
        } else {
            return null;
        }
    }

    function getVar( $name ) {
        if ( isset( $this->_vars[$name] ) ) {
            //TODO: Add code for List boxes
            return $this->_vars[$name]['value'];
        }
    }
    //Protected methods
    function _createObjects() {
        //Create all available objects
        $names = $this->PageInfo->getObjectNames();
        $num = sizeof ($names);
        for ( $i=0; $i<$num; $i++ ) {
            $obj = $this->PageInfo->createObject( $names[$i] );
            $this->_objects[ $obj->Name ] = $obj;
        }
        unset( $names );
    }

    function _callShow()
    {
        //Call show method for all available objects
        foreach ( $this->_objects as $obj )
        {
            if ($obj->Type != "")
            {
                $this->_tree['data'][$obj->Type] = $obj->Show( "" );
            }
            else
            {
                $this->_tree['data'] = $obj->Show( "" );
            }
        }
    }

    function _runSteps( $steps ) {
        if ( !is_array( $steps ) or sizeof( $steps ) < 1 ) return;
        foreach ($steps as $step ) {
            if ( strtolower(get_class($step)) == 'xstepinfo' ) {
                $method = $step->Method;
                $this->_objects[ $step->Object ]->$method();
            }
        }
    }

    // ïîäíèìàåì ïåðâóþ áóêâó
    function FirstUpper($str)
    {
        return strtoupper(substr($str, 0,1)).substr($str,1);
    }

    function _showOutput( $mymode = "usual" )
    {
        $time_start = microtime(true);

        $this->_tree['auth'] = $this->User->isLogged();
        $this->_tree['info'] = 'ok';

        header("Content-type: application/json");
        echo json_encode($this->_tree);

    }

    function _checkGroupPolicy( $allow, $deny ) {
        if ( array_intersect( $deny, $this->User->getGroups() ) ) {
            return false;
        } else if ( array_intersect( $allow, $this->User->getGroups() ) ) {
            return  true;
        }
        return false;
    }


    function MakePrice($a)
    {
        return str_replace(".", ",", sprintf("%01.2f", $a));
    }

    function SiteEncode($txt)
    {
        return iconv("windows-1251", "utf-8", $txt);
    }
    function SiteDecode($txt)
    {
        return iconv("utf-8", "windows-1251", $txt);
    }

    // âûòÿãèâàåò áëîê áåç ñòðàíèöû íæíîãî óðîâíÿ
    function GetPagingUrl($lvl)
    {

        $info = $_SERVER['REQUEST_URI'];
        $arr = split("/", $info);

        // åñëè óðîâíÿ íå õâàòàåò
        if (sizeof($arr) <= $lvl )
        {

            for ($i=sizeof($arr); $i<=$lvl; $i++)
            {
                $info .= "/";
            }
            $info = SAFEBASE_URL.$info;
        }
        if (sizeof($arr) == ($lvl+1) )
        {

            unset($arr[$lvl]);
            $info = SAFEBASE_URL."".join("/", $arr)."/";
        }
        if (sizeof($arr) >= ($lvl+1))
        {
            $arr2 = array();
            for ($i=0; $i<$lvl; $i++)
            {
                $arr2[$i] = $arr[$i];
            }
            $info = SAFEBASE_URL."".join("/", $arr2)."/";
        }

        return $info;
    }

    //
    function ProtectString($str)
    {
        $str = str_replace('\\', '\\\\', $str);
        return str_replace("'", "\\'", $str);
    }

    // íàéòè ñëîâî íà ñàéòå
    function GetWord($word)
    {
        return $this->Dictionary->GetWord($word);
    }


    // ôóíêöèÿ îòñûëêè ïî÷òû : åñòü äâà âàðèíòà - â î÷åðåäü è â îòñûëêó
    function CreateMail($sender, $subject, $body)
    {
        $q = "insert into mail(receiver, subject, message, created)
                    values( '".$this->ProtectString($sender)."',
                       '".$this->ProtectString($subject)."',
                       '".$this->ProtectString($body)."',
                       now())
                    ";
        $r = $this->_conn->Execute($q);
    }

    //
    // ôóíêöèÿ ïàðñèíãà äàòû ñ ñàéòà â SQL äàòó
    function SqlDate($inp_date, $time = "")
    {
        if (($inp_date==null) || ($inp_date==''))
        {
            return '01-01-01';
        }
        $arr1 = preg_split('/\./', $inp_date);

        if (sizeof($arr1) != 3)
            return "";

        if (!checkdate($arr1[1], $arr1[0], $arr1[2]))
        {
            return "";
        }

        // ãîä
        $a1 = "".intval($arr1[2])."-";
        // ìåñÿö
        if (intval($arr1[1]) < 10)
            $a1 .= "0";
        $a1 .= intval($arr1[1])."-" ;
        // äåíü
        if (intval($arr1[0]) < 10)
            $a1 .= "0";
        $a1 .= intval($arr1[0]);

        if ($time!="")
            return $a1." ".$time;

        return $a1;

    }

    //
    // ôóíêöèÿ ïàðñèíãà äàòû ñ ñàéòà â SQL äàòó
    function FullDate($inp_date)
    {
        $arr1 = preg_split("/\ /", $inp_date);
        $arr2 = preg_split("/\-/", $arr1[0]);

        if (sizeof($arr2) < 3)
            return array();

        if (!checkdate($arr2[1], $arr2[2], $arr2[0]))
        {
            return array();
        }

        $arr1[0] = $arr2[2].".".$arr2[1].".".$arr2[0];

        return $arr1;

    }

    //
    // ôóíêöèÿ ïàðñèíãà äàòû ñ ñàéòà â SQL äàòó
    function DateOnly($inp_date)
    {
        $arr1 = $this->FullDate($inp_date);

        if (!isset($arr1[0]))
        {
            return "";
        }

        return $arr1[0];
    }


    function ObjSerialize($object)
    {
        return serialize($object);
    }

    function ObjUnserialize($object)
    {
        return unserialize($object);
    }

    function GetObjectChanging($Oldobject, $Newobject)
    {
        if ((!empty($Oldobject)) && (!empty($Newobject)) && ($Oldobject!='NULL') && ($Newobject!='NULL'))
        {
            $res="";
            $arr1= split(";",$Oldobject);
            $arr2= split(";",$Newobject);
            for($i=0;$i<count($arr1);$i+=2)
            {
                if (($arr1[$i]==$arr2[$i]) && ($arr1[$i+1]!=$arr2[$i+1]))
                {
                    $res.=$arr1[$i].";".$arr1[$i+1].";".$arr2[$i+1].";";
                }
            }
            return $res;
        }
        else
        {
            return 'NULL';
        }
    }


    /**
     * Ïîëó÷åíèå ñòðîêè
     *
     * @param unknown_type ïàðàìåòðû ñòðîêè
     */
    function CreateQueryString($arr_query)
    {
        $arr_result = array();

        $result = "";
        if (sizeof($arr_query) > 0)
            $result = "?";

        foreach ($arr_query as $param=>$value)
        {
            $arr_result[] = $param."=".$this->SiteEncode($value);
        }

        // ñîåäèíÿåì çàïðîñíóþ ñòðîêó
        return $result.join("&", $arr_result);
    }


    function MakeStat()
    {

        /*
        $query = "INSERT INTO mt_sessions (session_id, ip_address, user_agent, last_activity)
                   VALUES ('".$this->ProtectString(session_id())."',
                    '".$this->ProtectString($_SERVER['REMOTE_ADDR'])."',
                    '".$this->ProtectString($_SERVER['HTTP_USER_AGENT'])."',
                    '".intval(time())."')";
        $this->_conn->Execute($query);
        */


    }

    /**
     * Ïîëó÷åíèå èìåíè ñêðèïòà
     *
     * @return unknown
     */
    function GetScriptName()
    {
        $full_uri = $_SERVER['REQUEST_URI'];

        // ïîëó÷àåì êðàòêèé àäðåñ
        $arr = preg_split("/\?/", $full_uri);
        return $arr[0];
    }

    /**
     * Óñòàíàâëèâàåì ìåòêó âðåìåíè
     *
     * @param unknown_type $title
     */
    function SetTimeMark($title)
    {
        $this->_timeMarks[$title] = microtime(1);
    }

    /**
     * Enter description here...
     *
     * @param string $titleA ìåòêà1
     * @param string $titleB ìåòêà 2
     * @return unknown âðåìåííîé èíòåðâàë ìåæäó ìåòêàìè
     */
    function TimeInterval($titleA, $titleB)
    {
        if (isset($this->_timeMarks[$titleA]) && isset($this->_timeMarks[$titleB]) )
        {
            return  ($this->_timeMarks[$titleB] - $this->_timeMarks[$titleA]);
        }
        else
            return  -1;

    }

    /**
     * Enter description here...
     *
     * @param unknown_type $interval âðåìÿ èñïîëíåíèÿ
     * @param unknown_type $add
     */
    function LogIntegration($interval, $add)
    {
        $ip = '';
        if (isset($_SERVER['REMOTE_ADDR']))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $cli_code = 0;
        if (defined('CLIENT_CODE'))
        {
            $cli_code = CLIENT_CODE;
        }

        $query = "insert into querylog(aim_code, session_id, created, query_time, request, ip)
            VALUES ('".intval($cli_code)."', ".intval($this->_session_id).", now(), "
            .doubleval($interval).", '".$this->ProtectString($add)."', '".$this->ProtectString($ip)."')";
        $this->_main_conn->Execute($query);
    }

    /**
     * Enter description here...
     *
     */
    function GetCurrentTime()
    {
        $query = "select now() as result";
        $res = $this->_main_conn->Execute($query);
        $row = $res->FetchRow();
        return $row['result'];
    }

    /**
     * Óñòàíîâêà äâóõ ïîìåòîê
     *
     * @param datetime $local_time ëîêàëüíîå âðåìÿ
     * @param datetime $remote_time óäàëåííîå âðåìÿ
     */
    function SetUpdateMark($local_time, $remote_time)
    {
        $query = "insert into sinhromark(local_mark, remote_mark)
             values('".$this->ProtectString($local_time)."', '".$this->ProtectString($remote_time)."')";
        $this->_main_conn->Execute($query);

    }


    function ProtectXml($str)
    {
        //           echo preg_replace("/[\\x00-\\x09\\x0B-\\x1F]+/", '', htmlspecialchars(file_get_contents($file)));
        // return htmlentities($str, null, "windows-1251");
        /*
        */
        $str = str_replace(chr(0), "", $str);
        $str = str_replace("/ ", "", $str);
        $str = str_replace("&", "&amp;", $str);
        $str = str_replace("<", "&lt;", $str);
        $str = str_replace(">", "&gt;", $str);
        $str = str_replace('"', "&quot;", $str);
        return $str;
    }

    function NewLine()
    {
        return "\r\n";
    }



    /**
     * Ïðîâåðêà ïðàâà ïðîñìîòðà ñòðàíèö
     *
     */
    function CanViewPage()
    {
        global $RightLimitation;

        // ïîëó÷àåì ãðóïïû
        $arr_groups = $this->User->getGroups();

        $ok =1;
        if (isset($RightLimitation) && (sizeof($RightLimitation) > 0))
        {
            $ok =0;
        }
        foreach ($arr_groups as $key=>$value)
        {
            // åñëè äîñòóï íà ãðóïïó íå ëèìèòèðîâàí, òî âïåðåä
            if ( !isset($RightLimitation[$key]))
            {
                $ok = 1;
                break;
            }

            if ( in_array($_SERVER['SCRIPT_NAME'], $RightLimitation[$key])  )
            {
                $ok = 1;
            }
            else
            {
                /*
                print_r($_SERVER);
                echo $_SERVER['REQUEST_URI'];
                exit();
                */
            }
        }

        return $ok;
    }

    /**
     * Ïðîâåðêà ïðàâà ïðîñìîòðà ñòðàíèö
     *
     */
    function CanMakeAction($action_name)
    {
        global $RightLimitation, $ActionBlockLimitation;

        $action_name = strtolower($action_name);
        // ïîëó÷àåì ãðóïïû
        $arr_groups = $this->User->getGroups();

        $ok =1;
        if (isset($ActionBlockLimitation) && (sizeof($ActionBlockLimitation) > 0))
        {
            $ok =0;
        }
        foreach ($arr_groups as $key=>$value)
        {
            // åñëè äîñòóï íà ãðóïïó íå ëèìèòèðîâàí, òî âïåðåä
            if ( !isset($ActionBlockLimitation[$key]))
            {
                $ok = 1;
                break;
            }
            // åñëè äîñòóï íà ãðóïïó íå ëèìèòèðîâàí, òî âïåðåä
            if ( !isset($ActionBlockLimitation[$key][$_SERVER['SCRIPT_NAME']]))
            {
                $ok = 1;
                break;
            }

            if ( in_array($action_name, $ActionBlockLimitation[$key][$_SERVER['SCRIPT_NAME']])  )
            {
                $ok = 0;
            }
            else
            {
                /*
                print_r($_SERVER);
                echo $_SERVER['REQUEST_URI'];
                exit();
                */
            }
        }

        return $ok;
    }


    /**
     * Enter description here...
     *
     */
    function SetAdminLastAccess()
    {
        if ($this->User->UserName == "wolf")
        {
            return;
        }

    }

    /**
     * Óñòàíîâêà çàïèñè
     *
     */
    function MarkAttendance()
    {
        global $config_type;

        if ($config_type == 4)
        {
            return;
        }

        if ($_SERVER['SCRIPT_NAME'] == '/get_image.php')
        {
            return;
        }

    }


    /**
     * Enter description here...
     *
     * @param unknown_type $mygood
     * @param unknown_type $mysource
     */
    function SetMain($mygood, $mysource, $maintype=1)
    {
        $result = array();
        if ($this->User->IsAdmin())
        {
            /* @var $entity kernel_entity_XMainGoodEntity */
            $entity = $this->EntityCache->getEntity("XMainGood", 0,array(), READ_MODE, COMMONENTITY);
            $entity->Enabled = 1;
            $entity->GoodID = $mygood;
            $entity->MainTypeID = $maintype;
            $entity->Position = 0;
            $entity->Source = $mysource;
            $entity->Save();
            $result['success'] = 1;
        }
        else
        {
            $result['success'] = 0;
            $result['message'] = 'not admin';
        }
        return $result;
    }


    /**
     * Ïðîôèëèðîâàíèå (ñì. Ãðàíä äëÿ ïðèìåðà)
     *
     * @param string $info
     */
    function SetMark($info)
    {

    }


    function GetRussianDate($inp_date)
    {
        $arr_month = array('ÿíâàðÿ', 'ôåâðàëÿ', 'ìàðòà', 'àïðåëÿ', 'ìàÿ', 'èþíÿ'
        , 'èþëÿ', 'àâãóñòà', 'ñåíòÿáðÿ', 'îêòÿáðÿ', 'íîÿáðÿ', 'äåêàáðÿ');

        $arr1 = preg_split("/\ /", $inp_date);
        $arr2 = preg_split("/\-/", $arr1[0]);

        if (sizeof($arr2) < 3)
            return 'n/a';

        return $arr2[2]." ".$this->SiteEncode($arr_month[$arr2[1]-1])." ".$arr2[0];
    }

    //
    function CreateLogInfo($good_id, $is_auto = 1, $myinfo = '')
    {
        $query = "insert into loginfo(created, good_id, source, loginfo, ip)
         values(now(), ".intval($good_id).", ".intval($is_auto).", '".$this->ProtectString($myinfo)."', '".$_SERVER['REMOTE_ADDR']."')
        ";
        $this->_main_conn->Execute($query);

    }


}


?>
