<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        $this->tag->prependTitle("Circuitos | ");
    }

    public function beforeExecuteRoute() {
        /*
         * Force HTTPS.
         */
        if(!$this->request->isSecure()){
            $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $this->response->redirect($url);
            return false;
        }
        return true;
    }
}
