<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = $_POST["valor"];
    $data = $_POST["data"];
    $descricao = $_POST["descricao"];
    
    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "fluxocaixa");

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Preparar a instrução SQL
    $sql = "INSERT INTO entradas (valor, data, descricao) VALUES ('$valor', '$data', '$descricao')";

    // Executar a consulta
    if ($conn->query($sql) === TRUE) {
        // Redirecionar para a página de entradas ou exibir uma mensagem de sucesso
        header("Location: entradas.php?success=1");
        exit();
    } else {
        echo "Erro ao inserir dados: " . $conn->error;
    }

    $conn->close();
}
?>
