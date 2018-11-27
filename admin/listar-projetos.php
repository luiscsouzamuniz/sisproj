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

$wp->PageTitle("Listar projetos");
include '../includes/menu-admin-home.php';
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

    <?php  
        $projeto = new projeto;
        $dadosProjeto = $projeto->listarProjetoProfResp($_SESSION['dados_professor']['sucesso']['codigo']);
        
    ?>  
    
    <div class="table-responsive">
        <input class="form-control" id="myInput" type="text" placeholder="Buscar">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Detalhe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dadosProjeto as $lista): ?>
                    <tr>
                        <td><?=$lista->titulo?></td>
                        <td><?=$lista->categoria?></td>
                        <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#<?=$lista->idProjeto?>"><span class="fa fa-info-circle"></span></button></td>
                    </tr>
                    <div id="<?=$lista->idProjeto?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?= $lista->titulo?></h4>
                          </div>
                          <div class="modal-body">
                            <?php
                            $dadosProfessor = $API->selecionarProfessor(array('codigo' => $lista->responsavel, 'chave' => $_SESSION['chave_professor']['chave']));
                            ?>
                            <p>Data de início: <?=date('d/m/Y',strtotime($lista->dtInicio))?></p>
                            <p>Data de término: <?=date('d/m/Y',strtotime($lista->dtTermino))?></p>
                            <?php foreach ($dadosProfessor['sucesso'] as $listaProfessor): ?>
                                <p>Responsável: <?= $listaProfessor['nome']?></p>
                                <p>Email: <?= $listaProfessor['email']?></p>
                            <?php endforeach ?>

                            <?php  
                                $dadosAlocacao = $projeto->selecionarProfessoresAlocados($lista->idProjeto);
                            ?>

                            <?php if (!empty($dadosAlocacao)): 
                                
                            ?>
                                <h3>Alocação:</h3>
                                <hr>
                                
                                <?php foreach ($dadosAlocacao as $listaAlocacao): ?>

                                    <?php $dadosAlocados = $API->selecionarProfessor(array('codigo' => $listaAlocacao->idProfessor, 'chave' => $_SESSION['chave_professor']['chave'])); ?>
                                    <?php foreach ($dadosAlocados['sucesso'] as $listaAlocados): ?>
                                        <p>Nome: <?= $listaAlocados['nome']?></p>
                                        <p>Email: <?= $listaAlocados['email']?></p>
                                    <?php endforeach ?>
                                    <?php if ($listaAlocacao->orientador == 1): ?>
                                        <p>Função: Orientador</p>
                                    <?php elseif ($listaAlocacao->coOrientador == 1) :?>
                                        <p>Função: Coorientador</p>
                                    <?php elseif ($listaAlocacao->tutor == 1) :?>
                                        <p>Função: Tutor</p>
                                    <?php endif ?>
                                    

                                    <?php if ($_SESSION['dados_professor']['sucesso']['coordenador'] == 1): ?>
                                    	<button class="btn btn-danger" onclick="excluirAloc<?=$listaAlocacao->idAlocacao?>()"> <span class="fa fa-fw fa-trash"></span></button>
                                    <?php endif ?>
                                    <hr>

                                    <script type="text/javascript">
                                    	function excluirAloc<?=$listaAlocacao->idAlocacao?>(){
                                    		var confirm = window.confirm('Deseja realmente excluir essa alocação?');

                                    		if (confirm == true) {
                                    			location.href = '<?=URL?>controller/projeto.php?command=excluirAlocacao&idProjeto=<?=$listaAlocacao->idProjeto?>&idAlocacao=<?=$listaAlocacao->idAlocacao?>';
                                    		}
                                    	}
                                    </script>
                                <?php endforeach ?>

                            <?php else: ?>
                                <h3>Não há professores alocados</h3>
                                <hr>
                            <?php endif ?>

                            <?php 

                            $inscricao = $projeto->selecionarAlunosProjeto($lista->idProjeto);
                            ?>

                            <?php if (!empty($inscricao)): ?>
                                <h3>Alunos:</h3>
                                <hr>
                                <?php foreach ($inscricao as $listaInscricao): ?>
                                    <ul>
                                        <?php  
                                            $dadosAluno = $API->retornaAlunoCPF(array('cpf' => $listaInscricao->cpfAluno, 'chave'=> $_SESSION['chave_professor']['chave']));
                                        ?>
                                        <legend><?= $dadosAluno['sucesso']['nome']?></legend>
                                        <ul class="list-group">
                                            <li class="list-group-item">CPF: <?= $dadosAluno['sucesso']['CPF']?></li>
                                            <li class="list-group-item">Email: <?= $dadosAluno['sucesso']['email']?></li>
                                            <li class="list-group-item">Semestre: <?= $dadosAluno['sucesso']['serie']?></li>
                                        </ul>

                                        
                                        
                                    </ul>
                                <?php endforeach ?>
                                
                                
                            <?php else: ?>
                                <h3>Não há alunos inscritos!</h3>
                            <?php endif ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>

                      </div>
                    </div>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function(){
          $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
    </script>


    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();