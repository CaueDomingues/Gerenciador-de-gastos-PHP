<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #121212; /* Cor de fundo escura */
            color: #FFFFFF; /* Texto principal em branco */
        }
        h2 {
            color: #BB86FC; /* Título em roxinho */
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
        .text-success {
            color: #03DAC6; /* Texto de saldo positivo em verde-azulado */
        }
        .text-danger {
            color: #FF6B6B; /* Texto de saldo negativo em vermelho */
        }
        .table {
            background-color: #1E1E1E; /* Fundo da tabela escura */
        }
    </style>
</head>
<body>
    
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "fluxocaixa");

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar o tipo de usuário (direitos)
if ($_SESSION["direitos"] == 1) {
    // Usuário comum
    $permitirRegistroEntrada = false;
    $permitirRegistroSaida = false;
} elseif ($_SESSION["direitos"] == 2) {
    // Administrador
    $permitirRegistroEntrada = true;
    $permitirRegistroSaida = true;
}

// Consulta para recuperar todas as entradas do mês selecionado
$consultaEntradas = "SELECT valor, data, descricao FROM entradas WHERE MONTH(data) = ?";
$stmtEntradas = $conn->prepare($consultaEntradas);
$stmtEntradas->bind_param("i", $mesSelecionado);
$mesSelecionado = date("n");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mesSelecionado = $_POST["mesSelecionado"];
    $stmtEntradas->bind_param("i", $mesSelecionado);
}

$stmtEntradas->execute();
$resultadoEntradas = $stmtEntradas->get_result();

// Consulta para recuperar todas as saídas do mês selecionado
$consultaSaidas = "SELECT valor, data, descricao FROM saidas WHERE MONTH(data) = ?";
$stmtSaidas = $conn->prepare($consultaSaidas);
$stmtSaidas->bind_param("i", $mesSelecionado);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmtSaidas->bind_param("i", $mesSelecionado);
}

$stmtSaidas->execute();
$resultadoSaidas = $stmtSaidas->get_result();

// Inicializar arrays para armazenar os valores por mês
$entradasPorMes = array_fill(1, 12, 0);
$saidasPorMes = array_fill(1, 12, 0);

// Processar entradas
while ($registro = $resultadoEntradas->fetch_assoc()) {
    $valor = floatval($registro["valor"]);
    $mes = date("n", strtotime($registro["data"])); // Extrai o número do mês (1 a 12)
    $entradasPorMes[$mes] += $valor;
}

// Processar saídas
while ($registro = $resultadoSaidas->fetch_assoc()) {
    $valor = floatval($registro["valor"]);
    $mes = date("n", strtotime($registro["data"])); // Extrai o número do mês (1 a 12)
    $saidasPorMes[$mes] += $valor;
}

// Variável para armazenar o mês selecionado (padrão: mês atual)
$mesSelecionado = date("n");

// Nomes dos meses em português
$nomesMeses = [
    1 => "Janeiro",
    2 => "Fevereiro",
    3 => "Março",
    4 => "Abril",
    5 => "Maio",
    6 => "Junho",
    7 => "Julho",
    8 => "Agosto",
    9 => "Setembro",
    10 => "Outubro",
    11 => "Novembro",
    12 => "Dezembro"
];

// Se o formulário foi submetido, use o mês selecionado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mesSelecionado = $_POST["mesSelecionado"];
}

// Consulta SQL para selecionar todos os lançamentos do mês selecionado
$consultaLancamentosMes = "SELECT * FROM lancamentos WHERE MONTH(data) = ?";
$stmtLancamentosMes = $conn->prepare($consultaLancamentosMes);
$stmtLancamentosMes->bind_param("i", $mesSelecionado);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmtLancamentosMes->bind_param("i", $mesSelecionado);
}

$stmtLancamentosMes->execute();
$resultadoLancamentosMes = $stmtLancamentosMes->get_result();

// Calcula o saldo total de acordo com os valores de entrada e saída
$saldoTotal = array_sum($entradasPorMes) - array_sum($saidasPorMes);
$corSaldo = ($saldoTotal >= 0) ? 'text-success' : 'text-danger';
?>

<div class="container mt-4">
    <h2 class="mb-4">Dashboard</h2>
    
    <canvas id="graficoGastos"></canvas>
    
    <h3 class="mt-4">Saldo Total</h3>
    <p class="<?php echo $corSaldo; ?>">R$ <?php echo number_format($saldoTotal, 2, ',', '.'); ?></p>

    <?php if ($permitirRegistroEntrada) { ?>
        <a href="entradas.php" class="btn btn-success">Registrar Nova Entrada</a>
    <?php } ?>
    
    <?php if ($permitirRegistroSaida) { ?>
        <a href="saidas.php" class="btn btn-danger">Registrar Nova Saída</a>
        <a href="limpar_registros.php" class="btn btn-danger">Limpar Registros</a>
    <?php } ?>
    
    <a href="home.php" class="btn btn-danger position-absolute top-0 end-0 m-4">
        <i class="fas fa-home"></i>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('graficoGastos').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_values($nomesMeses)); ?>,
            datasets: [{
                label: 'Entradas',
                data: <?php echo json_encode(array_values($entradasPorMes)); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Cor das barras das entradas (verde)
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Saídas',
                data: <?php echo json_encode(array_values($saidasPorMes)); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Cor das barras das saídas (vermelho)
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>