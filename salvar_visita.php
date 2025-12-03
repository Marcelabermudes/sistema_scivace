<?php
session_start();

// Se não estiver logado, volta para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Conexão com o banco
require_once 'db/conexao.php';

// ID do usuário logado
$id_usuario = $_SESSION['usuario_id'];


try {
    // Preparar a query de inserção
    $sql = "INSERT INTO visitas (
        bairro, quarteirao, numero, complemento, rua, tipo_de_imovel,
        tipo_de_visita, nome_morador, observacao, imovel_inspecionado,
        imovel_tratado, quant_deposito_eliminado, quant_focos_encontrados,
        tipo_larvicida, quantidade_gramas, tipo_deposito_a1, tipo_deposito_a2,
        tipo_deposito_b, tipo_deposito_c, tipo_deposito_d1, tipo_deposito_d2,
        tipo_deposito_e, id_usuario
    ) VALUES (
        :bairro, :quarteirao, :numero, :complemento, :rua, :tipo_de_imovel,
        :tipo_de_visita, :nome_morador, :observacao, :imovel_inspecionado,
        :imovel_tratado, :quant_deposito_eliminado, :quant_focos_encontrados,
        :tipo_larvicida, :quantidade_gramas, :tipo_deposito_a1, :tipo_deposito_a2,
        :tipo_deposito_b, :tipo_deposito_c, :tipo_deposito_d1, :tipo_deposito_d2,
        :tipo_deposito_e, :id_usuario
    )";

    $stmt = $pdo->prepare($sql);

    // Executar a query
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
        ':tipo_deposito_e' => $tipo_deposito_e,
        ':id_usuario' => $id_usuario
    ]);

    // Redireciona com sucesso
    header("Location: registro_visita.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar visita: " . $e->getMessage());
}
?>
