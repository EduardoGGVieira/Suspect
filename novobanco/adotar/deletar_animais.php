<?php
session_start();
include("../conexao.php");

if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    die("Acesso negado.");
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo'];
$id_animal = $_POST['id_animal'] ?? null;

if (!$id_animal) {
    die("ID do animal inválido.");
}

// 1️⃣ Verifica se o animal pertence à ONG logada OU se o usuário é ADM
if ($tipo_usuario === "adm") {
    $permissao = true;
} else {
    $check = $conn->prepare("SELECT id FROM animal_encontrado WHERE id = ? AND id_ong = ?");
    $check->bind_param("ii", $id_animal, $id_usuario);
    $check->execute();
    $result = $check->get_result();
    $permissao = ($result->num_rows > 0);
}

if (!$permissao) {
    die("Erro: Você não tem permissão para deletar este animal.");
}

// 2️⃣ Deleta o animal
$stmt = $conn->prepare("DELETE FROM animal_encontrado WHERE id = ?");
$stmt->bind_param("i", $id_animal);

if ($stmt->execute()) {
    echo "Animal deletado com sucesso!";
} else {
    echo "Erro ao deletar: " . $conn->error;
}
?>
