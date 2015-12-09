<?php

/**
 * Êëàññ-çàãëóøêà ïóñòîé
 *
 */
class logic_XMain extends XObject {

    /**
     * Enter description here...
     *
     * @param php4DOMElement $tmpl
     */
    function Show($tmpl)
    {
        global $app;

        $result = array();
        {
            /* @var $entity entity_XFormEntity */
            $entity = $app->EntityCache->getEntity("XForm", '', array(), READ_MODE, COMMONENTITY );
            $entity->key = 'login';
            $entity->type = 'input';
            $entity->templateOptions->type = 'text';
            $entity->templateOptions->label = 'Login';
            $entity->templateOptions->placeholder = 'Enter login';
            $result[] = $entity->GetJson();
        }
        {
            /* @var $entity entity_XFormEntity */
            $entity = $app->EntityCache->getEntity("XForm", '', array(), READ_MODE, COMMONENTITY );
            $entity->key = 'password';
            $entity->type = 'input';
            $entity->templateOptions->type = 'password';
            $entity->templateOptions->label = 'Password';
            $entity->templateOptions->placeholder = 'Enter login';
            $result[] = $entity->GetJson();
        }



        return $result;

    }
}

?>