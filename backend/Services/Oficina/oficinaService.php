<?php
require_once __DIR__ . '/../../Connection/db.php';
date_default_timezone_set('America/Sao_Paulo');

class OficinaService
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
        o.horario, o.total_vagas, i.id_instrutor, i.nome, i.email, i.telefone, i.area_atuacao
         FROM oficina o INNER JOIN instrutor i ON i.id_instrutor = o.instrutor_idinstrutor ');

        // voltar as vagas disponíveis;
        $resultado = [];

        while ($row = $query->fetch()) {
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

    public function cadastrarOficina($oficinaDados)
    {
        try {


            $criar = $this->db->prepare('INSERT INTO oficina (titulo, descricao, categoria, carga_horaria, data_oficina, horario, total_vagas, instrutor_idinstrutor)
            VALUES (:titulo, :descricao, :categoria, :carga_horaria, :data_oficina, :horario, :total_vagas, :instrutor_idinstrutor)');


            $criar->execute([
                ':titulo' => $oficinaDados['titulo'],
                ':descricao' => $oficinaDados['descricao'],
                ':categoria' => $oficinaDados['categoria'],
                ':carga_horaria' => $oficinaDados['carga_horaria'],
                ':data_oficina' => $oficinaDados['data_oficina'],
                ':horario' => $oficinaDados['horario'],
                ':total_vagas' => $oficinaDados['total_vagas'],
                ':instrutor_idinstrutor' => $oficinaDados['instrutor_idinstrutor']


            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Oficina criada com sucesso'
            ];
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'fk_oficina_instrutor')) {
                throw new Exception('Instrutor referenciado não encontrado', 409);
            }

            throw new Exception('Erro ao cadastrar Oficina' . $e->getMessage(), 500);
        }
    }

    public function atualizarOficina($oficinaDados, $idOficina)
    {
        try {
            $oficina = $this->buscarOficinaPorId($idOficina);


            if ($oficina['sucesso'] === true) {
                $atualizar = $this->db->prepare('UPDATE oficina SET titulo = :titulo, 
            descricao = :descricao, categoria = :categoria, carga_horaria = :carga_horaria,
            data_oficina = :data_oficina, horario = :horario, total_vagas = :total_vagas, instrutor_idinstrutor = :instrutor_idinstrutor
             WHERE id_oficina = :id_oficina');

                $atualizar->execute([
                    ':titulo' => $oficinaDados['titulo'],
                    ':descricao' => $oficinaDados['descricao'],
                    ':categoria' => $oficinaDados['categoria'],
                    ':carga_horaria' => $oficinaDados['carga_horaria'],
                    ':data_oficina' => $oficinaDados['data_oficina'],
                    ':horario' => $oficinaDados['horario'],
                    ':total_vagas' => $oficinaDados['total_vagas'],
                    ':instrutor_idinstrutor' => $oficinaDados['instrutor_idinstrutor'],
                    ':id_oficina' => $idOficina
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Oficina atualizada com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'fk_oficina_instrutor')) {
                throw new Exception('Instrutor referenciado não encontrado', 409);
            }

            throw new Exception('Erro ao atualizar oficina', 500);
        }
    }

    public function deletarOficina($idOficina)
    {
        try {
            $oficina = $this->buscarOficinaPorId($idOficina);
            if ($oficina['sucesso'] === true) {
                $deletar = $this->db->prepare('DELETE FROM oficina WHERE id_oficina = :id_oficina');

                $deletar->execute([
                    'id_oficina' => $idOficina
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Oficina deletada com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'parent row')) {
                throw new Exception('Impossível deletar oficina referenciada', 409);
            }

            throw new Exception('Erro ao deletar oficina', 500);
        }
    }
}
