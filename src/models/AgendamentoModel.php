<?php

declare(strict_types=1);

class AgendamentoModel
{
    private PDO $banco;

    public function __construct(PDO $banco)
    {
        $this->banco = $banco;
    }

    public function verificarDisponibilidade(int $barbeiroId, string $data, string $hora): bool
    {
        $sql = "SELECT COUNT(*) FROM agendamentos 
                WHERE barbeiro_id = :barbeiro_id 
                AND data_agendamento = :data 
                AND hora_agendamento = :hora
                AND status = 'agendado'";

        $stmt = $this->banco->prepare($sql);
        $stmt->execute([
            ':barbeiro_id' => $barbeiroId,
            ':data' => $data,
            ':hora' => $hora,
        ]);

        $quantidade = $stmt->fetchColumn();
        return $quantidade === 0;
    }

    public function criar(int $barbeiroId, int $clienteId, string $data, string $hora): bool
    {
        $horarioDisponivel = $this->verificarDisponibilidade($barbeiroId, $data, $hora);

        if (!$horarioDisponivel) {
            throw new Exception("Horario indisponivel! Esse barbeiro ja tem agendamento nesse horario.");
        }

        $sql = "INSERT INTO agendamentos (barbeiro_id, cliente_id, data_agendamento, hora_agendamento)
                VALUES (:barbeiro_id, :cliente_id, :data, :hora)";

        $stmt = $this->banco->prepare($sql);

        return $stmt->execute([
            ':barbeiro_id' => $barbeiroId,
            ':cliente_id' => $clienteId,
            ':data' => $data,
            ':hora' => $hora,
        ]);
    }

    public function cancelar(int $id): bool
    {
        $sql = "UPDATE agendamentos 
                SET status = 'cancelado'
                WHERE id = :id
                AND status = 'agendado'";

        $stmt = $this->banco->prepare($sql);
        $stmt->execute([':id' => $id]);

        $linhasAfetadas = $stmt->rowCount();

        if ($linhasAfetadas === 0) {
            throw new Exception("Agendamento nao encontrado ou ja cancelado.");
        }

        return true;
    }

    public function listarPorBarbeiro(int $barbeiroId): array
    {
        $sql = "SELECT a.*, c.nome as nome_cliente, b.nome as nome_barbeiro
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN barbeiros b ON a.barbeiro_id = b.id
                WHERE a.barbeiro_id = :barbeiro_id
                AND a.status = 'agendado'
                ORDER BY a.data_agendamento, a.hora_agendamento";

        $stmt = $this->banco->prepare($sql);
        $stmt->execute([':barbeiro_id' => $barbeiroId]);
        return $stmt->fetchAll();
    }
}