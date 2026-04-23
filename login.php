<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios_admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha_hash'])) {

            $_SESSION['admin'] = $usuario['email'];

            header("Location: dashboard.php");
            exit;

        } else {
            $erro = "Senha incorreta";
        }

    } else {
        $erro = "Usuário não encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Acesso restrito</title>

<style>
body{
    background:#0f0f0f;
    color:white;
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:#1a1a1a;
    padding:40px;
    border-radius:10px;
    width:300px;
    text-align:center;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:5px;
}

button{
    width:100%;
    padding:10px;
    background:#d4af37;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

.erro{
    color:red;
}
</style>

</head>
<body>

<div class="box">
    <h2>Área da Imobiliária</h2>

    <?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>