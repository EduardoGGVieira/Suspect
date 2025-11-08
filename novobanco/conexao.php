<?php
// $host = "localhost:3306";
// $senha = "1Marcelo2";

$usuario = "root";
$banco = "suspect";

$host = "localhost:3307";
$senha = "";

// Conexão com MySQL
$conn = mysqli_connect($host, $usuario, $senha, $banco);

mysqli_set_charset($conn, "utf8");
// Verifica se deu certo
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
