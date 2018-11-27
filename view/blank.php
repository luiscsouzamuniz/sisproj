<?php
include("../../wp.include.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionAluno();
$API = new consumo_api;
//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');

//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Aluno");
include '../includes/menu-aluno.php';
$wp->Entry("open");

	?>

	<?php if (isset($_SESSION['mensagem'])): ?>
		
            <div class="col-lg-12">
                <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
                </div>
            </div>
       
        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>

        <?php $dados = $API->retornarAluno($_SESSION['chave_aluno']); ?>
        
        <?php print_r($dados) ?>

        <p>Nome:  <?= $dados['sucesso']['nome'] ?></p>
        <p>RA:    <?= $dados['sucesso']['RA'] ?></p>
        <p>Email: <?= $dados['sucesso']['email'] ?></p>
        <p>Curso: <?= $dados['sucesso']['curso'] ?></p>
        


	<?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();