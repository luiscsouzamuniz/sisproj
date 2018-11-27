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

$wp->PageTitle("Selecionar de Projetos");
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

    <form action="<?=URL?>admin/visualizar-projeto" method="post" class="col-md-6">
        <label>Selecionar projeto:</label>
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

        <button class="btn btn-primary" style="margin-top: 10px">Visualizar</button>
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