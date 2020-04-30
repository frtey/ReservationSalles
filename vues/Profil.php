<?php $title = 'Profil';
ob_start(); ?>


<div class="container-fluid mt-5">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <a class="navbar-brand" href="index.php?action=accueil">Accueil</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                  <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-between" id="collapsibleNavbar">
                  <ul class="navbar-nav">
                        <li class="nav-item">
                              <a class="nav-link" href="index.php?action=Listes">Liste des ligues et salles</a>
                        </li>
                  </ul>
                  <span class="navbar-text ml-auto border border-success py-2 px-5 rounded rounded-lg font-weight-bold"><?php echo "Bonjour " . $_SESSION['Prenom'] ."  ". $_SESSION['Nom']; ?></span>
                  <ul class="navbar-nav ml-auto">
                        <li class="nav-item ml-auto">
                              <a class="nav-link " href="index.php?action=Deconnexion">Déconnexion</a>
                        </li>
                  </ul>
            </div>
      </nav>
      <br>
      <br>
      <div id="calendar">
      </div>
      <div id="calendarModalReservation" class="modal fade">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h2 id="modalTitleResa" class="modal-title"></h2>
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                        </div>
                        <div id="modalBodyResa" class="modal-body">
                              <form id="formReservation">
                                    <div class="form-group">
                                          <label for="NumLigue_FK">Ligue représentée : </label>
                                          <select class="form-control" id="Reservation_NomsLigues" name="NumLigue_FK">
                                                <?php
                                                      foreach ($Ligues as $Ligue) {
                                                            echo '<option value="' . $Ligue["NumLigue"] . '" id="' . utf8_encode($Ligue["NomLigue"]) . '">'
                                                            . utf8_encode($Ligue["NomLigue"]) . '</option>';
                                                      }
                                                ?>
                                          </select>
                                    </div>

                                    <div class="form-group">
                                          <label for="CodeTypeSalle_FK">Type de salle souhaité : </label>
                                          <select class="form-control" id="Reservation_TypeSalle" name="CodeTypeSalle_FK">
                                                <?php
                                                      foreach ($TypesSalles as $Type) {
                                                            echo '<option value="' . $Type["CodeTypeSalle"] . '">'
                                                            . utf8_encode($Type["Description"]) . '</option>';
                                                      }
                                                ?>
                                          </select>
                                    </div>

                                    <div class="form-group">
                                          <label class="control-label">Début de la réservation : </label>
                                          <div class="input-group date" id="DateDebutReserver" data-target-input="nearest">
                                                <input type="text" id="DateDebutReserverInput" class="form-control datetimepicker-input" data-target="#DateDebutReserver" name="DateDebutReserver" />
                                                <div class="input-group-append" data-target="#DateDebutReserver" data-toggle="datetimepicker">
                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                          <label class="control-label">Fin de la réservation : </label>
                                          <div class="input-group date" id="DateFinReserver" data-target-input="nearest">
                                                <input type="text" id="DateFinReserverInput" class="form-control datetimepicker-input" data-target="#DateFinReserver" name="DateFinReserver" />
                                                <div class="input-group-append" data-target="#DateFinReserver" data-toggle="datetimepicker">
                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                          </div>
                                    </div>
                              </form>
                        </div>
                        <div class="modal-footer">
                              <p id="erreurReservation" style="color:red">Les dates renseignées sont incorrectes</p>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                              <button type="button" class="btn btn-primary" id="saveReservation">Sauvegarder</button>
                        </div>
                  </div>
            </div>
      </div>
      <div id="calendarModalModifReservation" class="modal fade">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header">
                              <h2 id="modalTitleModifResa" class="modal-title">Modification de la réservation</h2>
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                        </div>
                        <div id="modalBodyModifResa" class="modal-body">
                              <form id="formReservationModif">
                                    <div class="form-group">
                                          <label for="NumLigue_FK">Ligue représentée : </label>
                                          <select class="form-control" id="ModifReservation_NomsLigues" name="NumLigue_FK">
                                                <?php
                                                      foreach ($Ligues as $Ligue) {
                                                            echo '<option value="' . $Ligue["NumLigue"] . '" id="' . utf8_encode($Ligue["NomLigue"]) . '">'
                                                            . utf8_encode($Ligue["NomLigue"]) . '</option>';
                                                      }
                                                ?>
                                          </select>
                                    </div>

                                    <div class="form-group">
                                          <label for="CodeTypeSalle_FK">Type de salle souhaité : </label>
                                          <select class="form-control" id="ModifReservation_TypeSalle" name="CodeTypeSalle_FK">
                                                <?php
                                                      foreach ($TypesSalles as $Type) {
                                                            echo '<option value="' . $Type["CodeTypeSalle"] . '">'
                                                            . utf8_encode($Type["Description"]) . '</option>';
                                                      }
                                                ?>
                                          </select>
                                    </div>

                                    <div class="form-group">
                                          <label class="control-label">Début de la réservation : </label>
                                          <div class="input-group date" id="ModifDateDebutReserver" data-target-input="nearest">
                                                <input type="text" id="ModifDateDebutReserverInput" class="form-control datetimepicker-input" data-target="#ModifDateDebutReserver" name="DateDebutReserver" />
                                                <div class="input-group-append" data-target="#ModifDateDebutReserver" data-toggle="datetimepicker">
                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                          <label class="control-label">Fin de la réservation : </label>
                                          <div class="input-group date" id="ModifDateFinReserver" data-target-input="nearest">
                                                <input type="text" id="ModifDateFinReserverInput" class="form-control datetimepicker-input" data-target="#ModifDateFinReserver" name="DateFinReserver" />
                                                <div class="input-group-append" data-target="#ModifDateFinReserver" data-toggle="datetimepicker">
                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                          </div>
                                    </div>
                                    <input type="hidden" id="IDReservation" name="IDReservation" />
                              </form>
                        </div>
                        <div class="modal-footer">
                              <p id="erreurModifReservation" style="color:red">Les dates renseignées sont incorrectes</p>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                              <button type="button" class="btn btn-primary" id="saveModifReservation">Sauvegarder</button>
                              <button type="button" class="btn btn-danger" id="deleteReservation">Supprimer</button>
                        </div>
                  </div>
            </div>
      </div>
</div>

<?php $content = ob_get_clean();
require('template.php'); ?>
