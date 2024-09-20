<?php
    //Inicie a sessão
    session_start();
    
     //Verifique se o usuário está autenticado
    if (!isset($_SESSION['nome_usuario'])) {
        // Redirecione para a página de login se não estiver autenticado
        header("Location: index.php");
       exit();
       
    }

    //Obtenha o nome do usuário da sessão
    $nomeUsuario = $_SESSION['nome_usuario'];
    ?>
    
    
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gerenciamento</title>
        <link rel="stylesheet" href="gerenciar_estoque.css">
    </head>
    <body>
        <h1>Gerenciar Estoque</h1>
        
        <!-- Formulário para movimentação -->
        <form id="meu_gerenciamento" method="post" action="movimentacao.php">

        <div>
            <label for="tipoMovimentacao">Movimentação:</label>
            <select name="tipoMovimentacao" id="tipoMovimentacao" required>
                <!--<option value="CADASTRO">Cadastro</option>-->
                <?php  
                if ($nomeUsuario == 'junior'){
            echo '<option value="SAÍDA">SAÍDA</option>';
            
            } else{
           
            echo '<option value="" disabled selected>Selecione</option>
            <option value="ENTRADA">ENTRADA</option>
            <option value="ENTRADA - SEDUC">ENTRADA - SEDUC</option>
            <option value="ENTRADA - SECMA">ENTRADA - SECMA</option>
            <option value="ENTRADA - CARTÃO CORPORATIVO">ENTRADA - CARTÃO CORPORATIVO</option>
            <option value="ENTRADA - SALDO DE EVENTOS">ENTRADA - SALDO DE EVENTOS</option>
            <option value="SAÍDA">SAÍDA</option>';
            } 
            
                ?>

                
            </select>
        </div>
        <div>   
            <label for="tipoItem">Tipo</label>
            <select name="tipoItem" id="tipoItem" required>
            <option value="" disabled selected>Selecione</option>
            <option value="Material de Limpeza">Material de Limpeza</option>
            <option value="Material de Expediente">Material de Expediente</option>
            <option value="Material de Manutenção">Material de Manutenção</option>
            <option value="Material de EPIs">Material de EPI's</option>
            <option value="Material de Informática">Material de Informática</option>
            <option value="Material Elétrico">Material Elétrico</option>
            <option value="Material de Eventos">Material de Eventos</option>
            <option value="Material do Educacional">Material Educacional</option>
            <option value="Material do Laborátorio">Material do Laborátorio</option>
            <option value="Material da Comunincação">Material da Comunicação</option>
            </select>
            <button type="button" onclick="exibirDetalhes()">Exibir Detalhes por Tipo</button>
        </div>
        <label for="inserido_por">Inserido Por</label>

            <select name="inserido_por" id="inserido_por" required>
            <option value="<?php echo $nomeUsuario; ?>"><?php echo $nomeUsuario; ?></option>   
             
        </select>
        <div>
            <label for="funcionarioResponsavel">Solicitado por</label>
            <input type="text" name="funcionarioResponsavel" required>

            <!--<input type="text" id="funcionarioResponsavel" name="funcionarioResponsavel" required>-->
            </select>

            <label for="setor">Setor</label>
            <select name="setor" id="setor">
             <option value=""disable selected>Selecione</option>
             <option value="Departamento de Material e Patrimonio">Departamento de Material e Patrimonio</option>
             <option value="Gabinete da Presidência">Gabinete da Presidência </option>
             <option value="Departamento de Projetos sociais ">Departamento de Projetos sociais</option>
             <option value="Departamento de Gestão de Recursos Humanos">Departamento de Gestão de Recursos Humanos</option>
             <option value="Diretoria Cultural ">Diretoria Cultural</option>
             <option value="Diretoria Educacional ">Diretoria Educacional</option>
             <option value="Diretoria Técnica">Diretoria Técnica</option>
             <option value="Assessoria Jurídica">Assessoria Jurídica</option>
             <option value="Acervologia">Acervologia</option>
             <option value="Departamento de Assessoria Técnica e de Comunicação">Departamento de Assessoria Técnica e de Comunicação</option>
             <option value="Departamento de Assessoria de Planejamento e Ações estratégicas">Departamento de Assessoria de Planejamento e Ações estratégicas</option>
             <option value="Setor de Licitação">Setor de Licitação</option>
             <option value="Departamento de Biblioteconomia">Departamento de Biblioteconomia</option>
             <option value="Diretoria Administrativa e Financeira">Diretoria Administrativa e Financeira</option>
             <option value="Departamento de informática">Departamento de informática</option>
             <option value="Departamento de Execução Orçamentária e Controle Contábil Financeira">Departamento de Execução Orçamentária e Controle Contábil Financeira</option>
             <option value="Operacional">Operacional</option>
             
            </select>
        </div>

        <div id="detalhesItemContainer"></div>
        <div id="messageContainer"></div>

        <button type="submit">Registrar</button>
        <button type="button" onclick="retornarparamovimentacao()">Movimentações</button>
        <button type="button" onclick="irparamenuprincipal()">Menu Principal</button>
    </form>

        <script>

            function irparamenuprincipal(){
                window.location.href = "menu.php";
            }


        function retornarparamovimentacao() {
            window.location.href = 'mostrar_movimentacao.php';
        }

        function exibirDetalhes() {
        var tipoItemSelecionado = document.getElementById('tipoItem').value;

        // Realiza uma chamada AJAX para obter os detalhes do item
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // O servidor respondeu com sucesso, exibe os detalhes do item na tabela
                    document.getElementById('detalhesItemContainer').innerHTML = xhr.responseText;
                } else {
                    // O servidor respondeu com um erro
                    console.error('Erro ao obter detalhes do item. Código de status:', xhr.status);
                }
            }
        };

        // Configura a requisição
        xhr.open('POST', 'detalhes_item.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('tipoItem=' + tipoItemSelecionado);
    }

        </script>
    </body>
    </html>
