<?php
session_start();
include("../conexao.php"); // Ajuste o caminho para seu arquivo de conexão

// 1. Verificação de Permissão do Administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'adm') {
    die("Acesso negado. Apenas administradores podem deletar usuários.");
}

// 2. Processamento da Requisição POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ID do usuário a ser deletado (vindo do campo hidden)
    $id_usuario_a_deletar = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    // ID do administrador logado (para evitar autodeleção)
    // Você precisará garantir que o ID do ADM seja salvo na SESSION durante o login.
    // Vamos assumir que você tem uma forma de buscar o ID do ADM pelo email ou nome:
    $email_adm_logado = $_SESSION['email']; 
    
    // Busca o ID e Tipo do ADM logado para dupla verificação de segurança
    $sql_adm = "SELECT id, tipo FROM usuario WHERE email = '$email_adm_logado'";
    $result_adm = mysqli_query($conn, $sql_adm);
    
    if (mysqli_num_rows($result_adm) == 0) {
        die("Erro de segurança: Administrador logado não encontrado.");
    }
    
    $adm_logado = mysqli_fetch_assoc($result_adm);
    $id_adm_logado = $adm_logado['id'];

    if (!$id_usuario_a_deletar) {
        die("ID de usuário inválido para deleção.");
    }
    
    // 3. Segurança: Impedir que o ADM logado se delete
    if ($id_usuario_a_deletar === $id_adm_logado) {
        die("Erro: Administradores não podem deletar a si mesmos através desta interface.");
    }

    // 4. Segurança: Verificar o tipo de usuário que está sendo deletado
    $sql_check = "SELECT tipo FROM usuario WHERE id = $id_usuario_a_deletar";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) == 0) {
        $_SESSION['mensagem'] = "Usuário com ID $id_usuario_a_deletar não encontrado.";
        header("Location: ../index.html");
        exit;
    }

    $usuario_delecao = mysqli_fetch_assoc($result_check);
    
    // Segurança: Impedir que o ADM delete outro ADM (opcional, mas recomendado)
    if ($usuario_delecao['tipo'] === 'adm') {
        die("Erro: Não é permitido deletar outros administradores via interface de usuário.");
    }

    // 5. Execução da Deleção
    // Devido ao FOREIGN KEY ON DELETE CASCADE, deletar o registro na tabela `usuario`
    // automaticamente deletará as entradas relacionadas em `voluntario` ou `ong`.
    $sql_delete = "DELETE FROM usuario WHERE id = $id_usuario_a_deletar";

    if (mysqli_query($conn, $sql_delete)) {
        $_SESSION['mensagem'] = "Usuário deletado com sucesso!";
        header("Location: index.html"); // Redireciona para a lista de usuários
        exit;
    } else {
        die("Erro ao deletar usuário: " . mysqli_error($conn));
    }

   

} else {
    // Redireciona se a requisição não for POST (acesso direto)
    header("Location: ../index.html");
    exit;
}
?>