<?php

declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/src/config/app.php';
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/controllers/BarbeiroController.php';
require_once __DIR__ . '/src/controllers/AgendamentoController.php';

$url = $_SERVER['REQUEST_URI'];
$url = strtok($url, '?');
$url = str_replace('/barbearia', '', $url);
$url = trim($url, '/');

$method = $_SERVER['REQUEST_METHOD'];

try {
    $banco = conectarBanco();

    switch ($url) {
        case '':
    header('Content-Type: application/json');
    echo json_encode(['mensagem' => 'API Barbearia funcionando!']);
    break;

        case 'barbeiros':
            $controller = new BarbeiroController($banco);
            $controller->listar();
            break;

        case 'agendamentos':
            $controller = new AgendamentoController($banco);
            if ($method === 'GET') {
                if (isset($_GET['barbeiro_id']) && isset($_GET['data'])) {
                    $controller->horariosDisponiveis(
                        (int)$_GET['barbeiro_id'],
                        $_GET['data']
                    );
                } else {
                    $barbeiroId = $_GET['barbeiro_id'] ?? 1;
                    $controller->listarPorBarbeiro((int)$barbeiroId);
                }
            } elseif ($method === 'POST') {
                $controller->criar();
            }
            break;

        case (preg_match('/^agendamentos\/(\d+)\/cancelar$/', $url, $matches) ? $url : '!'):
            $controller = new AgendamentoController($banco);
            $controller->cancelar((int)$matches[1]);
            break;

        default:
            http_response_code(404);
            echo json_encode(['erro' => 'Pagina nao encontrada!']);
            break;
    }

} catch (Exception $e) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['erro' => $e->getMessage()]);
}