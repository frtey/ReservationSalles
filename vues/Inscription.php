<?php $title = 'Inscription';
ob_start(); ?>

<div class="container-fluid mt-5">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <h1 class="navbar-text ml-auto mr-auto">Bienvenue sur le site de la MDL</h1>
      </nav>
      <br>
      <br>
      <br>
      <form action="index.php?action=ProcessInscription" method="POST" id="formInscr">
            <div class="form-group">
                  <input type="text" name="tab[Prenom]" placeholder="Prénom" class="form-control">
            </div>
            <div class="form-group">
                  <input type="text" name="tab[Nom]" placeholder="Nom" class="form-control">
            </div>
            <div class="form-group">
                  <input type="text" name="tab[Login]" placeholder="Entrez votre pseudonyme" class="form-control">
            </div>
            <div class="form-group">
                  <input type="password" name="tab[MotDePasse]" placeholder="Entrez votre mot de passe" class="form-control">
            </div>
            <div class="form-group">
                  <input type="password" name="mdp_conf" placeholder="Confirmez votre mot de passe" class="form-control">
                  <p id="erreurCorrespondanceMDP">Les mots de passe entrés ne sont pas les mêmes</p>
            </div>
            <div class="form-group">
                  <input type="email" name="tab[AdrMail]" placeholder="Mail" class="form-control">
                  <p id="erreurMail">L'adresse mail indiquée n'est pas valable</p>
            </div>
            <div class="form-group">
                  <input type="tel" name="tab[Telephone]" placeholder="Numero de telephone" class="form-control">
                  <p id="erreurTel">Le numero de telephone indiqué n'est pas valable</p>
            </div>
            <div class="wrapper">
                  <button type="submit" class="btn btn-outline-info btn-lg">Envoyer</button>
            </div>
      </form>
      <p id="Connexion" class="mt-2"><a href="index.php?action=Connexion">Cliquez ici</a> pour vous connecter</p>
</div>

<?php $content = ob_get_clean();
require('template.php'); ?>
