<?php
session_start();
include("../../conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Busca o usuário no banco
    $sql = "SELECT id, email, senha, nome, tipo FROM usuario WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<p style='color:red;'>Usuário não cadastrado.</p>";
        echo "<a href='../cadastro'>Cadastre-se aqui</a>";
    } else {
        $user = mysqli_fetch_assoc($result);

        // Verifica a senha
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['id'] = $user['id']; // Salva a caceta do ID
            $_SESSION['tipo'] = $user['tipo']; // Salva tipo
            $_SESSION['nome'] = $user['nome']; // Salva nome
            $_SESSION['email'] = $user['email']; // Salva email
            echo "correto";
            header("Location: ../../");
            exit;
        } else {
            echo "<p style='color:red;'>Senha incorreta.</p>";
        }
    }
}
