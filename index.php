<html>
<head>
	<title>Listes d�roulantes adaptatives</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="Javascript">
	

		//changement de la couleur du fond du dernier paragraphe
		function accueil(){
			document.location.href ="lbmaWebsite.php?";
			
			document.fgColor = "red";
		}
		function connexion(){
			document.location.href ="Connexion.php?type=connexion";
			
			document.fgColor = "red";
		}
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";

			document.fgColor = "red";
		}
		function goNext(id){
			document.fgColor = "red";

			//document.location.href='page1.htm'
		}
	</script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>



<body>

    <header>
    <h1>LBMA WEBSITE</h1>
	<button onclick="accueil()">Accueil</button>
	<button onclick="connexion()">Connexion</button>
	<button onclick="inscription()">Inscription</button>

	
    </header>
    <main>
    <p>Ceci est le main</p>


	<?php


//FONCTION RECURSIVE POUR OBTENIR TOUS LES ID INGREDIENTS SOUS JACENTS
function tableFromID($id){
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);

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

<?php

$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
$ok = $mysqli->select_db("LBMA");





//Affichage des recettes
if(isset($_GET['hierarchie'])){
	//RÉCUPÉRATION POUR AVOIR LES RECETTES CORRESPONDANT À LA HIERARCHIE
	$hierarchie = $_GET['hierarchie'];
	//$id_ingredients = tableFromID($hierarchie);
	/*foreach($id_ingredients as $value){
		echo" <p>ON DEVRAIT VOIR ".$value."</p>";
	}
	*/
	
	echo" <p>ON DEVRAIT VOIR ".$hierarchie."</p>";


	
	//Récupération des recettes
	$result = $mysqli->query("SELECT r.id_recette,r.titre_recette,r.preparation
	FROM RECETTE r, COMPOSITION c
	WHERE r.id_recette = c.id_recette
	AND c.id_ingredient ='".$mysqli->escape_string($hierarchie)."'");
while($row = $result-> fetch_row()){
	foreach($row as $attribut){ 
		echo "<p>";
	  echo $attribut;
	  echo "</p>";echo " ";
	  echo "<br>";
	}
	echo "<br>";
	echo "<br>";
  }

}

$mysqli->close();


?>

    </main>
    <nav>
    <p>Ceci est le nav</p>
   <?php
     $host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
	 $ok = $mysqli->select_db("LBMA");







	if(!isset($_GET['hierarchie'])){
		//Affichage des ingrédient qui n'ont pas de categorie superieur
		$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie,i.nom_ingredient
		FROM HIERARCHIE h, INGREDIENT i
		WHERE i.id_ingredient = h.id_hierarchie
		AND id_hierarchie NOT IN 
		(SELECT hi.cat_inf FROM HIERARCHIE hi) ");
   
		//AFFICHAGE DES LIENS (SPAN)
		while($row = $result-> fetch_row()){
		   echo "<a href=\"lbmaWebsite.php?hierarchie=".$mysqli->escape_string($row[0])."\">".$mysqli->escape_string($row[1])."</a>";
			 //echo $row[0];echo " ";echo $row[1];
		   
		   echo "<br>";
		 }
	}else{
		//ON A CLIQUÉ POUR ATTEINDRE LES ÉLÉMENTS SUIVANTS
		echo "<p>ON EST BIEN DANS LE ELSE</p>";
		$hierarchie = $_GET['hierarchie'];
		//".$mysqli->escape_string($hierarchie)."
		/*$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
    FROM HIERARCHIE h, INGREDIENT i
    WHERE i.id_ingredient = h.id_hierarchie
    AND NOT EXISTS (
        SELECT hi.cat_inf
        FROM HIERARCHIE hi
        WHERE hi.id_hierarchie = '".$mysqli->escape_string($hierarchie)."'
        AND hi.cat_inf = h.id_hierarchie
    )");
	*/


	$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
    FROM HIERARCHIE h, INGREDIENT i
    WHERE i.id_ingredient = h.id_hierarchie
    AND h.id_hierarchie = (
        SELECT hi.cat_inf
        FROM HIERARCHIE hi
        WHERE hi.id_hierarchie = '".$mysqli->escape_string($hierarchie)."'
        AND hi.cat_inf = h.id_hierarchie
    )");

		/*
		!= ".$mysqli->escape_string($hierarchie)."
		AND id_hierarchie NOT IN 
		(SELECT hi.cat_inf FROM HIERARCHIE hi) ");
		*/

		//Test si le résultat est null on affiche le même élément
		if($result->num_rows ==0) {
			echo "<p>ON ne peut aller plus bas</p>";

			$result = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
			FROM HIERARCHIE h, INGREDIENT i
			WHERE i.id_ingredient = h.id_hierarchie
			AND h.id_hierarchie = '".$mysqli->escape_string($hierarchie)."'");
			while($row = $result-> fetch_row()){
				echo "<a href=\"lbmaWebsite.php?hierarchie=".$mysqli->escape_string($row[0])."\">".$mysqli->escape_string($row[1])."</a>";
				//echo $row[0];echo " ";echo $row[1];
				
				echo "<br>";
			}
		}else{
			//AFFICHAGE DES LIENS (SPAN)
		while($row = $result-> fetch_row()){
			echo "<a href=\"lbmaWebsite.php?hierarchie=".$mysqli->escape_string($row[0])."\">".$mysqli->escape_string($row[1])."</a>";
			  //echo $row[0];echo " ";echo $row[1];
			
			echo "<br>";
		  }
		}
   
		
	}






	



















	 $mysqli->close();
   
 
 
 	?>


    </nav>


</body>
</html>
