<?php
require_once __DIR__ . '/../../Connection/db.php';
date_default_timezone_set('America/Sao_Paulo');

class InstrutorService
{
    protected $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function buscarInstrutorPorId($idInstrutor)
    {
        if (empty($idInstrutor)) {
            throw new Exception('Id inválido', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM instrutor WHERE id_instrutor = :id_instrutor');

        $buscar->execute([
            ':id_instrutor' => $idInstrutor
        ]);

        $instrutor = $buscar->fetch();

        if (empty($instrutor)) {
            throw new Exception('Instrutor não encontrado', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $instrutor
        ];
    }

    public function listarInstrutores()
    {
        $query = $this->db->query('SELECT * FROM instrutor');

        $instrutores = $query->fetchAll();

        return [
            'sucesso' => true,
            'dados' => $instrutores
        ];
    }

    public function cadastrarInstrutor($instrutorDados)
    {
        try {
            $instrutorDados['telefone'] = str_replace('/\D/', '', $instrutorDados['telefone']);

            
            $criar = $this->db->prepare('INSERT INTO instrutor (nome, email, telefone, area_atuacao)
            VALUES (:nome, :email, :telefone, :area_atuacao)');


            $criar->execute([
                ':nome' => $instrutorDados['nome'],
                ':email' => $instrutorDados['email'],
                ':telefone' => $instrutorDados['telefone'],
                ':area_atuacao' => $instrutorDados['area_atuacao']
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Instrutor criado com sucesso'
            ];
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('Email já em uso', 409);
            }

            throw new Exception('Erro ao cadastrar Instrutor' , 500);
        }
    }

    public function atualizarInstrutor($instrutorDados, $idInstrutor)
    {
        try {
            $instrutor = $this->buscarInstrutorPorId($idInstrutor);

            
            $instrutorDados['telefone'] = str_replace('/\D/', '', $instrutorDados['telefone']);

           

            if ($instrutor['sucesso'] === true) {
                $atualizar = $this->db->prepare('UPDATE instrutor SET nome = :nome, 
            email = :email, telefone = :telefone, area_atuacao = :area_atuacao WHERE id_instrutor = :id_instrutor');

                $atualizar->execute([
                    ':nome' => $instrutorDados['nome'],
                    ':email' => $instrutorDados['email'],
                    ':telefone' => $instrutorDados['telefone'],
                    ':area_atuacao' => $instrutorDados['area_atuacao'],
                    'id_instrutor' => $idInstrutor
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Instrutor atualizado com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('Email já em uso', 409);
            }

            throw new Exception('Erro ao atualizar instrutor' , 500);
        }
    }

    public function deletarInstrutor($idInstrutor)
    {
        try {
            $instrutor = $this->buscarInstrutorPorId($idInstrutor);
            if ($instrutor['sucesso'] === true) {
                $deletar = $this->db->prepare('DELETE FROM instrutor WHERE id_instrutor = :id_instrutor');

                $deletar->execute([
                    'id_instrutor' => $idInstrutor
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Instrutor deletado com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if(str_contains($e->getMessage(), 'parent row')){
                throw new Exception('Impossível deletar instrutor referenciado', 409);
            }

            throw new Exception('Erro ao deletar instrutor', 500);
        }
    }
}
