<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

require_once __DIR__ . "/../../Services/Controller/inscricaoService.php";

class InscricaoController
{
    protected $inscricaoService;


    public function __construct()
    {
        $this->inscricaoService = new InscricaoService();
    }

    public function validarDados($dados)
    {

        try {
            $statusPermitidos = ['inscrito, cancelado'];
            $esquema = v::key('status', v::in($statusPermitidos))
                ->key('participante_idparticipante', v::intVal()->notEmpty())
                ->key('oficina_idoficina', v::intVal()->notEmpty());


            $esquema->assert($dados);
        } catch (NestedValidationException $e) {
            $mensagemPersonalizada = [
                'status' => 'Status fora do escopo: apenas inscrito ou cancelado',
                'participante_idparticipante' => 'Referênia do participante inválida',
                'oficina_idoficina' => 'Referência da oficina inválida'
            ];

            $mensagemOriginal = $e->getMessages();
            $mensagemTraduzida = [];

            foreach ($mensagemOriginal as $campo => $mensagem) {
                $mensagemTraduzida[$campo] = $mensagemPersonalizada[$campo] ?? $mensagem;
            }

            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erros de validação',
                'erros' => $mensagemTraduzida
            ]);
            exit;
        }
    }

    public function listarInscricoes()
    {
        http_response_code(200);
        echo json_encode($this->inscricaoService->listarInscricao());
        exit;
    }

    public function listarInscricaoPorParticipante () {
         http_response_code(200);
         $idParticipante = $_GET['id_participante'];
        echo json_encode($this->inscricaoService->listarInscricaoEspecificaPorParticipante($idParticipante));
        exit;
    }

    public function listarInscricaoPorOficina () {
         http_response_code(200);
         $idOficina = $_GET['id_oficina'];
         
        echo json_encode($this->inscricaoService->listarInscricaoEspecificaPorOficina($idOficina));
        exit;
    }

    public function cadastrarInscricao()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $this->validarDados($dados);
            http_response_code(201);
            echo json_encode($this->inscricaoService->cadastrarInscricao($dados));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function atualizarInscricao()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $idInscricao = $_GET['id_inscricao'];

            $this->validarDados($dados);
            http_response_code(200);
            echo json_encode($this->inscricaoService->atualizarInscricao($dados, $idInscricao));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    
}
