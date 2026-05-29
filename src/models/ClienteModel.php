<?php

declare(strict_types=1);

class ClienteModel
{
    private PDO $banco;

    public function __construct(PDO $banco)
    {
        $this->banco = $banco;
    }

    public function criar(string $nome, string $telefone): bool
    {
        $sql = "INSERT INTO clientes (nome, telefone)
        VALUES (:nome, :telefone)";

        $stmt = $this->banco->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome,
            ':telefone' => $telefone,
        ]);
    }

        public function buscarPorId(int $id): array|false
        {
            $sql = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $this->banco->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }

        public function listarTodos(): array
        {
            $sql = "SELECT * FROM clientes";
            $stmt = $this->banco->query($sql);
            return $stmt->fetchAll();
        }
}