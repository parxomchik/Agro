<?php

/**
 * Êëàññ-çàãëóøêà ïóñòîé
 *
 */
class logic_XPlat extends XObject {

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
            $entity = $app->EntityCache->getEntity("XPlat", '', array(), READ_MODE, COMMONENTITY );
            $result[] = $entity->GetJson();
        }
        {
            /* @var $entity entity_XFormEntity */
            $entity = $app->EntityCache->getEntity("XPlat", '', array(), READ_MODE, COMMONENTITY );
            $result[] = $entity->GetJson();
        }
        {
            /* @var $entity entity_XFormEntity */
            $entity = $app->EntityCache->getEntity("XPlat", '', array(), READ_MODE, COMMONENTITY );
            $result[] = $entity->GetJson();
        }



        return $result;

    }
}

?>