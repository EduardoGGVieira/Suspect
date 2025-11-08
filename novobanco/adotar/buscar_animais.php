<?php
// Configurações do banco de dados TROCAR 3307 caso esteja na puc!
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "suspect";

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// ADD O TESTE do id ong !!!
$result = mysqli_query($conn, "SELECT id, nome, idade, raca, foto, id_ong FROM animal_encontrado"); 



$data = mysqli_fetch_all($result, MYSQLI_ASSOC); // Busca todos os dados como um array associativo
echo json_encode($data); // Converte o array em JSON e exibe

mysqli_free_result($result); // Libera a memória associada ao resultado
mysqli_close($conn); // Fecha a conexão com o banco de dados

?>