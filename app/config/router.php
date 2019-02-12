<?php

//$router = $di->getRouter();
//
//$router->handle();

$router = $di->getRouter(FALSE);

// HANDLER ROTAS: Se não estiver discriminado a baixo, redirecionará para página de Erro :D
$router->notFound(array(
    "controller" => "error",
    "action" => "show404"
));

// Rota padrão de Erro
$router->add("/error/show404", [
    "controller" => "error",
    "action" => "show404"
]);

// Rota padrão de Erro
$router->add("/error/show401", [
    "controller" => "error",
    "action" => "show401"
]);

include "systemRoutes.php";

// Sempre que houver CONTROLLER e ACTION, será executada a ACTION
$router->add("/:controller/:action", ["controller" => 1, "action" => 2]);
$router->add("/:controller/a/:action/:params", ["controller" => 1, "action" => 2, "params" => 3,]);

$router->handle();