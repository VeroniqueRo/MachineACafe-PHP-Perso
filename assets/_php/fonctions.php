<?php

/* Déclaration des variables */

$date = date("l d F Y"); // Déclaration d'une variable $date qui prend pour valeur la fonction date avec les paramètres le jour (nom + numéro) le mois et l'année
$heure = date("H"); // Déclaration d'une variable $heure qui prend pour valeur la fonction date avec le paramètre Heure
$minutes  = date("i"); // Déclaration d'une variable $minutes qui prend pour valeur la fonction date avec le paramètre minutes
$argentInsere = 0; // Déclaration de la variable $argentInsere qui prend pour valeur 0
$messageAttente = "Vous voulez un café ou bien ?";

/* Déclaration des fonctions : Version avec BDD EXTERNE */

// Fonction de connection à la BDD
function connection(){
    //teste les infos d'accès à la base de données
    try 
    {
      // connexion à MySQL
      $bdd = new PDO('mysql:host=localhost;dbname=machineacafe;charset=utf8', 'Groot66', 'TuTeCalmes!');  
    }
    catch (Exception $e)
    {
      // en cas d'erreur, affiche un message et s'arrête
      die('Erreur : '.$e->getMessage());
    }
    return $bdd;
  }

$mabdd = connection();

// Fonction menu déroulant avec lecture de la BDD et variable $mabdd en paramètre
function menuDeroulant($database) {

  $menu = "";
  $reqDrinkName = $database->query('SELECT nomboisson FROM boisson');

  // affiche chaque entrée une à une
  while ($tabDrinkName = $reqDrinkName->fetch())
  {
    // ajoute une balise de champs de menu déroulant avec les données de la BDD externe
    $menu .= "<option>".$tabDrinkName["nomboisson"]."</option>";
  }
  return $menu;
  $reqDrinkName->closeCursor();
}

// Fonction affichage recette qui utilise les informations de la BDD  
function afficheRecette($database, $choixBoisson, $choixSucres) {
  
  $i=0;// variable pour affichage unique du nom de la boisson
  $recetteFinale = "";
  
  // Si la connexion est ok, récupère la liste des boissons 
  $requeteBdd = $database->prepare ('
    SELECT  nomboisson, nomingredients, qteboisson
    FROM boisson_ingredients
    INNER JOIN boisson ON boisson.codeboisson = boisson_ingredients.boisson_codeboisson
    INNER JOIN ingredients ON ingredients.codeingredients = boisson_ingredients.ingredients_codeingredients
    WHERE nomboisson = ?
  '); // Identique à WHERE nomboisson = :nomboisson

  $requeteBdd->execute(array($choixBoisson)); // Avec le ? s'écrit : execute(array($_POST["choixBoisson"]));
  
  // Affiche la valeur nomboisson selectionnée
  // var_dump(array($choixBoisson)); 

  // affiche chaque entrée une à une
  while ($tabDonnees = $requeteBdd->fetch())
  {
    if ($i==0)
    {
      $i=1;// Affiche le nom de la boisson une seule fois
      $recetteFinale .= "<p>"." Vous avez commandé un ".$tabDonnees["nomboisson"]."</p>"."Dont la recette est"."<br>";
    }
      $recetteFinale .= $tabDonnees["nomingredients"]." ".$tabDonnees["qteboisson"]." dose(s) <br>";
      // var_dump($tabDonnees);
  }

  if ($choixSucres == 1)  {
    $recetteFinale .=  "avec ".$choixSucres . " sucre" ."<br>";
  } else if ($choixSucres > 1) {
    $recetteFinale .=  "avec ".$choixSucres . " sucres" ."<br>";
  } else if ($choixSucres == 0) {
    $recetteFinale .=  "Sans sucre" ."<br>";
  }

  return $recetteFinale;
  $requeteBdd->closeCursor(); // Termine le traitement de la requête
}

// Ajout de la vente par rapport
function ajoutVente($database) {

  $date = date("Y-m-d"); // récupère date à l'instant
  $heure = date("H:i:s");// récupère l'heure à l'instant
  $choixSucres = 0;
  $choixSucres = $_POST["choixSucre"];// récupère qté sucre choisie
  $choixBoisson = $_POST["choixBoisson"];// récupère nom complet boisson choisie
    
  // Recherche code boisson correspondant au nom complet
  $reqCodeBoisson = $database->prepare('
    SELECT codeboisson 
    FROM boisson 
    WHERE nomboisson="'.$choixBoisson.'"
  ');
  $reqCodeBoisson->execute(array('nomboisson'=>$_POST["choixBoisson"]));
  $codeBoisson = $reqCodeBoisson->fetch();
  // var_dump($codeBoisson);

  // Ajoute la vente à la BDD
  $requeteBdd = $database->prepare('
    INSERT INTO vente (datevente, heurevente, nbsucres, boisson_codeboisson)
    VALUES (?,?,?,?)
  ');

  $requeteBdd->execute(array($date, $heure, $choixSucres, $codeBoisson[0]));

  echo 'Données BDD entrées : '.$date.' / '.$heure.'/ Sucre : '.$choixSucres.'/ CodeBoisson : '.$codeBoisson[0];

  $reqCodeBoisson->closeCursor(); // Termine la requête de recherche codeBoisson
  $requeteBdd->closeCursor(); // Termine la requête d'ajout de la vente

}

function ajouterSucre($recetteTab, $nbSucres) {
  if ($nbSucres == 1) {
    array_push($recetteTab, " Sucre x " . $nbSucres);
  } else if ($nbSucres > 1) {
    array_push($recetteTab, " Sucres x " . $nbSucres);
  } else if ($nbSucres == 0) {
    array_push($recetteTab, " Sans sucre");
  }

  return $recetteTab;
}

function afficheTabBoissons($database) {

  $tabDonnees = "";
  $requete = $database->query('SELECT nomboisson, prixboisson FROM boisson');

  
  while ($donnees = $requete->fetch())
  {
    $tabDonnees .= ".<td>".$donnees["nomboisson"]."</td>
                    <td>".$donnees["prixboisson"]." cents</td></tr>";
  }
  return $tabDonnees;
  $requete->closeCursor();
}

function ajoutBoisson($database) {

  if (isset($_POST["newCodeBoisson"]) && isset($_POST["newNomBoisson"]) && isset($_POST["newPrixBoisson"])) { // si formulaire soumis
    
    $resultat = "";
    $choixCodeBoisson = $_POST["newCodeBoisson"];// récupère 
    $choixNomBoisson = $_POST["newNomBoisson"];// récupère nom complet boisson choisie
    $choixPrixBoisson = $_POST["newPrixBoisson"];

    // Ajoute la vente à la BDD
    $requeteBdd = $database->prepare('
      INSERT INTO boisson (codeboisson, nomboisson, prixboisson)
      VALUES (?,?,?)
    ');

    $requeteBdd->execute(array($choixCodeBoisson,  $choixNomBoisson, $choixPrixBoisson));

    // echo $resultat = $choixCodeBoisson."-". $choixNomBoisson."-". $choixPrixBoisson;
    $requeteBdd->closeCursor(); // Termine la requête d'ajout de la boisson
    
  }
}
 

?>