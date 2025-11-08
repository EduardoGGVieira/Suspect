<?php
$conn = mysqli_connect("localhost:3307", "root", "", "suspect"); 

$result = mysqli_query($conn, "SELECT  id, nome, raca, idade, peso, localizacao, altura, estado, estado_saude, castrado, vacinas, sexo, observacoes, foto FROM animal_encontrado"); 

$data = mysqli_fetch_all($result, MYSQLI_ASSOC); // Busca todos os dados como um array associativo
echo json_encode($data); // Converte o array em JSON e exibe

mysqli_free_result($result); // Libera a memória associada ao resultado
mysqli_close($conn); 
?>

