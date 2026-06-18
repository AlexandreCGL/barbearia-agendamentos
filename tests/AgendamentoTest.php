<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/models/AgendamentoModel.php';

class AgendamentoTest extends TestCase
{
    private function criarBancoMock(int $quantidadeAgendamentos): PDO
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetchColumn')->willReturn($quantidadeAgendamentos);

        $bancoMock = $this->createMock(PDO::class);
        $bancoMock->method('prepare')->willReturn($stmtMock);

        return $bancoMock;
    }

    public function testHorarioDisponivelQuandoNaoHaAgendamentos(): void
    {
        $bancoMock = $this->criarBancoMock(0);
        $model = new AgendamentoModel($bancoMock);

        $resultado = $model->verificarDisponibilidade(1, '2026-06-20', '10:00');

        $this->assertTrue($resultado, 'Horario deve estar disponivel quando nao ha agendamentos');
    }

    public function testHorarioIndisponivelQuandoJaExisteAgendamento(): void
    {
        $bancoMock = $this->criarBancoMock(1);
        $model = new AgendamentoModel($bancoMock);

        $resultado = $model->verificarDisponibilidade(1, '2026-06-20', '10:00');

        $this->assertFalse($resultado, 'Horario deve estar indisponivel quando ja existe agendamento');
    }
    public function testCriarLancaExcecaoQuandoHorarioOcupado(): void
    {

        $bancoMock = $this->criarBancoMock(1);
        $model = new AgendamentoModel($bancoMock);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Horario indisponivel! Esse barbeiro ja tem agendamento nesse horario.');

        $model->criar(1, 1, '2026-06-20', '10:00');
    }

    public function testCriarComSucessoQuandoHorarioDisponivel(): void
    {
        $bancoMock = $this->criarBancoMock(0);
        $model = new AgendamentoModel($bancoMock);

        $resultado = $model->criar(1, 1, '2026-06-20', '10:00');

        $this->assertTrue($resultado);
    }

    public function testCancelarLancaExcecaoQuandoAgendamentoNaoExiste(): void
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('rowCount')->willReturn(0);

        $bancoMock = $this->createMock(PDO::class);
        $bancoMock->method('prepare')->willReturn($stmtMock);

        $model = new AgendamentoModel($bancoMock);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Agendamento nao encontrado ou ja cancelado.');

        $model->cancelar(999);
    }

    public function testCancelarComSucessoQuandoAgendamentoExiste(): void
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('rowCount')->willReturn(1);

        $bancoMock = $this->createMock(PDO::class);
        $bancoMock->method('prepare')->willReturn($stmtMock);

        $model = new AgendamentoModel($bancoMock);

        $resultado = $model->cancelar(1);

        $this->assertTrue($resultado);
    }
}
