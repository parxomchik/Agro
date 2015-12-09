<?php

class XUser {

    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $PacketName = "";


    var $UserTitle = "";

    // ÿçûê âûáðàííûé â àäìèíêå
    var $AdminLanguage = "ua";
    var $Language = "ua";

    /**
     * ID ïîëüçîâàòåëÿ
     *
     * @var unknown_type
     */
    var $UserID = -1;

    /**
     * Êóðñ ïåðåñ÷åòà
     *
     * @var float
     */
    var $ExchangeRate = 1;

    /**
     * Ñêèäêà äë ïîëüçîâàòåëÿ
     *
     * @var unknown_type
     */
    var $Discount = 0;

    /**
     * Âàëþòà
     *
     * @var int
     */
    var $CurrencyID = 1;

    /**
     * Çíàê âàëþòû
     *
     * @var string
     */
    var $CurrencySign = "";

    var $LastOrderID = 0;

    // 1 - àíîíèìíûé
    // 4 - àäìèíèñòðàòîð
    var $UserTypeID = 0;
    // ôàìèëèÿ
    var $Name1 = "";
    // èìÿ
    var $Name2 = "";
    // îò÷åñòâî
    var $Name3 = "";

    // êîìáèíàöèÿ èìåíè è ïàðîëÿ
    var $UserName = "";
    // èìÿ íà ñàéòå
    var $FullName = "";
    // åìåéë
    var $Email = "";
    // àñòêà
    var $ICQ = "";
    // òåëåôîí
    var $Phone = "";
    var $IsLocal = 0;
    var $Password = "";
    var $Created = '';
    var $Address = '';
    var $ErrorMessage = "";

    var $_pass;

    var $FacultyID = -1;

    // Ïóòü ãäå þçåð áûë ïåðåõâà÷åí
    var $ReturnPath = '';

    // êàïò÷è
    var $_Kaptcha = array();

    /**
     * Ïîñëåäíèé íîìåð ñ÷åòà
     *
     * @var unknown_type
     */
    var $LastBillID  = 0;

    //Protected properties
    var $_groups = array( );
    var $_hash = ''; //Password's md5 hash
    var $_session;
    var $_auth = 0; // Some user's, such as Visitors, can be not authorized
    var $_conn;

    var $Special = array();

    var $Okpo = '';
    var $Organization = '';
    var $Inn = '';
    var $Witness = '';
    var $Contact = '';
    var $OrganizationTypeID = 1;

    var $LastAddedSubitemID = 0;
    var $Lang = "ru";

    function XUser()
    {
        global $app;
        $this->FullName = "";
    }

    // óñòàíîâêà ñîåäèíåíèÿ
    function setConn( &$conn )
    {
        $this->_conn = null;
        $this->_conn = &$conn;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $curr_id
     */
    function SetCurrency($curr_id)
    {
        global $app;

        /* @var $entity kernel_entity_XCurrencyEntity */
        $entity = $app->EntityCache->getEntity("XCurrency", $curr_id, array(), READ_MODE, COMMONENTITY	);
        if ($entity->Loaded)
        {
            $this->ExchangeRate = $entity->Rate;
            $this->CurrencyID = $entity->CurrencyID;
            $this->CurrencySign = $entity->Sign;
        }
    }


    /**
     * âõîä íà ñàéò - ïîïûòêà àâòîðèçàöèè
     *
     * @param unknown_type $username
     * @param unknown_type $password
     * @return bool
     */
    function loginUser( $username, $password )
    {
        global $app;
        // øèôðîâàíèå åñëè íàäî áóäåò
        // if( $this->_Login( $username, md5($password) ))
        if( $this->_Login( $username, $password ))
        {
            $this->_auth = true;
            return true;
        }
        return false;
    }

    /**
     * âõîä íà ñàéò - ïîïûòêà àâòîðèçàöèè
     *
     * @param unknown_type $username
     * @param unknown_type $password
     * @return bool
     */
    function loginAdmin( $username, $password )
    {
        global $app;

        return $this->_Login($username, $password, 1);
    }

    // ========================================
    // âûõîä ñ ñàéòà
    function logoutUser( )
    {
        global $app;

        $this->_auth = false;
        $this->UserID = -1;
        $this->UserTypeID = 0;
        $this->Email = "";
        $this->ICQ = "";
        $this->_pass = "";
        $this->Login = "";
        $this->Discount = 0;
        $this->FullName = "";
        $this->Phone = "";
        $this->SiteName = "";
        $this->Name1 = "";
        $this->Name2 = "";
        $this->Name3 = "";
        $this->_groups = array();
        $_SESSION['auth'] = 0;
    }


    /**
     * ßâëÿåòñÿ ëè àäìèíîì
     *
     * @return unknown
     */
    function IsAdmin()
    {
        // if (in_array($this->UserTypeID, array(2,3,4)))
        if (sizeof($this->_groups) > 0)
        {
            return 1;
            // return 1;
        }
        return 0;
    }


    // ==========================================
    //  ñïèñîê ãðóï
    function getGroups()
    {
        if ( sizeof( $this->_groups ) == 0) {
            $this->_loadGroups();
        }
        return $this->_groups;
    }

    // àâòîðèçèðîâàííûé ïîëüçîâàòåëü
    function isLogged()
    {
        return ($this->_auth );
    }

    //Protected methods
    function _loadGroups()
    {
        global $app;

        $this->_groups = array();

        if ($this->UserID > 0)
        {
            $query = "select wg.work_group_id, wg.title
                   from workuser as wu
                   join workgroup wg on wg.work_group_id=wu.work_group_id
                  where client_id = ".intval($this->UserID)."
                ";
            $res = $app->_main_conn->Execute($query);
            while (!$res->EOF)
            {
                $row = $res->FetchRow();
                $this->_groups[$row['work_group_id']] = $row['title'];
            }
        }
        // $this->_groups = array( VISITORS_GROUP );
    }


    /**
     * Îòðàáîòêà âõîäà â ñèñòåìó
     *
     * @param unknown_type $username
     * @param unknown_type $password
     * @return unknown
     */
    function _Login( $username, $password )
    {
        global $app;

        //Username and Password must be not empty.
        if ( empty( $username ) or empty( $password ) )
        {
            $this->_uath = false;
            return false;
        }

        // âõîä äëÿ êëèåíòà
        $query = "select *
                            from client
                           where username='".$app->ProtectString($username)."'
                             and password='".$app->ProtectString($password)."'
                             and enabled = 1
                             ";

        $res = $app->_main_conn->Execute( $query );
        if ( $res->RowCount() == 1 )
        {
            $row = $res->FetchRow();

            if ($row[ 'enabled'] == 0)
            {
                $this->ErrorMessage = $app->SiteEncode("Âàøà ó÷åòíàÿ çàïèñü áëîêèðîâàíà àäìèíèñòðàòîðîì");
                return false;
            }


            //
            $this->UserID = intval( $row[ 'client_id'] );
            $this->UserName = $row[ 'username' ];
//            $this->Name1 = $row[ 'name1' ];
//            $this->Name2 = $row[ 'name2' ];
 //           $this->Name3 = $row[ 'name3' ];

//            $this->FullName = $this->Name1." ".$this->Name2." ".$this->Name3;
            $this->_pass = $row[ 'password' ];
//            $this->Password = $row[ 'password' ];
//            $this->ICQ = $row[ 'icq' ];
//            $this->Phone = $row[ 'phone' ];
//            $this->Email = $row[ 'email' ];
//            $this->Address = $row[ 'address' ];
//            $this->UserTypeID= $row[ 'client_type_id'];
//            $this->Created = $row[ 'created'];



            $this->_hash = md5( $password );
            $this->_loadGroups();
            $this->_auth = true;

            /*
            $entClientType = $app->EntityCache->getEntity("XClientType", $this->UserTypeID, array(), READ_MODE, COMMONENTITY);
            $this->Discount = $entClientType->Discount;
            $this->UserTitle = $entClientType->Title;

            $q = "update client set lastlogin=now() where client_id=".intval($this->UserID);
            $r = $app->_main_conn->Execute( $q);

            if ($this->IsAdmin())
            {
                $_SESSION['auth'] = 1;
            }
            */


            return true;
        }
        else
        {
            // TODO: add if account was locked
            $this->ErrorMessage = $app->SiteEncode("Íåâiðíèé ëîãií àáî ïàðîëü");


            $this->_uath = false;
            return false;
        }

    }



    // ïîëó÷åíèå ñëåäóþùåé êàïò÷è
    function NextKaptcha()
    {
        return sizeof($this->_Kaptcha) + 1;
    }

    // óñòàíîâêà êàïò÷è
    function SetKaptcha($mcode, $code)
    {
        $this->_Kaptcha[$mcode-1] = $code;
    }


}
?>