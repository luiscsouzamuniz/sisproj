<?php
include("../../wp.include.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionAluno();
$API = new consumo_api;
//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');
$projeto = new projeto;

//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Lista de projetos");
include '../includes/menu-aluno.php';
$wp->Entry("open");

	?>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<?php if (isset($_SESSION['mensagem'])): ?>
		

                <div class="alert alert-<?=$_SESSION['mensagem']['classe']?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i>  <strong><?=$_SESSION['mensagem']['tipo']?></strong> <?= $_SESSION['mensagem']['texto']; ?>
                </div>

       
        <?php unset($_SESSION['mensagem']) ?>
    <?php endif ?>

    <?php $dados = $API->retornarAluno($_SESSION['chave_aluno']); ?>
    <?php $dadosProjeto = $projeto->listarProjetosCurso($_SESSION['dados_aluno']['sucesso']['cr_codigo']); ?>
    <?php if (!empty($dadosProjeto)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <th>Título</th>
                    <th>Início</th>
                    <th>Término</th>
                    <th>Inscrever</th>
                </thead>
                <tbody>
                    <?php foreach ($dadosProjeto as $listaProjetos): ?>
                        <tr>
                            <td><?= $listaProjetos->titulo?></td>
                            <td><?= date('d/m/Y', strtotime($listaProjetos->dtInicio))?></td>
                            <td><?= date('d/m/Y', strtotime($listaProjetos->dtTermino))?></td>

                            <?php  
                            	$inscricao = $projeto->selecionarInscricaoAluno(strval($_SESSION['dados_aluno']['sucesso']['RA']), $listaProjetos->idProjeto);

                            	if (!empty($inscricao) && $inscricao[0]->status == 1) {
                            		$classBtn = 'success';
                                    $text = 'Inscrito';
                            		$value = 1;
                            	}else{
                            		$classBtn = 'danger';
                                    $text = 'Inscrever-se';
                            		$value = 0;
                            	}
                            ?>
                            <td><button type="button" class="btn btn-<?=$classBtn?>" value="<?=$value?>" id="<?= $_SESSION['dados_aluno']['sucesso']['RA'].$listaProjetos->idProjeto ?>"><span id="text<?= $listaProjetos->idProjeto?>"><?= $text?></span></button></td>

                            <script>
                                $(document ).ready(function() {
                                    var botao = document.getElementById("<?= $_SESSION['dados_aluno']['sucesso']['RA'].$listaProjetos->idProjeto ?>");

                                    $(botao).click(function(){
                                    	if(botao.value == 0){
                                    		$.ajax({
			                                    type: "POST",
			                                    url: "<?= URL?>controller/projeto.php",
			                                    data: { idProjeto: <?= $listaProjetos->idProjeto?>, raAluno: '<?= strval($_SESSION['dados_aluno']['sucesso']['RA']) ?>', cpfAluno: '<?= strval($_SESSION['dados_aluno']['sucesso']['CPF']) ?>', command: 'inscricao', parametro: botao.value},
			                                    success: function(data){
			                                        botao.className = "btn btn-success";
                                                    $(botao).attr('value', 1 );
                                                    $("#text<?= $listaProjetos->idProjeto?>").html("Inscrito");
			                                    }
			                                }).done(function (data) {
                                                alert(data);
                                            });
                                    	}
                                    	else if (botao.value == 1) {

                                            var confirm =  window.confirm("Deseja realmente cancelar a inscrição?");

                                            if (confirm == true) {
                                                    $.ajax({
                                                    type: "POST",
                                                    url: "<?= URL?>controller/projeto.php",
                                                    data: { idProjeto: <?= $listaProjetos->idProjeto?>, raAluno: '<?= strval($_SESSION['dados_aluno']['sucesso']['RA']) ?>', cpfAluno: '<?= strval($_SESSION['dados_aluno']['sucesso']['CPF']) ?>', command: 'inscricao', parametro: botao.value},
                                                    success: function(data){
                                                        botao.className = "btn btn-danger";
                                                        $(botao).attr('value', 0 );
                                                        $("#text<?= $listaProjetos->idProjeto?>").html("Inscrever-se");
                                                    }
                                                }).done(function (data) {
                                                    alert(data);
                                                });
                                            }
                                            		
                                    	}
                                    });
                                    
                                       
                                                   
                                });
                            </script>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <h3>Não há projetos cadastrados para o curso.</h3>
    <?php endif ?>
        


	<?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();