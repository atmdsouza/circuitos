<?php

// Rotas padrão do sistema
$router->add("/", [
    "controller" => "index",
    "action" => "index"
]);

$router->add("/cidade_digital", [
    "controller" => "cidade_digital",
    "action" => "index"
]);

$router->add("/circuitos", [
    "controller" => "circuitos",
    "action" => "index"
]);

$router->add("/cliente", [
    "controller" => "cliente",
    "action" => "index"
]);

$router->add("/cliente_unidade", [
    "controller" => "cliente_unidade",
    "action" => "index"
]);

$router->add("/empresa", [
    "controller" => "empresa",
    "action" => "index"
]);

$router->add("/equipamento", [
    "controller" => "equipamento",
    "action" => "index"
]);

$router->add("/fabricante", [
    "controller" => "fabricante",
    "action" => "index"
]);

$router->add("/index", [
    "controller" => "index",
    "action" => "index"
]);

$router->add("/lov", [
    "controller" => "lov",
    "action" => "index"
]);

$router->add("/modelo", [
    "controller" => "modelo",
    "action" => "index"
]);

$router->add("/usuario", [
    "controller" => "usuario",
    "action" => "index"
]);

$router->add("/session", [
    "controller" => "session",
    "action" => "login"
]);

