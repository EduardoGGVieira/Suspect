<?php
// 1. INICIAR SESSÃO
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login/index.html");
    exit();
}

include("../../conexao.php");

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. COLETA E VALIDAÇÃO BÁSICA DOS DADOS
    $id_form = $_POST['id'] ?? null;
    $id_session = $_SESSION['id'];

    // Garante que o ID do formulário corresponde ao ID da sessão
    if ($id_form != $id_session || $id_session === null) {
        $_SESSION['mensagem_perfil'] = ['tipo' => 'erro', 'texto' => 'Erro de segurança: ID do usuário inválido.'];
        header("Location: index.html");
        exit();
    }
    

    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $tipo = $_POST[''] ?? null;
    $cpf = $_POST['cpf'] ?? null; 
    $data_nascimento = $_POST['data_nascimento'] ?? null;

    // Inicia uma transação
    $conn->begin_transaction();
    
    try {
        // 1. ATUALIZAÇÃO DA TABELA USUARIO (usa as variáveis recém-coletadas)
        $sql_update_user = "UPDATE usuario SET nome=?, email=?, telefone=?, endereco=? WHERE id=?";
        $stmt_user = $conn->prepare($sql_update_user);
        $stmt_user->bind_param('ssssi', $nome, $email, $telefone, $endereco, $id_session);
        if (!$stmt_user->execute()) {
            throw new Exception("Falha ao atualizar dados básicos do usuário.");
        }
        $stmt_user->close();
        
        // 2. ATUALIZAÇÃO DA TABELA VOLUNTARIO
        $sql_update_vol = "UPDATE voluntario SET cpf=?, data_nascimento=? WHERE id=?";
        $stmt_vol = $conn->prepare($sql_update_vol);
        $stmt_vol->bind_param('ssi', $cpf, $data_nascimento, $id_session);
        if (!$stmt_vol->execute()) {
            throw new Exception("Falha ao atualizar dados de voluntário.");
        }
        $stmt_vol->close();

        // TUDO CERTO: Commit (salva) as alterações
        $conn->commit();
        $_SESSION['mensagem_perfil'] = ['tipo' => 'sucesso', 'texto' => 'Seu perfil foi atualizado com sucesso!'];
        header("Location: ../logout.php");
        exit();
        
        // Atualiza o nome na sessão
        if ($nome !== null) {
            $_SESSION['nome'] = $nome;
        }
        
    } catch (Exception $e) {
        // ALGO FALHOU: Rollback (desfaz) as alterações
        $conn->rollback();
        $_SESSION['mensagem_perfil'] = ['tipo' => 'erro', 'texto' => 'Erro ao salvar perfil: ' . $e->getMessage()];
    }

    header("Location: /suspect/novobanco/index.html");
    exit();


}

// Feche a conexão fora do IF
$conn->close();
?>