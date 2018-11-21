<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        $this->tag->prependTitle("Circuitos | ");
    }
}
