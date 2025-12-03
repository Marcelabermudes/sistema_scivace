<?php
session_start();
require_once 'db/conexao.php';

// -------------------------
// FILTRO POR DATA (OPCIONAL)
// -------------------------
$data_inicio = $_POST['data_inicio'] ?? null;
$data_fim    = $_POST['data_fim'] ?? null;

$where = [];
$params = [];

if ($data_inicio && $data_fim) {
    $where[] = "DATE(data_registro) BETWEEN ? AND ?";
    $params[] = $data_inicio;
    $params[] = $data_fim;
}

$whereSql = "";
if (count($where) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $where);
}

// -------------------------
// BUSCA REGISTROS
// -------------------------
$sql = $pdo->prepare("SELECT * FROM visitas $whereSql ORDER BY data_registro DESC");
$sql->execute($params);
$registros = $sql->fetchAll(PDO::FETCH_ASSOC);

// -------------------------
// INICIALIZA TOTAIS
// -------------------------
$totais = [
    'total_imoveis' => 0,
    'residencia' => 0,
    'comercio' => 0,
    'terreno_baldio' => 0,
    'outros' => 0,
    'pe' => 0,
    'imoveis_inspecionados' => 0,
    'tratados' => 0,
    'depositos_eliminados' => 0,
    'focos_encontrados' => 0,
    'gramas_usadas' => 0,
    'depositos_inspecionados' => 0,
    'A1'=>0, 'A2'=>0, 'B'=>0, 'C'=>0, 'D1'=>0, 'D2'=>0, 'E'=>0
];

// -------------------------
// CALCULA TOTAIS
// -------------------------
foreach($registros as $r) {
    $totais['total_imoveis']++;

    switch($r['tipo_de_imovel']) {
        case 'Residência': $totais['residencia']++; break;
        case 'Comércio':   $totais['comercio']++; break;
        case 'Terreno Baldio': $totais['terreno_baldio']++; break;
        case 'Outros':     $totais['outros']++; break;
        case 'PE':         $totais['pe']++; break;
    }

    $totais['imoveis_inspecionados'] += ($r['imovel_inspecionado'] == 'sim') ? 1 : 0;
    $totais['tratados'] += ($r['imovel_tratado'] == 'sim') ? 1 : 0;
    $totais['depositos_eliminados'] += $r['quant_deposito_eliminado'];
    $totais['focos_encontrados'] += $r['quant_focos_encontrados'];
    $totais['gramas_usadas'] += $r['quantidade_gramas'];

    // Depósitos inspecionados (soma de todos tipos)
    $totais['depositos_inspecionados'] += $r['tipo_deposito_a1'] + $r['tipo_deposito_a2'] + $r['tipo_deposito_b'] +
                                          $r['tipo_deposito_c'] + $r['tipo_deposito_d1'] + $r['tipo_deposito_d2'] +
                                          $r['tipo_deposito_e'];

    $totais['A1'] += $r['tipo_deposito_a1'];
    $totais['A2'] += $r['tipo_deposito_a2'];
    $totais['B'] += $r['tipo_deposito_b'];
    $totais['C'] += $r['tipo_deposito_c'];
    $totais['D1'] += $r['tipo_deposito_d1'];
    $totais['D2'] += $r['tipo_deposito_d2'];
    $totais['E'] += $r['tipo_deposito_e'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Relatório Diário</title>
<link rel="stylesheet" href="css/relatorio.css">
</head>
<body>

<header>
    <figure>
        <img class="logo" src="img/logo_scivace.PNG" alt="Logomarca SCIVACE">
    </figure>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="visita.php">Registrar visita</a></li>
            <li><a href="relatorio.php">Relatório</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Relatório <span>Diário</span></h1>

    <!-- FILTRO POR DATA -->
    <form method="post">
        <label>Data inicial: <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>"></label>
        <label>Data fim: <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>"></label>
        <button type="submit">Filtrar</button>
        <a href="relatorio.php"><button type="button">Limpar filtro</button></a>
    </form>

    <!-- TABELA DE REGISTROS -->
    <table class="tabela-relatorio">
        <tr>
            <th>Data registro</th>
            <th>Tipo Imóvel</th>
            <th>Imóvel Inspecionado</th>
            <th>Tratado</th>
            <th>Depósitos Eliminados</th>
            <th>Focos</th>
            <th>Gramas</th>
            <th>A1</th>
            <th>A2</th>
            <th>B</th>
            <th>C</th>
            <th>D1</th>
            <th>D2</th>
            <th>E</th>
        </tr>
        <?php if(count($registros) > 0): ?>
            <?php foreach($registros as $r): ?>
            <tr>
                <td><?= $r['data_registro'] ?></td>
                <td><?= htmlspecialchars($r['tipo_de_imovel']) ?></td>
                <td><?= $r['imovel_inspecionado'] ?></td>
                <td><?= $r['imovel_tratado'] ?></td>
                <td><?= $r['quant_deposito_eliminado'] ?></td>
                <td><?= $r['quant_focos_encontrados'] ?></td>
                <td><?= $r['quantidade_gramas'] ?></td>
                <td><?= $r['tipo_deposito_a1'] ?></td>
                <td><?= $r['tipo_deposito_a2'] ?></td>
                <td><?= $r['tipo_deposito_b'] ?></td>
                <td><?= $r['tipo_deposito_c'] ?></td>
                <td><?= $r['tipo_deposito_d1'] ?></td>
                <td><?= $r['tipo_deposito_d2'] ?></td>
                <td><?= $r['tipo_deposito_e'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="14">Nenhum registro encontrado.</td></tr>
        <?php endif; ?>
    </table>

    <!-- TOTAIS -->
    <h2>Total</h2>
    <p>Total de Imóveis: <?= $totais['total_imoveis'] ?></p>
    <!--<p>Residência: <?= $totais['residencia'] ?></p>-->
    <!--<p>Comércio: <?= $totais['comercio'] ?></p>-->
    <!--<p>Terreno Baldio: <?= $totais['terreno_baldio'] ?></p>-->
    <!--<p>Outros: <?= $totais['outros'] ?></p>-->
    <!--<p>PE: <?= $totais['pe'] ?></p>-->
    <p>Imóveis Inspeccionados: <?= $totais['imoveis_inspecionados'] ?></p>
    <p>Tratados: <?= $totais['tratados'] ?></p>
    <p>Depósitos Eliminados: <?= $totais['depositos_eliminados'] ?></p>
    <p>Total de Focos: <?= $totais['focos_encontrados'] ?></p>
    <p>Gramas usadas: <?= $totais['gramas_usadas'] ?></p>
    <p>Depósitos Inspeccionados: <?= $totais['depositos_inspecionados'] ?></p>
    <p>A1: <?= $totais['A1'] ?> | A2: <?= $totais['A2'] ?> | B: <?= $totais['B'] ?> | C: <?= $totais['C'] ?> | D1: <?= $totais['D1'] ?> | D2: <?= $totais['D2'] ?> | E: <?= $totais['E'] ?></p>

    <!-- BOTÃO GERAR PDF -->
    <form class="pdf" method="post" action="gerar_pdf.php" target="_blank">
        <input type="hidden" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>">
        <input type="hidden" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>">
        <button type="submit">Gerar PDF</button>
    </form>

</main>
</body>
</html>
