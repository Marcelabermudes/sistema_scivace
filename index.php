<?php
session_start();
require_once 'db/conexao.php';

$erro = "";
$matricula = "";

// Função para limpar valores
function limpar($v) {
    return htmlspecialchars(trim($v ?? ""), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $matricula = limpar($_POST['matricula']);
    $senha = $_POST['senha'] ?? "";

    // --- VALIDAÇÃO ---
    if ($matricula === "" || $senha === "") {
        $erro = "Preencha todos os campos.";
    } elseif (!preg_match("/^[0-9]+$/", $matricula)) {
        $erro = "A matrícula deve conter apenas números.";
    } else {

        // Verifica se matrícula existe
        $sql = $pdo->prepare("SELECT * FROM usuario WHERE matricula = ?");
        $sql->execute([$matricula]);

        if ($sql->rowCount() === 1) {

            $usuario = $sql->fetch(PDO::FETCH_ASSOC);

            // Verifica senha hash
            if (password_verify($senha, $usuario['senha'])) {

                // Cria sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome_completo'];

                // Redireciona
                header("Location: home.php");
                exit();

            } else {
                $erro = "Senha incorreta!";
            }

        } else {
            $erro = "Matrícula não encontrada.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>SCIVACE</title>

    <style>
        /* Só para garantir legibilidade do erro */
        .erro {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <!--TOPO-->
    <header>
        <figure></figure>
    </header>

    <!--MAIN-->
    <main>
        <div class="centro">
            <fieldset>
                <figure>
                    <img class="logo" src="img/logo_scivace.PNG" alt="Logomarca SCIVACE">
                </figure>
                <h1>SCIVACE</h1>
                <p>Seja bem vindo ao Sistema de Coleta de Informações de Visitas de Agente de Combate às Endemias.</p>

                <!-- ERRO -->
                <?php if (!empty($erro)): ?>
                    <p class="erro"><?= $erro ?></p>
                <?php endif; ?>

                <!--FORMULÁRIO-->
                <form method="POST">

                    <label for="matricula">Matrícula</label>
                    <input type="text" id="matricula" name="matricula"
                           value="<?= $matricula ?>" placeholder="Digite sua matrícula" required>

                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    <div class="botoes">
                        <button type="submit" class="btn">Entrar</button>
                    </div>
                </form>

                <a class="novo_usuario" href="cadastro.php">Novo usuário</a>
            </fieldset>
        </div>
    </main>

    <!--RODAPÉ-->
    <footer>
        <p class="autora">Contato: (27) 997406498 | Todos os direitos reservados à Marcela Bermudes</p>
    </footer>

    <script>
        // Permitir apenas números na matrícula ao digitar
        document.getElementById("matricula").addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>

</body>
</html>
