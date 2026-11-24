<?php
// index.php

// Define que a resposta será em JSON
header("Content-Type: application/json; charset=UTF-8");
// Permite que qualquer origem acesse (CORS - importante para testes)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Inclui o nosso "banco de dados"
require_once 'banco.php';

// Pega o método HTTP da requisição (GET, POST, etc)
$metodo = $_SERVER['REQUEST_METHOD'];
// Pega o caminho da URL (ex: /situacao/123)
$caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Quebra o caminho em partes: ['', 'situacao', '123']
$partes = explode('/', trim($caminho, '/'));

// Verifica se a rota começa com /situacao
if ($partes[0] !== 'situacao') {
    http_response_code(404);
    echo json_encode(["mensagem" => "Rota não encontrada."]);
    exit;
}

// Se houver um ID na URL (ex: /situacao/123), pega ele
$id = isset($partes[1]) ? $partes[1] : null;

// Pega o corpo da requisição (para POST e PUT)
$dadosRecebidos = json_decode(file_get_contents("php://input"), true);

// ==========================================
// LÓGICA DOS ENDPOINTS
// ==========================================

switch ($metodo) {
    case 'POST':
        // 1. POST /situacao (Cadastrar)
        if (!isset($dadosRecebidos['nome'], $dadosRecebidos['unidadeCurricular'], $dadosRecebidos['aprovado'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Todos os campos são obrigatórios."]);
            break;
        }

        $novaSituacao = [
            "id" => gerarId(),
            "nome" => $dadosRecebidos['nome'],
            "unidadeCurricular" => $dadosRecebidos['unidadeCurricular'],
            "aprovado" => $dadosRecebidos['aprovado']
        ];

        $_SESSION['situacoes'][] = $novaSituacao;
        http_response_code(201);
        echo json_encode($novaSituacao);
        break;

    case 'GET':
        // 2. GET /situacao/{id} (Consultar)
        if (!$id) {
            http_response_code(400);
            echo json_encode(["mensagem" => "ID não fornecido."]);
            break;
        }

        // Procura o aluno pelo ID
        $encontrado = null;
        foreach ($_SESSION['situacoes'] as $situacao) {
            if ($situacao['id'] === $id) {
                $encontrado = $situacao;
                break;
            }
        }

        if ($encontrado) {
            echo json_encode($encontrado);
        } else {
            http_response_code(404);
            echo json_encode(["mensagem" => "Estudante não encontrado."]);
        }
        break;

    case 'PUT':
        // 3. PUT /situacao/{id} (Atualizar APENAS aprovado)
        if (!$id) {
            http_response_code(400);
            echo json_encode(["mensagem" => "ID não fornecido."]);
            break;
        }

        if (!isset($dadosRecebidos['aprovado'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "O campo 'aprovado' é obrigatório."]);
            break;
        }

        $atualizado = false;
        // Percorre a lista para achar e atualizar
        foreach ($_SESSION['situacoes'] as $indice => $situacao) {
            if ($situacao['id'] === $id) {
                // Atualiza APENAS o campo aprovado
                $_SESSION['situacoes'][$indice]['aprovado'] = $dadosRecebidos['aprovado'];
                $atualizado = $_SESSION['situacoes'][$indice];
                break;
            }
        }

        if ($atualizado) {
            echo json_encode($atualizado);
        } else {
            http_response_code(404);
            echo json_encode(["mensagem" => "Estudante não encontrado."]);
        }
        break;

    case 'DELETE':
        // 4. DELETE /situacao/{id} (Remover)
        if (!$id) {
            http_response_code(400);
            echo json_encode(["mensagem" => "ID não fornecido."]);
            break;
        }

        $removido = false;
        foreach ($_SESSION['situacoes'] as $indice => $situacao) {
            if ($situacao['id'] === $id) {
                // Remove o item do array
                array_splice($_SESSION['situacoes'], $indice, 1);
                $removido = true;
                break;
            }
        }

        if ($removido) {
            echo json_encode(["mensagem" => "Registro removido com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["mensagem" => "Estudante não encontrado."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["mensagem" => "Método não permitido."]);
        break;
}
?>