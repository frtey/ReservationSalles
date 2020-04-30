<?php $title = 'Accueil';
ob_start(); ?>
      
<div class="container-fluid mt-5">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <h1 class="navbar-text ml-auto mr-auto">Bienvenue sur le site de la MDL</h1>
      </nav>
      <br>
      <br>
      <br>
      

      <div class="d-flex justify-content-around">
            <a class="btn btn-outline-info btn-lg" href="index.php?action=Connexion">Se connecter</a>

            <a class="btn btn-outline-info btn-lg" href="index.php?action=Inscription">S'inscrire</a>
      </div>
</div>

<?php $content = ob_get_clean();
require('template.php'); ?>
