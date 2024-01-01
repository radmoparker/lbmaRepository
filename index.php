<html>
<head>
	<title>LBMA </title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="Javascript">
	

		//Charge index.php : accueil
		function accueil(){
			document.location.href ="index.php?";
			

		}
		//Charge rechercher.php : l'interface de recherche de recette
		function rechercher(){
			document.location.href ="rechercher.php?";
			

		}
		//Charge panier.php : l'intrface du panier
		function panier(){
			document.location.href ="panier.php?";	

		}
		//Charge Connexion.php : l'intrface de connexion au compte client
		function connexion(){
			document.location.href ="Connexion.php?type=connexion";
			

		}
		//Charge Connexion.php : l'intrface d'inscription client
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";


		}
		function goNext(id){


			//document.location.href='page1.htm'
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
		
    <p style="color:green; font-weight:bold; font-size:20px;">Naviguez à travers nos ingrédients ! (à gauche)</p>
	<?php
	$chemin="";

?>
	<?php
	//FONCTION REMPLI TABLEAU $listRecette de toutes les reccettes dont les 
	//ingrédients ont por ascendant $hier,
	

	function getAllRecipies($hier,$mysqli,&$listRecette){
		
		/*
    
		$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
		$port = 25060;
		$username = 'doadmin';
		$password = 'AVNS_0_3_USnXxaDGye-lb-w';
		$database = 'defaultdb';
		$sslmode = 'REQUIRED';

		// Connexion à la base de données
		$mysqli = mysqli_connect($host, $username, $password, $database, $port);
		*/
		/*$mysqli = mysqli_connect('127.0.0.1', 'root', '');
		// Vérifier la connexion
		if ($mysqli->connect_error) {
			die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
		}
		
		$ok = $mysqli->select_db("LBMA");
		*/
	
		$hierarchie=$hier;
		//$listRecette = array();
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
		//$mysqli->close();
		
	}
	
		?>


<!--MAIN DU SITE : LA PARTIE CENTRALE-->

<?php
    /*
    
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
	$port = 25060;
	$username = 'doadmin';
	$password = 'AVNS_0_3_USnXxaDGye-lb-w';
	$database = 'defaultdb';
	$sslmode = 'REQUIRED';

	// Connexion à la base de données
	$mysqli = mysqli_connect($host, $username, $password, $database, $port);
	*/
	$mysqli = mysqli_connect('127.0.0.1', 'root', '');
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}
$ok = $mysqli->select_db("LBMA");





//AFFICHAGE DE TOUTES LES RECETTES DE BASE (SANS AVOIR CLIQUÉ SUR IUN INGRÉDIENT)
if(isset($_GET['hierarchie'])){
	//Récupération de l'ingrédient correspondant
	$result = $mysqli->query("SELECT DISTINCT i.nom_ingredient
	FROM HIERARCHIE h, INGREDIENT i
	WHERE i.id_ingredient = '".$mysqli->escape_string($_GET['hierarchie'])."'
	AND i.id_ingredient = h.id_hierarchie");
	//il n'y a qu'une ligne (1 ingrédient)
	while($row = $result-> fetch_row()){
		$ingredient = $mysqli->escape_string($row[0]);
	  }
	

	//Récupération du chemin d'ingrédient sélectionné
	if(!isset($_GET['arbre_ingredient'])){
		$chemin = $ingredient."/";
	}else{
		$chemin = $_GET['arbre_ingredient'].$ingredient."/";
	}
	echo "<p style=\"color:blue;font-weight:bold;font-size:20px;\">".$chemin."</p>";

	//RÉCUPÉRATION POUR AVOIR LES RECETTES CORRESPONDANT À LA HIERARCHIE
	$hierarchie = $_GET['hierarchie'];
	
	
	//echo" <p>ON DEVRAIT VOIR ".$hierarchie."</p>";

  //Affichage de toutes les recettes
  
  $bb = array();
	getAllRecipies($hierarchie,$mysqli,$bb);
	
	foreach ($bb as $rec){
		/*$u=0;
		foreach ($rec as $elem){
			echo "<p>".$u." ".$elem."</p>";
			echo " ";
			echo "<br>";
			$u++;
			
		}
		*/
		echo "<p style=\"font-weight:bold;font-size:20px;\">".$rec[1]."</p>";
		echo "<p>".$rec[2]."</p>";
		$image = preg_replace('/\s+/', '_', $rec[1]);	//remplace " " par _ 
		if (file_exists("Photos/".$image.".jpg")) {
			echo "<img src=\"Photos/".$image.".jpg\" width=\"150\" height=\"150\">";
		} else {
			echo "<img src=\"Photos/default.jpg\" width=\"150\" height=\"150\">";
		}
		echo "<br>";
		echo "<br>";
		echo "<button class=\"buttonPanier\" onclick=\"ajouterPanier('".$rec[0]."','".$rec[1]."')\">Ajouter au panier</button>";
		//<button onclick="connexion()">Connexion</button>

		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<p>----------------------------------------------------------</p>";
		
	}

}

$mysqli->close();


?>

    </main>
	<!--NAVIGATION À GAUCHE DU SITE-->
    <nav>
    <p style="color:black; font-weight:bold; font-size:20px;">NAVIGATION</p>
   <?php
         /*
    
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
	$port = 25060;
	$username = 'doadmin';
	$password = 'AVNS_0_3_USnXxaDGye-lb-w';
	$database = 'defaultdb';
	$sslmode = 'REQUIRED';

	// Connexion à la base de données
	$mysqli = mysqli_connect($host, $username, $password, $database, $port);
	*/
	$mysqli = mysqli_connect('127.0.0.1', 'root', '');
	// Vérifier la connexion
	if ($mysqli->connect_error) {
		die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
	}
	 $ok = $mysqli->select_db("LBMA");

	//ON A CLIQUÉ SUR AUCUN INGRDIENT
	if(!isset($_GET['hierarchie'])){
		//RECHERCHE des ingrédient qui n'ont pas de categorie superieur il n'y a qu' "ALIMENT"
		$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie,i.nom_ingredient
		FROM HIERARCHIE h, INGREDIENT i
		WHERE i.id_ingredient = h.id_hierarchie
		AND id_hierarchie NOT IN 
		(SELECT hi.cat_inf FROM HIERARCHIE hi) ");
   
		//AFFICHAGE DES INGREDIENTS il n'y a qu' "ALIMENT"
		while($row = $result-> fetch_row()){
		   echo "<a href=\"index.php?hierarchie=".$mysqli->escape_string($row[0])."&arbre_ingredient=".$mysqli->escape_string($chemin)."\">".$mysqli->escape_string($row[1])."</a>";		   
		   echo "<br>";
		 }
	}else{ //ON A CLIQUÉ SUR AU MOINS UN INGREDIENT
		
		echo "<p>On a cliqué sur un ingrédient</p>";
		//Récupération du dernier élément cliqué
		$hierarchie = $_GET['hierarchie'];

		//Recherche des sous-catégorie de l'element cliqué
		$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
		FROM HIERARCHIE h, INGREDIENT i
		WHERE i.id_ingredient = h.id_hierarchie
		AND h.id_hierarchie = (
			SELECT hi.cat_inf
			FROM HIERARCHIE hi
			WHERE hi.id_hierarchie = '".$mysqli->escape_string($hierarchie)."'
			AND hi.cat_inf = h.id_hierarchie
		)");

	

		//Test si le résultat est null (pas de descendant) on affiche lrien
		//soit les feuilles de l'arbre
		if($result->num_rows ==0) {
			echo "<p style=\"color:red;font-weight:bold;\">IL N'Y A PLUS D'INGREDIENT</p>";
			
		}else{
			//AFFICHAGE DES INGREDIENTS SOUS CATEG DE L'ELEMENT CLIQUÉ PRÉCÉDEMENT
		while($row = $result-> fetch_row()){
			echo "<a href=\"index.php?hierarchie=".$mysqli->escape_string($row[0])."&arbre_ingredient=".$mysqli->escape_string($chemin)."\">".$mysqli->escape_string($row[1])."</a>";				
			echo "<br>";
		  }
		}
   
		
	}






	



















	 $mysqli->close();
   
 
 
 	?>
	
	

    </nav>

	<?php	//ANCIENNE FONCTION


//FONCTION RECURSIVE POUR OBTENIR TOUS LES ID INGREDIENTS SOUS JACENTS
function tableFromID($id){
    /*
    
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
	$port = 25060;
	$username = 'doadmin';
	$password = 'AVNS_0_3_USnXxaDGye-lb-w';
	$database = 'defaultdb';
	$sslmode = 'REQUIRED';

	// Connexion à la base de données
	$mysqli = mysqli_connect($host, $username, $password, $database, $port);
	*/
	$mysqli = mysqli_connect('127.0.0.1', 'root', '');
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

$ok = $mysqli->select_db("LBMA");
	$condition = true;
	$monTableau = array();
	while($condition){
		$result = $mysqli->query("SELECT cat_inf FROM HIERARCHIE WHERE id_hierarchie = '".$mysqli->escape_string($id)."'");
		if($result->num_rows!= 0){
			while($row = $result-> fetch_row()){
				$monTableau[]= $row[0];
			}
			$provisoir = array();
			foreach($monTableau as $value){
				//Récurssion sur les nouvelles valeurs
				$provisoir += tableFromID($value);
			}
			//Ajout des nouveaux ingredient
			$monTableau += $provisoir;
			

		}else{	//Il n'y a plus d'ingredient à ajouter
			$condition = false;
		}
	}
	$mysqli->close();
	return $monTableau;

}









	?>
</body>
</html>
