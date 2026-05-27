<?php

declare(strict_types=1);

require_once __DIR__ . '/src/config/app.php';
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/models/BarbeiroModel.php';

try {
    $banco = conectarBanco();
    $barbeiroModel = new BarbeiroModel($banco);

    $barbeiros = $barbeiroModel->listarTodos();

    foreach ($barbeiros as $barbeiro) {
        echo "ID: " . $barbeiro['id'] . " | ";
        echo "Nome: " . $barbeiro['nome'] . " | ";
        echo "Especialidade: " . $barbeiro['especialidade'];
        echo "<br>";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
