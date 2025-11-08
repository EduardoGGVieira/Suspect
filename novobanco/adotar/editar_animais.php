<?php
session_start();
include("../conexao.php");

// 1️⃣ Verifica login
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    die("Acesso negado.");
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo'];

$id_animal = $_POST['id_animal'] ?? null;
$nome = $_POST['nome'] ?? null;
$idade = $_POST['idade'] ?? null;
$raca = $_POST['raca'] ?? null;

if (!$id_animal || !$nome || !$idade || !$raca) {
    die("Dados incompletos.");
}

// 2️⃣ Verifica se o animal pertence à ONG logada OU se é ADM
if ($tipo_usuario === "adm") {
    // Admin pode editar qualquer um
    $permissao = true;
} else {
    // ONG só pode editar os seus
    $check = $conn->prepare("SELECT id FROM animal_encontrado WHERE id = ? AND id_ong = ?");
    $check->bind_param("ii", $id_animal, $id_usuario);
    $check->execute();
    $result = $check->get_result();
    $permissao = ($result->num_rows > 0);
}

if (!$permissao) {
    die("Erro: Você não tem permissão para editar este animal.");
}

// 3️⃣ Atualiza
$stmt = $conn->prepare("UPDATE animal_encontrado SET nome=?, idade=?, raca=? WHERE id=?");
$stmt->bind_param("sssi", $nome, $idade, $raca, $id_animal);

if ($stmt->execute()) {
    echo "Animal atualizado com sucesso!";
} else {
    echo "Erro ao atualizar: " . $conn->error;
}
?>
