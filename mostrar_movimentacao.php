<?php 
include 'conexao.php';

//session_start();  // Inicia a sessão, se ainda não estiver iniciada     
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentações de Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #333;
            color: #fff;
            padding: 15px;
            margin: 0;
        }

        nav {
            justify-content: center;
            background-color: #b61914;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        div {
            margin-top: 35px;
        }

        input {
            width: 189px;
            height: 35px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            overflow-y: auto;
            max-height: 400px;
}

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 9px;
            text-align: center;
            background-color:white;
        }

        th {
            background-color: #b61914;
            color: white;
            position: sticky;
            top: 0;
        }

        .tdnomeitem {
            width: 300px;
        }

        .tdsolicitadopor{
            width:200px;
        }

        button {
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            width: 99px;
            height: px;
        }

        button:hover {
            background-color: #45a049;
            color:black;
        }

        input[type="date"] {
            width: 189px;
            height: 35px;
        }
    </style>
</head>
<body>

    <h1>Movimentações de Estoque</h1>

    <nav>
        
    <?php if ($_SESSION['nome_usuario'] != 'junior'): ?>
            <a href="#cadastrar_item" onclick="ircadastro_item()">Cadastrar Item</a>
        <?php endif; ?>
        <a href="#estoque_atual" onclick="irestoque_atual()">Estoque Atual</a>
        <a href="#gerenciar_estoque" onclick="irgerenciar_estoque()">Gerenciar Estoque</a>
        <a href="#movimentacao_estoque" onclick="irmovimentacao_estoque()">Movimentações de Estoque</a>
        <a href="#relatorio" onclick="irpararelatorio()">Relatorio</a>
        <!--BOTAO DE OCULTAR A TABELA NO MENU DE NAVEGAÇÃO-->
        <!--<a href="#ocultar" onclick="ocultarTabela()">Ocultar Tabela</a>-->
        
        <a href="#menu_principal" onclick="retornarMenuPrincipal()">Menu Principal</a>
        <a href="#pagina_login" onclick="irParaLogout()">Sair do Sistema</a>
        
        
    </nav>

    <div>
        <input type="text" id="searchMovimentacao" placeholder="Pesquisar...">
    </div>

    <!-- Funcionalidade de pesquisa por data -->
    <!-- Essa funcionalidade será trabalhada em uma outra versão -->
    <div>
        <!--
        <label for="dataInicial">Data Inicial:</label>
        <input type="date" id="dataInicial">

        <label for="dataFinal">Data Final:</label>
        <input type="date" id="dataFinal">
        
        <button type="button" onclick="filtrarPorData()">Pesquisar por Data</button>
        -->
    </div>

    <table id="movimentacoesTable">
        <tr>
            <th>Tipo de Movimentação</th>
            <th>Inserido Por</th>
            <th>Item</th>
            <th>Quantidade Movimentada</th>
            <th>Quantidade Estoque</th>
            <th>Tipo</th>
            <th>Solicitado por</th>
            <th>Setor</th>
            <th>Data - Hora</th>
        </tr>

<?php
// Obtém o usuário da sessão
$usuario = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : '';

// Sua consulta SQL modificada
$sql = "SELECT movimentacao.*, item.tipo AS tipo_item, DATE_FORMAT(movimentacao.dataMovimentacao, '%d/%m/%Y %H:%i:%s') AS dataMovimentacao,
        (SELECT COALESCE(SUM(CASE WHEN tipoMovimentacao IN ('ENTRADA','ENTRADA - SEDUC','ENTRADA - SECMA','ENTRADA - CARTÃO CORPORATIVO','ENTRADA - SALDO DE EVENTOS', 'CADASTRO') THEN quantidade_item ELSE -quantidade_item END), 0)
         FROM movimentacao m2 WHERE m2.codigoItem = item.id AND m2.dataMovimentacao <= movimentacao.dataMovimentacao) AS no_estoque,
        inserido_por,
        funcionarioResponsavel,
        movimentacao.setor AS setor
FROM movimentacao
LEFT JOIN item ON movimentacao.codigoItem = item.id
ORDER BY movimentacao.dataMovimentacao DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['tipoMovimentacao'] . '</td>';
        echo '<td>' . $row['inserido_por'] . '</td>';
        echo '<td class=tdnomeitem>' . $row['nomeItem'] . '</td>';
        echo '<td>' . $row['quantidade_item'] . '</td>';
        
        // Campo "NO_ESTOQUE" ajustado conforme a lógica
        $no_estoque = $row['no_estoque'];

        echo '<td>' . max(0, $no_estoque) . '</td>';
        echo '<td>' . $row['tipo_item'] . '</td>';
        echo '<td class=tdsolicitadopor>' . $row['funcionarioResponsavel'] . '</td>';
        echo '<td>' . $row['setor'] . '</td>';
        echo '<td>' . $row['dataMovimentacao'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7">Nenhuma movimentação encontrada.</td></tr>';
}

$conn->close();
?>






    </table>

    <script>
        function retornarMenuPrincipal() {
            window.location.href = 'menu.php';
        }
        function ircadastro_item(){
            window.location.href = 'cadastrar_item.php';
        }
        function irestoque_atual(){
            window.location.href = 'estoque.php';
        }
        function irgerenciar_estoque(){
            window.location.href = 'menu_gerenciar_estoque.php';
        }
        function irmovimentacao_estoque(){
            window.location.href = 'movimentacao_menu.html';
        }

        function irpararelatorio(){
            window.location.href = 'relatorio_geral.php';
        }
        function irParaLogout() {
            window.location.href = 'logout.php';
        }

        function ocultarTabela() {
            var table = document.getElementById('movimentacoesTable');
            if (table.style.display === 'none') {
                table.style.display = 'table';
            } else {
                table.style.display = 'none';
            }
        }

        /*
           <!-- Funcionalidade de pesquisa por data -->
            <!-- Essa funcionalidade será trabalhada em uma outra versão -->

        function filtrarPorData() {
            var dataInicial = document.getElementById('dataInicial').value;
            var dataFinal = document.getElementById('dataFinal').value;
            var searchText = document.getElementById('searchMovimentacao').value.toLowerCase();

            var table = document.getElementById('movimentacoesTable');
            var rows = table.getElementsByTagName('tr');

            for (var i = 1; i < rows.length; i++) {
                var found = false;

                for (var j = 0; j < 6; j++) {
                    var cellText = rows[i].getElementsByTagName('td')[j].innerText.toLowerCase();
                    if (cellText.indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }

                var cellDate = rows[i].getElementsByTagName('td')[5].innerText; // Assume que a data está na sexta coluna
                if ((dataInicial === '' || cellDate >= dataInicial) && (dataFinal === '' || cellDate <= dataFinal)) {
                    found = found && true;
                } else {
                    found = false;
                }

                rows[i].style.display = found ? '' : 'none';
            }
        }
        */

        document.getElementById('searchMovimentacao').addEventListener('keyup', function () {

            
            /*
               <!-- Funcionalidade de pesquisa por data -->
                <!-- Essa funcionalidade será trabalhada em uma outra versão -->

            var dataInicial = document.getElementById('dataInicial').value;
            var dataFinal = document.getElementById('dataFinal').value;
            */
            var searchText = this.value.toLowerCase();

            var table = document.getElementById('movimentacoesTable');
            var rows = table.getElementsByTagName('tr');

            for (var i = 1; i < rows.length; i++) {
                var found = false;

                for (var j = 0; j < 9; j++) {
                    var cellText = rows[i].getElementsByTagName('td')[j].innerText.toLowerCase();
                    if (cellText.indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }

                /*
                   <!-- Funcionalidade de pesquisa por data -->
                     <!-- Essa funcionalidade será trabalhada em uma outra versão -->

                var cellDate = rows[i].getElementsByTagName('td')[5].innerText; // Assume que a data está na sexta coluna
                if ((dataInicial === '' || cellDate >= dataInicial) && (dataFinal === '' || cellDate <= dataFinal)) {
                    found = found && true;
                } else {
                    found = false;
                }
                */

                rows[i].style.display = found ? '' : 'none';
            }
        });
    </script>
</body>
</html>
