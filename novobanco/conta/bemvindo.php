<?php
session_start();

if (!isset($_SESSION['tipo'])) {
    // Se tentar acessar direto, redireciona para login
    header("Location: login");
    exit;
}

$nome = $_SESSION['nome'];
$tipo = $_SESSION['tipo'];
?>

<h2>Seja bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h2>
<h2>Você entrou como, <?php echo htmlspecialchars($tipo); ?>!</h2>


<p><a href="logout.php">Sair</a></p>