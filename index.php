<?php
ob_start();

require "vendor/autoload.php";

use CoffeeCode\Router\Router;

date_default_timezone_set('America/Sao_Paulo');

//Inicio das rotas
$router = new Router(url(), ":");
$router->namespace("Source\Controller");

/**
 * APP
 */
$router->group(null);
$router->get("/", "App:search", "app.search");
$router->get("/monitoramento", "App:monitoring", "app.monitoring");
$router->get("/alterar-senha", "App:updatePassword", "app.updatePass");
$router->post("/alterar-senha", "App:updatePassword", "app.update_password");

//PESQUISAS
$router->get("/despesas", "App:expenses", "app.expenses");
$router->post("/despesas", "App:expenses", "app.expenses");
$router->get("/licitacao", "App:bidding", "app.bidding");
$router->post("/licitacao", "App:bidding", "app.bidding");
$router->get("/contrato", "App:contract", "app.contract");
$router->post("/contrato", "App:contract", "app.contract");
$router->get("/legislacao", "App:legislation", "app.legislation");
$router->post("/legislacao", "App:legislation", "app.legislation");
$router->get("/relatorio", "App:report", "app.report");
$router->post("/relatorio", "App:report", "app.report");
$router->get("/convenio", "App:convention", "app.convention");
$router->post("/convenio", "App:convention", "app.convention");

/**
 * AUTH
 */
$router->namespace("Source\Controller");
$router->group(null);
$router->get("/entrar", "Auth:login", "auth.login");
$router->post("/entrar", "Auth:login", "auth.login");
$router->get("/esqueceu-senha", "Auth:forget", "auth.forget");
$router->post("/esqueceu-senha", "Auth:forget", "auth.forget");
$router->get("/recuperar", "Auth:recover", "auth.recover");
$router->post("/recuperar", "Auth:recover", "auth.recover");
$router->get("/sair", "Auth:logout", "auth.logout");

//Selecionar empresa
$router->get("/empresa", "Auth:company", "auth.company");
$router->post("/empresa", "Auth:company", "auth.company");


/**
 * Admin
 */
$router->namespace("Source\Controller");
$router->group("/admin");
$router->get("/indexar", "Admin:indexar", "admin.indexar");
$router->post("/indexar", "Admin:indexar", "admin.indexar");
$router->get("/empresas", "Admin:companies", "admin.companies");
$router->get("/empresa/editar/{id}", "Admin:updateCompany", "admin.updateCompany");
$router->post("/empresa/editar/{id}", "Admin:updateCompany", "admin.updateCompany");
$router->get("/empresa/criar", "Admin:createCompany", "admin.createCompany");
$router->post("/empresa/criar", "Admin:createCompany", "admin.createCompany");
$router->post("/empresa/excluir", "Admin:deleteCompany", "admin.deleteCompany");
$router->get("/usuarios", "Admin:users", "admin.users");
$router->get("/usuario/criar", "Admin:createUser", "admin.createUser");
$router->post("/usuario/criar", "Admin:createUser", "admin.createUser");
$router->get("/usuario/editar/{id}", "Admin:updateUser", "admin.updateUser");
$router->post("/usuario/editar/{id}", "Admin:updateUser", "admin.updateUser");
$router->post("/usuario/excluir", "Admin:deleteUser", "admin.deleteUser");

/**
 * ERROR ROUTES
 */
$router->group("/ops");
$router->get("/{errcode}", "App:error", "app.error");

/**
 * ROUTE
 */
$router->dispatch();

/**
 * ERROR REDIRECT
 */
if ($router->error()) {
    $router->redirect("/ops/{$router->error()}");
}

ob_end_flush();