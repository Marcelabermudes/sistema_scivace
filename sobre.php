<?php
session_start();
require_once 'db/conexao.php'; // Conecta com o banco

// Se não estiver logado, volta para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Dados do usuário (opcional)
$nome_usuario = $_SESSION['usuario_nome'] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sobre.css">
    <title>Sobre</title>
</head>

<body>

    <!--TOPO/CABEÇALHO-->
    <header>

        <!--LOGO DO MENU-->
        <figure>
            <img class="logo" src="img/logo_scivace.PNG" alt="Logomarca SCIVACE">
        </figure>

        <div class="menu">

            <!--MENU DE NAVEGAÇÃO-->
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="visita.php">Registrar visita</a></li>
                    <li><a href="relatorio.php">Relatório</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>

            <h2>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>!</h2>
        </div>

    </header>

    <!--MAIN-->
    <main>

        <h1>Sobre <span>nós</span></h1>

        <p>O SCIVACE (Sistema de Coleta de Informações de Visitas de Agentes de Combate à Endemia) é uma plataforma
            desenvolvida para apoiar o trabalho diário dos agentes de saúde no controle e prevenção da dengue.</p> <br>

        <p>O sistema foi projetado para simplificar e organizar o registro de informações durante as visitas
            domiciliares, garantindo maior agilidade, precisão e segurança nos dados coletados em campo.</p> <br>

        <p>Com o SCIVACE, é possível registrar dados detalhados sobre os imóveis visitados, tipos de inspeções
            realizadas, tratamento aplicado, focos identificados e observações adicionais, tudo em uma interface prática
            e acessível.</p> <br>

        <p>Além disso, o sistema permite gerar relatórios completos para acompanhamento e análise, facilitando a tomada
            de decisões por parte das equipes de saúde e gestores.</p> <br>

        <p>Nosso objetivo é contribuir para a eficiência das ações de combate à dengue, promovendo a saúde pública
            através da tecnologia.</p> <br>

        <!-- GALERIA DE FOTOS -->
        <section class="galeria_geral">
            <img src="img/ace_1.jpg" alt="agentes de endemias 1">
            <img src="img/ace_3.jpg" alt="agentes de endemias 2">
            <img src="img/ace_4.jpg" alt="agentes de endemias 3">
            <img src="img/ace_6.jpg" alt="agentes de endemias 4">
            <img src="img/ace_7.jpg" alt="agentes de endemias 5">
            <img src="img/ace_8.jpg" alt="agentes de endemias 6">
        </section>

    </main>

    <!--RODAPÉ-->
    <footer>
        <p class="autora">Contato: (27) 997406498 | Todos os direitos reservados à Marcela Bermudes</p>
    </footer>

</body>
</html>
