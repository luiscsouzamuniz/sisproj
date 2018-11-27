<?php
include("../../wp.include.php");
require_once '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionProfessor();
$API = new consumo_api;
$projeto = new projeto;
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
        
            <div class="col-md-6">
                <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
                </div>
            </div>

        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>
    
    <form class="col-md-6" method="post" action="<?=URL?>controller/projeto.php?command=cadastrar">
        <label>Título do Projeto:</label>
        <input type="text" name="titulo" class="form-control" required>
        <label>Categoria:</label>
        <?php $dados = $projeto->listarCategorias(); ?>

        <select class="form-control" name="categoria" required>
            <option>Selecione uma opção</option>

            <?php foreach ($dados as $lista): ?>
                <option value="<?= $lista->idCategoria ?>"><?= $lista->categoria ?></option>
            <?php endforeach ?>
                
        </select>
        <label>Selecionar curso:</label>
        <select name="curso" class="form-control">
            <?php $dados = $API->listarCursos($_SESSION['chave_professor']) ?>
            <option>Selecione uma opção</option>
            <?php foreach ($dados['sucesso'] as $lista): ?>
                <option value="<?= $lista['codigo']?>"><?= $lista['nome']?></option>
            <?php endforeach ?>
        </select>

        <label>Data de início:</label>
        <input type="date" name="dtInicio" class="form-control" required>

        <label>Data de término:</label>
        <input type="date" name="dtTermino" class="form-control" required>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Enviar <span class="fa fa-fw fa-send"></span></button>
    </form>
    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();