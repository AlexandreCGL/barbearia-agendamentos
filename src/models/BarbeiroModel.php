<?php

declare(strict_types=1);

class BarbeiroModel
{
    private PDO $banco;

    public function __construct(PDO $banco)
    {
        $this->banco = $banco;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT * FROM barbeiros";
        $stmt = $this->banco->query($sql);
        return $stmt->fetchAll();
    }

    public function criar(string $nome, string $telefone, string $especialidade): bool
    {
        $sql = "INSERT INTO barbeiros (nome, telefone, especialidade)
        VALUES (:nome, :telefone, :especialidade)";

        $stmt = $this->banco->prepare($sql);

        return  $stmt->execute([
            ':nome'=> $nome,
            ':telefone'=> $telefone,
            ':especialidade'=> $especialidade,
        ]);

    }
}