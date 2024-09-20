<?php
session_start();  // Inicia a sessão, se ainda não estiver iniciada

// Limpa as variáveis de sessão
$_SESSION = array();

// Destrói a sessão
session_destroy();

// Limpa os cookies relacionados à sessão
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redireciona para a página de login
header("Location: index.php");
exit();
?>
