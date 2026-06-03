<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/BarbeiroModel.php';

class BarbeiroController
{
    private BarbeiroModel $barbeiroModel;

    public function __construct(PDO $banco)
    {
        $this->barbeiroModel = new BarbeiroModel($banco);
    }

    public function listar(): void
    {
        $barbeiros = $this->barbeiroModel->listarTodos();

        header('Content-Type: application/json');
        echo json_encode($barbeiros);
    }

}   