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

$router->add("/conectividade", [
    "controller" => "conectividade",
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

$router->add("/central_mensagens", [
    "controller" => "central_mensagens",
    "action" => "index"
]);

$router->add("/controle_acesso", [
    "controller" => "controle_acesso",
    "action" => "index"
]);

$router->add("/contrato", [
    "controller" => "contrato",
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

$router->add("/estacao_telecon", [
    "controller" => "estacao_telecon",
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

$router->add("/logs_sistema", [
    "controller" => "logs_sistema",
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

$router->add("/proposta_comercial", [
    "controller" => "proposta_comercial",
    "action" => "index"
]);

$router->add("/proposta_comercial_servico", [
    "controller" => "proposta_comercial_servico",
    "action" => "index"
]);

$router->add("/proposta_comercial_servico_grupo", [
    "controller" => "proposta_comercial_servico_grupo",
    "action" => "index"
]);

$router->add("/proposta_comercial_servico_unidade", [
    "controller" => "proposta_comercial_servico_unidade",
    "action" => "index"
]);

$router->add("/relatorios_gestao", [
    "controller" => "relatorios_gestao",
    "action" => "index"
]);

$router->add("/set_seguranca", [
    "controller" => "set_seguranca",
    "action" => "index"
]);

$router->add("/set_equipamento", [
    "controller" => "set_equipamento",
    "action" => "index"
]);

$router->add("/session", [
    "controller" => "session",
    "action" => "login"
]);

$router->add("/terreno", [
    "controller" => "terreno",
    "action" => "index"
]);

$router->add("/torre", [
    "controller" => "torre",
    "action" => "index"
]);

$router->add("/unidade_consumidora", [
    "controller" => "unidade_consumidora",
    "action" => "index"
]);

$router->add("/usuario", [
    "controller" => "usuario",
    "action" => "index"
]);