<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Itens</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <!--
    INSERIR DEPOIS O NAVBAR (BARRA DE NAVEAGAÇÃO)    
    <nav>
        <a href="#menu_principal" onclick="retornarMenuPrincipal()">Menu Principal</a>
        <a href="#cadastrar_item" onclick="ircadastro_item()">Cadastrar Item</a>
        <a href="#estoque_atual" onclick="irestoque_atual()">Estoque Atual</a>
        <a href="#gerenciar_estoque" onclick="irgerenciar_estoque()">Gerenciar Estoque</a>
        <a href="#movimentacao_estoque" onclick="irmovimentacao_estoque()">Movimentações de Estoque</a>
        <a href="#relatorio" onclick="irpararelatorio()">Relatorio</a>
        <a href="#pagina_login" onclick="irParaLogout()">Sair</a>
        <a href="#ocultar" onclick="ocultarTabela()">Ocultar Tabela</a>
    </nav>-->

<?php
// Inclui o arquivo de conexão
include 'conexao.php';
//session_start();

// Obtém o usuário da sessão
$usuario = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : '';

// Variável para armazenar a mensagem de status
$statusMensagem = null;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa o formulário e atualiza o banco de dados
    if (isset($_POST["item_nome"]) &&
        isset($_POST["tipo_item"]) &&
        isset($_POST["setor"])) {
        $nome = mysqli_real_escape_string($conn, $_POST["item_nome"]);
        $tipo = mysqli_real_escape_string($conn, $_POST["tipo_item"]);
        $setor = mysqli_real_escape_string($conn, $_POST["setor"]);

        // Certifique-se de que $quantidade está definido
        $quantidade = isset($_POST["quantidade"]) ? intval($_POST["quantidade"]) : 0;

        // Certifique-se de que $responsavel está definido
        $responsavel = isset($_POST["funcionarioResponsavel"]) ? mysqli_real_escape_string($conn, strtoupper($_POST["funcionarioResponsavel"])) : '';


        // Verifica se a quantidade é válida
        if ($quantidade <= 0) {
            $statusMensagem = "<p style=text-align:center;>Quantidade inválida. Digite um valor maior que zero.</p>";
        } else {
            // Restante do código permanece inalterado

            // Verifica se o item já existe no banco de dados
            $verificarExistencia = $conn->query("SELECT * FROM item WHERE nome = '$nome'");

            if ($verificarExistencia->num_rows > 0) {
                // Item já existe, recupera o ID e exibe a mensagem
                $itemExistente = $verificarExistencia->fetch_assoc();
                $statusMensagem = "Item já inserido. Número de ID:" . $itemExistente['id'];
            } else {
                // Insere o novo item no banco de dados
                $sql = "INSERT INTO item (nome, tipo, quantidade) VALUES ('$nome', '$tipo', $quantidade)";
                if ($conn->query($sql) === TRUE) {
                    $statusMensagem = "Item inserido com sucesso. Número de ID: " . $conn->insert_id;

                    // Insere a movimentação na tabela de movimentações
                    $tipoMovimentacao = 'CADASTRO';
                    //$setor = 'ALMOXARIFADO';
                    $inserido_por = $usuario;
                    
                    $sqlMovimentacao = "INSERT INTO movimentacao (tipoMovimentacao, codigoItem, nomeItem, quantidade_item, funcionarioResponsavel, inserido_por, setor)
                    VALUES ('$tipoMovimentacao', {$conn->insert_id}, '$nome', $quantidade, '$responsavel', '$usuario', '$setor')";

                    $conn->query($sqlMovimentacao);
                } else {
                    $statusMensagem = "<p>Erro ao inserir o item: </p>" . $conn->error;
                }
            }
        }
    }
}
?>


<!-- Restante do seu código HTML permanece inalterado -->


<!-- Restante do seu código HTML permanece inalterado -->


<!-- Restante do seu código HTML permanece inalterado -->


<!-- Formulário para cadastrar item -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h1>Tela de Cadastro</h1>

    <!-- Exibe a mensagem de status -->
    <?php if ($statusMensagem !== null): ?>
        <p><?php echo $statusMensagem; ?></p>
    <?php endif; ?>

    <div class="form-group">
        <label for="item_nome">Item:</label>
        <input type="text" name="item_nome" required>
    </div>

    <div class="form-group">
        <label for="tipo_item">Tipo de Item:</label>
        <select name="tipo_item" required>
        <option value="" disabled selected>Selecione</option>
            <option value="Material de Limpeza">Material de Limpeza</option>
            <option value="Material de Expediente">Material de Expediente</option>
            <option value="Material de EPIs">Material de EPI's</option>
            <option value="Material de Manutenção">Material de Manutenção</option>
            <option value="Material de Informática">Material de Informática</option>
            <option value="Material Elétrico">Material Elétrico</option>
            <option value="Material de Eventos">Material de Eventos</option>
            <option value="Saldo de Eventos">Saldo de Eventos</option>
            <option value="Material do Educacional">Material Educacional</option>
            <option value="Material do Laborátorio">Material do Laborátorio</option>
            <option value="Material da Comunincação">Material da Comunicação</option>
            
        </select>
    </div>

    <!-- Adiciona o campo para a quantidade -->
    <div class="form-group">
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" required>
    </div>

    <!-- Adiciona o campo para o responsável -->
    <div class="form-group">
        <label for="funcionarioResponsavel">Inserido Por</label>
        <select name="funcionarioResponsavel" id="funcionarioResponsavel"required>
        <!--<option value="" disabled selected>Selecione</option>
        <option value="JOÃO VITOR">JOÃO VITOR</option>
        <option value="ANDRÉ">ANDRÉ</option>
        <option value="NAYGON">NAYGON</option>-->
        <option value="<?php echo $usuario; ?>"><?php echo $usuario; ?></option>    
        </select>
    </div>


    <label for="setor">Setor</label>
            <select name="setor" id="setor">
            <option value="" disabled selected>Selecione</option>
             <option value="ALMOXARIFADO">ALMOXARIFADO</option>
            <!-- <option value="ARMÁRIO PATRIMÔNIO">ARMÁRIO PATRIMÔNIO</option>-->
             <option value="ARMÁRIO T.I">ARMÁRIO T.I</option>

            </select>


    <button type="submit">Salvar</button>
    <button onclick="window.location.href='menu.php'">Menu Principal</button>
    <button type="button" onclick="sair_do_sistema()">Sair do Sistema</button>
</form>

<style>
    /* Adicione o estilo para formatação do layout conforme necessário */
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: inline-block;
        width: 120px; /* Ajuste conforme necessário */
        margin-right: 10px;
        text-align: right;
    }

    select, input {
        width: 200px; /* Ajuste conforme necessário */
    }

    button {
        margin-top: 10px;
    }
</style>

<script>
    function sair_do_sistema() {
        window.location.href = 'logout.php';
    }

    function verificarTecla(event) {
        if (event.key === "Enter") {
            filtrarItens();
        }
    }
</script>



</body>
</html>






