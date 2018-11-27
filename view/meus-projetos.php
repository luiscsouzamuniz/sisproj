<?php
include("../../wp.include.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionAluno();
$API = new consumo_api;
$projeto = new projeto;
//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');

//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Meus Projetos");
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
        <?php $dados = $projeto->listarInscricaoAluno($_SESSION['dados_aluno']['sucesso']['RA']); ?>

        <?php if (!empty($dados)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Visualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $lista): ?>
                            <tr>
                                <td><?=$lista->titulo?></td>
                                <td class="center"><button class="btn btn-info" onclick="location.href = this.value" value="<?= URL ?>aluno/visualizar-projeto?projeto=<?=$lista->idProjeto?>"><span class="fa fa-fw fa-eye"></span></button></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <h3>Não há inscrição em projetos!</h3>
        <?php endif ?>
        
            

        


    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();