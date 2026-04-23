<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? '');
    $telefone = trim($_POST["telefone"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $interesse = trim($_POST["interesse"] ?? '');
    $faixa_preco = trim($_POST["faixa_preco"] ?? '');
    $mensagem = trim($_POST["mensagem"] ?? '');

    if (empty($nome) || empty($telefone) || empty($email) || empty($interesse)) {
        die("Preencha todos os campos obrigatórios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }

    $sql = "INSERT INTO clientes (nome, telefone, email, interesse, faixa_preco, mensagem)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $telefone, $email, $interesse, $faixa_preco, $mensagem);

    if ($stmt->execute()) {
        header("Location: obrigado.php");
        exit;
    } else {
        die("Erro ao salvar cadastro: " . $conn->error);
    }
}
?>