<?php
// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os dados do formulário
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Informações de conexão ao banco de dados
    $host = "localhost"; 
    $usuario_db = "root";       
    $senha_db = "";            
    $banco_de_dados = "fluxocaixa";  

    // Conectar ao banco de dados
    $conexao = mysqli_connect($host, $usuario_db, $senha_db, $banco_de_dados);

    // Verificar a conexão
    if (!$conexao) {
        die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Consulta SQL para verificar o login
    $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
    
    // Use declarações preparadas para proteger contra injeção SQL
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    // Obtenha o resultado da consulta
    $resultado = mysqli_stmt_get_result($stmt);

    // Verifique se houve um resultado
    if ($row = mysqli_fetch_assoc($resultado)) {
        // Login bem-sucedido, obtenha os direitos do usuário do banco de dados
        $direitos = $row["direitos"];

        // Iniciar a sessão
        session_start();

        // Armazene os dados do usuário na sessão
        $_SESSION["username"] = $username;
        $_SESSION["direitos"] = $direitos;

        // Redirecionar para a página de home
        header("Location: home.php");
        exit;
    } else {
        // Login falhou, exibir mensagem de erro na página de login
        $error_message = "Usuário ou senha incorretos. Tente novamente.";
        include("index.php"); // Exibe a mensagem de erro na página de login
    }

    // Feche a conexão com o banco de dados
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}
?>
