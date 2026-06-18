<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Validacao.php';

class ValidacaoTest extends TestCase
{
    public function testRetornaErroQuandoNomeEstaVazio(): void
    {
        $dados = [
            'barbeiro_id' => 1,
            'nome' => '',
            'telefone' => '13999991111',
            'data' => '2026-06-20',
            'hora' =>  '10:00',
        ];

        $erros = Validacao::validarAgendamento($dados);
        $this->assertContains('O campo nome e obrigatorio.', $erros);
    }

     public function testRetornaErroQuandoDataEstaEmFormatoInvalido(): void
    {
        $dados = [
            'barbeiro_id' => 1,
            'nome' => 'Joao',
            'telefone' => '13999991111',
            'data' => '20-06-2026',
            'hora' => '10:00',
        ];

        $erros = Validacao::validarAgendamento($dados);

        $this->assertContains('A data deve estar no formato AAAA-MM-DD.', $erros);
    }

    public function testNaoRetornaErrosQuandoDadosSaoValidos(): void
    {
        $dados = [
            'barbeiro_id' => 1,
            'nome' => 'Joao',
            'telefone' => '13999991111',
            'data' => '2026-06-20',
            'hora' => '10:00',
        ];

        $erros = Validacao::validarAgendamento($dados);

        $this->assertEmpty($erros);
    }
}


