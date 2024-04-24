<?php
session_start();

 

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

 

// Verifique se o usuário tem permissão para excluir registros (neste caso, apenas administradores)
if ($_SESSION["direitos"] != 2) {
    header("Location: dashboard.php");
    exit();
}

 

// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "fluxocaixa");

 

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

 

// Query SQL para excluir todos os registros de saídas
$excluirSaidasSQL = "DELETE FROM saidas";
$excluirEntradasSQL = "DELETE FROM entradas";

 

// Executar a consulta para excluir saídas
if ($conn->query($excluirSaidasSQL) === TRUE) {
    // Se a exclusão das saídas for bem-sucedida, execute a consulta para excluir entradas
    if ($conn->query($excluirEntradasSQL) === TRUE) {
        // Redirecionar de volta para o painel de administração com uma mensagem de sucesso
        header("Location: dashboard.php?success=1");
    } else {
        // Se houver um erro na exclusão das entradas, redirecione com uma mensagem de erro
        header("Location: dashboard.php?error=1");
    }
} else {
    // Se houver um erro na exclusão das saídas, redirecione com uma mensagem de erro
    header("Location: dashboard.php?error=1");
}

 

$conn->close();
?>