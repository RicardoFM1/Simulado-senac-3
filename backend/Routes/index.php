<?php

use Dotenv\Dotenv;
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Controllers/Participante/participanteController.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');

$rotaRequisicao = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodoRequisicao = $_SERVER['REQUEST_METHOD'];


if($metodoRequisicao === 'OPTIONS'){
    http_response_code(200);
}

if($rotaRequisicao === '/participante'){
    $participanteController = new ParticipanteController();

    if($rotaRequisicao === "GET"){
        $participanteController->listarParticipantes();
    }
    if($rotaRequisicao === "POST"){
        $participanteController->cadastrarParticipante();
    }
    if($rotaRequisicao === "PUT"){
        $participanteController->atualizarParticipante();
    }
    if($rotaRequisicao === "DELETE"){
        $participanteController->deletarParticipante();
    }
}