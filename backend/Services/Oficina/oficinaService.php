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

    public function buscarOficinaPorId($idOficina)
    {
        if (empty($idOficina)) {
            throw new Exception('Id inválido', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM oficina WHERE id_oficina = :id_oficina');

        $buscar->execute([
            ':id_oficina' => $idOficina
        ]);

        $oficina = $buscar->fetch();

        if (empty($oficina)) {
            throw new Exception('Oficina não encontrada', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $oficina
        ];
    }

    public function listarOficinas()
    {
        $query = $this->db->query('SELECT o.id_oficina, o.titulo, o.categoria, o.carga_horaria, o.data_oficina,
        o.horario, o.total_vagas i.id_instrutor, i.nome, i.email, i.telefone, i.area_atuacao
         FROM oficina o INNER JOIN instrutor i ON i.id_instrutor = o.instrutor_idinstrutor ');

        // voltar as vagas disponíveis;
        $resultado = [];

        while($row = $query->fetch()){
            $resultado[] = [
                'id_oficina' => $row['id_oficina'],
                'titulo' => $row['titulo'],
                'categoria' => $row['categoria'],
                'carga_horaria' => $row['carga_horaria'],
                'data_oficina' => $row['data_oficina'],
                'horario' => $row['horario'],
                'total_vagas' => $row['total_vagas'],
                'instrutor' => [
                    'id_instrutor' => $row['id_instrutor'],
                    'nome' => $row['nome'],
                    'email' => $row['email'],
                    'telefone' => $row['telefone'],
                    'area_atuacao' => $row['area_atuacao']
                ]
            ];
        }

        return [
            'sucesso' => true,
            'dados' => $resultado
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
