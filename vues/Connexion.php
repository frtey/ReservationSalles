<?php $title = 'Se connecter';
ob_start(); ?>

<div class="container-fluid mt-5">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <h1 class="navbar-text ml-auto mr-auto">Bienvenue sur le site de la MDL</h1>
      </nav>
      <br>
      <br>
      <br>
<?php
      if(isset($_GET['erreur']) and $_GET['erreur'] == "1") { echo '<p style="color: red">Le mail ou le login existent déjà, veuillez vous connecter</p>'; }
      if(isset($_GET['erreur']) and $_GET['erreur'] == "2") { echo '<p style="color: red">Le mot de passe n\'est pas correct, ou l\'identifiant n\'existe pas</p>'; }
?>
      <form action="index.php?action=ProcessConn" method="POST" id="formConn">
            <div class="form-group">
                  <label for="Login">Pseudo : </label>
                  <input type="text" name="Login" placeholder="Entrez votre pseudonyme" class="form-control">
            </div> 
            <div class="form-group">
                  <label for="MotDePasse">Mot de passe : </label>
                  <input type="password" name="MotDePasse" placeholder="Entrez votre mot de passe" class="form-control">
            </div>
            <div class="wrapper">
                  <button type="submit" class="btn btn-outline-info btn-lg">Envoyer</button>
            </div>
            <p id="inscription"><a href="index.php?action=Inscription">Cliquez ici</a> pour vous inscrire</p>
      </form>
</div>

<?php $content = ob_get_clean();
require('template.php'); ?>
