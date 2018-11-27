<?php
include("../wp.include.php");

//INICIA CLASSE E RECUPERA CONFIGURAÇÕES DO TEMA
$wp = new WPINC();
$options = get_option('zeevision_options');

//RECUPERA CORPO DO SITE
$wp->Head();
$wp->Wrap("open");

$wp->PageTitle("Grupos de Estudo e Iniciação Científica");
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

    <?php endif  ?>
	
	<div class="col-md-12">
		<fieldset>
			<legend>Login:</legend>
			<form action="controller/login.php" method="post">
				<div class="col-md-6">
					<label>RA/Email:</label>
					<input type="text" name="ra" class="form-control" required>
					<label>Senha:</label>
					<input type="password" name="senha" class="form-control" placeholder="********" required>
					<label>Perfil:</label>
					<select class="form-control" name="tipo" required>
						<option value="">Selecione uma opção:</option>
						<option value="a">Aluno</option>
						<option value="p">Professor</option>
					</select>
					<input type="hidden" name="command" value="logar">

					<button class="btn btn-primary" style="margin-top: 10px;">Entrar</button>
				</div>
			</form>
		</fieldset>
	</div>

	<?php

//RECUPERA CORPO DO SITE
$wp->Entry("close");
$wp->Wrap("close");
$wp->Footer();