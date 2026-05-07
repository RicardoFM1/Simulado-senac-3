<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

require_once __DIR__ . "/../../Services/Instrutor/instrutorService.php";

class InstrutorController
{
    protected $instrutorService;


    public function __construct()
    {
        $this->instrutorService = new InstrutorService();
    }

    public function validarDados($dados)
    {
        try {
            $esquema = v::key('nome', v::stringVal()->notEmpty()->length(1, 45))
                ->key('email', v::email())
                ->key('telefone', v::phone());

            $esquema->assert($dados);
        } catch (NestedValidationException $e) {
            $mensagemPersonalizada = [
                'nome' => 'Nome inválido',
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

    public function listarInstrutores()
    {
        http_response_code(200);
        echo json_encode($this->instrutorService->listarInstrutores());
        exit;
       
    }

    public function cadastrarInstrutores()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $this->validarDados($dados);
            http_response_code(201);
            echo json_encode($this->instrutorService->cadastrarInstrutor($dados));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function atualizarInstrutor()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $idInstrutor = $_GET['id_instrutor'];
           
            $this->validarDados($dados);
            http_response_code(200);
            echo json_encode($this->instrutorService->atualizarInstrutor($dados, $idInstrutor));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deletarInstrutor()
    {
        try {
            $idInstrutor = $_GET['id_instrutor'];
            http_response_code(200);

            echo json_encode($this->instrutorService->deletarInstrutor($idInstrutor));
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
