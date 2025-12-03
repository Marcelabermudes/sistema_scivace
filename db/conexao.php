<?php
// CONFIGURAÇÕES DO BANCO
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "scivace";

try {
    // CONEXÃO PDO
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);

    // Habilitar erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// FUNÇÃO PARA LIMPAR ENTRADAS
function limpaPost($dado) {
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado, ENT_QUOTES, 'UTF-8');
    return $dado;
}
?>
