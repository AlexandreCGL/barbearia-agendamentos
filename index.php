<?php

declare(strict_types=1);

require_once __DIR__ . '/src/config/app.php';

require_once __DIR__ . '/src/config/database.php';

try {
    $banco = conectarBanco();
    echo "Conexao com o banco: OK! ✅";
} catch (Exception $e) {
    echo "Erro na conexao: " . $e->getMessage();
}

$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/barbearia', '', $url);
$url = trim($url, '/');

switch ($url) {
    case '':
    echo "Bem-vindo à " . APP_NAME . "!";
    break;

    case 'agendamentos':
    echo "Página de Agendamentos - em breve";
    break;

    case 'barbeiros':
    echo "Página de Barbeiros - em breve";
    break;

    default:
    http_response_code(404);
    echo "Página não encontrada!";
    break;

}

