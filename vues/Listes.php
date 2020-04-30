<?php $title = 'Listes';
ob_start(); ?>

<div class="container-fluid mt-5" id="pageListe">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <a class="navbar-brand" href="index.php?action=accueil">Accueil</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                  <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-between" id="collapsibleNavbar">
                  <span class="navbar-text ml-auto border border-success py-2 px-5 rounded rounded-lg font-weight-bold"><?php echo "Bonjour " . $_SESSION['Prenom']."  ". $_SESSION['Nom']; ?></span>
                  <ul class="navbar-nav ml-auto">
                        <li class="nav-item ml-auto">
                              <a class="nav-link " href="index.php?action=Deconnexion">Déconnexion</a>
                        </li>
                  </ul>
            </div>
      </nav>
      <br>
      <br>

	<?php
	if ($_SESSION["Profil"] == 0) {
		echo '<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalAjoutSalle">Ajouter une salle</button>
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalAjoutLigue">Ajouter une ligue</button>';
	}
	?>

	<!-- ############## MODAUX AJOUTS ####################### -->

		<!-- {[{[{[{[{[{[{[{[{ MODAL AJOUT SALLE }]}]}]}]}]}]}]}]} -->

	<div id="ModalAjoutSalle" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Ajouter une salle</h2>
				</div>
				<div class="modal-body">
					<form id="formAjoutSalle">
						<label for="NomSalle">Nom de la salle : <input class="form-control" name="NomSalle" id="NomSalle" type="text"></label>
						<label for="CodeBatiment_FK">Batiment : <select class="form-control" id="CodeBatiment_FK" name="CodeBatiment_FK">
							<?php
                                        foreach ($tbBatiments as $Batiment) {
                                            echo '<option value="' . $Batiment["CodeBatiment"] . '">' . $Batiment["CodeBatiment"] . ' - ' . $Batiment["NomBatiment"] . '</option>';
                                        }
                                        ?>
						</select>
						<label for="CodeTypeSalle_FK">Type de salle : <select class="form-control" id="CodeTypeSalle_FK" name="CodeTypeSalle_FK">
							<?php
                            foreach ($tbTypesSalles as $Type) {
                                echo '<option value="' . $Type["CodeTypeSalle"] . '">' . utf8_encode($Type["Description"]) . '</option>';
                            }
                            ?>
						</select>
						<br>
						<p style="color: red" id="erreurSalle"></p>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="saveAjoutSalle">Sauvegarder</button>
				</div>
			</div>
		</div>
	</div>

			<!-- {[{[{[{[{[{[{[{[{ MODAL AJOUT SALLE }]}]}]}]}]}]}]}]} -->

	<div id="ModalAjoutLigue" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Ajouter une ligue</h2>
				</div>
				<div class="modal-body">
					<form id="formAjoutLigue">
						<label for="NomLigue">Nom de la ligue : <input class="form-control" name="NomLigue" id="NomLigue" type="text"></label>
						<label for="NumUser_FK">Utilisateur affilié :
							<select class="form-control" name="NumUser_FK" id="NumUser_FK">
								<?php
                                foreach ($tbUsers as $User) {
                                    echo '<option value="' . $User["NumUser"] . '">' . utf8_encode($User["Prenom"]) . ' ' . utf8_encode($User["Nom"]) . '</option>';
                                }
                                ?>
							</select>
						</label>
						<br>
						<p style="color: red" id="erreurLigue"></p>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="saveAjoutLigue">Sauvegarder</button>
				</div>
			</div>
		</div>
	</div>
	<hr>

	<!-- ################### GENERATION TABLEAUX ########################3 -->

      <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
              <a class="nav-item nav-link active" id="gestionSalles-tab" data-toggle="tab" href="#gestionSalles" role="tab" aria-controls="gestionSalles" aria-selected="true">Salles</a>
              <a class="nav-item nav-link" id="gestionLigues-tab" data-toggle="tab" href="#gestionLigues" role="tab" aria-controls="gestionLigues" aria-selected="false">Ligues</a>
          </div>
      </nav>
	<div id="tab-content">
		<div class="tab-content" id="nav-tabContent">

			<!-- {[{[{[{[{[{[{[{[{ GENERATION LIGNES SALLES }]}]}]}]}]}]}]}]} -->

			<div class="tab-pane fade show active" id="gestionSalles" role="tabpanel" aria-labelledby="gestionSalles-tab">
				<table class="table table-hover">
					<thead class="thead-dark">
						<tr>
							<th>Nom de la salle</th>
							<th>Type de salle</th>
							<th>Batiment</th>
							<?php if ($_SESSION["Profil"] == 0) {
								echo '<th>Statut</th>
								 <th>Modifier</th>';
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
                                    foreach ($tbSalles as $Salle) {
							if ($_SESSION["Profil"] == 0) {
								echo "<tr>";
									echo "<td>" . $Salle['NomSalle'] . "</td>";
									echo "<td>" . utf8_encode($Salle['Description']) . "</td>";
									echo "<td>" . $Salle['NomBatiment'] . "</td>";
									if ($Salle['Activation'] == 0) {
										echo "<td>Désactivée</td>";
									} else {
										echo "<td>Activée</td>";
									}
									echo "<td>
										<button class='btn btn-secondary edit-button-salle' data-toggle='modal' data-target='#ModalModifSalle'>
											<i class='fas fa-edit'></i>
										</button>
									</td>";
									echo "<td style='display: none'>" . $Salle['NumSalle'] . "</td>";
								echo "</tr>";
							} else {
								if ($Salle['Activation'] == 1) {
									echo "<tr>";
										echo "<td>" . $Salle['NomSalle'] . "</td>";
										echo "<td>" . utf8_encode($Salle['Description']) . "</td>";
										echo "<td>" . $Salle['NomBatiment'] . "</td>";
										echo "<td style='display: none'>" . $Salle['NumSalle'] . "</td>";
									echo "</tr>";
								}
							}
                                    }
                                ?>
					</tbody>
				</table>
			</div>

			<!-- {[{[{[{[{[{[{[{[{ GENERATION LIGNES LIGUES }]}]}]}]}]}]}]}]} -->

			<div class="tab-pane fade show" id="gestionLigues" role="tabpanel" aria-labelledby="gestionSalles-tab">
				<table class="table table-hover">
					<thead class="thead-dark">
						<tr>
							<th>Nom de la ligue</th>
							<th>Utilisateur affilié</th>
							<?php if ($_SESSION["Profil"] == 0) {
								echo '<th>Statut</th>
									<th>Modifier</th>';
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
                                    foreach ($tbLigues as $Ligue) {
							if ($_SESSION["Profil"] == 0) {
								echo "<tr>";
								echo "<td>" . utf8_encode($Ligue['NomLigue']) . "</td>";
								if ($Ligue['Prenom']) {
									echo "<td>" . utf8_encode($Ligue['Prenom']) . " " . utf8_encode($Ligue['Nom']) . "</td>";
								} else {
									echo "<td>---------</td>";
								}
								if ($Ligue['Activation'] == 0) {
									echo "<td>Désactivée</td>";
								} else {
									echo "<td>Activée</td>";
								}
								echo "<td>
									<button class='btn btn-secondary edit-button-ligues' data-toggle='modal' data-target='#ModalModifLigue'>
										<i class='fas fa-edit'></i>
									</button>
								</td>";
								echo "<td style='display: none'>" . $Ligue['NumLigue'] . "</td>";
								echo "</tr>";
							} else {
								if ($Ligue['Activation'] == 1 && $Ligue['Prenom'] == $_SESSION['Prenom']) {
									echo "<tr>";
									echo "<td>" . utf8_encode($Ligue['NomLigue']) . "</td>";
									if ($Ligue['Prenom']) {
										echo "<td>" . utf8_encode($Ligue['Prenom']) . " " . utf8_encode($Ligue['Nom']) . "</td>";
									} else {
										echo "<td>---------</td>";
									}
									echo "<td style='display: none'>" . $Ligue['NumLigue'] . "</td>";
									echo "</tr>";
								}
							}
                                    }
                                ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- ################## MODAUX MODIFS ####################### -->

			<!-- {[{[{[{[{[{[{[{[{ MODAL MODIFS SALLES }]}]}]}]}]}]}]}]} -->

	<div id="ModalModifSalle" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Modifier la salle</h2>
				</div>
				<div class="modal-body">
					<form id="formModifSalle">
						<label for="NomSalleModif">Nom de la salle : </label>
						<input class="form-control" name="NomSalle" id="NomSalleModif" type="text">
						<label for="CodeBatiment_FKModif">Batiment : </label>
						<select class="form-control" id="CodeBatiment_FKModif" name="CodeBatiment_FK">
							<?php
                                        foreach ($tbBatiments as $Batiment) {
                                            echo '<option value="' . $Batiment["CodeBatiment"] . '" id="' . $Batiment["NomBatiment"] . '">'
                                  . $Batiment["CodeBatiment"] . ' - ' . $Batiment["NomBatiment"] . '</option>';
                                        }
                                        ?>
						</select>
						<label for="CodeTypeSalle_FKModif">Type de salle : </label>
						<select class="form-control" id="CodeTypeSalle_FKModif" name="CodeTypeSalle_FK">
							<?php
                                        foreach ($tbTypesSalles as $Type) {
                                            echo '<option value="' . $Type["CodeTypeSalle"] . '" id="' . utf8_encode($Type["Description"]) . '">'
							. utf8_encode($Type["Description"]) . '</option>';
                                        }
                                        ?>
						</select>
						<hr>
						<div class="form-check">
							<input type="radio" id="Activée" class="form-check-input" name="Activation" value=1>
								<label class="form-check-label" for="Activée">Activée</label>
						</div>
						<div class="form-check">
							<input type="radio" id="Désactivée" class="form-check-input" name="Activation" value=0>
								<label class="form-check-label" for="Désactivée">Désactivée</label>
						</div>
						<input name="NumSalle" id="NumSalleModif" type="hidden">
						<br>
						<p style="color: red" id="erreurModifSalle"></p>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="saveModifSalle">Sauvegarder</button>
				</div>
			</div>
		</div>
	</div>

				<!-- {[{[{[{[{[{[{[{[{ MODAL MODIFS LIGUES }]}]}]}]}]}]}]}]} -->

	<div id="ModalModifLigue" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Modifier la ligue</h2>
				</div>
				<div class="modal-body">
					<form id="formModifLigue">
						<label for="NomLigueModif">Nom de la ligue : </label>
							<input class="form-control" name="NomLigue" id="NomLigueModif" type="text">
						<label for="NumUser_FKModif">Utilisateur affilié : </label>
							<select class="form-control" id="NumUser_FKModif" name="NumUser_FK">
								<?php
                                                foreach ($tbUsers as $User) {
                                                    echo '<option value="' . $User["NumUser"] . '" id="' . $User["Prenom"] . ' ' . $User["Nom"] . '">
										' . $User["Prenom"] . ' ' . $User["Nom"] . '</option>';
                                                }
                                            ?>
							</select>
						<hr>
						<div class="form-check">
							<input type="radio" id="LigueActivée" class="form-check-input" name="Activation" value=1>
								<label class="form-check-label" for="LigueActivée">Activée</label>
						</div>
						<div class="form-check">
							<input type="radio" id="LigueDésactivée" class="form-check-input" name="Activation" value=0>
								<label class="form-check-label" for="LigueDésactivée">Désactivée</label>
						</div>
						<input name="NumLigue" id="NumLigueModif" type="hidden">
						<br>
						<p style="color: red" id="erreurModifLigue"></p>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="saveModifLigue">Sauvegarder</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $content = ob_get_clean();
require('template.php'); ?>
