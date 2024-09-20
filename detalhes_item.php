<?php
// Inclui o arquivo de conexão
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["tipoItem"])) {
        $tipoItem = $conn->real_escape_string($_POST["tipoItem"]);

        // Verifica se o item existe no banco de dados
        $sqlCheckItem = "SELECT id, nome, quantidade, tipo FROM item WHERE tipo = '$tipoItem'";
        $resultCheckItem = $conn->query($sqlCheckItem);

        if ($resultCheckItem->num_rows > 0) {
            // Exibe os detalhes do item
            echo '<h2>Detalhes do Item</h2>';
            echo '<form id="meu_gerenciamento" method="post" action="movimentacao copy.php">';
            echo '<input type="hidden" name="tipoItem" value="' . $tipoItem . '">'; // Adiciona um campo oculto para o tipoItem
            echo '<table border="1">';
            echo '<tr><th>Código</th><th>Item</th><th>Quantidade</th><th>Tipo</th><th>Ações</th></tr>';

            while ($rowItem = $resultCheckItem->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $rowItem["id"] . '</td>';
                echo '<td>' . $rowItem["nome"] . '</td>';
                echo '<td>' . $rowItem["quantidade"] . '</td>';
                echo '<td>' . $rowItem["tipo"] . '</td>';
                echo '<td>';
                echo '<input type="number" id="quantidade_item" name="quantidade_item[' . $rowItem["id"] . ']">';; // Input para exibir quantidade
                //echo '<input type="hidden" name="id_item[' . $rowItem["id"] . ']" value="'. $rowItem["id"] . '" >'; // Campo oculto com o ID do item
                echo '</td>';   
                // Adicione mais colunas conforme necessário
                echo '</tr>';   
            }

            echo '</table>';
            //echo '<button type="submit">Registrar Movimentação</button>';
            echo '</form>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Nenhum item encontrado para o tipo selecionado.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Parâmetros inválidos.</div>';
    }
}
?>
