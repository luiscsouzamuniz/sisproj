<?php
include("../../wp.include.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionProfessor();
$API = new consumo_api;
//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');
$projeto = new projeto;
//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Relatório");
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

    <div class="table-responsive">
        <h3>EM CONSTRUÇÃO</h3>
        <form action="?relatorio=ok" class="form-group col-md-8" method="post">
            <label>Tipo:</label>
            <select class="form-control js-example-basic-single" name="tipo" required>
                <option value="">Selecionar uma opção</option>
                <option value="1">Relatório de atividades docentes</option>
                <option value="2">Relatório de atividades discentes</option>
            </select>

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
            
            <button class="btn btn-primary" style="margin-top: 10px;">Buscar</button>

        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>
    </div>

    <?php if (isset($_REQUEST['relatorio'])): ?>

        <?php if ($_REQUEST['tipo'] == 1): ?>
            

        <?php elseif($_REQUEST['tipo'] == 2): ?>
            relatorio atividade
        <?php endif ?>
        <div class="table-responsive">
            <?php  
            //$dadosProjeto = $projeto->listarProjetoProfResp($_SESSION['dados_professor']['sucesso']['codigo']); 
            //$dadosProjeto = $projeto->exibirProjetoCompleto(7)
            ?>
            <?php //$dadosProjeto = json_decode(json_encode($dadosProjeto), true) ?>

            <table>
                <thead>
                    <?php //foreach ($dadosProjeto as $key => $value): ?>
                    <tr>
                        <?php //foreach (array_keys($value) as $key): ?>
                            <th></th>
                        <?php //endforeach ?>
                    </tr>
                    <?php //endforeach ?>
                </thead>
            </table>
            
        </div>
    <?php endif ?>
        
    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();