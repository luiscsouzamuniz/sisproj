
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

<ul class="nav nav-tabs" style="margin-bottom: 20px">
  	<li class="nav-item">
    	<a class="nav-link active" href="<?=URL?>admin/">Página inicial</a>
  	</li>
  	<li class="nav-item dropdown">
	    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Projeto</a>
	    <div class="dropdown-menu">
		    <a class="dropdown-item" href="<?= URL ?>admin/cadastro-projetos">Cadastrar Projeto</a>
		    <a class="dropdown-item" href="<?= URL ?>admin/sessao-projeto">Cadastrar Sessão de Projeto</a>
		    <a class="dropdown-item" href="<?= URL ?>admin/listar-projetos">Lista de projetos</a>
		    <a class="dropdown-item" href="<?= URL ?>admin/selecionar-projeto">Selecionar projeto</a>
	    </div>
  	</li>

  	<li class="nav-item dropdown">
	    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Documentação</a>
	    <div class="dropdown-menu">
		    <a class="dropdown-item" href="#">Cadastrar documentação de projeto</a>
	    </div>
  	</li>

  	<?php if ($_SESSION['dados_professor']['sucesso']['coordenador'] == '1'): ?>	
  	<li class="nav-item">
    	<a class="nav-link" href="<?=URL?>admin/alocacao">Alocação</a>
  	</li>
  	<?php endif ?>

  	<li class="nav-item">
    	<a class="nav-link" href="<?=URL?>admin/inscricoes">Inscrições</a>
  	</li>

  	<li class="nav-item">
    	<a class="nav-link disabled" href="<?= URL ?>admin/logout">Sair</a>
  	</li>
  	
</ul>