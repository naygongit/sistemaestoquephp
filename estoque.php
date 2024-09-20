<?php
// Inclui o arquivo de conexão
include 'conexao.php';

function carregarItens($filtro = "", $tipo = "") {
    global $conn;

    // Constrói a consulta SQL com base no filtro e no tipo
    $sql = "SELECT * FROM item";
    if (!empty($filtro) || !empty($tipo)) {
        $filtro = mysqli_real_escape_string($conn, $filtro);
        $sql .= " WHERE (id LIKE '%$filtro%' OR nome LIKE '%$filtro%' OR quantidade LIKE '%$filtro%')";
        if (!empty($tipo)) {
            $sql .= " AND tipo = '$tipo'";
        }
    }

    // Adiciona a cláusula ORDER BY para organizar por tipo
    //$sql .= " ORDER BY tipo && id";

    // Realiza a consulta SQL
    $result = $conn->query($sql);

    // Verifica se a consulta foi bem-sucedida
    if (!$result) {
        die('Erro na consulta SQL: ' . $conn->error);
    }

    // Retorna os itens, ou um array vazio se não houver resultados
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Verifica se houve uma requisição para alterar o estoque (adicionar, remover, etc.)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lógica para processar a requisição e modificar o estoque
    // ...

    // Após a modificação, recarrega os itens
    $itens = carregarItens();
} else {
    // Se não houver uma requisição POST, verifica se há um parâmetro de pesquisa
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
    
    // Verifica se há um parâmetro de tipo
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
    
    $itens = carregarItens($filtro, $tipo);

    // Se $itens for nulo (sem resultados), define como um array vazio
    $itens = $itens ?? [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <style>
       body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

h1 {
    text-align: center;
    background-color: #333;
    color: #fff;
    padding: 20px;
    margin: 0;
}

div {
    margin-top: 35px;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #b61914;
    color: white;
}

input {
    width: 150px;
}

button {
    padding: 10px 20px;
    cursor: pointer;
    background-color: #b61914;
    color: white;
    border: none;
    border-radius: 4px;
}

button:hover {
    background-color: #ddd;
    color: black;
}

/* Efeito de botão pressionado */
.tipo-buttons:active {
    
    color: black;
}

.ultimobotao {
    margin-top: 5px;
}

/* Adiciona um estilo para o campo de pesquisa */
input#search {
    padding: 8px;
}

    </style>
</head>
<body>
    
    <!-- Seus botões e scripts -->

    <!-- Campo de pesquisa por código -->
    <div>
        <label for="search">Pesquisar</label>
        <input type="text" id="search" onkeyup="verificarTecla(event)">
        <button type="button" onclick="filtrarItens()">Pesquisar</button>
        <button onclick="window.location.href='menu.php'">Voltar</button>
        <button type="button" onclick="sairsistema()">Sair</button>
        
    </div>

    <!-- Botões para filtrar por tipo -->
    <div class="tipo-buttons">
        <button type="button" onclick="filtrarPorTipo('Material de Limpeza')">Material de Limpeza</button>
        <button type="button" onclick="filtrarPorTipo('Material de Expediente')">Material de Expediente</button>
        <button type="button" onclick="filtrarPorTipo('Material de Manutenção')">Material de Manutenção</button>
        <button type="button" onclick="filtrarPorTipo('Material de EPIs')">Material de EPI's</button>
        <button type="button" onclick="filtrarPorTipo('Material de Informática')">Material de Informática</button>
        <button type="button" onclick="filtrarPorTipo('Material Elétrico')">Material Elétrico</button>
        <button type="button" onclick="filtrarPorTipo('Material de Eventos')">Material de Eventos</button>
        <button type="button" onclick="filtrarPorTipo('Saldo de Eventos')">Saldo de Eventos</button>
        <button type="button" onclick="filtrarPorTipo('Material do Educacional')">Material Educacional</button>
        <button type="button" onclick="filtrarPorTipo('Material do Laborátorio')">Material do Laborátorio</button>
        <button type="button" onclick="filtrarPorTipo('Material do Comunicação')">Material da Comunicação</button>
        <!--<button class="ultimobotao" type="button" onclick="filtrarPorTipo('Assesoria e Comunicação')">Assesoria e Comunicação</button>-->
        <!--<button class="ultimobotao" type="button" onclick="filtrarPorTipo('Bens e Materiais')">Bens e Materiais</button>-->

        <!-- Adicione mais botões conforme necessário -->
    </div>

    <!-- Tabela para exibir o estoque -->
    <table>
        <tr>
            <th>Código</th>
            <th>Item</th>
            <th>Quantidade</th>
            <th>Tipo</th>
        </tr>
        <?php foreach ($itens as $item): ?>
            <tr class="tipo-<?php echo str_replace(' ', '', $item['tipo']); ?>">
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['nome']; ?></td>
                <td><?php echo $item['quantidade']; ?></td>
                <td><?php echo $item['tipo']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <script>
        // Função JavaScript para filtrar os itens por tipo
        function filtrarPorTipo(tipo) {
            var filtro = document.getElementById('search').value;
            window.location.href = 'estoque.php?filtro=' + filtro + '&tipo=' + encodeURIComponent(tipo);
        }
        

        // Restante do seu script permanece inalterado
        function verificarTecla(event) {
            if (event.key === "Enter") {
                filtrarItens();
            }
        }

        function filtrarItens() {
            var filtro = document.getElementById('search').value;
            var tipo = ""; // Não filtrar por tipo
            window.location.href = 'estoque.php?filtro=' + filtro + '&tipo=' + encodeURIComponent(tipo);
        }

        function sairsistema() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
