<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "fluxoc");

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta para verificar registros de saída
$consultaSaidas = "SELECT * FROM lancamentos WHERE tipo = 'saida'";
$resultadoSaidas = $conn->query($consultaSaidas);

// Verificar se existem registros de saída
if ($resultadoSaidas->num_rows > 0) {
    echo "Existem registros de saída na tabela 'lancamentos'.";
} else {
    echo "Não existem registros de saída na tabela 'lancamentos'.";
}

// Fechar a conexão
$conn->close();
?>
