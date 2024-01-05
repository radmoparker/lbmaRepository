<?php
    //Récupération de l'ensemble des ingrédients à partir de la bases de données 
    
    
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
	$port = 25060;
	$username = 'doadmin';
	$password = 'AVNS_0_3_USnXxaDGye-lb-w';
	$database = 'defaultdb';
	$sslmode = 'REQUIRED';

	// Connexion à la base de données
	$mysqli = mysqli_connect($host, $username, $password, $database, $port);
	
	//$mysqli = mysqli_connect('127.0.0.1', 'root', '');
	// Vérifier la connexion
	if ($mysqli->connect_error) {
		die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
	}
	$ok = $mysqli->select_db("LBMA");
	$allIngredients = array();
	$resultat = $mysqli->query("SELECT DISTINCT nom_hierarchie FROM HIERARCHIE");
	while ($row = $resultat->fetch_row()) {
		$allIngredients[]=$row[0];
	}
    //$mysqli->close();

	//FONCTION REMPLI TABLEAU $listRecette de toutes les reccettes dont les 
	//ingrédients ont por ascendant $hier,
	function getAllRecipies($hier,$mysqli,&$listRecette){
	    $hierarchie=$hier;
		//get all recipies avec la hierarchie actuelle si elles existent 
		$result = $mysqli->query("SELECT r.id_recette,r.titre_recette,r.preparation
		FROM RECETTE r, COMPOSITION c
		WHERE r.id_recette = c.id_recette
		AND c.id_ingredient ='".$mysqli->escape_string($hierarchie)."'");
		while($row = $result-> fetch_row()){
	
			$uneRecette = array();
			foreach($row as $attribut){ 
				$uneRecette[]=$attribut;
			}
			$listRecette[$row[0]]=$uneRecette;
			//echo $row[0]."   ";
	
		}
		//get all subs 
	
		$result2 = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
		FROM HIERARCHIE h, INGREDIENT i
		WHERE i.id_ingredient = h.id_hierarchie
		AND h.id_hierarchie = (
		SELECT hi.cat_inf
		FROM HIERARCHIE hi
		WHERE hi.id_hierarchie = '".$mysqli->escape_string($hierarchie)."'
		AND hi.cat_inf = h.id_hierarchie
		)");
	
		if($result2->num_rows ==0) {
			return;
	
		}else{
	
			while($row2 = $result2-> fetch_row()){
				getAllRecipies($row2[0],$mysqli,$listRecette);
				//echo "<a href=\"lbmaWebsite.php?hierarchie=".$mysqli->escape_string($row[0])."\">".$mysqli->escape_string($row[1])."</a>";
				//echo "<br>";
			}
		
		}
	}

?>
<html>
<head>
	<title>Rechercher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="rechercheJS.js"></script>
	<script type="text/javascript">
        //changement de la couleur du fond du dernier paragraphe
		function accueil(){
			document.location.href ="index.php?";
		
		}
		function panier(){
			document.location.href ="panier.php?";
			
		}
		function connexion(){
			document.location.href ="Connexion.php?type=connexion";
			
		}
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";

		}
		//Charge rechercher.php : l'interface de recherche de recette
		function rechercher(){
			document.location.href ="rechercher.php?"
		}
        function ajouterPanier(id,recette){
			var expireDate = new Date();
			delaiExpiration = 1;
    		expireDate.setDate(expireDate.getDate() + delaiExpiration);
			//Cookie
			document.cookie = "tab["+id+"]" + "=" + recette + "; expires=" + expireDate.toUTCString()+ ";";
			alert('Ajout au panier de  : ' + recette);

			//document.location.href='page1.htm'
		}
		function composition(id,nom){
			document.location.href ="composition.php?id="+id+"&nom="+nom;
		}
	</script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
	<h1>LBMA WEBSITE</h1>
    <button class="buttonMenu" onclick="accueil()">Accueil</button>
    <button class="buttonMenu" onclick="connexion()">Connexion</button>
	<button class="buttonMenu" onclick="inscription()">Inscription</button>
    <button class="buttonMenu" onclick="panier()">Panier</button>
    <button class="buttonMenu" onclick="rechercher()">Rechercher</button>
    </header>
    <main>
    <div>
		<div class="search">
			Recherche: 
			<input id="txtRecherche" type="text" name="example" list="exampleList">
			<datalist id="exampleList">
			<?php
			foreach ($allIngredients as $row) {
				echo "<option value='$row'>";
			}
			?>
			<option value="Boulanger">
			</datalist>
			<button id="boutonAddAvec" class="buttonFiltre" onClick="ajouterAvec()">AddAvec</button>
			<button id="boutonAddSans" class="buttonFiltre" onClick="ajouterSans()">AddSans</button>
			<button id="boutonAddSans" class="buttonFiltre" onClick="reinitialiserIngredients()">Réinitialiser</button>
		</div>
		<br>
		<div>
			Avec: 
			<span id="spanAvec"></span>
		</div>
		<br>
		<div>
			Sans: 
			<span id="spanSans"></span>
		</div>
		<br>
		<div>
			<button id="boutonRecherche" class="buttonConnexion" onClick="bRecherche()">Rechercher</button>
        </div>
		</div>
	</div>
	<br>
	<p style="color:blue;font-weight:bold;font-size:18px">La  <span style="color:red;font-weight:bold;font-size:18px">Satisfaction</span> vous indiquera le nombre d'ingrédients <span style="color:red;font-weight:bold;font-size:18px">AVEC</span> que comporte la recette (Ordre décroissant)</p>
    <br>
    <br>
    <br>
    <div id="divMain">
    </div>
    </main>
    <nav>
	<h3>Recherche</h3>
	<p>L'abus d'alcool est dangeureux pour la santé,</p><p>Buvez avec modération,</p><p>LBMA </p>
	</nav>
</body>
</html>
