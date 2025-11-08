<?php
session_start();
include("../../conexao.php");

// 1. Verificação de Sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo'])) {
    // Redireciona o usuário se não houver email ou tipo na sessão
    echo json_encode(['erro' => 'Sessão inválida ou expirada. Redirecionando para login.']);
    // É recomendado adicionar um redirecionamento aqui, mas o frontend pode lidar com a resposta de erro.
    exit;
}

$email = $_SESSION['email'];
$tipo = $_SESSION['tipo'];

// 2. Validação Rígida do Tipo ANTES da consulta 
$tipos_validos = ['vol', 'ong', 'adm'];
if (!in_array($tipo, $tipos_validos)) {
    echo json_encode(['erro' => 'Tipo de usuário inválido na sessão.']);
    // Força a saída da sessão para evitar loop/uso indevido de tipo.
    session_unset();
    session_destroy();
    exit;
}


$usu = mysqli_query($conn, "SELECT id, nome, email, telefone, endereco, senha, tipo FROM usuario WHERE email = '$email'");
if (!$usu) {
    echo json_encode(['erro' => 'Erro na consulta principal: ' . mysqli_error($conn)]);
    exit;
}

$data = mysqli_fetch_assoc($usu);

// 3. Verifica se o usuário existe no banco (pode ter sido apagado, mas a sessão ainda ativa)
if (!$data || mysqli_num_rows($usu) == 0) {
    echo json_encode(['erro' => 'Usuário não encontrado no banco de dados.']);
    session_unset();
    session_destroy();
    exit;
}

$id_usuario = $data['id'];


if ($tipo === 'vol') {
    $especifico = mysqli_query($conn, "SELECT cpf, data_nascimento, id_validador FROM voluntario WHERE id = '$id_usuario'");
} elseif ($tipo === 'ong') {
    $especifico = mysqli_query($conn, "SELECT cnpj, id_validador, descricao, logo, financeiro, link, nome_link FROM ong WHERE id = '$id_usuario'");
} elseif ($tipo === 'adm') {
    $especifico = mysqli_query($conn, "SELECT matricula, cpf FROM administrador WHERE id = '$id_usuario'");
} else {
    // Este bloco deve ser inalcançável devido à verificação inicial.
    // Manteremos a destruição da sessão por segurança.
    echo json_encode(['erro' => 'Tipo de usuário inválido.']);
    session_unset();
    session_destroy();
    exit;
}

if (!$especifico) {
    echo json_encode(['erro' => 'Erro na consulta específica: ' . mysqli_error($conn)]);
    exit;
}

$dataesp = mysqli_fetch_assoc($especifico);

if ($dataesp) {
  $data = array_merge($data, $dataesp);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

mysqli_free_result($usu);
mysqli_free_result($especifico);
mysqli_close($conn);

?>