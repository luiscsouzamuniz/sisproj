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

$wp->PageTitle("Professor");
include '../includes/menu-admin-home.php';
$wp->Entry("open");

	?>

	<?php if (isset($_SESSION['mensagem'])): ?>
		<div class="row">
            <div class="col-lg-12">
                <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>
	<?php $listarCursosCoordenados = $API->listarCursosCoordenados($_SESSION['chave_professor']); ?>
	<?php $dados = $API->retornarProfessor($_SESSION['chave_professor']); ?>

    <p> Nome: <?= $dados['sucesso']['nome'] ?></p>
    <p> Email: <?= $dados['sucesso']['email'] ?></p>

	<?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();