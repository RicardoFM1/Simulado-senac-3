<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

require_once __DIR__ . "/../../Services/Oficina/oficinaService.php";

class OficinaController
{
    protected $oficinaService;


    public function __construct()
    {
        $this->oficinaService = new OficinaService();
    }

    public function validarDados($dados)
    {
        try {
            $esquema = v::key('titulo', v::stringVal()->notEmpty()->length(1, 45))
                ->key('descricao', v::stringVal()->notEmpty()->length(1, 45))
                ->key('categoria', v::stringVal()->notEmpty()->length(1, 45))
                ->key('carga_horaria', v::notEmpty())
                ->key('data_oficina', v::notEmpty())
                ->key('horario', v::notEmpty())
                ->key('total_vagas', v::intval()->notEmpty());


                

               

            $esquema->assert($dados);
        } catch (NestedValidationException $e) {
            $mensagemPersonalizada = [
                'titulo' => 'Titulo inválido',
                'descricao' => 'Descricao inválida',
                'categoria' => 'Categoria inválida',
                'carga_horaria' => 'Carga horária inválida',
                'data_oficina' => 'Data da oficina inválida',
                'horario' => 'Horário inválido',
                'total_vagas' => 'Total de vagas inválidas',

                
                
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

    public function listarOficinas()
    {
        http_response_code(200);
        echo json_encode($this->oficinaService->listarOficinas());
        exit;
       
    }

    public function cadastrarOficina()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $this->validarDados($dados);
            http_response_code(201);
            echo json_encode($this->oficinaService->cadastrarOficina($dados));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function atualizarOficina()
    {
        try {
            $dados = json_decode(file_get_contents('php://input'), true);
            $idOficina = $_GET['id_oficina'];
           
            $this->validarDados($dados);
            http_response_code(200);
            echo json_encode($this->oficinaService->atualizarOficina($dados, $idOficina));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deletarOficina()
    {
        try {
            $idOficina = $_GET['id_oficina'];
            http_response_code(200);

            echo json_encode($this->oficinaService->deletarOficina($idOficina));
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
