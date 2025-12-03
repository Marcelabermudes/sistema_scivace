<?php
session_start();

// encerra a sessão corretamente
session_unset();
session_destroy();

// redireciona para a página de login
header("Location: index.php");
exit;
?>
