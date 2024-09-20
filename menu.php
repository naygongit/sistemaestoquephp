<?php
// Verificar se o usuário está autenticado
session_start();
if (!isset($_SESSION['nome_usuario'])) {
    header('Location: index.php'); // Redirecionar para a página de login se não estiver autenticado
    exit();
}

// Verificar se o usuário é "junior"
$usuario_nao_Permitido ="junior"; // "junior" nao permitido
$ocultarIrParaCadastrarItem = ($_SESSION['nome_usuario'] === $usuario_nao_Permitido);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <link rel="stylesheet" href="css_index.css">
</head>
<body class=menu-php>

<h1  class=menu-php>Sistema de Controle do Almoxarifado da FMRB</h1>
<div class=menu-php>

<?php
    // Exibir o nome do usuário
    echo "<h2 class=menu-php>Bem-vindo, {$_SESSION['nome_usuario']}!</h2>";

    // Exibir botão apenas se o usuário NÃO for "junior"
    if (!$ocultarIrParaCadastrarItem) {
        echo '<button class=menu-php id="btnCadastrar" onclick="irParaCadastrarItem()">Cadastro de itens</button>';
    }

    // Mostrar todos os outros botões se o usuario não for junior//
?>

<!--<button class=menu-php id="btnCadastrar" onclick="irParaCadastrarItem()">Cadastro de Itens</button>-->
<button class=menu-php id="btnEstoque" onclick="irParaEstoque()">Estoque</button>
<button class=menu-php id="btnGerenciar_Esoque" onclick="irParaGerenciar_Estoque()">Gerenciar Estoque</button>
<button class=menu-php id="btnmostrar_movimentacao" onclick="irParamostrar_movimentacao()">Movimentação do Estoque</button>
<button class=menu-php id="btnrelatorio_geral" onclick="irPararelatorio_geral()">Relatório</button>
<!--<button class=menu-php id="btnrelatorio_geral" onclick="irParainventario()">Inventário</button>-->
<button class=menu-php  id="btnLogout" onclick="irParaLogout()">Sair</button>
</div>
<script>
    function irParaCadastrarItem() {
        window.location.href = 'cadastrar_item.php';    
    }

    function irParaEstoque() {
        window.location.href = 'estoque.php';       
    }

    function irParaGerenciar_Estoque() {
        window.location.href = 'gerenciar_estoque.php';    
    }

    function irParamostrar_movimentacao() {
        window.location.href = 'mostrar_movimentacao.php';    
    }

    function irPararelatorio_geral() {
       window.location.href = 'relatorio_geral.php';    
   }

   function irParainventario() {
       window.location.href = 'inventario.html';    
   }


   function irParaLogout() {
        window.location.href = 'logout.php';   
    }
</script>

<footer class=menu-php>
Desenvolvido por Naygon Sann - Departamento de Tecnologia da FMRB
</footer>

</body>
</html>
