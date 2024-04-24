<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$nomeUsuario = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #0b2447;
            color: #fff;
        }
        .container {
            text-align: center;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Bem-vindo, <?php echo $nomeUsuario; ?> (Usuário Comum)!</h1>
        <!-- Adicione conteúdo específico do painel do usuário aqui -->
        <a href="index.php" class="btn btn-primary">Sair</a>
        <a href="home.php" class="btn btn-success ml-2">Ir para a Home</a>
    </div>
</body>
</html>
