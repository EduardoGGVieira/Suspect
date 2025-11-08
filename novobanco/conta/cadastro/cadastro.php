<?php
include("../../conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);
    $telefone = trim($_POST['telefone']);
    $tipo = trim($_POST['tipo']);
    $endereco = trim($_POST['endereco']);

    // voluntario
    $cpf = trim($_POST['cpf']);
    $datanascimento = trim($_POST['datenascimento']);

    // ong
    $cnpj = trim($_POST['cnpj']);
    $descricao = trim($_POST['descricao']);
    $financeiro = trim($_POST['financeiro']);
    $link = trim($_POST['link']);
    $nome_link = trim($_POST['nome_link']);

    // img 
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        // Pega o caminho temporário do arquivo enviado
        $imagem_tmp = $_FILES["imagem"]["tmp_name"];
        // Obtém o tamanho do arquivo enviado (em bytes)
        $tamanho = $_FILES["imagem"]["size"];
        // Define o tamanho máximo permitido (em bytes) → 4 MB
        $limite = 1 * 1024 * 1024;

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
                $imagem = addslashes(file_get_contents($imagem_tmp));
            } catch (mysqli_sql_exception $e) {
                // Caso ocorra um erro ao salvar, exibe uma mensagem com o erro do banco
                echo "<p style='color:red;'>❌ Erro ao transformar imagem em binario: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    } else {
        $imagem = 'Sem logo';
    }

    // Criptografa a senha antes de salvar
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se já existe
    $verifica = mysqli_query($conn, "SELECT email FROM usuario WHERE email = '$email'");

    if (mysqli_num_rows($verifica) > 0) {
        echo "<p style='color:red;'>Usuário já cadastrado!</p>";
        echo "<a href='../login'>Faça login aqui</a>";
    } else {
        $usu = "INSERT INTO usuario (email, nome, senha, telefone, tipo, endereco) VALUES ('$email', '$nome', '$senha_hash', '$telefone', '$tipo', '$endereco')";
        if (mysqli_query($conn, $usu)) {
            // Pega o ID gerado automaticamente
            $id = mysqli_insert_id($conn);

            if ($tipo === 'vol') {
                $sql = "INSERT INTO voluntario (id, cpf, data_nascimento)
                        VALUES ('$id', '$cpf', '$datanascimento')";
            } else {
                $sql = "INSERT INTO ong (id, cnpj, descricao, logo, financeiro, link, nome_link) VALUES ('$id', '$cnpj', '$descricao', '$imagem', '$financeiro', '$link', '$nome_link')";
            }

            if (mysqli_query($conn, $sql)) {
                echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
                echo "<a href='../login'>Faça login aqui</a>";
            } else {
                echo "<p style='color:red;'>Erro ao cadastrar tipo específico!</p>";
                echo mysqli_error($conn);
            }
        } else {
            echo "<p style='color:red;'>Erro ao cadastrar usuário!</p>";
            echo mysqli_error($conn);
        }
    }
}
