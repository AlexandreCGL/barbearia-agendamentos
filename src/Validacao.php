<?php

declare(strict_types=1);

class Validacao
{
    public static function validarAgendamento(array $dados): array

    {
        $erros = [];

        if (empty($dados['barbeiro_id'])) {
            $erros[] = "O campo barbeiro_id e obrigatorio.";
        }

        if (empty($dados['nome'])) {
            $erros[] = "O campo nome e obrigatorio.";
    }

        if (empty($dados['telefone'])) {
            $erros[] = "O campo telefone e obrigatorio.";
        }

        if (empty($dados['data'])) {
            $erros[] = "O campo data e obrigatorio.";
        }elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['data'])) {
            $erros[] = "A data deve estar no formato AAAA-MM-DD.";
        }

        if (empty($dados['hora'])) {
            $erros[] = "O campo hora e obrigatorio.";
        } elseif (!preg_match('/^\d{2}:\d{2}$/', $dados['hora'])) {
            $erros[] = "A hora deve estar no formato HH:MM";
        }

        return $erros;
    }
}