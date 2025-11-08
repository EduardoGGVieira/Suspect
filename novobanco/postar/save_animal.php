<?php

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "suspect";

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ong = (isset($_POST['id_ong']) && is_numeric($_POST['id_ong'])) ? (int)$_POST['id_ong'] : null;
    $nome = ($_POST['nome']);
    $raca = ($_POST['raca']);
    $idade = ($_POST['idade']);
    $peso = ($_POST['peso']);
    $sexo = ($_POST['sexo']);
    $localizacao = ($_POST['localizacao']);
    $altura = ($_POST['altura']);
    $estado_saude = ($_POST['estado_saude']);
    // $castrado = ($_POST['castrado']);  nao existe mais ;-;   
    $vacinas = ($_POST['vacinas']); 
    $observacoes = ($_POST['observacoes']);

// TESTE DE ADD FOTO!

     $caminho_foto = NULL; // Valor padrão para o BD

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $arquivo_tmp = $_FILES['foto']['tmp_name'];
        $nome_original = basename($_FILES['foto']['name']);
        

        $diretorio_destino = 'uploads/animais/'; 
        
        // Gera um nome de arquivo único para evitar colisões
        $extensao = pathinfo($nome_original, PATHINFO_EXTENSION);
        $nome_unico = uniqid('animal_', true) . '.' . $extensao;
        $caminho_destino = $diretorio_destino . $nome_unico;

        // Tenta mover o arquivo
        if (move_uploaded_file($arquivo_tmp, $caminho_destino)) {
            // Salva o caminho relativo (URL) que será usado no HTML
            $caminho_foto = $caminho_destino; 
        } else {
            echo'Você é burro';
        }
    }


    // Consulta SQL para inserir os dados   // removido castrado
    $sql = $conn->prepare("INSERT INTO animal_encontrado (nome, raca, idade, peso, sexo, localizacao, altura, estado_saude, vacinas, observacoes, foto, id_ong) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssdsdsssssis", $nome, $raca, $idade,$peso,$sexo,$localizacao,$altura,$estado_saude, $vacinas, $observacoes, $caminho_foto, $id_ong);


     
    $botao_voltar = '<a href="index.html" style="display: block; margin-top: 15px;  color: #333; background-color: #f0f0f0; padding: 50px 15px; border-radius: 5px; border: 1px solid #f80000ff;">Voltar ao Formulário</a>';



    if ($sql->execute()) {
        echo '<p style="color: white; background-color: #4CAF50; padding: 250px; border-radius: 20px; font-size: 18px; text-align: center;">Deu certo essa bosta</p>';
        echo $botao_voltar; 
    } else {
        echo '<p style="color: white; background-color: #f44336; padding: 10px; border-radius: 5px; font-size: 18px; text-align: center;">Deu ruim testa outra coisa</p>';
        echo $botao_voltar; 
    }
      

// Fecha a conexão com o banco
$sql->close(); // Fechar o statement
$conn->close();
}
?>