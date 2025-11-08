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
    $nome_campanha = ($_POST['nome_campanha']);
    $descricao_campanha = ($_POST['descricao_campanha']);
    $data_publicacao = ($_POST['data_publicacao']);

    $imagem_tmp = $_FILES["foto_campanha"]["tmp_name"];
    // Obtém o tamanho do arquivo enviado (em bytes)
    $tamanho = $_FILES["foto_campanha"]["size"];

    // Define o tamanho máximo permitido (em bytes) → 4 MB
    $limite = 4 * 1024 * 1024;

    // Se o arquivo for maior que o limite, mostra erro e não envia
    if ($tamanho > $limite) {
        // Exibe mensagem de erro mostrando o tamanho da imagem e o limite permitido
        echo "<p style='color:red;'>⚠️ A imagem é muito grande ("
            . round($tamanho / 1024 / 1024, 2)
            . " MB). Envie uma imagem menor que "
            . ($limite / 1024 / 1024)
            . " MB.</p>";
    } else {
        try {
            // Lê o conteúdo binário da imagem e escapa caracteres especiais para inserção no SQL
            $imagem = file_get_contents($imagem_tmp);
            // Cria o comando SQL para inserir o nome e o conteúdo da imagem na tabela
            $stmt = $conn->prepare("insert into campanha (nome_campanha, descricao_campanha, data_publicacao, foto_campanha) values (?, ?, ?, ?)");
            $stmt->bind_param("ssss" ,$nome_campanha,$descricao_campanha,$data_publicacao,$imagem);
            $stmt->execute();
            // Exibe mensagem de sucesso
            echo "<p style='color:green;'>✅ Imagem salva com sucesso!</p>";
        } catch (mysqli_sql_exception $e) {
            // Caso ocorra um erro ao salvar, exibe uma mensagem com o erro do banco
            echo "<p style='color:red;'>❌ Erro ao salvar: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>