<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Verificar se o usuário tem direitos de administrador (direitos igual a 2)
$direitos = $_SESSION["direitos"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #1e1e1e; /* Fundo escuro */
            color: #ffffff; /* Texto branco */
        }
        .navbar {
            background-color: #333; /* Cinza escuro para o barra de navegação */
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffffff; /* Texto branco para a marca do navbar */
        }
        .nav-link {
            font-size: 18px;
            color: #ffffff; /* Texto branco para os links de navegação */
        }
        .welcome-section {
            text-align: center;
            padding: 60px 0;
            background-color: #1e1e1e; /* Fundo mais escuro */
            color: #ffffff; /* Texto branco para o conteúdo principal */
        }
        .color-icon-white {
            color: #ffffff; 
        }
        .color-icon-green {
            color: #28a745; /* Verde semelhante ao Chat GPT */
        }
        .color-icon-red {
            color: #dc3545; /* Vermelho semelhante ao Chat GPT */
        }
        .color-icon-red-dark {
            color: #dc3545; 
        }
        .texto-preto {
            color: #ffffff !important;
        }
        
    </style>
    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand texto-preto" href="#">Investe</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link texto-preto" href="dashboard.php"><i class="fas fa-chart-bar color-icon-white"></i> Dashboard</a>
      </li>
      <?php if ($direitos == 2) { ?>
        <!-- Se o usuário tiver direitos de administrador, mostre os links para Entrada e Saída -->
        <li class="nav-item">
          <a class="nav-link texto-preto" href="entradas.php"><i class="fas fa-plus color-icon-green"></i> Entrada</a>
        </li>
        <li class="nav-item">
          <a class="nav-link texto-preto" href="saidas.php"><i class="fas fa-minus color-icon-red"></i> Saída</a>
        </li>
      <?php } ?>
      <li class="nav-item">
        <a class="nav-link texto-preto" href="index.php"><i class="fas fa-sign-out-alt color-icon-red-dark"></i> Sair</a>
      </li>
    </ul>
  </div>
</nav>
<div class="container welcome-section">
    <h2 class="text-center">Bem-vindo à Página Inicial da Investe, <?php echo $_SESSION["username"]; ?></h2>
    <div class="investment-text">
        <p class="lead">Descubra o Poder dos Investimentos</p>
        <p>Na jornada para a independência financeira, os investimentos desempenham um papel crucial. Este é o seu portal para o mundo dos investimentos inteligentes e gerenciamento financeiro eficaz.</p>
        <p>Investir é uma maneira estratégica de fazer seu dinheiro trabalhar para você, aumentar seu patrimônio e alcançar seus objetivos financeiros a longo prazo.</p>
        <p>Explore diversas opções de investimento, desde ações e títulos até imóveis e fundos mútuos. Saiba como criar uma carteira diversificada que reduz riscos e maximiza retornos.</p>
        <p>Nossos recursos educacionais fornecem insights valiosos e estratégias comprovadas para ajudá-lo a tomar decisões financeiras informadas. Lembre-se, investir requer paciência, disciplina e um plano sólido.</p>
        <p>Defina metas financeiras claras, acompanhe seu progresso e assista seu patrimônio crescer ao longo do tempo. Estamos aqui para apoiar você em cada passo da sua jornada de investimento.</p>
    </div>
</div>

<style>
    /* Estilos para a seção de texto de investimento */
    .investment-text {
        background-color: #333; /* Cor de fundo cinza mais escura */
        color: #ffffff; /* Cor do texto branco */
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .investment-text p {
        font-size: 18px;
        margin-bottom: 15px;
        text-align: justify;
    }

    /* Estilos para o título principal */
    .investment-text .lead {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
        color: #28a745; /* Verde semelhante ao Chat GPT */
    }
</style>




<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <!-- Inclua os scripts do Bootstrap e do Font Awesome no final do corpo -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
