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

$wp->PageTitle("Alocação");
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

    <?php $listaPRofessores = $API->retornarProfessores($_SESSION['chave_professor']); ?>
    
    <form class="col-md-6" method="post" action="<?=URL?>controller/projeto.php?command=cadastrarAlocacao">

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

        <label>Selecionar o professor:</label>
        <select class="js-example-basic-single form-control" name="professor" required>
            <option value="">Selecione uma opção</option>
            <?php foreach ($listaPRofessores['sucesso'] as $lista): ?>
                <option value="<?= $lista['codigo']?>"><?= $lista['nome']?></option>
            <?php endforeach ?>
        </select>

        <label>Função</label>
        <select class="js-example-basic-single form-control" name="funcao" required>
            <option value="">Selecione uma opção</option>
            <option value="orientador">Orientador</option>
            <option value="coorientador">Coorientador</option>
            <option value="tutor">Tutor</option>
        </select>

        <label>Carga Horária Semanal:</label>
        <input type="number" name="cargaHoraria" class="form-control" required min="0" max="44" step="0.5" required>

        <label>Valor Hora:</label>
        <input type="number" name="valorHora" class="form-control" step="0.01" min="0" required>

        <button class="btn btn-primary" style="margin-top: 10px">Alocar <span class="fa fa-fw fa-exchange"></span></button>
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