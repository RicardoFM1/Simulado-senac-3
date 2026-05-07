<?php

use Dotenv\Dotenv;
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../Controllers/Participante/participanteController.php";
require_once __DIR__ . "/../Controllers/Instrutor/instrutorController.php";
require_once __DIR__ . "/../Controllers/Oficina/oficinaController.php";



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

    if($metodoRequisicao === "GET"){
        $participanteController->listarParticipantes();
    }
    if($metodoRequisicao === "POST"){
        $participanteController->cadastrarParticipante();
    }
    if($metodoRequisicao === "PUT"){
        $participanteController->atualizarParticipante();
    }
    if($metodoRequisicao === "DELETE"){
        $participanteController->deletarParticipante();
    }
}

if($rotaRequisicao === '/instrutor'){
    $instrutorController = new InstrutorController();

    if($metodoRequisicao === "GET"){
        $instrutorController->listarInstrutores();
    }
    if($metodoRequisicao === "POST"){
        $instrutorController->cadastrarInstrutores();
    }
    if($metodoRequisicao === "PUT"){
        $instrutorController->atualizarInstrutor();
    }
    if($metodoRequisicao === "DELETE"){
        $instrutorController->deletarInstrutor();
    }
}

if($rotaRequisicao === '/oficina'){
    $oficinaController = new OficinaController();

    if($metodoRequisicao === "GET"){
        $oficinaController->listarOficinas();
    }
    if($metodoRequisicao === "POST"){
        $oficinaController->cadastrarOficina();
    }
    if($metodoRequisicao === "PUT"){
        $oficinaController->atualizarOficina();
    }
    if($metodoRequisicao === "DELETE"){
        $oficinaController->deletarOficina();
    }
}


if($rotaRequisicao === '/inscricao'){
    $inscricaoController = new InscricaoController();

    if($metodoRequisicao === "GET"){
        $inscricaoController->listarInscricoes();
    }

    if($metodoRequisicao === "GET" && isset($_GET['id_oficina'])){
        $inscricaoController->listarInscricaoPorOficina();
    }

    if($metodoRequisicao === "GET" && isset($_GET['id_participante'])){
        $inscricaoController->listarInscricaoPorParticipante();
    }

    if($metodoRequisicao === "POST"){
        $inscricaoController->cadastrarInscricao();
    }
    if($metodoRequisicao === "PUT"){
        $inscricaoController->atualizarInscricao();
    }
   
}

