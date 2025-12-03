<?php
session_start();

// Se não estiver logado, volta para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Dados do usuário 
$nome_usuario = $_SESSION['usuario_nome'] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Home</title>
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

        <h1><span>Home</span></h1>

        <!--CAIXA AZUL 1-->
        <section class="caixa">
            <div class="div1">
                <figure>
                    <img src="img/ace_2.jpg" alt="agente de combate às endemias">
                </figure>
                <p>Quer iniciar o registro de uma visita? Clique no botão abaixo e registre sua visita no SCIVACE.</p>
            </div>

            <div class="div2">
                <p>Registre suas visitas</p>
                <a href="visita.php"><button class="botao_entrar">Entrar</button></a>
            </div>
        </section>

        <!--CAIXA AZUL 2-->
        <section class="caixa">
            <div class="div1">
                <figure>
                    <img src="img/relatorio.jpg" alt="relatório">
                </figure>
                <p>Quer conferir suas visitas feitas hoje? Clique no botão abaixo e consulte seus relatórios.</p>
            </div>

            <div class="div2">
                <p>Confira seu relatório</p>
                <a href="relatorio.php"><button class="botao_entrar">Entrar</button></a>
            </div>
        </section>

    </main>

    <footer>
        <p class="autora">Contato: (27) 997406498 | Todos os direitos reservados à Marcela Bermudes</p>
    </footer>

</body>

</html>
