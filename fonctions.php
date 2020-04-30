<?php
/********************************************************
*** FONCTIONS TECHNIQUES ********************************
********************************************************/

/* =====================================================
Description fonction requete
@arg : sql (requete sql)
@return : résultat requête ou ID si Insert
======================================================*/

function requete($sql)
{
    // echo $sql;
    $cnx=mysqli_connect("localhost", "root", "", "maison_des_ligues") or die("La connexion à la BDD a échoué");
    $res=mysqli_query($cnx, $sql) or die(mysqli_error($cnx));
    $tab=explode(" ", $sql);
    $action=$tab[0];
    if ($action=="INSERT" and $res==true) {
          //Dans le cas d'une clé primaire composée de 2 champs, mysqli_insert_id() ne sait pas quel ID renvoyer
          if (mysqli_insert_id($cnx) == 0) {
                return True;
          } else {
             return mysqli_insert_id($cnx);
          }
    } else {
        return $res;
    }
}

/* =====================================================
@arg1 : sql (requete sql)
@arg2 : dim
@return : une valeur (0), ou un tableau à 1 ou 2 dimensions
===================================================== */


function select($sql, $dim=2)
{
    $res=requete($sql);
    if ($dim==0) {
         $ligne=mysqli_fetch_array($res, MYSQLI_NUM);
        return $ligne[0];
    } elseif ($dim==1) {
          $ligne=mysqli_fetch_array($res, MYSQLI_ASSOC);
        return $ligne;
    } elseif ($dim==2) {
        $tab[]=mysqli_fetch_array($res, MYSQLI_ASSOC);
        while ($ligne=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            $tab[]=$ligne;
        }
        return $tab;
    }
}

/* =====================================================
@arg : nomTable
@return : nom de la clé primaire de la table
===================================================== */

function getPK($nomTable)
{
    return select("SELECT COLUMN_NAME
				   FROM INFORMATION_SCHEMA.COLUMNS
	               WHERE TABLE_NAME = '".$nomTable."'
	               AND COLUMN_KEY='PRI'", 0);
}


/* =====================================================
@arg1 : nom table
@arg2 : tbData (tableau associatif de données)
@arg3 : id (identifiant de l'enregistrement si !=0)
@return : resultat requête ou ID si insert
===================================================== */


function ecrire($nomTable, $tbData, $id=0)
{
    if ($id==0) { // INSERT
        $sql="INSERT INTO ";
    } else { //UPDATE
        $sql="UPDATE ";
    }

    $sql.=$nomTable." SET "; // INSERT & UPDATE

    foreach ($tbData as $champ=>$val) { // INSERT & UPDATE
        $sql.=$champ."='".$val."', ";
    }

    $sql=substr($sql, 0, -2);

    if (!empty($id)) { // UPDATE
        $nomId=getPK($nomTable);
        $sql.=" WHERE ".$nomId."=".$id;
    }
    return requete($sql);
}


/* =====================================================
@arg1 : nomTable
@arg2 : id
@return : une valeur (0), ou un tableau à 1 ou 2 dimensions
===================================================== */

function supprimer($nomTable, $id)
{
    $nomId=getPK($nomTable);
    $sql="DELETE FROM ".$nomTable." WHERE ".$nomId."=".$id;
    return requete($sql);
}


/********************************************************
*** FONCTIONS METIER ************************************
********************************************************/

/* =======================================
fonction estLibre()
      Vérifie validité du mot de passe entré par l'utilisateur cherchant à se connecter
@arg1 string $pseudo
      pseudo rentré par l'utilisateur
@arg2 string $mail
      mail rentré par l'utilisateur
@return booléen
======================================= */

function estLibre($pseudo, $mail)
{
    return select("SELECT COUNT(*) FROM utilisateurs WHERE Login = '" . $pseudo . "' OR AdrMail = '" . $mail . "'; ", 0)==0;
}

/* =======================================
fonction créerProfil()
      Insertion nouvel utilisateur dans la table utilisateurs
@arg array $tabData
      Données envoyées par l'utilisateur
@return array
======================================= */

function créerProfil($tabData)
{
    return ecrire("utilisateurs", $tabData);
}

/* =======================================
fonction goodPass()
      Vérifie validité du mot de passe entré par l'utilisateur cherchant à se connecter
@arg1 string $pseudo
      pseudo de l'utilisateur
@arg2 string $mdp
      mot de passe rentré par l'utilisateur
@return booléen
======================================= */

function goodPass($pseudo, $mdp)
{
    return select("SELECT COUNT(*) FROM utilisateurs WHERE Login = '" . $pseudo . "' AND MotDePasse = '" . $mdp . "'; ", 0)==1;
}

/* =======================================
fonction getUserID()
      Récupère le prénom de l'utilisateur
@arg string $pseudo
      pseudo de l'utilisateur
@return string
======================================= */

function getPrenom($pseudo)
{
    return select("SELECT Prenom FROM utilisateurs WHERE Login = '" . $pseudo . "'; ", 0);
}

/* =======================================
fonction getNom()
      Récupère le nom de l'utilisateur
@arg string $pseudo
      pseudo de l'utilisateur
@return string
======================================= */

function getNom($pseudo)
{
      return select("SELECT Nom FROM utilisateurs WHERE Login = '" . $pseudo . "'", 0);
}

/* =======================================
fonction getUserID()
      Récupère l'id utilisateur
@arg string $pseudo
      pseudo de l'utilisateur
@return int
======================================= */

function getUserID($pseudo)
{
    return select("SELECT NumUser FROM utilisateurs WHERE Login = '" . $pseudo . "'; ", 0);
}

/* =======================================
fonction getProfil()
      Obtient informations sur les utilisateurs
@arg string $login
      Récupère le profil lié à l'usager (admin ou utilisateur)
@return int
======================================= */

function getProfil($login)
{
    return select("SELECT Profil FROM utilisateurs WHERE Login = '" . $login  . "'; ", 0);
}

/* =======================================
fonction getUsers()
      Obtient informations sur les utilisateurs
@return array
======================================= */

function getUsers()
{
    return select("SELECT NumUser, Prenom, Nom FROM utilisateurs WHERE Profil != 0");
}

/* =======================================
fonction getBatiments()
      Obtient informations sur les batiments
@return array
======================================= */

function getBatiments()
{
    return select("SELECT * FROM batiment");
}

/* =======================================
fonction getTypesSalles()
      Obtient informations sur les types
@return array
======================================= */

function getTypesSalles()
{
    return select("SELECT * FROM typesalle");
}

/* =======================================
fonction getSalles()
      Obtient informations sur les salles
@return array
======================================= */

function getSalles()
{
    return select("SELECT * FROM typesalle JOIN salles ON typesalle.CodeTypeSalle = salles.CodeTypeSalle_FK JOIN batiment ON salles.CodeBatiment_FK = batiment.CodeBatiment
           ORDER BY NomBatiment, CodeTypeSalle_FK, NomSalle");
}

/* =======================================
fonction getLigues()
      Obtient informations sur les ligues
@return array
======================================= */

function getLigues()
{
    return select("SELECT * FROM ligues LEFT OUTER JOIN appartenir ON ligues.NumLigue = appartenir.NumLigue_FK LEFT OUTER JOIN utilisateurs ON
          appartenir.NumUser_FK = utilisateurs.NumUser GROUP BY NomLigue ORDER BY NomLigue");
}

/* =======================================
fonction getReservation()
      Obtient informations sur toutes réservations si admin, ou réservations activées et liées à l'id si utilisateur
@arg1 int $numUser
      id Utilisateur
@arg2 int $profil
      utilisateur ou admin
@return array
======================================= */

function getReservation($numUser, $profil) {
      if ($profil == 0) {
            return select("SELECT * FROM ligues join reservation ON NumLigue = NumLigue_FK JOIN salles ON NumSalle_FK = NumSalle JOIN typesalle ON CodeTypeSalle_FK = CodeTypeSalle", 2);
      } else {
            return select("SELECT * FROM ligues JOIN reservation ON NumLigue = NumLigue_FK JOIN salles ON NumSalle_FK = NumSalle JOIN typesalle ON CodeTypeSalle_FK = CodeTypeSalle WHERE NumLigue_FK IN (SELECT NumLigue_FK FROM appartenir JOIN ligues ON NumLigue_FK = NumLigue WHERE NumUser_FK = " . $numUser . " AND Activation = 1)", 2);
      }
}

/* =======================================
Fonction getReservationSeule()
      utilisée pour le back-up de la réservation
@arg int $IDResa
      id réservation
@return int
======================================= */

function getReservationSeule($IDResa) {
      return select("SELECT * FROM reservation WHERE IDReservation = " . $IDResa);
}

/* =======================================
fonction Reservation_getLigues()
      Permet d'obtenir les informations de la ligue liée à la réservation
@arg1 int $idUser
      id utilisateurs
@arg2 int $profil
      utilisateur ou admin
@return array informations de la ligue liée à la réservation
======================================= */

function Reservation_getLigues($idUser, $profil) {
      if ($profil == 0) {
            return select("SELECT * FROM ligues");
      } else {
            return select("SELECT * FROM ligues JOIN appartenir ON ligues.NumLigue = appartenir.NumLigue_FK WHERE NumUser_FK = " . $idUser . " AND Activation = 1");
      }
}

/* =======================================
fonction getSallesLibre()
      numéro et nom des salles n'étant pas réservées sur le créneau demandé, correspondant au type demandé, et étant activées
@arg1 string $dateDébut
      date début de la réservation renseignée
@arg2 string $dateFin
      date fin de la réservation renseignée
@arg3 int $TypeSalle
      type salle demandé
@return array
======================================= */

function getSallesLibre($dateDebut, $dateFin, $TypeSalle) {
      return select("SELECT NumSalle, NomSalle FROM salles WHERE NumSalle NOT IN (SELECT NumSalle_FK FROM reservation
                                                                                    WHERE (TIMEDIFF(DateFinReserver, '" . $dateDebut . "') > 0
                                                                                          AND TIMEDIFF(DateDebutReserver, '" . $dateDebut . "') < 0)
                                                                                    OR (TIMEDIFF(DateFinReserver, '" . $dateFin . "') > 0
                                                                                          AND TIMEDIFF(DateDebutReserver, '" . $dateFin . "') < 0)
                                                                                    OR (TIMEDIFF(DateDebutReserver, '" . $dateDebut . "') >= 0
                                                                                          AND TIMEDIFF(DateFinReserver, '" . $dateFin . "') <= 0)
                                                                                    )
                                                            AND CodeTypeSalle_FK = '" . $TypeSalle . "'
                                                            AND Activation = 1
                  ");
}

/* =======================================
fonction deleteResa()
      supprime une réservation de la BDD
@arg int $IDResa
      id réservation
@return booléen
      Réussite ou échec de la requête
======================================= */

function deleteResa($IDResa) {
      return supprimer("reservation", $IDResa);
}

/* =======================================
fonction changeDateFormat()
      Modifie la date donnée par l'utilisateur pour qu'elle soit conforme au format SQL
@arg string $date
@return string
======================================= */

function changeDateFormat($date) {
      $day = substr($date, 0, 2);
      $month = substr($date, 3, 2);
      $year = substr($date, 6, 4);
      $time = substr($date, 10);
      return $year . "-" . $month . "-" . $day . $time . ":00";
}

/* =======================================
fonction creerReservation()
      Insert nouvelle réservation dans la base de données
@arg array $tabData
      Données envoyées par l'utilisateur
@return json
======================================= */

function creerReservation($tabData) {
      // Changer dates au format SQL
      $tabData['DateDebutReserver'] = changeDateFormat($tabData['DateDebutReserver']);
      $tabData['DateFinReserver'] = changeDateFormat($tabData['DateFinReserver']);

      // Récupérer salles libres, et en choisir une au hasard parmi celles libres
      $salleLibre = getSallesLibre($tabData['DateDebutReserver'], $tabData['DateFinReserver'], $tabData['CodeTypeSalle_FK']);
      shuffle($salleLibre);
      $salleLibre = $salleLibre[0];

      // Peupler tableau avec champs de la table
      unset($tabData['CodeTypeSalle_FK']);
      $tabData['NumSalle_FK'] = $salleLibre['NumSalle'];
      $ecritureTableReservation = ecrire("reservation", $tabData);
      $tabData['NomSalle'] = $salleLibre['NomSalle'];
      if ($ecritureTableReservation > 0) {
            return json_encode($tabData);
      }
}

/* =======================================
fonction verifSalle()
      Verification de la validité des données entrées par l'administrateur quand création de salle
@arg array $tabData
      Données envoyées par l'utilisateur
@return string
======================================= */

function verifSalle($tabData)
{
    if (empty($tabData["NomSalle"]) or empty($tabData["CodeTypeSalle_FK"]) or empty($tabData["CodeBatiment_FK"])) {
        return "Tous les champs n'ont pas été renseignés";
    }

    $salleExisteDeja = select("SELECT COUNT(*) FROM salles WHERE NomSalle = '" . $tabData['NomSalle'] . "'; ", 0);

    if ($salleExisteDeja) {
        return 'La salle existe déjà';
    } else {
        return 'OK';
    }
}

/* =======================================
fonction verifLigue()
      Verification de la validité des données entrées par l'administrateur quand création de ligue
@arg array $tabData
      Données envoyées par l'utilisateur
@return string
======================================= */

function verifLigue($tabData)
{
    $ligueExisteDeja = select("SELECT COUNT(*) FROM ligues WHERE NomLigue = '" . $tabData['NomLigue'] . "'; ", 0);

    if ($ligueExisteDeja) {
        return 'La ligue existe déjà';
    } elseif (empty($tabData['NomLigue']) or empty($tabData['NumUser_FK'])) {
        return "Tous les champs n'ont pas été renseignés";
    } else {
        return 'OK';
    }
}

/* =======================================
fonction créerSalle()
      Insert nouvelle salle dans BDD
@arg array $tabData
      Données envoyées par l'utilisateur
@return int
======================================= */

function créerSalle($tabData)
{
    return ecrire("salles", $tabData);
}

/* =======================================
fonction créerLigue()
      Insert nouvelle ligue dans BDD (tables "appartenir" et "ligues")
@arg array $tabData
      Données envoyées par l'utilisateur
@return int
======================================= */

function créerLigue($tabData)
{
      $tabDataTableLigue = $tabData;
      unset($tabDataTableLigue["NumUser_FK"]);
      $ecritureTableLigue = ecrire("ligues", $tabDataTableLigue);

      $tabDataTableAppartenir = array(
          "NumLigue_FK" => select('SELECT NumLigue FROM ligues WHERE NomLigue ="' . $tabData['NomLigue'] . '";', 0),
          "NumUser_FK" => $tabData["NumUser_FK"],
      );
      $ecritureTableAppartenir = ecrire("appartenir", $tabDataTableAppartenir);

      if ($ecritureTableLigue > 0 and $ecritureTableAppartenir) {
          return 1;
      } else {
          return 0;
      }
}

/* =======================================
fonction modifSalle()
      update table salles
@arg array $tabData
      Données envoyées par l'utilisateur
@return int
======================================= */

function modifSalle($tabData)
{
      return ecrire("salles", $tabData, $tabData["NumSalle"]);
}

/* =======================================
fonction modifLigue()
      update tables ligues et appartenir
@arg array $tabData
      Données envoyées par l'utilisateur
@return int
======================================= */

function modifLigue($tabData)
{
      //UPDATE table appartenir pour ajouter la DateFinAppartenir à l'élément que l'on veut archiver
      $tabDataTableAppartenir = select("SELECT * FROM appartenir WHERE NumLigue_FK = " . $tabData['NumLigue'] . " AND DateFinAppartenir IS NULL", 1);
      if ($tabDataTableAppartenir) {
            if ($tabDataTableAppartenir['NumUser_FK'] != $tabData['NumUser_FK']) {
                  requete("UPDATE appartenir SET DateFinAppartenir = NOW() WHERE NumUser_FK = " . $tabDataTableAppartenir['NumUser_FK'] . " AND
                  NumLigue_FK = " . $tabDataTableAppartenir['NumLigue_FK'] . " AND DateDebutAppartenir = '" . $tabDataTableAppartenir['DateDebutAppartenir'] . "';");
            }
      }

      //Update table LIGUES
      $tabDataTableLigue = $tabData;
      unset($tabDataTableLigue["NumUser_FK"]);
      $ecritureTableLigue = ecrire("ligues", $tabDataTableLigue, $tabDataTableLigue["NumLigue"]);

      //Redéclarer table appartenir pour INSERT du nouvel élément
      $ecritureTableAppartenir = True;
      if ($tabDataTableAppartenir['NumUser_FK'] != $tabData['NumUser_FK']) {
            $tabDataTableAppartenir = array(
              "NumLigue_FK" => $tabData["NumLigue"],
              "NumUser_FK" => $tabData["NumUser_FK"],
            );
            $ecritureTableAppartenir = ecrire("appartenir", $tabDataTableAppartenir);
      }
      if ($ecritureTableLigue > 0 and $ecritureTableAppartenir) {
        return 1;
      } else {
        return 0;
      }
}
