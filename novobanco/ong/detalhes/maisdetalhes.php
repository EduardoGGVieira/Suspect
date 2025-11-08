<?php

// $conn = mysqli_connect("localhost:3306", "root", "1Marcelo2", "suspect"); // Conecta ao banco de dados MySQL
// $conn = mysqli_connect("localhost:3307", "root", "", "suspect"); // Conecta ao banco de dados MySQL

include("../../conexao.php");

$usuario = mysqli_query($conn, "SELECT  id, email, nome,telefone, endereco FROM usuario WHERE tipo='ong'"); // Executa a consulta SQL para selecionar todos os dados da tabela "ong"
$ong = mysqli_query($conn, "SELECT cnpj, descricao, financeiro, link, nome_link FROM ong"); // Executa a consulta SQL para selecionar todos os dados da tabela "ong"
$img = mysqli_query($conn, "SELECT logo FROM ong"); // Executa a consulta SQL para selecionar todos os dados da tabela "ong"


$data = mysqli_fetch_all($usuario, MYSQLI_ASSOC); // Busca todos os dados como um array associativo
$dataongs = mysqli_fetch_all($ong, MYSQLI_ASSOC);
$logos = mysqli_fetch_all($img, MYSQLI_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['logo'] = base64_encode($logos[$key]['logo']);
    $data[$key]['cnpj'] = $dataongs[$key]['cnpj'];
    $data[$key]['descricao'] = $dataongs[$key]['descricao'];
    $data[$key]['financeiro'] = $dataongs[$key]['financeiro'];
    $data[$key]['link'] = $dataongs[$key]['link'];
    $data[$key]['nome_link'] = $dataongs[$key]['nome_link'];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE); // Converte o array em JSON e exibe

mysqli_free_result($usuario); // Libera a memória associada ao resultado
mysqli_free_result($ong); // Libera a memória associada ao resultado
mysqli_free_result($img); // Libera a memória associada ao resultado
mysqli_close($conn); // Fecha a conexão com o banco de dados
