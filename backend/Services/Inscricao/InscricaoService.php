<?php



require_once __DIR__ . '/../../Connection/db.php';
date_default_timezone_set('America/Sao_Paulo');

class InscricaoService
{
    protected $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function buscarInscricaoPorId($idInscricao)
    {
        if (empty($idInscricao)) {
            throw new Exception('Id inválido', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM incricao WHERE id_inscricao = :id_inscricao');

        $buscar->execute([
            ':id_inscricao' => $idInscricao
        ]);

        $inscricao = $buscar->fetch();

        if (empty($inscricao)) {
            throw new Exception('Inscricao não encontrada', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $inscricao
        ];
    }

    public function listarInscricaoEspecificaPorOficina($idOficinaEspecifica)
    {
        if (empty($oficinaEspecifica)) {
            throw new Exception('Oficina não referenciada', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM inscricao WHERE oficina_idoficina = :oficina_idoficina');

        $buscar->execute([
            ':oficina_idoficina' => $idOficinaEspecifica
        ]);

        $inscricao = $buscar->fetch();

        if (empty($inscricao)) {
            throw new Exception('Inscricao não encontrada', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $inscricao
        ];
    }

    public function listarInscricaoEspecificaPorParticipante($idParticipanteEspecifico)
    {
        if (empty($idParticipanteEspecifico)) {
            throw new Exception('Participante não referenciada', 400);
        }

        $buscar = $this->db->prepare('SELECT * FROM inscricao WHERE participante_idparticipante = :participante_idparticipante');

        $buscar->execute([
            ':participante_idparticipante' => $idParticipanteEspecifico
        ]);

        $inscricao = $buscar->fetch();

        if (empty($inscricao)) {
            throw new Exception('Inscricao não encontrada', 404);
        }

        return [
            'sucesso' => true,
            'dados' => $inscricao
        ];
    }


    public function listarInscricao()
    {
        $query = $this->db->query('SELECT * FROM inscricao');

        $inscricoes = $query->fetchAll();

        return [
            'sucesso' => true,
            'dados' => $inscricoes
        ];
    }

    public function cadastrarInscricao($inscricaoDados)
    {
        try {
            //validar oficinas lotadas.

            $criar = $this->db->prepare('INSERT INTO inscricao (data_inscricao, status, participante_idparticipante, oficina_idoficina)
            VALUES (:data_inscricao, :status, :participante_idparticipante, :oficina_idoficina)');

            $data = new DateTime();
            $dataFormatada = date('Y-m-d, H:i:s', $data->getTimestamp());

            $criar->execute([
                ':data_inscricao' => $dataFormatada,
                ':status' => $inscricaoDados['status'],
                ':participante_idparticipante' => $inscricaoDados['participante_idparticipante'],
                ':oficina_idoficina' => $inscricaoDados['oficina_idoficina']
            ]);

            return [
                'sucesso' => true,
                'mensagem' => 'Inscricao criada com sucesso'
            ];
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'participante_idparticipante')) {
                throw new Exception('Participante já inscrito', 409);
            }

            if (str_contains($e->getMessage(), 'fk_inscricao_participante')) {
                throw new Exception('Participante não encontrado', 409);
            }

            if (str_contains($e->getMessage(), 'fk_inscricao_oficina')) {
                throw new Exception('Oficina não encontrada', 409);
            }


            throw new Exception('Erro ao cadastrar oficina', 500);
        }
    }

    public function atualizarInscricao($inscricaoDados, $idInscricao)
    {
        try {
            $inscricao = $this->buscarInscricaoPorId($idInscricao);

            $data = new DateTime();
            $dataFormatada = date('Y-m-d, H:i:s', $data->getTimestamp());

            if ($inscricao['sucesso'] === true) {
                $atualizar = $this->db->prepare('UPDATE inscricao SET data_inscricao = :data_inscricao, 
            status = :status, participante_idparticipante = :participante_idparticipante, oficina_idoficina = :oficina_idoficina WHERE id_inscricao = :id_inscricao');

                $atualizar->execute([
                    ':data_inscricao' => $dataFormatada,
                    ':status' => $inscricaoDados['status'],
                    ':participante_idparticipante' => $inscricaoDados['participante_idparticipante'],
                    ':oficina_idoficina' => $inscricaoDados['oficina_idoficina']
                ]);

                return [
                    'sucesso' => true,
                    'mensagem' => 'Instrutor atualizado com sucesso'
                ];
            }
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'participante_idparticipante')) {
                throw new Exception('Participante já inscrito', 409);
            }

            if (str_contains($e->getMessage(), 'fk_inscricao_participante')) {
                throw new Exception('Participante não encontrado', 409);
            }

            if (str_contains($e->getMessage(), 'fk_inscricao_oficina')) {
                throw new Exception('Oficina não encontrada', 409);
            }

            throw new Exception('Erro ao atualizar inscricao', 500);
        }
    }
}
