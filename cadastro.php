<?php
require 'db/conexao.php';

// Função para limpar
function limpar($v) { return trim($v ?? ""); }

// Variáveis
$erroNome = $erroCPF = $erroMatricula = $erroSenha = $erroConfirmar = "";
$sucesso = "";

$nome = $cpf_raw = $cpf = $matricula = $senha = $confirmar_senha = "";

// PROCESSAMENTO DO FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = limpar($_POST['nome']);
    $cpf_raw = limpar($_POST['cpf']);
    $matricula = limpar($_POST['matricula']);
    $senha = limpar($_POST['senha']);
    $confirmar_senha = limpar($_POST['confirmar_senha']);

    // CPF sem máscara
    $cpf = preg_replace('/\D/', '', $cpf_raw);

    // ----------------- VALIDAÇÕES ----------------

    // Nome
    if ($nome === "") {
        $erroNome = "Campo obrigatório";
    } elseif (!preg_match("/^[A-Za-zÀ-ÿ' ]+$/", $nome)) {
        $erroNome = "O nome deve conter apenas letras e espaços.";
    }

    // CPF
    if ($cpf === "") {
        $erroCPF = "Campo obrigatório";
    } elseif (!preg_match("/^[0-9]{11}$/", $cpf)) {
        $erroCPF = "O CPF deve ter 11 dígitos numéricos.";
    }

    // Matrícula
    if ($matricula === "") {
        $erroMatricula = "Campo obrigatório";
    } elseif (!preg_match("/^[0-9]+$/", $matricula)) {
        $erroMatricula = "A matrícula deve conter apenas números.";
    }

    // Senha
    if ($senha === "") {
        $erroSenha = "Informe uma senha.";
    } elseif (strlen($senha) < 6) {
        $erroSenha = "A senha deve ter no mínimo 6 caracteres.";
    }

    // Confirmar senha
    if ($confirmar_senha === "") {
        $erroConfirmar = "Confirme sua senha.";
    } elseif ($senha !== $confirmar_senha) {
        $erroConfirmar = "As senhas não coincidem.";
    }

    // ----------------- INSERÇÃO NO BANCO ----------------
    if ($erroNome === "" && $erroCPF === "" && $erroMatricula === "" && $erroSenha === "" && $erroConfirmar === "") {

        try {

            // Verificar duplicidade
            $verificar = $pdo->prepare("SELECT id FROM usuario WHERE cpf = ? OR matricula = ? LIMIT 1");
            $verificar->execute([$cpf, $matricula]);

            if ($verificar->rowCount() > 0) {

                // Verifica qual duplicou
                $c1 = $pdo->prepare("SELECT id FROM usuario WHERE cpf = ?");
                $c1->execute([$cpf]);

                if ($c1->rowCount() > 0) $erroCPF = "CPF já cadastrado.";

                $c2 = $pdo->prepare("SELECT id FROM usuario WHERE matricula = ?");
                $c2->execute([$matricula]);

                if ($c2->rowCount() > 0) $erroMatricula = "Matrícula já cadastrada.";

            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $ins = $pdo->prepare("INSERT INTO usuario (nome_completo, cpf, matricula, senha) VALUES (?, ?, ?, ?)");
                $ok = $ins->execute([$nome, $cpf, $matricula, $senhaHash]);

                if ($ok) {
                    $sucesso = "Seu cadastro foi realizado com sucesso!";
                    $nome = $cpf = $matricula = $senha = $confirmar_senha = "";
                }
            }

        } catch (Exception $e) {
            $sucesso = "Erro interno: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SCIVACE - Cadastro</title>
<link rel="stylesheet" href="css/cadastro.css">

<style>
    .erro { color: #c62828; font-size: 0.9rem; margin: 4px 0; }
    .sucesso { color: #2e7d32; font-size:1rem; font-weight: bold; margin-bottom: 10px; }
    .senha-container { position: relative; }
    .toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
    }
</style>
</head>
<body>

<main>
    <div class="centro">
        <fieldset>

            <figure><img class="logo" src="img/logo_scivace.PNG"></figure>
            <h1>SCIVACE</h1>

            <?php if ($sucesso): ?>
                <p class="sucesso"><?= $sucesso ?></p>
            <?php endif; ?>

            <form method="POST">

                <!-- Nome -->
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" placeholder="Seu nome completo" required>
                <?php if ($erroNome): ?><div class="erro"><?= $erroNome ?></div><?php endif; ?>

                <!-- CPF -->
                <label>CPF</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" value="<?= htmlspecialchars($cpf) ?>" placeholder="Somente números" required>
                <?php if ($erroCPF): ?><div class="erro"><?= $erroCPF ?></div><?php endif; ?>

                <!-- Matrícula -->
                <label>Matrícula</label>
                <input type="text" id="matricula" name="matricula" value="<?= htmlspecialchars($matricula) ?>" placeholder="Somente números" required>
                <?php if ($erroMatricula): ?><div class="erro"><?= $erroMatricula ?></div><?php endif; ?>

                <!-- Senha -->
                <label>Senha</label>
                <div class="senha-container">
                    <input type="password" id="senha" name="senha" minlength="6" placeholder="Mínimo 6 caracteres" required>
                    <span class="toggle" onclick="toggleSenha('senha')">👁</span>
                </div>
                <?php if ($erroSenha): ?><div class="erro"><?= $erroSenha ?></div><?php endif; ?>

                <!-- Confirmar senha -->
                <label>Confirmar Senha</label>
                <div class="senha-container">
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" required>
                    <span class="toggle" onclick="toggleSenha('confirmar_senha')">👁</span>
                </div>
                <?php if ($erroConfirmar): ?><div class="erro"><?= $erroConfirmar ?></div><?php endif; ?>

                <button type="submit">Salvar</button>
            </form>

            <a class="voltar" href="index.php">Voltar</a>
        </fieldset>
    </div>
</main>

<script>
// Mostrar/ocultar senha
function toggleSenha(id) {
    let campo = document.getElementById(id);
    campo.type = campo.type === "password" ? "text" : "password";
}

// CPF e matrícula apenas números
document.getElementById("cpf").addEventListener("input", function(){
    this.value = this.value.replace(/\D/g, '').slice(0, 11);
});

document.getElementById("matricula").addEventListener("input", function(){
    this.value = this.value.replace(/\D/g, '');
});
</script>

</body>
</html>
