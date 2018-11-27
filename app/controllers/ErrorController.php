<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{

    public function show404Action()
    {
        $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
    }

    public function show401Action()
    {
        $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
    }

}
