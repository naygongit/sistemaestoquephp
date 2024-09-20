<?php
// Inicia a sessão
session_start();

// Definir cabeçalhos para evitar o cache das páginas
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Pragma: no-cache"); // HTTP/1.0

// Configuração do tempo de inatividade permitido em segundos (ex: 15 minutos)
$tempo_inatividade = 900; // 15 * 60

// Verificar a última atividade
if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso']) > $tempo_inatividade) {
    // Tempo de inatividade excedido, encerrar sessão
    session_unset();
    session_destroy();
    header("Location: index.html?msg=tempo_excedido");
    exit();
}

// Atualizar o tempo da última atividade
$_SESSION['ultimo_acesso'] = time();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: index.php"); // Redirecionar para a página de login se não estiver autenticado
    exit();
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "controle_estoque2";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Outros códigos relacionados à conexão podem ser colocados aqui
?>
