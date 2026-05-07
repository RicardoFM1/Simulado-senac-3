<?php
require_once __DIR__ . '/../../Connection/db.php';
date_default_timezone_set('America/Sao_Paulo');

class ParticipanteService
{
    protected $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function buscarParticipantePorId($idParticipante)
    {
        if (empty($idParticipante)) {
            throw new Exception('Id inválido', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM participante WHERE id_participante = :id_participante');

        $buscar->execute([
            ':id_participante' => $idParticipante
        ]);

        $participante = $buscar->fetch();

        if (empty($participante)) {
            throw new Exception('Participante não encontrado', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $participante
        ];
    }

    public function listarParticipantes()
    {
        $query = $this->db->query('SELECT * FROM participantes');

        $participantes = $query->fetchAll();

        return [
            'sucesso' => true,
            'dados' => $participantes
        ];
    }

    public function cadastrarParticipante($participanteDados)
    {
        try {
            $participanteDados['cpf'] = str_replace('/\D/', '', $participanteDados['cpf']);
            $participanteDados['telefone'] = str_replace('/\D/', '', $participanteDados['telefone']);

            $dataFormatada = date('Y-m-d, H:i:s', $participanteDados['data_nascimento']);
            $criar = $this->db->prepare('INSERT INTO participante (nome, cpf, email, telefone, data_nascimento');


            $criar->execute([
                ':nome' => $participanteDados['nome'],
                ':cpf' => $participanteDados['cpf'],
                ':telefone' => $participanteDados['telefone'],
                ':data_nascimento' => $dataFormatada
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Participante criado com sucesso'
            ];
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'cpf')) {
                throw new Exception('CPF já em uso', 409);
            }
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('Email já em uso', 409);
            }

            throw new Exception('Erro ao cadastrar participante', 500);
        }
    }

    public function atualizarParticipante($participanteDados, $idParticipante)
    {
        try {
            $participante = $this->buscarParticipantePorId($idParticipante);

            $participanteDados['cpf'] = str_replace('/\D/', '', $participanteDados['cpf']);
            $participanteDados['telefone'] = str_replace('/\D/', '', $participanteDados['telefone']);

            $dataFormatada = date('Y-m-d, H:i:s', $participanteDados['data_nascimento']);

            if ($participante['sucesso'] === true) {
                $atualizar = $this->db->prepare('UPDATE participante SET nome = :nome, cpf = :cpf,
            email = :email, telefone = :telefone, data_nascimento = :data_nascimento WHERE id_participante = :id_participante');

                $atualizar->execute([
                    ':nome' => $participanteDados['nome'],
                    ':cpf' => $participanteDados['cpf'],
                    ':telefone' => $participanteDados['telefone'],
                    ':data_nascimento' => $dataFormatada,
                    'id_participante' => $idParticipante
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Participante atualizado com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'cpf')) {
                throw new Exception('CPF já em uso', 409);
            }
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('Email já em uso', 409);
            }

            throw new Exception('Erro ao atualizar participante', 500);
        }
    }

    public function deletarParticipante($idParticipante)
    {
        try {
            $participante = $this->buscarParticipantePorId($idParticipante);
            if ($participante['sucesso'] === true) {
                $deletar = $this->db->prepare('DELETE FROM participante WHERE id_participante = :id_participante');

                $deletar->execute([
                    'id_participante' => $idParticipante
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Participante deletado com sucesso'
                ];
            }
        } catch (PDOException $e) {
            throw new Exception('Erro ao deletar participante', 500);
        }
    }
}
