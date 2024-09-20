<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa o formulário e atualiza a tabela de movimentação

    if (
        isset($_POST["tipoItem"]) &&
        isset($_POST["inserido_por"])&&
        isset($_POST["funcionarioResponsavel"]) &&
        isset($_POST["tipoMovimentacao"]) &&
        isset($_POST["quantidade_item"]) &&
        isset($_POST["setor"])
    ) {
        $tipoItem = mysqli_real_escape_string($conn, $_POST["tipoItem"]);
        $inserido_por = mysqli_real_escape_string($conn, $_POST["inserido_por"]);
        $funcionarioResponsavel = mysqli_real_escape_string($conn, $_POST["funcionarioResponsavel"]);
        $setor = mysqli_real_escape_string($conn, $_POST["setor"]);
        $tipoMovimentacao = $_POST["tipoMovimentacao"];

        // Inicia uma transação para garantir a consistência das operações
        $conn->begin_transaction();

        //VERIFICAR QUE TIPO DE VÁRIAVEL ESTÁ INDO PARA QUANTIDADE
       // echo "<pre>";
       // print_r($_POST["quantidade_item"]);
        //echo "</pre>";

        foreach ($_POST["quantidade_item"] as $idItem => $quantidade_item) {
            // Verifica se a quantidade não está vazia e se é uma string numérica
            if (!empty($quantidade_item) && is_numeric($quantidade_item)) {
                $quantidade_item = intval($quantidade_item);


                // Verifica se a quantidade é válida
                if ($quantidade_item <= 0) {
                    // Se a quantidade for inválida, faz rollback da transação e exibe mensagem de erro
                    $conn->rollback();
                    echo 'Quantidade inválida <a href=gerenciar_estoque_copy.php><button>Voltar</button></a>';
                    exit();
                }

                // Verifica se é uma entrada e se a quantidade é maior que zero
                /*
                if (($tipoMovimentacao == 'ENTRADA - SEDUC' || $tipoMovimentacao == 'ENTRADA - SECMA' || $tipoMovimentacao == 'ENTRADA - CARTÃO CORPORATIVO' || $tipoMovimentacao == 'ENTRADA - SALDO DE EVENTOS' ) && $quantidade_item <= 0) {
                    // Se for uma entrada e a quantidade for menor ou igual a zero, faz rollback da transação e exibe mensagem de erro
                    $conn->rollback();
                    echo 'Quantidade de entrada inválida <a href=gerenciar_estoque.php><button>Voltar</button></a>';
                    exit();
                }
                */

                // Verifica se é uma saída e se a quantidade disponível no estoque é suficiente
                if ($tipoMovimentacao == 'SAÍDA') {
                    $sqlVerificarEstoque = "SELECT quantidade FROM item WHERE tipo = '$tipoItem' AND id = $idItem";
                    $resultEstoque = $conn->query($sqlVerificarEstoque);

                    if ($resultEstoque && $resultEstoque->num_rows > 0) {
                        $rowEstoque = $resultEstoque->fetch_assoc();
                        $quantidadeEmEstoque = $rowEstoque['quantidade'];

                        if ($quantidade_item > $quantidadeEmEstoque) {
                            // Se a quantidade de saída for maior que a quantidade em estoque, faz rollback da transação e exibe mensagem de erro
                            $conn->rollback();
                            echo 'Quantidade de saída maior que a quantidade em estoque <a href=gerenciar_estoque_copy.php><button>Voltar</button></a>';
                            exit();
                        }
                    } else {
                        // Se não foi possível verificar o estoque, faz rollback da transação e exibe mensagem de erro
                        $conn->rollback();
                        echo 'Erro ao verificar o estoque <a href=gerenciar_estoque.php><button>Voltar</button></a>';
                        exit();
                    }
                }

                // Atualiza a tabela item com base no tipo de movimentação
                if ($tipoMovimentacao == 'ENTRADA' || $tipoMovimentacao == 'ENTRADA - SEDUC' || $tipoMovimentacao === 'ENTRADA - SECMA' || $tipoMovimentacao === 'ENTRADA - CARTÃO CORPORATIVO' || $tipoMovimentacao === 'ENTRADA - SALDO DE EVENTOS' ) {
                    $sqlUpdateItem = "UPDATE item SET quantidade = quantidade + $quantidade_item WHERE tipo = '$tipoItem' AND id = $idItem";
                } elseif ($tipoMovimentacao == 'SAÍDA') {
                    $sqlUpdateItem = "UPDATE item SET quantidade = quantidade - $quantidade_item WHERE tipo = '$tipoItem' AND id = $idItem";
                } else {
                    // Se o tipo de movimentação não for reconhecido, faz rollback da transação e exibe mensagem de erro
                    $conn->rollback();
                    echo 'Tipo de movimentação inválido <a href=gerenciar_estoque.php><button>Voltar</button></a>';
                    exit();
                }

                if ($conn->query($sqlUpdateItem) === FALSE) {
                    // Se houver um erro na atualização, faz rollback da transação e exibe mensagem de erro
                    $conn->rollback();
                    echo 'Erro ao atualizar a quantidade do item.</div>';
                    exit();
                }

                // Insere na tabela movimentacao
                $sqlInsertMovimentacao = "INSERT INTO movimentacao (codigoItem, inserido_por, nomeItem, quantidade_item, tipoMovimentacao, funcionarioResponsavel, setor) 
        SELECT id, '$inserido_por', nome, $quantidade_item, '$tipoMovimentacao', '$funcionarioResponsavel', '$setor' FROM item WHERE tipo = '$tipoItem' AND id = $idItem";


                if ($conn->query($sqlInsertMovimentacao) === FALSE) {
                    // Se houver um erro na inserção da movimentação, faz rollback da transação e exibe mensagem de erro
                    $conn->rollback();
                    echo 'Erro ao registrar a movimentação.</div>';
                    exit();
                }
            }
        }

        // Se tudo ocorreu bem, commita a transação
        $conn->commit();
        // USAR PARA VERIFICAR ERROS:
       // var_dump($quantidade_item);
        echo 'Movimentação registrada com sucesso <a href=gerenciar_estoque.php><button>Voltar</button></a>';
        exit(); 
    } else {
        echo 'Parâmetros inválidos.</div>';
    }
}
?>
