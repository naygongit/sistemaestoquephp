<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Função para carregar detalhes de um item específico
function carregarItem($codigo, $nome) {
    global $conn;

    // Constrói a condição SQL com base nos parâmetros
    $condicao = "";
    if (!empty($codigo)) {
        $condicao = "id = '$codigo'";
    } elseif (!empty($nome)) {
        $condicao = "nome LIKE '%$nome%'";
    }

    // Realiza a consulta SQL para obter os detalhes do item
    $result = $conn->query("SELECT * FROM item WHERE $condicao");

    // Verifica se a consulta foi bem-sucedida
    if (!$result) {
        die('Erro na consulta SQL: ' . $conn->error);
    }

    
    // Retorna os detalhes do item
    return $result->fetch_assoc();
}


// Verifica se houve uma requisição POST para manipular a quantidade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];


    
    // Retorna os detalhes do item
    $item = carregarItem($codigo, $nome);

    // Retorna os detalhes do item em formato JSON
    echo json_encode($item);
    exit;
}

// Insere o novo item no banco de dados
$sql = "INSERT INTO movimentacao (nome, tipo, quantidade) VALUES ('$nome', '$tipo', $quantidade)";
if ($conn->query($sql) === TRUE) {
    $statusMensagem = "Item inserido com sucesso. Número de ID: " . $conn->insert_id;
} else {
    $statusMensagem = "Erro ao inserir o item: " . $conn->error;
}
?>
