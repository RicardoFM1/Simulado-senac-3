<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

require_once __DIR__ . "/../../Services/Participante/participanteService.php";

class ParticipanteController
{
    protected $participanteService;


    public function __construct()
    {
        $this->participanteService = new ParticipanteService();
    }

    public function validarDados($dados)
    {
        try {
            $esquema = v::key('nome', v::stringVal()->notEmpty()->length(1, 45))
                ->key('cpf', v::cpf())
                ->key('email', v::email())
                ->key('telefone', v::phone());

            $esquema->assert($dados);
        } catch (NestedValidationException $e) {
            $mensagemPersonalizada = [
                'nome' => 'Nome inválido',
                'cpf' => 'Cpf inválido',
                'email' => 'Email inválido',
                'telefone' => 'Telefone inválido'
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

    public function listarParticipantes()
    {
        http_response_code(200);
        echo json_encode($this->participanteService->listarParticipantes());
        exit;
    }

    public function cadastrarParticipante()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $this->validarDados($dados);

            echo json_encode($this->participanteService->cadastrarParticipante($dados));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function atualizarParticipante()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $idParticipante = $_GET['id_participante'];
            $this->validarDados($dados);

            echo json_encode($this->participanteService->atualizarParticipante($dados, $idParticipante));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deletarParticipante()
    {
        try {
            $idParticipante = $_GET['id_participante'];

            echo json_encode($this->participanteService->deletarParticipante($idParticipante));
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
