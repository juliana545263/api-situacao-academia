<?php
// banco.php

// Inicia a sessão para guardar os dados enquanto o servidor estiver rodando
session_start();

// Se a lista de situações não existir na sessão, criamos ela vazia
if (!isset($_SESSION['situacoes'])) {
    $_SESSION['situacoes'] = [];
}

// Função para gerar um ID único para cada aluno
function gerarId() {
    return uniqid();
}
?>