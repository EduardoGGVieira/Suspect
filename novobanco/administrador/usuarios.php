<?php

session_start();


// Apenas administradores devem acessar esta lista
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'adm') {
    header('Content-Type: application/json');
    http_response_code(403); // Acesso negado
    echo json_encode(["erro" => "Acesso não autorizado. Apenas administradores podem ver esta página."]);
    exit();
}

include("../conexao.php");

// Garante que a saída será JSON
header('Content-Type: application/json; charset=utf-8');

// Consulta SQL para selecionar TODOS os usuários cadastrados no sistema
// (diferente do ong.php que filtra por tipo='ong')
$sql = "SELECT id, email, nome, telefone, tipo, endereco FROM usuario"; 

// Executa a consulta SQL
$result = mysqli_query($conn, $sql); 

// Verifica se houve erro na consulta
if (!$result) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao executar a consulta: " . mysqli_error($conn)]);
    mysqli_close($conn);
    exit();
}

// Busca todos os dados como um array associativo
$data = mysqli_fetch_all($result, MYSQLI_ASSOC); 

// Nota: Diferente do ong.php, a tabela 'usuario' não armazena BLOBs de imagem diretamente, 
// então a lógica de "logos" e `base64_encode` não é necessária aqui.

// Converte o array em JSON e exibe
echo json_encode($data, JSON_UNESCAPED_UNICODE); 

// Libera a memória e fecha a conexão
mysqli_free_result($result); 
mysqli_close($conn); 

?>