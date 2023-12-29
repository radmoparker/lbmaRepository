

<html>
<head>
	<title>Listes d�roulantes adaptatives</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript">
		
	// a completer
	</script>
    
</head>



<body>
<?php   //Inclusion des données recettes fournis
include 'Donnees.inc.php';
?>


<?php // Cr�ation de la base de donn�es 

  function query($link,$requete)
  { 
    $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));
	return($resultat);
  }


$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port)or die("Erreur de connexion");

//$mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");
$base="Recette";
/*
$Sql="
		DROP DATABASE IF EXISTS $base;
		CREATE DATABASE $base;
		USE $base;
		CREATE TABLE region (id INT AUTO_INCREMENT PRIMARY KEY, lib VARCHAR(255) NOT NULL);
		CREATE TABLE departement (id INT AUTO_INCREMENT PRIMARY KEY, region INT NOT NULL, lib VARCHAR(255) NOT NULL);
		
		INSERT INTO region VALUES (1, 'Lorraine');
		INSERT INTO region VALUES (2, 'Alsace');
		
		INSERT INTO departement VALUES (1, 1, 'Moselle');
		INSERT INTO departement VALUES (2, 1, 'Meurthe-et-Moselle');
		INSERT INTO departement VALUES (3, 1, 'Vosges');
		INSERT INTO departement VALUES (4, 1, 'Meuse');
		
		INSERT INTO departement VALUES (5, 2, 'Bas-Rhin');
		INSERT INTO departement VALUES (6, 2, 'Haut-Rhin')";
        */


//foreach(explode(';',$Sql) as $Requete) query($mysqli,$Requete);
//echo "<p>".print_r($Recettes)."mdeldkezk</p>";

//Test de fonctionnement du tableau de recette


/*Comment faire la bd
  Pour ingredient on va d'abord entrer les ingrédient 
  et leur nom dans la tables. Un tuples aura une clé donnée
  et comme nom les clés de la tables hierarchie.

  //On construira ensuite la table hierarchie en lui donnant
  comme id_hierarchie l'id de l'ingredient du meme nom dans la table ingrédient
  comme nom le nom de l'ingredient (pour faciliter la recherche)
  comme id_sous_categ : l'id du tuple de la table hierarchie qui a le meme id(ou nom pour simplifié)
  la meme chose pour id_sup_categ

 */
$base="LBMA";
$mysqli->query("DROP DATABASE IF EXISTS $base");
$mysqli->query("CREATE DATABASE $base");
$mysqli->query("USE $base");

//Creation de la table INGREDIENT et remplissage de celle ci
$mysqli->query("CREATE TABLE INGREDIENT (id_ingredient VARCHAR(20) PRIMARY KEY, nom_ingredient VARCHAR(255))");

$dic_n=array();
$id_ingredient = 0;
foreach($Hierarchie as $indice => $ingredient){ //$mysqli->escape_string()
  //$mysqli->query("INSERT INTO INGREDIENT VALUES ('TEST1','banane')");
  /*if(array_key_exists('sous-categorie', $ingredient)){
    no es 
  }*/

//exemple $dic_n['Pomme'] = ing5
  $dic_n[$indice]="ing".$mysqli->escape_string($id_ingredient);
  $mysqli->query("INSERT INTO INGREDIENT VALUES ('ing".$mysqli->escape_string($id_ingredient)."','".$mysqli->escape_string($indice)."')");
  $id_ingredient++;
}
//TEST D'INGREDIENT
$result = $mysqli->query("SELECT * FROM INGREDIENT");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){
    //echo $attribut;echo " ";
  }
  //echo "<br>";
}


//Creation de la table HIERARCHIE et remplissage de celle ci
//indique le refus d'une clé primaire
//$mysqli->query("SET sql_require_primary_key=0");
$mysqli->query("CREATE TABLE HIERARCHIE (
  key_hier INT NOT NULL,
  id_hierarchie VARCHAR(20),
  nom_hierarchie VARCHAR(255),
  cat_inf VARCHAR(255) NULL, 
  PRIMARY KEY(key_hier),
  FOREIGN KEY(id_hierarchie) REFERENCES INGREDIENT(id_ingredient))");

//indique le refus d'une clé primaire
$mysqli->query("SET sql_require_primary_key=0");	//UNIQUEMENT POUR SERVER DISTANT
$mysqli->query("CREATE TABLE HIERARCHIE_ASC (id_hierarchie VARCHAR(20),nom_hierarchie VARCHAR(255), cat_sup VARCHAR(255) NULL, FOREIGN KEY(id_hierarchie) REFERENCES INGREDIENT(id_ingredient))");


$key=0;
//Remplissage des sous catégorie et sup catégorie
foreach($Hierarchie as $indice => $ingredient){ //$mysqli->escape_string()
  //$mysqli->query("INSERT INTO INGREDIENT VALUES ('TEST1','banane')");
  //$mysqli->query("INSERT INTO HIERARCHIE(cat_sup,cat_inf ) SELECT id_ingredient,nom_ingredient,NULL,NULL FROM INGREDIENT");
  //$id_ingredient++;
  /*if(array_key_exists('super-categorie', $ingredient)){
    $super_categ = $ingredient['super-categorie'];

    foreach($super_categ as $sc){  //super-categorie
      //echo $sc;
      //echo "<br>";
      $mysqli->query("UPDATE HIERARCHIE SET cat_sup = (SELECT id_ingredient FROM INGREDIENT WHERE nom_ingredient = ".$mysqli->escape_string($sc).") WHERE nom_hierarchie = ".$mysqli->escape_string($indice)."");
  
    }
  }*/

  if(array_key_exists('sous-categorie', $ingredient)){
    $super_categ = $ingredient['sous-categorie'];

    foreach($super_categ as $sc){  //super-categorie
      $key++;

      /*echo $indice." :   ".$dic_n[$indice];
      echo "<br>";
      echo "\n";
      echo $sc." :   ".$dic_n[$sc];
      echo "<br>";*/
      if ($indice=="Fruit"){
        echo $sc."          OOOOOOOO             \n";

      }

      //$mysqli->query("INSERT INTO HIERARCHIE(id_hierarchie,nom_hierarchie,cat_inf ) SELECT id_ingredient,nom_ingredient,(SELECT i.id_ingredient FROM INGREDIENT i WHERE nom_ingredient = ".$mysqli->escape_string($sc).") FROM INGREDIENT WHERE nom_ingredient = $mysqli->escape_string($indice)");
      $mysqli->query("INSERT INTO HIERARCHIE (key_hier, id_hierarchie, nom_hierarchie, cat_inf) VALUES (".$key.",'".$dic_n[$indice]."','".$mysqli->escape_string($indice)."','".$dic_n[$sc]."')");
      /*$result = $mysqli->query("SELECT * FROM HIERARCHIE");
      while($row = $result-> fetch_row()){
        foreach($row as $attribut){ 
          echo $attribut;echo " ";
        }
        echo "<br>";
      }*/
 
    }
  }else{
        $key++;
        $mysqli->query("INSERT INTO HIERARCHIE (key_hier, id_hierarchie, nom_hierarchie, cat_inf) VALUES (".$key.",'".$dic_n[$indice]."','".$mysqli->escape_string($indice)."','NULL')");
  }
  if(array_key_exists('super-categorie', $ingredient)){
    $super_categ = $ingredient['super-categorie'];

    foreach($super_categ as $sc){  //super-categorie
      //$mysqli->query("INSERT INTO HIERARCHIE(id_hierarchie,nom_hierarchie,cat_inf ) SELECT id_ingredient,nom_ingredient,(SELECT i.id_ingredient FROM INGREDIENT i WHERE nom_ingredient = ".$mysqli->escape_string($sc).") FROM INGREDIENT WHERE nom_ingredient = $mysqli->escape_string($indice)");
      $mysqli->query("INSERT INTO HIERARCHIE_ASC (id_hierarchie, nom_hierarchie, cat_sup)
    SELECT id_ingredient, nom_ingredient,
           (SELECT i.id_ingredient FROM INGREDIENT i WHERE i.nom_ingredient = '".$mysqli->escape_string($sc)."')
    FROM INGREDIENT WHERE nom_ingredient = '".$mysqli->escape_string($indice)."'");


      //echo $sc;
      //echo "<br>";
      //$mysqli->query("UPDATE HIERARCHIE SET cat_sup = (SELECT id_ingredient FROM INGREDIENT WHERE nom_ingredient = ".$mysqli->escape_string($sc).") WHERE nom_hierarchie = ".$mysqli->escape_string($indice)."");
  
    }
  }
 
}

/*
$id_ingredient = 0;
foreach($Hierarchie as $indice => $ingredient){ //$mysqli->escape_string()
  //$mysqli->query("INSERT INTO INGREDIENT VALUES ('TEST1','banane')");
  $mysqli->query("INSERT INTO INGREDIENT VALUES ('ing".$mysqli->escape_string($id_ingredient)."','".$mysqli->escape_string($indice)."')");
  $id_ingredient++;
}
*/
//Affichage des catégorie superieur d'epice
$result = $mysqli->query("SELECT i.nom_ingredient FROM HIERARCHIE_ASC ha, INGREDIENT i WHERE ha.cat_sup = i.id_ingredient AND ha.nom_hierarchie = 'Épice'");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo $attribut;echo " ";
  }
  echo "<br>";
}
//Affichage des sous catégorie de Épice
echo "Aca§§§§§§§§§§§§§§§              ";
$result = $mysqli->query("SELECT * FROM HIERARCHIE ha");
while($row = $result-> fetch_row()){
  echo "\n";
  foreach($row as $attribut){ 
    echo $attribut;echo " ";
  }
  echo "<br>";
}

//Affichage des sous catégorie de Épice
$result = $mysqli->query("SELECT i.nom_ingredient FROM HIERARCHIE ha, INGREDIENT i WHERE ha.cat_inf = i.id_ingredient AND ha.nom_hierarchie = 'Épice'");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo $attribut;echo " ";
  }
  echo "<br>";
}
//TEST D'INGREDIENT
/*
$result = $mysqli->query("SELECT * FROM HIERARCHIE_ASC");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo $attribut;echo " ";
  }
  echo "<br>";
}
*/

/*
$Sql="
		
		CREATE DATABASE $base;";
    $result = $mysqli->query("SELECT * FROM region");
while ($row = $result->fetch_row()){
    echo $row[1];
    echo "<br>";
}
*/
//Affichage des ingrédients
/*
$id_ingredient = 0;
foreach($Hierarchie as $indice => $ingredient){
  echo $indice."  -  "."ing".$id_ingredient;
  echo "<br><br>";
  $id_ingredient++;
}
*/







//affichage des recettes
$mysqli->query("CREATE TABLE RECETTE (
id_recette VARCHAR(20) PRIMARY KEY,
titre_recette VARCHAR(255),
preparation VARCHAR(1000) )");

$mysqli->query("CREATE TABLE COMPOSITION (
  id_recette VARCHAR(20) ,
  qtt_ingredient VARCHAR(255),
  id_ingredient VARCHAR(255) NULL,
  nom_ingredient VARCHAR(255) NULL,
  FOREIGN KEY(id_recette) REFERENCES RECETTE(id_recette),
  FOREIGN KEY(id_ingredient) REFERENCES INGREDIENT(id_ingredient)
)");
foreach($Recettes as $indice => $tuples){
  //Affichage titres
  $mysqli->query("INSERT INTO RECETTE VALUES 
  ('".$mysqli->escape_string($indice)."','".$mysqli->escape_string($tuples['titre'])."','".$mysqli->escape_string($tuples['preparation'])."')");

  //echo $tuples['titre'];
  //echo "<br>";
  //echo "<br>";
  //Séparation des ingrédient par | 
  $tab_ingredient = explode("|",$tuples['ingredients']);
  //Affichage des ingrédient  l'ordre des clés index coincide avec ingredients
  $num_ingredient =0;
  $ingredient_associe = $tuples['index'];
  foreach($tab_ingredient as $ingredient){
    $mysqli->query("INSERT INTO COMPOSITION VALUES 
    ('".$mysqli->escape_string($indice)."','".$mysqli->escape_string($ingredient)."', 
    (SELECT id_ingredient 
    FROM INGREDIENT 
    WHERE nom_ingredient = '".$mysqli->escape_string($ingredient_associe[$num_ingredient])."'),
    (SELECT nom_ingredient 
    FROM INGREDIENT 
    WHERE nom_ingredient = '".$mysqli->escape_string($ingredient_associe[$num_ingredient])."'))");

      //echo $ingredient;
     // echo "<br>";
      $num_ingredient++;
  }
  
  //Affichage de lapréparation
 // echo "<br>";
 // echo $tuples['preparation'];
 // echo "<br><br>";
  //echo "<br>";echo "<br>";
}
//Affichage de Recette$//Affichage des sous catégorie de Épice
/*
$result = $mysqli->query("SELECT * FROM COMPOSITION");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo $attribut;echo " ";
    echo "<br>";
  }
  echo "<br>";
  echo "<br>";
}
*/

/*
foreach($Recettes as $tuples){
    //Affichage titres
    echo $tuples['titre'];
    echo "<br>";
    echo "<br>";
    //Séparation des ingrédient par | 
    $tab_ingredient = explode("|",$tuples['ingredients']);
    //Affichage des ingrédient
    foreach($tab_ingredient as $ingredient){
        echo $ingredient;
        echo "<br>";
    }
    //Affichage de lapréparation
    echo "<br>";
    echo $tuples['preparation'];
    echo "<br><br>";
    echo "<br>";echo "<br>";
}
*/

//CREATION DE LA TABLE CLIENT
$mysqli->query("CREATE TABLE CLIENT (
  id_client VARCHAR(200) PRIMARY KEY ,
  mdp VARCHAR(255) NOT NULL,
  nom VARCHAR(255) NULL,
  prenom VARCHAR(255) NULL,
  sexe VARCHAR(255) NULL,
  mail VARCHAR(255) NULL,
  naissance VARCHAR(255) NULL,
  adresse VARCHAR(255) NULL
)");
//CREATION TABLE PANIER
$mysqli->query("CREATE TABLE PANIER (
  id_client VARCHAR(200),
  id_recette VARCHAR(255),
  FOREIGN KEY(id_recette) REFERENCES RECETTE(id_recette),
  FOREIGN KEY(id_client) REFERENCES CLIENT(id_client)

)");

//Insertion valeurs test
$mysqli->query("INSERT INTO CLIENT VALUES ('dupont','mdp','Dupont','Eric','M','mail@outlook.fr','01/01/1999','8 rue du Nil')");
$result = $mysqli->query("SELECT r.id_recette 
FROM RECETTE r, COMPOSITION c, INGREDIENT i
WHERE r.id_recette = c.id_recette 
AND c.id_ingredient = i.id_ingredient
AND i.nom_ingredient = 'Orange'");

while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    $mysqli->query("INSERT INTO PANIER VALUES
    ('dupont',".$mysqli->escape_string($attribut).")");
    
  }

}
/*
$mysqli->query("INSERT INTO PANIER VALUES
    ('dupont',( SELECT r.id_recette 
    FROM RECETTE r, COMPOSITION c, INGREDIENT i
    WHERE r.id_recette = c.id_recette 
    AND c.id_ingredient = i.id_ingredient
    AND i.nom_ingredient = 'Orange'))");
*/

$result = $mysqli->query("SELECT p.id_client, p.id_recette,r.titre_recette FROM PANIER p, RECETTE r WHERE r.id_recette = p.id_recette");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo "<p>".$attribut."</p>";echo " ";
    //echo "<br>";
  }

  echo "<br>";
  echo "<br>";
}
/*

$result = $mysqli->query("SELECT id_recette FROM RECETTE ORDER BY id_recette ASC");
while($row = $result-> fetch_row()){
  foreach($row as $attribut){ 
    echo "<span>".$attribut."</span>";
    //echo "<br>";
  }

  echo "<br>";
  echo "<br>";
}
*/


mysqli_close($mysqli);
?>

</body>
</html>


