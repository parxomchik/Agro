<?php

/**
 * Êëàññ-çàãëóøêà ïóñòîé
 *
 */
class logic_XLogin extends XObject {

    /**
     * Enter description here...
     *
     * @param php4DOMElement $tmpl
     */
    function Show($tmpl)
    {
        global $app;

        $app->User->logoutUser();

        $result = array();
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        {
            $result['auth'] = 0;
            return $result;
        }

        $app->User->loginUser($app->Request['login'], $app->Request['password']);
        if (!$app->User->isLogged())
        {
            $result['auth'] = -1;
            $result['message'] = 'Bad request';
            return $result;
        }

        $result['auth'] = 1;
        $result['session'] = session_id();



        return $result;

    }
}

?>