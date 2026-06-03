<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/AgendamentoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';

class AgendamentoController
{
    private AgendamentoModel $agendamentoModel;
    private ClienteModel $clienteModel;

    public function __construct(PDO $banco)
    {
        $this->agendamentoModel = new AgendamentoModel($banco);
        $this->clienteModel = new ClienteModel($banco);
    }

    public function listarPorBarbeiro(int $barbeiroId): void
    {
        $agendamentos = $this->agendamentoModel->listarPorBarbeiro($barbeiroId);

        header('Content-Type: application/json');
        echo json_encode($agendamentos);
    }

    public function criar(): void
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        
        $barbeiroId = $dados['barbeiro_id'];
        $nome = $dados['nome'];
        $telefone = $dados['telefone'];
        $data = $dados['data'];
        $hora = $dados['hora'];

        $this->clienteModel->criar($nome, $telefone);

        $clienteId = $this->agendamentoModel->getUltimoClienteId();

        $this->agendamentoModel->criar($barbeiroId, $clienteId, $data, $hora);

        header('Content-Type: application/json');
        echo json_encode (['mensagem' =>'Agendamento criado com sucesso!']);
    }
    public function cancelar(int $id): void
    {
        $this->agendamentoModel->cancelar($id);

        header('Content-Type: application/json');
        echo json_encode(['mensagem' => 'Agendamento cancelado com sucesso!']);
    }


        }


