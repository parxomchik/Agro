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
            $entity = $app->EntityCache->getEntity("XForm", '', array("a"=>1), READ_MODE, COMMONENTITY );
            $entity->key = 'login';
            $entity->type = 'input';
            $entity->templateOptions->type = 'text';
            $entity->templateOptions->label = 'Login';
            $entity->templateOptions->placeholder = 'Enter login';
            $result[] = $entity->GetJson();
        }
        {
            /* @var $entity entity_XFormEntity */
            $entity2 = $app->EntityCache->getEntity("XForm", '', array("a"=>2), READ_MODE, COMMONENTITY );
            $entity2->key = 'password';
            $entity2->type = 'input';
            $entity2->templateOptions->type = 'password';
            $entity2->templateOptions->label = 'Password';
            $entity2->templateOptions->placeholder = 'Enter login';
            $result[] = $entity2->GetJson();
        }



        return $result;

    }
}

?>