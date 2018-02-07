<?php
	//lien vers les fonctions php
	include "assets/_php/fonctions.php";
	ajoutBoisson($mabdd);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Machine à café en php</title>
	<!-- liens vers les librairies jquery et bootstrap-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/_css/machineACafe.css">

</head>
<body>
	<div class="mainContener">
		<h1>Ma Machine à café en PHP</h1>
		
		<div id="afficheurInfo" class="date">
			Date : <?= $date?></br> <!-- Insertion de la date du jour en php -->
			Heure : <?= $heure?> <!-- Insertion de l'heure en php -->
		    heures et <?=$minutes?> minutes <!-- Insertion des minutes en php -->
		</div>

		<div class="blocInfos">
			<form method="post" action="machineACafe.php">
				<select name="choixBoisson">
					<option>Choisissez votre boisson</option>
					<?= menuDeroulant($mabdd)?>
				</select>
				<input type="number" min="0" max="5" name="choixSucre" placeholder="Combien de sucres ?"/>
				<input type="submit" value="Valider"/></br>
			</form>
			<p>
			
			</p>
		</div>
		
		<div class="blocInfos">

		<?php
			
			if (isset($_POST["choixBoisson"]) && isset($_POST["choixSucre"])) 
			{
			  echo afficheRecette($mabdd,$_POST["choixBoisson"],$_POST["choixSucre"]);
			  ajoutVente($mabdd);
			} 
			else
			{
				echo $messageAttente;
			} 
		?>

		</div>
		<div class="col-sm-4">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Boissons</th>
						<th>Prix Boisson</th>
					</tr>
				</thead>
				<tbody>
					<!--Afficher le tableau des boissons -->
					
					<?= afficheTabBoissons($mabdd) ?>
					
				</tbody>	
			</table> 
			<form method="post" action="machineACafe.php">
				<div class="form-group">
				<label>Code de la nouvelle boisson</label>
					<input class="form-control" type="text" name="newCodeBoisson" placeholder="Code"></input>
				</div>
				<div class="form-group">
				<label>Nom de la nouvelle boisson</label>
					<input class="form-control" type="text" name="newNomBoisson" placeholder="Nom"></input>
				</div>
				<div class="form-group">
				<label>Prix de la nouvelle boisson</label>
					<select class="form-control" name="newPrixBoisson">
						<option value="40">40</option>
						<option value="50">50</option>
						<option value="60">60</option>
						<option value="70">70</option>
						<option value="80">80</option>
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Ajouter à la BDD</button>
			</form>
		</div>
			
		
	   	<!-- <div id="pieces">
	       	<img id="btnCinqCts" class="piece" alt="0.05" src="images/5_cts.png">
	        <img id="btnDixCts" class="piece" alt="0.10" src="images/10_cts.png">
	        <img id="btnVingtCts" class="piece" alt="0.20" src="images/20_cts.png">
	        <img id="btnCinquanteCts" class="piece" alt="0.50" src="images/50_cts.png">
	        <img id="btnUnEuro" class="piece" alt="1.00" src="images/1_euros.png">
	        <img id="btnDeuxEuros" class="piece" alt="2.00" src="images/2_euros.png">
	    </div> -->
		    <!-- <div id="afficheurMonnaie">
		        <div id="monnayeur">Crédit : 0.00 €</div>
			</div>
			<div id="btnResetCoin"><button>Reset Coin</button></div> -->
	</div>
</body>
</html>