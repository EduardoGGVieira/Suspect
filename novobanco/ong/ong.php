<?php

// $conn = mysqli_connect("localhost:3306", "root", "1Marcelo2", "suspect"); // Conecta ao banco de dados MySQL
// $conn = mysqli_connect("localhost:3307", "root", "", "suspect"); // Conecta ao banco de dados MySQL

include("../conexao.php");

$result = mysqli_query($conn, "SELECT id, Nome, Telefone, Endereco FROM usuario WHERE tipo='ong'"); // Executa a consulta SQL para selecionar todos os dados da tabela "ong"
$img = mysqli_query($conn, "SELECT logo FROM ong"); // Executa a consulta SQL para selecionar todos os dados da tabela "ong"

$data = mysqli_fetch_all($result, MYSQLI_ASSOC); // Busca todos os dados como um array associativo
$logos = mysqli_fetch_all($img, MYSQLI_ASSOC);


// pega cada linha da tabela ong e cria um array com a imagem em base64 e os outros dados
foreach ($data as $key => $value) {
  $data[$key]['logo'] = base64_encode($logos[$key]['logo']);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE); // Converte o array em JSON e exibe

mysqli_free_result($result); // Libera a memória associada ao resultado
mysqli_close($conn); // Fecha a conexão com o banco de dados
