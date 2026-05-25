<?php

declare(strict_types=1);

require_once __DIR__ . '/src/config/app.php';

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

