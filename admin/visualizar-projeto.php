<?php

include("../../wp.include.php");
include '../Autoload.php';

$Autoload = new Autoload;
if (!isset($_REQUEST['projeto']) || empty($_REQUEST['projeto'])) {
    header('location: '.URL.'admin/selecionar-projeto');
}else{
    $idProjeto = $_REQUEST['projeto'];
}
$Autoload->sessionProfessor();
$API = new consumo_api;
$projeto = new projeto;
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
	<script type="text/javascript" src="<?=URL?>assets/tinymce/jquery.tinymce.min.js"></script>
    <script type="text/javascript" src="<?=URL?>assets/tinymce/tinymce.min.js"></script>
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
        <?php $dadosProjeto = $projeto->exibirProjetoCompleto($idProjeto) ?>

        <?php foreach ($dadosProjeto as $listaProjeto): ?>
            <h3><?= $listaProjeto->titulo ?></h3>
            <p>Categoria de projeto: <strong><?= $listaProjeto->categoria ?></strong></p>
            <p>Data de início: <?= date('d/m/Y', strtotime($listaProjeto->dtInicio)) ?></p>
            <p>Data de Término: <?= date('d/m/Y', strtotime($listaProjeto->dtTermino)) ?></p>
	</div>
    <div class="table-responsive">
        <h3>Alunos</h3>

	      	<?php 

            $inscricao = $projeto->selecionarAlunosProjeto($idProjeto);
            ?>

            <?php if (!empty($inscricao)): ?>
                <div class="table-responsive">
                	<table>
                		<thead>
                			<tr>
                    			<th>RA</th>
                    			<th>Nome</th>
                    			<th>Email</th>
                    			<th>CPF</th>
                                <th>Visualizar projeto</th>
                    			<th>Inscrição</th>
                    		</tr>
                		</thead>
                		<tbody>
                			<?php foreach ($inscricao as $listaInscricao): ?>
                                <?php  
                                    $dadosAluno = $API->retornaAlunoCPF(array('cpf' => $listaInscricao->cpfAluno, 'chave'=> $_SESSION['chave_professor']['chave']));
                                ?>
                                <tr>
                                	<td><?= $dadosAluno['sucesso']['RA']?></td>
                                	<td><?= $dadosAluno['sucesso']['nome']?></td>
                                	<td><?= $dadosAluno['sucesso']['email']?></td>
                                    <td><?= $dadosAluno['sucesso']['CPF']?></td>
                                	<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#<?= $dadosAluno['sucesso']['RA']?>mod"><span class="fa fa-eye"></span></button></td>
                                	<td><button type="button" class="btn btn-danger" onclick="deletar<?=$listaInscricao->idInscricao?>()"><span class="fa fa-trash"></span></button></td>
                                </tr>

                                <div id="<?= $dadosAluno['sucesso']['RA']?>mod" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><?= $dadosAluno['sucesso']['nome']?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <ul>
                                                    <li><span class="fa fa-edit" style="color: blue"></span> Liberado para edição</li>
                                                    <li><span class="fa fa-clock-o" style="color: orange"></span> Aguardando data de liberação</li>
                                                    <li><span class="fas fa-clipboard-check" style="color: green"></span> Edição concluída</li>
                                                </ul>
                                                <?php  

                                                $sessaoProjeto = $projeto->listarSessaoProjeto($listaProjeto->idProjeto);
                                                ?>
                                                <ul class="nav nav-tabs">
                                                    <?php foreach ($sessaoProjeto as $listaSessao): ?>
                                                        <?php 
                                                        if (date('Y-m-d', strtotime($listaSessao->dtTermino)) >= date('Y-m-d') && date('Y-m-d', strtotime($listaSessao->dtInicio)) <= date('Y-m-d')): ?>
                                                            <li><a data-toggle="tab" href="#<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>tab"><?= $listaSessao->nome?> <span class="fa fa-edit" style="color: blue"></span></a></li>
                                                        <?php elseif(date('Y-m-d', strtotime($listaSessao->dtTermino)) < date('Y-m-d')): ?>
                                                            <li><a data-toggle="tab" href="#<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>tab"><?= $listaSessao->nome?> <span class="fas fa-clipboard-check" style="color: green"></span></a></li>
                                                        <?php elseif (date('Y-m-d', strtotime($listaSessao->dtInicio)) > date('Y-m-d')) :?>
                                                            <li><a data-toggle="tab" href="#<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>tab"><?= $listaSessao->nome?> <span class="fa fa-clock-o" style="color: orange"></span></a></li>
                                                        <?php endif; ?>
                                                    <?php endforeach ?>
                                                </ul>

                                                <div class="tab-content">

                                                    <?php foreach ($sessaoProjeto as $listaSessao): ?>
                                                        <?php 
                                                        if (date('Y-m-d', strtotime($listaSessao->dtTermino)) >= date('Y-m-d') && date('Y-m-d', strtotime($listaSessao->dtInicio)) <= date('Y-m-d')): ?>
                                                            <div id="<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>tab" class="tab-pane fade <?php if($contador == 1): echo 'in active'; endif; $contador++ ?>">
                                                                
                                                                <h3><?= $listaSessao->nome?></h3>

                                                                <p>Data de abertura: <?= date('d/m/Y', strtotime($listaSessao->dtInicio))?></p>
                                                                <p>Data de entrega: <?= date('d/m/Y', strtotime($listaSessao->dtTermino))?></p>

                                                                <?php  
                                                                $sessaoItem = $projeto->listarSessaoItemAluno($listaSessao->idSessao, $dadosAluno['sucesso']['RA']);
                                                                ?>
                                                                <script type="text/javascript">
                                                                    tinymce.init({
                                                                        valid_elements: "*[*]",
                                                                        selector: '#<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>item',
                                                                        plugins: [
                                                                          'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                                                                          'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking ',
                                                                          'save table contextmenu directionality emoticons template paste textcolor'
                                                                        ],
                                                                        toolbar: 'insert undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | fontsizeselect fontselect | bullist numlist outdent indent | link image | print preview media | forecolor backcolor paste',
                                                                        language_url : '<?=URL?>assets/tinymce/langs/pt_BR.js',
                                                                        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 32pt 36pt 42pt 72pt',
                                                                        entity_encoding : "raw",
                                                                        indentation : '20pt',
                                                                        content_css : '<?=URL?>assets/tinymce/css/abnt.css'
                                                                        
                                                                    });
                                                                </script>

                                                                <?php if (empty($sessaoItem)): ?>
                                                                    <h3>Não há item publicado.</h3>
                                                                <?php else: ?>
                                                                    <?php foreach ($sessaoItem as $listarSessaoItem): ?>
                                                                    <?php if (!empty($listarSessaoItem->texto)): ?>

                                                                        <?php $statusItem = $projeto->selecionarStatusItem($listarSessaoItem->idStatus); ?>
                                                                        <p>Status do item: <b style="color: <?=$statusItem[0]->cor?>;"><?= $statusItem[0]->status?></b></p>

                                                                        <form action="<?=URL?>controller/projeto.php?command=alterarSessaoProjetoItem" method="post">
                                                                            <textarea id="<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>item" name='texto' style="height: 300px"><?= $listarSessaoItem->texto?></textarea>
                                                                            <input type="hidden" name="idSessao" value="<?=$listaSessao->idSessao?>">
                                                                            <input type="hidden" name="raAluno" value="<?=$listarSessaoItem->raAluno?>">
                                                                            <input type="hidden" name="idStatus" value="2">
                                                                            <input type="hidden" name="idItem" value="<?=$listarSessaoItem->idItem?>">
                                                                            <input type="hidden" name="idProjeto" value="<?=$idProjeto?>">
                                                                            <input type="hidden" name="page" value="admin/visualizar-projeto">

                                                                            <button class="btn btn-primary" style="margin-top: 10px">Enviar</button>
                                                                            <button class="btn btn-warning" type="button" style="margin-top: 10px" onclick="window.open('<?=URL?>admin/gerar-pdf/selecionarSessaoItem/<?=$listaSessao->idSessao?>/<?=$listarSessaoItem->idItem?>','blank')">Gerar PDF</button>
                                                                        </form>
                                                                    <?php endif ?>
                                                                <?php endforeach ?>
                                                                <?php endif ?>
                                                                
                                                                    
                                                            </div>
                                                        <?php elseif(date('Y-m-d', strtotime($listaSessao->dtTermino)) < date('Y-m-d')): ?>
                                                            <div id="<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>tab" class="tab-pane fade <?php if($contador == 1): echo 'in active'; endif; $contador++ ?>">
                                                                <h3><?= $listaSessao->nome?></h3>


                                                                <p>Data de abertura: <?= date('d/m/Y', strtotime($listaSessao->dtInicio))?></p>
                                                                <p>Data de entrega: <?= date('d/m/Y', strtotime($listaSessao->dtTermino))?></p>


                                                                <script type="text/javascript">
                                                                    tinymce.init({
                                                                        selector: '#<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>item',
                                                                         readonly : 1,
                                                                        plugins: [
                                                                          'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                                                                          'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking fullpage',
                                                                          'save table contextmenu directionality emoticons template paste textcolor'
                                                                        ],
                                                                        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | fontsizeselect fontselect | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor paste',
                                                                        language_url : '<?=URL?>assets/tinymce/langs/pt_BR.js',
                                                                        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 32pt 36pt 42pt 72pt',
                                                                        entity_encoding : "raw",
                                                                        indentation : '20pt',
                                                                        content_css : '<?=URL?>assets/tinymce/css/abnt.css'
                                                                  });
                                                                </script>

                                                                <?php  
                                                                $sessaoItem = $projeto->listarSessaoItemAluno($listaSessao->idSessao, $dadosAluno['sucesso']['RA']);

                                                                ?>

                                                                <?php if (empty($sessaoItem)): ?>

                                                                        <h3>Não há publicação do item.</h3>
                                                                        
                                                                    </form>
                                                                <?php else: ?>
                                                                    
                                                                    <?php foreach ($sessaoItem as $listarSessaoItem): ?>

                                                                        <?php $statusItem = $projeto->selecionarStatusItem($listarSessaoItem->idStatus); ?>
                                                                        <p>Status do item: <b style="color: <?=$statusItem[0]->cor?>;"><?= $statusItem[0]->status?></b></p>

                                                                        <textarea id="<?= $dadosAluno['sucesso']['RA']?><?=$listaSessao->idSessao?>item" style="height: 300px" disabled><?= $listarSessaoItem->texto?></textarea>
                                                                        <button class="btn btn-warning" type="button" style="margin-top: 10px" onclick="window.open('<?=URL?>admin/gerar-pdf/selecionarSessaoItem/<?=$listaSessao->idSessao?>/<?=$listarSessaoItem->idItem?>','blank')">Gerar PDF</button>
                                                                    <?php endforeach ?>
                                                                        
                                                                <?php endif ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    
                                                    <?php endforeach ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <script type="text/javascript">
                                	function deletar<?=$listaInscricao->idInscricao?>() {
                                		var confirm = window.confirm("Deseja excluir a inscrição?");

                                		if (confirm) {
                                			window.location.href = "<?=URL?>controller/projeto.php?command=inscricao&parametro=1&cpfAluno=<?= $dadosAluno['sucesso']['CPF']?>&raAluno=<?=$dadosAluno['sucesso']['RA']?>&page=visualizar-projeto&idProjeto=<?=$listaSessao->idProjeto?>";
                                		}
                                	}
                                </script>

	                        <?php endforeach ?>
                		</tbody>
                	</table>
                </div>
	                        
                
                
            <?php else: ?>
                <h3>Não há alunos inscritos!</h3>
            <?php endif ?>

        <?php endforeach ?>
    </div>


    <?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();