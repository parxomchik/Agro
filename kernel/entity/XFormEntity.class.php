<?php

class TemplateOptions
{
    var $type = 'text';
    var $label = 'Login';
    var $placeholder = 'Enter login';

    function TemplateOptions()
    {

    }
}

/*
 * {
        key: 'login',
        type: 'input',
        templateOptions: {
          type: 'text',
          label: 'Login',
          placeholder: 'Enter login'
        }
      }
 */

class entity_XFormEntity extends XEntity {

    //Public attributes
    var $key = '';
    var $type = 'input';
    /* @var $templateOptions TemplateOptions */
    var $templateOptions = null;

    function Load( $key, $params, $mode )
    {
        global $app;
        $this->templateOptions = new TemplateOptions();

    }

    //
    function GetJson()
    {
        $result = array();
        $result['key'] = $this->key;
        $result['type'] = $this->type;
        $result['templateOptions'] = $this->templateOptions;
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