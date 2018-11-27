<?php
include("../../wp.include.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionProfessor();
$API = new consumo_api;
//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');

//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Inscrições");
include '../includes/menu-admin-home.php';
$wp->Entry("open");

    ?>

    <?php if (isset($_SESSION['mensagem'])): ?>

        <div class="table-responsive">
            <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
            </div>

        </div>
        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>

    <div class="table-responsive" style="margin-bottom: 60px;">
        <form action="?inscricao=ok" method="post">
            <label>Projeto:</label>
            <select class="js-example-basic-single form-control" name="projeto" required>
                <option value="">Selecione uma opção</option>
                <?php 
                $projeto = new projeto;
                $dados = $projeto->listarProjetoProfResp($_SESSION['dados_professor']['sucesso']['codigo']);
                 ?>

                 <?php foreach ($dados as $lista): ?>
                     <option value="<?= $lista->idProjeto?>"><?= $lista->titulo?></option>
                 <?php endforeach ?>
            </select>

            <button class="btn btn-primary" style="margin-top: 10px">Ver inscritos</button>
        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>
    </div>
    <div class="table-responsive">
        <?php $dadosProjeto = $projeto->exibirProjetoCompleto($_POST['projeto']) ?>

        <?php foreach ($dadosProjeto as $listaProjeto): ?>
            <p><strong>Título:</strong> <?= $listaProjeto->titulo ?></p>
            <p><strong>Categoria de projeto:</strong> <?= $listaProjeto->categoria ?></p>
            <p><strong>Data de início:</strong> <?= date('d/m/Y', strtotime($listaProjeto->dtInicio)) ?></p>
            <p><strong>Data de Término:</strong> <?= date('d/m/Y', strtotime($listaProjeto->dtTermino)) ?></p>
        <?php endforeach ?>

        <?php if (isset($_POST['projeto'])): ?>
             <?php 

                $inscricao = $projeto->selecionarAlunosProjeto($_POST['projeto']);
                ?>

                <?php if (!empty($inscricao)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>RA</th>
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>Inscrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscricao as $listaInscricao): ?>
                                <tr>
                                    <?php  
                                    $dadosAluno = $API->retornaAlunoCPF(array('cpf' => $listaInscricao->cpfAluno, 'chave'=> $_SESSION['chave_professor']['chave']));
                                    ?>
                                    <td><?= $dadosAluno['sucesso']['nome']?></td>
                                    <td><?= $dadosAluno['sucesso']['RA']?></td>
                                    <td><?= $dadosAluno['sucesso']['CPF']?></td>
                                    <td><?= $dadosAluno['sucesso']['email']?></td>
                                    <td><button type="button" class="btn btn-danger" onclick="deletar<?=$listaInscricao->idInscricao?>()"><span class="fa fa-trash"></span></button></td>
                                
                                </tr>

                                <script type="text/javascript">
                                    function deletar<?=$listaInscricao->idInscricao?>() {
                                        var confirm = window.confirm("Deseja excluir a inscrição?");

                                        if (confirm) {
                                            window.location.href = "<?=URL?>controller/projeto.php?command=inscricao&parametro=1&cpfAluno=<?= $dadosAluno['sucesso']['CPF']?>&raAluno=<?=$dadosAluno['sucesso']['RA']?>&page=inscricoes&idProjeto=<?=$_POST['projeto']?>";
                                        }
                                    }
                                </script>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    
                    
                <?php else: ?>
                    <h3>Não há alunos inscritos!</h3>
                <?php endif ?>
        <?php endif ?>
    </div>

    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();