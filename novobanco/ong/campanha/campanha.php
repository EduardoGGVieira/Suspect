<?php
session_start();
// O caminho da conexão pode precisar de ajuste dependendo de onde este arquivo estiver
include("../../conexao.php"); 

header('Content-Type: application/json; charset=utf-8');

// 1. Verificação de Permissão e Sessão
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'ong') {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso não autorizado. Apenas ONGs podem visualizar suas campanhas."]);
    exit();
}

// 2. Busca do ID da ONG Logada
// Você precisa ter o ID do usuário (ONG) logado na sessão. Assumindo que você salva o ID
// na sessão, se não, precisará buscá-lo pelo email. Vamos buscar pelo email da sessão:
$email_ong = $_SESSION['email']; 

$sql_id = "SELECT id FROM usuario WHERE email = ?";
$stmt_id = $conn->prepare($sql_id);
$stmt_id->bind_param("s", $email_ong);
$stmt_id->execute();
$result_id = $stmt_id->get_result();

if ($result_id->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["erro" => "ONG não encontrada no sistema."]);
    exit();
}

$ong_data = $result_id->fetch_assoc();
$id_ong_logada = $ong_data['id'];

$stmt_id->close();


// 3. Consulta SQL para buscar TODAS as campanhas DESSA ONG
// Campos da tabela campanha: id, id_animal_adoc, id_ong, data_publicacao
// Nota: Seu `campaign.php` insere `nome_campanha`, `descricao_campanha`, e `foto_campanha` que NÃO existem na sua tabela `campanha` no `Suspect.sql`. 
// Vou assumir que você tem outra tabela de campanhas ou que a tabela `campanha` foi alterada para incluir esses campos.
// Se a tabela `campanha` original for usada, só posso retornar ID, ID_ANIMAL e DATA.

// *** Assumindo que a estrutura da sua tabela de `campanha` no banco é, na verdade, a estrutura que o `campaign.php` está inserindo: ***
$sql = "SELECT id, nome_campanha, descricao_campanha, data_publicacao, foto_campanha 
        FROM campanha 
        WHERE id_ong = ? 
        ORDER BY data_publicacao DESC"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ong_logada);
$stmt->execute();
$result = $stmt->get_result();

$campanhas = [];

while($row = $result->fetch_assoc()) {
    // Converte a imagem BINÁRIA para base64 para o JavaScript
    if (!empty($row['foto_campanha'])) {
        $row['foto_campanha'] = base64_encode($row['foto_campanha']);
    }
    $campanhas[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($campanhas, JSON_UNESCAPED_UNICODE);

?>