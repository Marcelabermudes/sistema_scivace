<?php
session_start();
require_once 'db/conexao.php'; // Conecta com o banco

// Se não estiver logado, volta para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Dados do usuário
$nome_usuario = $_SESSION['usuario_nome'] ?? "Usuário";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpar e capturar dados do POST
    $bairro = limpaPost($_POST['bairro'] ?? '');
    $quarteirao = limpaPost($_POST['quarteirao'] ?? 0);
    $numero = limpaPost($_POST['numero'] ?? 0);
    $complemento = limpaPost($_POST['complemento'] ?? '');
    $rua = limpaPost($_POST['rua'] ?? '');
    $tipo_de_imovel = limpaPost($_POST['tipo_de_imovel'] ?? '');
    $tipo_de_visita = limpaPost($_POST['tipo_de_visita'] ?? '');
    $nome_morador = limpaPost($_POST['nome_morador'] ?? '');
    $observacao = limpaPost($_POST['observacao'] ?? '');
    $imovel_inspecionado = limpaPost($_POST['imovel_inspecionado'] ?? '');
    $imovel_tratado = limpaPost($_POST['imovel_tratado'] ?? '');
    $quant_deposito_eliminado = limpaPost($_POST['quant_deposito_eliminado'] ?? 0);
    $quant_focos_encontrados = limpaPost($_POST['quant_focos_encontrados'] ?? 0);
    $tipo_larvicida = limpaPost($_POST['tipo_larvicida'] ?? '');
    $quantidade_gramas = limpaPost($_POST['quantidade_gramas'] ?? 0);

    // Depósitos
    $tipo_deposito_a1 = limpaPost($_POST['tipo_deposito_a1'] ?? 0);
    $tipo_deposito_a2 = limpaPost($_POST['tipo_deposito_a2'] ?? 0);
    $tipo_deposito_b = limpaPost($_POST['tipo_deposito_b'] ?? 0);
    $tipo_deposito_c = limpaPost($_POST['tipo_deposito_c'] ?? 0);
    $tipo_deposito_d1 = limpaPost($_POST['tipo_deposito_d1'] ?? 0);
    $tipo_deposito_d2 = limpaPost($_POST['tipo_deposito_d2'] ?? 0);
    $tipo_deposito_e = limpaPost($_POST['tipo_deposito_e'] ?? 0);

    try {
        $sql = "INSERT INTO visitas (
            bairro, quarteirao, numero, complemento, rua, tipo_de_imovel, tipo_de_visita,
            nome_morador, observacao, imovel_inspecionado, imovel_tratado, quant_deposito_eliminado,
            quant_focos_encontrados, tipo_larvicida, quantidade_gramas,
            tipo_deposito_a1, tipo_deposito_a2, tipo_deposito_b, tipo_deposito_c,
            tipo_deposito_d1, tipo_deposito_d2, tipo_deposito_e
        ) VALUES (
            :bairro, :quarteirao, :numero, :complemento, :rua, :tipo_de_imovel, :tipo_de_visita,
            :nome_morador, :observacao, :imovel_inspecionado, :imovel_tratado, :quant_deposito_eliminado,
            :quant_focos_encontrados, :tipo_larvicida, :quantidade_gramas,
            :tipo_deposito_a1, :tipo_deposito_a2, :tipo_deposito_b, :tipo_deposito_c,
            :tipo_deposito_d1, :tipo_deposito_d2, :tipo_deposito_e
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':bairro' => $bairro,
            ':quarteirao' => $quarteirao,
            ':numero' => $numero,
            ':complemento' => $complemento,
            ':rua' => $rua,
            ':tipo_de_imovel' => $tipo_de_imovel,
            ':tipo_de_visita' => $tipo_de_visita,
            ':nome_morador' => $nome_morador,
            ':observacao' => $observacao,
            ':imovel_inspecionado' => $imovel_inspecionado,
            ':imovel_tratado' => $imovel_tratado,
            ':quant_deposito_eliminado' => $quant_deposito_eliminado,
            ':quant_focos_encontrados' => $quant_focos_encontrados,
            ':tipo_larvicida' => $tipo_larvicida,
            ':quantidade_gramas' => $quantidade_gramas,
            ':tipo_deposito_a1' => $tipo_deposito_a1,
            ':tipo_deposito_a2' => $tipo_deposito_a2,
            ':tipo_deposito_b' => $tipo_deposito_b,
            ':tipo_deposito_c' => $tipo_deposito_c,
            ':tipo_deposito_d1' => $tipo_deposito_d1,
            ':tipo_deposito_d2' => $tipo_deposito_d2,
            ':tipo_deposito_e' => $tipo_deposito_e
        ]);

        $mensagem = "Visita registrada com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao salvar visita: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/visita.css">
    <title>Registro de Visita</title>
</head>

<body>
    <header>
        <figure>
            <img class="logo" src="img/logo_scivace.PNG" alt="Logomarca SCIVACE">
        </figure>
        <div class="menu">
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

    <main>
        <h1>Registro de <span>visita</span></h1>
        <?php if ($mensagem): ?>
            <p style="color: green; font-weight: bold;"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

    <!--FORMULÁRIO-->
    <form method="post">
        <div class="conteudo">
                <div class="colunas coluna_1">
                    <!--BAIRRO-->
                    <div class="secao">
                        <label class="titulo" for="bairro">Bairro<span style="color:red">*</span></label></label>
                        <select class="drop_largura" name="bairro" id="bairro">
                            <option value="lagoa">Lagoa</option>
                            <option value="bairro_das_laranjeiras">Bairro das Laranjeiras</option>
                            <option value="portal_de_jacaraipe">Portal de Jacaraípe</option>
                            <option value="enseada_de_jacaraipe">Enseada de Jacaraípe</option>
                        </select>
                    </div>
                    <!--NUMERO-->
                    <div class="secao numeros">
                        <div class="numero titulo">
                            <label for="quarteirao">Quarteirão<span style="color:red">*</span></label></label><br>
                            <input type="number" id="quarteirao" name="quarteirao" min="1" max="1000" placeholder="0" required>
                        </div>
                        <div class="numero titulo">
                            <label for="numero">N° Imóvel<span style="color:red">*</span></label></label><br>
                            <input type="number" id="numero" name="numero" min="1" max="1000" placeholder="0" required>
                        </div>
                        <div class="numero titulo">
                            <label for="complemento">Compl.</label><br>
                            <input type="number" id="complemento" name="complemento" min="1" max="1000" placeholder="0">
                        </div>

                    </div>
                    <!--RUA-->
                    <div class="secao texto_pequeno">
                        <label class="titulo" for="rua">Rua</label>
                        <input class="rua" type="text" id="rua" name="rua" value="" placeholder="Rua das Flores"><br>
                    </div>

                    <!--TIPO DE IMÓVEL 2-->
                    <div class="secao">
                        <p class="titulo">Tipo de imóvel<span style="color:red">*</span></label></p>
                        <div class="colunas_radio">
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="residencia" name="tipo_de_imovel" value="residencia" required>
                                <label for="residencia">Residência</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="outros" name="tipo_de_imovel" value="outros" required> <label
                                    for="outros">Outros</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="comercio" name="tipo_de_imovel" value="comercio" required> <label
                                    for="comercio">Comercio</label>
                            </div>
                            <div class="espacamento_coluna_radio">

                                <input type="radio" id="pe" name="tipo_de_imovel" value="pe" required> <label
                                    for="pe">PE</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="terreno_baldio" name="tipo_de_imovel" value="terreno_baldio" required>
                                <label for="terreno_baldio">TB</label>
                            </div>

                        </div>
                    </div>

                    <!--TIPO DE VISITA 2-->
                    <div class="secao">
                        <p class="titulo">Tipo de visita<span style="color:red">*</span></label></p>
                        <div class="colunas_radio">
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="aberta" name="tipo_de_visita" value="aberta" required> <label
                                    for="aberta">Aberta</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="recusada" name="tipo_de_visita" value="recusada" required>
                                <label for="recusada">Recusada</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="fechada" name="tipo_de_visita" value="fechada" required> <label
                                    for="fechada">Fechada</label>
                            </div>
                            <div class="espacamento_coluna_radio">
                                <input type="radio" id="recuperada" name="tipo_de_visita" value="recuperada"> <label
                                    for="recuperada" required>Recuperada</label>
                            </div>
                        </div>
                    </div>
                    <!--NOME DO MORADOR-->
                    <div class="secao texto_pequeno">
                        <label class="titulo" for="nome_morador">Nome do morador
                        </label>
                        <input class="morador" type="text" id="nome_morador" name="nome_morador" value=""
                            placeholder="João, Maria.."><br>
                    </div>
                    <!--OBSERVAÇÃO-->
                    <div class="secao texto_grande altura_secao">
                        <label class=" titulo" for="observacao">Observação</label>
                        <textarea class="observacao" id="observacao" name="observacao" rows="5" cols="50"
                            placeholder="Ex: casa com mato alto e muito lixo."></textarea>
                    </div>

                </div>
                <div class="colunas coluna_2">

                    <!--IMÓVEL INSPECIONADO 2-->
                    <div class="secao ">
                        <p class="titulo">Imóvel inspecionado<span style="color:red">*</span></label></p>
                        <div class="espacamento_radio">
                            <input type="radio" id="imovel_inspecionado_sim" name="imovel_inspecionado" value="sim" required>
                            <label for="imovel_inspecionado_sim">Sim</label>
                            <input type="radio" id="imovel_inspecionado_nao" name="imovel_inspecionado" value="nao" required> <label
                                for="imovel_inspecionado_nao">Não</label>
                        </div>
                    </div>

                    <!--IMÓVEL TRATADO 2-->
                    <div class="secao">
                        <p class="titulo">Imóvel tratado<span style="color:red">*</span></label></p>
                        <div class="espacamento_radio">
                            <input type="radio" id="imovel_tratado_sim" name="imovel_tratado" value="sim" required>
                            <label for="imovel_tratado_sim">Sim</label>
                            <input type="radio" id="imovel_tratado_nao" name="imovel_tratado" value="nao" required> <label
                                for="imovel_tratado_nao">Não</label>
                        </div>
                    </div>

                    <!--DEPOSITOS 2-->
                    <div class="secao">
                        <label class="titulo" for="quant_deposito_eliminado">Qtd. de depósitos
                            eliminados</label>
                        <input type="number" id="quant_deposito_eliminado" name="quant_deposito_eliminado" min="1"
                            max="1000" placeholder="0">
                    </div>

                    <!--TOTAL FOCOS-->
                    <div class="secao">
                        <label class="titulo" for="quant_focos_encontrados">Qtd. de focos encontrados</label>
                        <input type="number" id="quant_focos_encontrados" name="quant_focos_encontrados" min="1" max="1000"
                            placeholder="0">
                    </div>

                    <!--LIPO DE LARVICIDA 2-->
                    <div class="secao">
                        <label class="titulo" for="tipo_larvicida">Tipo de larvicida</label>
                        <select class="drop_l3" name="tipo_larvicida" id="tipo_larvicida">
                            <option value="l3">L3</option>
                        </select>

                    </div>

                    <!--QUANTIDADE DE GRAMAS 2-->
                    <div class="secao">
                        <label class="titulo" for="quantidade_gramas">Qtd. de gramas</label>
                        <input type="number" id="quantidade_gramas" name="quantidade_gramas" min="1" max="1000"
                            placeholder="0">
                    </div>

                    <div class="secao altura_secao">
                        <p class="titulo">Qtd. de depósito inspecionado</p>
                        <div class="tipo_deposito">
                            <div class="codigo">
                                <label for="tipo_deposito_a1">A1</label><br>
                                <input class="A1" type="number" id="tipo_deposito_a1" name="tipo_deposito_a1" min="1"
                                    max="1000" placeholder="0">
                            </div>

                            <div class="codigo">
                                <label for="tipo_deposito_a2">A2</label><br>
                                <input class="A1" type="number" id="tipo_deposito_a2" name="tipo_deposito_a2" min="1"
                                    max="1000" placeholder="0">
                            </div>

                            <div class="codigo">
                                <label for="tipo_deposito_b">B</label><br>
                                <input class="A1" type="number" id="tipo_deposito_b" name="tipo_deposito_b" min="1"
                                    max="1000" placeholder="0">
                            </div>

                            <div class="codigo">
                                <label for="tipo_deposito_c">C</label><br>
                                <input class="A1" type="number" id="tipo_deposito_c" name="tipo_deposito_c" min="1"
                                    max="1000" placeholder="0">
                            </div>
                            <div class="codigo">
                                <label for="tipo_deposito_d1">D1</label><br>
                                <input class="A1" type="number" id="tipo_deposito_d1" name="tipo_deposito_d1" min="1"
                                    max="1000" placeholder="0">
                            </div>

                            <div class="codigo">
                                <label for="tipo_deposito_d2">D2</label><br>
                                <input class="A1" type="number" id="tipo_deposito_d2" name="tipo_deposito_d2" min="1"
                                    max="1000" placeholder="0">
                            </div>

                            <div class="codigo">
                                <label for="tipo_deposito_e">E</label><br>
                                <input class="A1" type="number" id="tipo_deposito_e" name="tipo_deposito_e" min="1"
                                    max="1000" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="botoes">
                <button type="submit" class="enviar">Enviar</button>
            </div>
    </form>
</main>

<footer>
    <hr>
    <p class="autora">Contato: (27) 997406498 | Todos os direitos reservados a autora Marcela Bermudes</p>
</footer>

</body>
</html>
