<?php
session_start();

// Conectar ao banco de dados (substitua pelos seus próprios detalhes de conexão)
$host = 'localhost';
$usuario_bd = 'root';
$senha_bd = '';
$banco = 'controle_estoque2';

// Verificar se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se os campos do formulário estão definidos
    if (isset($_POST['nome']) && isset($_POST['senha'])) {
        $conexao = new mysqli($host, $usuario_bd, $senha_bd, $banco);

        // Verificar a conexão
        if ($conexao->connect_error) {
            die("Erro de conexão: " . $conexao->connect_error);
        }

        // Obter dados do formulário
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];

        // Verificar se o usuário existe no banco
        $sql = "SELECT * FROM usuarios WHERE nome='$nome' AND senha='$senha'";
        $resultado = $conexao->query($sql);

        if ($resultado->num_rows > 0) {
            // Usuário autenticado com sucesso
            $_SESSION['nome_usuario'] = $nome; // Armazenar o nome do usuário na sessão
            header('Location:menu.php'); // Redirecionar para a página home após o login
            exit();
        } else {
            // Falha na autenticação
            echo "Erro: Nome de usuário ou senha incorretos.";
        }

        // Fechar a conexão
        $conexao->close();
    } else {
        // Campos do formulário não definidos
        echo "Erro: Por favor, preencha todos os campos do formulário.";
    }
} else {
    // Requisição inválida
    echo "Erro: Requisição inválida.";
}
?>
