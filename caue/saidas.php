<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$mensagem_sucesso = ""; // Inicializa a mensagem de sucesso vazia

if (isset($_GET["success"]) && $_GET["success"] == "1") {
    $mensagem_sucesso = "Valor registrado com sucesso!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Saída</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #121212; /* Cor de fundo escura */
            color: #000; /* Texto principal em branco */
        }
        .container {
            margin-top: 50px;
            background-color: #1E1E1E !important; /* Fundo da área do formulário escura */
            border-radius: 8px; /* Cantos arredondados */
            padding: 20px; /* Espaçamento interno */
        }
        h2 {
            color: #BB86FC; /* Título em roxinho */
        }
        label {
            color: #FFFFFF !important; /* Texto dos rótulos em branco */
            font-weight: bold !important; /* Texto em negrito */
        }
        .form-control {
            background-color: #292929; /* Cor de fundo dos campos de entrada */
            color: #FFFFFF; /* Texto dos campos de entrada em branco */
            border: 1px solid #6200EE; /* Borda dos campos de entrada em roxo */
        }
        .form-control:focus {
            background-color: #121212; /* Cor de fundo ao focar */
            color: #FFFFFF; /* Texto ao focar em branco */
            border: 1px solid #BB86FC; /* Borda ao focar em roxinho */
        }
        .btn {
            background-color: #6200EE; /* Botões em roxo */
            border: none;
        }
        .btn-primary {
            background-color: #6200EE; /* Botões primários em roxo */
        }
        .btn-success {
            background-color: #03DAC6; /* Botões de sucesso em verde-azulado */
        }
        .btn-danger {
            background-color: #FF6B6B; /* Botões de perigo em vermelho */
        }
        .table {
            background-color: #FFFFFF; /* Fundo da tabela branco */
        }
        .table td {
            border: 1px solid #292929 !important; /* Borda da tabela */
            font-weight: bold !important; /* Texto em negrito */
            color: #000 !important; /* Texto das células da tabela em preto */
        }
        .table th {
            background-color: #292929; /* Fundo do cabeçalho da tabela */
            border: 1px solid #292929 !important; /* Borda da tabela */
            font-weight: bold !important; /* Texto em negrito */
            color: #FFFFFF !important; /* Texto das células da tabela em branco */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Registrar Saída</h2>
        <form action="processar_saida.php" method="post">
            <div class="mb-3">
                <label for="valor" class="form-label">Valor:</label>
                <input type="text" class="form-control" id="valor" name="valor" required>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data:</label>
                <input type="date" class="form-control" id="data" name="data" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Saída</button>
            <a href="dashboard.php" class="btn btn-success">Voltar para o Dashboard</a>
        </form>

        <h3 class="mt-4">Registros de Saídas</h3>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "fluxocaixa");

                // Verificar a conexão
                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                // Consulta SQL para recuperar registros de saída
                $sql = "SELECT * FROM saidas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['data']}</td>";
                        echo "<td>{$row['descricao']}</td>";
                        echo "<td>{$row['valor']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhum registro de saída encontrado.</td></tr>";
                }

                // Fechar a conexão
                $conn->close();
                ?>
            </tbody>
        </table>

        <a href="home.php" class="btn btn-danger position-absolute top-0 end-0 m-4">
            <i class="fas fa-home"></i>
        </a>
    </div>

    <!-- Inclua os scripts do Bootstrap e do jQuery no final do corpo -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para exibir a caixa de diálogo após o registro -->
    <script>
        $(document).ready(function() {
            <?php if (!empty($mensagem_sucesso)) { ?>
                $('#successModal').modal('show');
            <?php } ?>
        });
    </script>

    <!-- Modal de sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php echo $mensagem_sucesso; ?>
                </div>
                <div class="modal-footer">
                    <a href="dashboard.php" class="btn btn-secondary">Ir para o Dashboard</a>
                    <a href="saidas.php" class="btn btn-primary">Registrar Nova Saída</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
