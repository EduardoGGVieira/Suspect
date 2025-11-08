<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug_delecao.log');
error_reporting(E_ALL);
session_start();

// =========================================================
// DEFINIÇÃO DE CAMINHOS
// =========================================================
$LOGIN_PAGE = "/suspect/novobanco/conta/login";
$PERFIL_PAGE = "/suspect/novobanco/conta/perfil/";
$HOME_PAGE = "/suspect/novobanco/"; // Caminho solicitado: index de novobanco
$CONTATO_PAGE = "/suspect/novobanco/suporte/";

// Redireciona se a sessão não estiver ativa
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: {$LOGIN_PAGE}");
    exit();
}

// Inclui o arquivo de conexão (caminho relativo à pasta 'editar'/'deletarperfil.php')
include("../../../conexao.php");

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("=== DELETAR PERFIL ATIVADO ===");
    error_log("POST recebido: " . print_r($_POST, true));
    error_log("SESSION atual: " . print_r($_SESSION, true));
    $id_form = $_POST['id'] ?? null;
    $id_session = $_SESSION['id'];
    $tipo_usuario = $_SESSION['tipo'];

    // 3. VALIDAÇÃO DE SEGURANÇA FINAL
    if ($id_form != $id_session || $id_session === null) {
        error_log("Tentativa de deleção inválida para ID: " . $id_form);
        $_SESSION['mensagem_perfil'] = ['tipo' => 'erro', 'texto' => 'Ação inválida. Tente novamente.'];
        header("Location: {$PERFIL_PAGE}");
        exit();
    }

    $conn->begin_transaction();
    $sucesso_delecao = true;

    try {
        // =======================================================
        // 1. REMOÇÃO DE REGISTROS DEPENDENTES (CHAVES ESTRANGEIRAS)
        // ... (Este bloco permanece o mesmo)
        // =======================================================

        // 1a. Registros de Doação e Adoção (Afeta VOLUNTÁRIO e ONG - usa id_usuario/id_ong)
        $sql_delete_doacao = "DELETE FROM doacao WHERE id_usuario = ? OR id_ong = ?";
        $stmt_doacao = $conn->prepare($sql_delete_doacao);
        $stmt_doacao->bind_param('ii', $id_session, $id_session); 
        if (!$stmt_doacao->execute()) { throw new Exception("Falha ao deletar registros de doação."); }
        $stmt_doacao->close();

        $sql_delete_adocao = "DELETE FROM adocao WHERE id_usuario = ? OR id_ong = ?";
        $stmt_adocao = $conn->prepare($sql_delete_adocao);
        $stmt_adocao->bind_param('ii', $id_session, $id_session); 
        if (!$stmt_adocao->execute()) { throw new Exception("Falha ao deletar registros de adoção."); }
        $stmt_adocao->close();

        // 1b. Registros específicos de ONG (se for ONG)
        if ($tipo_usuario === 'ong') {
            // Delete Campanhas (campanha)
            $sql_delete_camp = "DELETE FROM campanha WHERE id_ong = ?";
            $stmt_camp = $conn->prepare($sql_delete_camp);
            $stmt_camp->bind_param('i', $id_session);
            if (!$stmt_camp->execute()) { throw new Exception("Falha ao deletar campanhas."); }
            $stmt_camp->close();

            // Seta id_ong para NULL nos animais encontrados (IMPORTANTE para manter o animal)
            $sql_update_animal = "UPDATE animal_encontrado SET id_ong = NULL WHERE id_ong = ?";
            $stmt_animal = $conn->prepare($sql_update_animal);
            $stmt_animal->bind_param('i', $id_session);
            if (!$stmt_animal->execute()) { throw new Exception("Falha ao atualizar FK de animais encontrados."); }
            $stmt_animal->close();
        }

        // =======================================================
        // 2. DELETE NAS SUB-TABELAS (ONG, VOLUNTARIO, ADM)
        // ... (Este bloco permanece o mesmo)
        // =======================================================
        $sub_tabela_deletar = '';

        if ($tipo_usuario === 'vol') {
            $sub_tabela_deletar = 'voluntario';
        } elseif ($tipo_usuario === 'ong') {
            $sub_tabela_deletar = 'ong';
        } elseif ($tipo_usuario === 'adm') {
            $sub_tabela_deletar = 'administrador';
        }
        
        if ($sub_tabela_deletar) {
            $sql_delete_sub = "DELETE FROM {$sub_tabela_deletar} WHERE id = ?";
            $stmt_sub = $conn->prepare($sql_delete_sub);
            $stmt_sub->bind_param('i', $id_session); 
            $sucesso_subtabela = $stmt_sub->execute();
            $stmt_sub->close();
        }

        // 3. DELETE NA TABELA USUARIO
        if ($sucesso_subtabela) {
             $sql_delete_u = "DELETE FROM usuario WHERE id = ?";
             $stmt_u = $conn->prepare($sql_delete_u);
             $stmt_u->bind_param('i', $id_session); 
             $sucesso_usuario = $stmt_u->execute();
             $stmt_u->close();
        } else {
             throw new Exception("Falha ao deletar da sub-tabela.");
        }


        if ($sucesso_usuario) {
            $conn->commit(); 
            
            // =========================================================
            // AÇÃO CRÍTICA PÓS-DELEÇÃO
            // 1. Limpa todas as variáveis de sessão
            session_unset();
            // 2. Destrói o ID de sessão no servidor
            session_destroy();
            // 3. FORÇA A REMOÇÃO DO COOKIE NO NAVEGADOR (CORREÇÃO)
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/'); // Define o cookie como expirado
            }
            // =========================================================
            
            // Redireciona para a home da pasta novobanco (CORRIGIDO)
            header("Location: {$HOME_PAGE}?deleted=true");
            exit();

        } else {
            $conn->rollback();
            throw new Exception("Erro durante a deleção da tabela principal 'usuario'.");
        }

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Erro de transação ao deletar perfil ID {$id_session}: " . $e->getMessage());
        
        // FALHA: Erro no banco de dados
        $_SESSION['mensagem_perfil'] = ['tipo' => 'erro', 'texto' => 'Erro fatal ao deletar o perfil. Contate o suporte.'];

        // Redireciona para a página de contato
        header("Location: {$CONTATO_PAGE}");
        exit();
    }
}

$conn->close();
?>