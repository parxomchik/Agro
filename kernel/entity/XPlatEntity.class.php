<?php

class entity_XPlatEntity extends XEntity {

    //Public attributes
    var $cadastrNumber = 111;
    var $area = 123;
    var $propertyLaw = 'Moroni';
    var $dateOfRegistration = 'Moroni';
    var $reason = 'Moroni';
    var $propertyOfPropertyLaw = 'Moroni';
    var $personOfPropertyLaw = 'Moroni';

    /* @var $templateOptions TemplateOptions */
    var $templateOptions = null;

    function Load( $key, $params, $mode )
    {
        global $app;
    }

    //
    function GetJson()
    {
        $result = array();
        $result['cadastrNumber'] = $this->cadastrNumber;
        $result['area'] = $this->area;
        $result['propertyLaw'] = $this->propertyLaw;
        $result['dateOfRegistration'] = $this->dateOfRegistration;
        $result['reason'] = $this->reason;
        $result['cadastrNumber'] = $this->cadastrNumber;
        $result['propertyOfPropertyLaw'] = $this->propertyOfPropertyLaw;
        $result['personOfPropertyLaw'] = $this->personOfPropertyLaw;
        return $result;
    }

    // çàïèñü ñóùíîñòè
    function Save()
    {
        global $app;


        return true;
    }

    function Delete()
    {
        global $app;

        /*
     $query = 'delete from news where news_id='.intval($this->Key);
     $this->_main_conn->Execute( $query );
                   */
        return true;
    }

} // êîíåö ïðîñòîé ñóùíîñòè


?>