<?php
//MDP ADMIN = OtEAsse
session_start();
require_once("fonctions.php");

if (isset($_GET['action'])) {
    switch ($_GET['action']) {

      case "Accueil":
            if (!isset($_SESSION["userID"])) {
                  include("vues/Accueil.php");
            } else {
                  $_SESSION['Profil']=getProfil($_SESSION["Login"]);
                  $Ligues = Reservation_getLigues($_SESSION["userID"], $_SESSION["Profil"]);
                  $TypesSalles = getTypesSalles();
                  // $Reservations = getReservation($_SESSION["userID"]);
                  include("vues/Profil.php");
            }
      break;

      case "Connexion":
            include("vues/Connexion.php");
      break;

      case "Inscription":
            include("vues/Inscription.php");
      break;

      case "getEvents" :
            $Reservations = getReservation($_SESSION["userID"], $_SESSION["Profil"]);
            if ($Reservations[0] != null) {
                  $tableauResa = [];
                  foreach ($Reservations as $Reservation) {
                        $tableauResaTemp = [];
                        $tableauResaTemp["id"] = $Reservation["IDReservation"];
                        $tableauResaTemp["start"] = $Reservation["DateDebutReserver"];
                        $tableauResaTemp["end"] = $Reservation["DateFinReserver"];
                        $tableauResaTemp["title"] = "Ligue : " . $Reservation["NomLigue"] . "\nSalle : " .  $Reservation["NomSalle"] . "\nDescription : " . $Reservation["Description"];
                        $tableauResaTemp["extendedProps"]["NumLigue"] = $Reservation["NumLigue"];
                        $tableauResaTemp["extendedProps"]["CodeTypeSalle"] = $Reservation["CodeTypeSalle"];
                        array_push($tableauResa, $tableauResaTemp);
                  }
                  echo json_encode($tableauResa);
            } else {
                  echo json_encode(array('id' => '', 'start' => '', 'end' => '', 'title' => ''));
            }
      break;

      case "ProcessInscription":
            foreach ($_POST["tab"] as $key => $value) {
                  $_SESSION[$key] = $value;
            }
            if (estLibre($_SESSION["Login"], $_SESSION["AdrMail"])) {
                  $_SESSION["MotDePasse"] = hash('sha512', $_SESSION["MotDePasse"]);
                  créerProfil($_SESSION);
                  header("Location: index.php?action=Accueil");
            } else {
                  header("Location: index.php?action=Connexion&erreur=1");
            }
      break;

      case "ProcessConn":
            foreach ($_POST as $key => $value) {
                  $_SESSION[$key] = $value;
            }
            $_SESSION["MotDePasse"] = hash('sha512', $_SESSION["MotDePasse"]);
            if (goodPass($_SESSION["Login"], $_SESSION["MotDePasse"])) {
                  $_SESSION["Nom"] = getNom($_SESSION["Login"]);
                  $_SESSION["Prenom"] = getPrenom($_SESSION["Login"]);
                  $_SESSION["userID"] = getUserID($_SESSION["Login"]);
                  header("Location: index.php?action=Accueil");
            } else {
                  header("Location: index.php?action=Connexion&erreur=2");
            }
      break;

      case "reservation":
            echo creerReservation($_POST);
      break;

      case "modifReservation" :
            if($_POST["IDReservation"]) {
                  $BUResa = getReservationSeule($_POST["IDReservation"]);
                  deleteResa($_POST["IDReservation"]);
                  $reservation = creerReservation($_POST);
                  if ($reservation) {
                        echo $reservation;
                  } else {
                        creerReservation($BUResa);
                        echo "La modification de la reservation n'a pas pu être effectuée";
                  }
            } else {
                  header("Location: index.php?action=Accueil");
            };
      break;

      case "supprResa" :
            if($_POST) {
                  echo deleteResa($_POST["IDReservation"]);
            } else {
                  header("Location: index.php?action=Accueil");
            }
      break;

        case "Deconnexion":
            session_unset();
            header("Location: index.php?action=Accueil");
        break;

        case "Listes":
            if (!isset($_SESSION["Profil"])) {
                header('Location: index.php?action=Accueil');
            } else {
                  $tbUsers = getUsers();
                  $tbTypesSalles = getTypesSalles();
                  $tbBatiments = getBatiments();
                  $tbSalles = getSalles();
                  $tbLigues = getLigues();
                  include("vues/Listes.php");
            }
        break;

        case "AjoutSalle":
            if (!isset($_SESSION["Profil"]) or $_SESSION['Profil'] != 0) {
                header('Location: index.php?action=Accueil');
            } elseif (verifSalle($_POST) != "OK") {
                echo verifSalle($_POST);
            } else {
                if (créerSalle($_POST) > 0) {
                    echo "Success";
                } else {
                    echo "Impossible d'enregistrer la salle dans la base de données";
                }
            }
        break;

        case "AjoutLigue":
            if (!isset($_SESSION["Profil"]) or $_SESSION['Profil'] != 0) {
                header('Location: index.php?action=Accueil');
            } elseif (verifLigue($_POST) != "OK") {
                echo verifLigue($_POST);
            } else {
                  $creerLigue = créerLigue($_POST);
                if ($creerLigue > 0) {
                    echo "Success";
                } else {
                    echo "Impossible d'enregistrer la ligue dans la base de données";
                }
            }
        break;

      case "ModifSalle":
            if (!isset($_SESSION["Profil"]) or $_SESSION['Profil'] != 0) {
                  header('Location: index.php?action=Accueil');
            } else {
                  $modifSalle = modifSalle($_POST);
                  if ($modifSalle > 0) {
                        echo "Success";
                  } else {
                        echo "Impossible de modifier la salle dans la base de données";
                  }
            }
      break;

      case "ModifLigue":
              if (!isset($_SESSION["Profil"]) or $_SESSION['Profil'] != 0) {
                  header('Location: index.php?action=Accueil');
              } else {
                  $modifLigue = modifLigue($_POST);
                  if ($modifLigue > 0) {
                      echo "Success";
                  } else {
                      echo "Modification de la ligue impossible";
                  }
              }
      break;

      default :
          header('Location: index.php?action=Accueil');
      }
} else {
      header('Location: index.php?action=Accueil');
}
