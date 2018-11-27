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

$wp->PageTitle("Cadastro de Projetos");
include '../includes/menu-admin-home.php';
$wp->Entry("open");

    ?>

    <?php if (isset($_SESSION['mensagem'])): ?>


            <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
            </div>

        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>

    <form action="<?=URL?>controller/projeto.php?command=cadastrarSessaoProjeto" class="col-md-6" method="post">
        <label>Selecionar projeto:</label>
        <select class="js-example-basic-single form-control" name="projeto" required>
            <option>Selecione uma opção</option>
            <?php 
            $projeto = new projeto;
            $dados = $projeto->listarProjetoProfResp($_SESSION['dados_professor']['sucesso']['codigo']);
             ?>

             <?php foreach ($dados as $lista): ?>
                 <option value="<?= $lista->idProjeto?>"><?= $lista->titulo?></option>
             <?php endforeach ?>
        </select>

        <label>Nome da sessão:</label>
        <input type="text" name="nome" class="form-control" placeholder="Ex.: Introdução" required>

        <label>Data de início:</label>
        <input type="date" name="dtInicio" class="form-control" required>

        <label>Data de término:</label>
        <input type="date" name="dtTermino" class="form-control" required>

        <button class="btn btn-primary" type="submit" style="margin-top: 10px">Cadastrar sessão</button>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();