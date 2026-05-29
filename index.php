<?php

declare(strict_types=1);

require_once __DIR__ . '/src/config/app.php';
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/models/BarbeiroModel.php';
require_once __DIR__ . '/src/models/ClienteModel.php';
require_once __DIR__ . '/src/models/AgendamentoModel.php';

$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/barbearia', '', $url);
$url = trim($url, '/');

switch ($url) {
    case '':
        echo "Bem-vindo à " . APP_NAME . "!";
        break;

    case 'agendamentos':
        echo "Pagina de Agendamentos - em breve!";
        break;

    case 'barbeiros':
        echo "Pagina de Barbeiros - em breve!";
        break;

    default:
        http_response_code(404);
        echo "Pagina nao encontrada!";
        break;
}