<html>
<head>
	<title>Panier LBMA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="Javascript">
	

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
		//Charge rechercher.php : l'interface de recherche de recette
		function rechercher(){
			document.location.href ="rechercher.php?";
			

		}
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";


		}
        //Supprime les cookies hors connexions
        function supprimerPanierHorsCo(id,recette){
			var expiration = new Date();
            //delai de 0 = suppression
    		expiration.setDate(expiration.getDate() -1);
			//Cookie
			document.cookie = "tab["+id+"]=; expires=" + expiration.toUTCString()+ ";";
            alert('Suppression du panier de  : ' + recette);
			document.location.href='panier.php';
		}
        //Supprime les cookies correspondant aux éléments supprimer de la liste du client
        function supprimerPanier(id,recette){
			var expireDate = new Date();
            //delai de 0 = suppression
    		expireDate.setDate(expireDate.getDate() -1);
			//Cookie
			document.cookie = "tab["+id+"]=; expires=" + expireDate.toUTCString()+ ";";
			
            var expireDate2 = new Date();
            expireDate2.setDate(expireDate2.getDate() + 1);
			//Cookiedes recettes à supprimer
			document.cookie = "delete["+id+"]" + "=" + recette + "; expires=" + expireDate2.toUTCString()+ ";";
            alert('Suppression du panier de  : ' + recette);

			document.location.href='panier.php';
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

<?php
//AFFICHAGE DES COOKIES
/* DEBOGGAGE COOKIES
if (isset($_COOKIE['delete']) && is_array($_COOKIE['delete'])) {
    foreach ($_COOKIE['delete'] as $indice => $valeur) {
        echo "<p>delete : " .$indice." => ". $valeur . "</p>";

    }
}

if(isset($_COOKIE['tab']) && (is_array($_COOKIE['tab']))){ 
    foreach($_COOKIE['tab'] as $indice => $valeur){ 
        echo "<p>tab : " .$indice." => ". $valeur . "</p>";
    }
}
*/






//Ici session_start sert à reprendre la session en cours. Doit etre à chaque début du script php
session_start();
echo "<p style=\"color:green; font-weight:bold; font-size:30px;\">Voici vos favoris : </p>";
//L'UTILISATEUR EST DECONNECTÉ
if(!isset($_SESSION['login'])){
    //SUPPRESSIONS DES COOKIES TABLEAU D'ELEMENT À SUPPRIMÉES
    if (isset($_COOKIE['delete']) && is_array($_COOKIE['delete'])) {
        foreach ($_COOKIE['delete'] as $indice => $valeur) {
           // echo "<p>" . $valeur . "</p>";
    
            // Suppression du cookie a
            $expiration = time() - 3600; // DATE ANTERIEUR POUR SUPPRIMER
            setcookie("delete[" . $indice . "]", '', $expiration);
        }
    }
    
    if(isset($_COOKIE['tab']) && (is_array($_COOKIE['tab']))){ 
        foreach($_COOKIE['tab'] as $indice => $valeur){ 
            //echo "$indice = $valeur\n";
            
    
            echo "<span style=\"color:blue; font-weight:bold; font-size:20px;\"> ".$valeur."</span>";
            echo "<br>";
            $image = preg_replace('/\s+/', '_', $valeur);	//remplace " " par _ 
           if (file_exists("Photos/".$image.".jpg")) {
                echo "<img src=\"Photos/".$image.".jpg\" width=\"150\" height=\"150\">";
            } else {
                echo "<img src=\"Photos/default.jpg\" width=\"150\" height=\"150\">";
            }
            echo "<br>";
            echo "<button class=\"buttonDelete\" onclick=\"supprimerPanierHorsCo('".$indice."','".$valeur."')\">Supprimer du Panier </button>";
    
            echo "<br>";
            echo "<br>"; 
            echo "<br>"; 
        }
    }
}else{  //LE CLIENT EST CONNECTÉ 
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
            $ok = $mysqli->select_db("LBMA");
    $login = $_SESSION['login'];
    //SUPPRESSION DANS SA BASE DE DONNÉE DES ÉLÉMENTS SUPPRIMÉES
    if(isset($_COOKIE['delete']) && (is_array($_COOKIE['delete']))){
        foreach($_COOKIE['delete'] as $indice => $valeur){ 
            
            //Suppression des recettes dans la tables du client ( PANIER) si déja présente
            $result = $mysqli->query("SELECT id_client,id_recette FROM PANIER WHERE id_client = '".$mysqli->escape_string($login)."' AND id_recette = '".$mysqli->escape_string($indice)."'  ");
           /* while($row = $result-> fetch_row()){
                echo "colonne 1 : ".$row[0].", colonne 2 : ".$row[1];
       
                echo "<br>";
                echo "<br>"; 
                //$ingredient = $mysqli->escape_string($row[1]);
            }*/
            if ($result->num_rows !=0) {
                $mysqli->query("DELETE FROM PANIER WHERE id_client = '".$mysqli->escape_string($login)."' AND id_recette = '".$mysqli->escape_string($indice)."'");
            }
    
            
        }
    }
    //Suppression des cookies dans le tableau correspondant
    //SUPPRESSIONS DES COOKIES TABLEAU D'ELEMENT À SUPPRIMÉES
    if (isset($_COOKIE['delete']) && is_array($_COOKIE['delete'])) {
        foreach ($_COOKIE['delete'] as $indice => $valeur) {
            //echo "<p>" . $valeur . "</p>";
    
            // Suppression du cookie a
            $expiration = time() - 3600; // DATE ANTERIEUR POUR SUPPRIMER
            setcookie("delete['" . $indice . "']", '', $expiration);
        }
    }
    
    
            

            
    if(isset($_COOKIE['tab']) && (is_array($_COOKIE['tab']))){ 
        //INSERTION DES RECETTES DU CLIENTS SI ELLES N'ÉTAIENT PAS DÉJA DANS LA BASE
       
        foreach($_COOKIE['tab'] as $indice => $valeur){ 
            
            //Insertion des recettes dans la tables du client ( PANIER) si non déja présente
            $result = $mysqli->query("SELECT id_client,id_recette FROM PANIER WHERE id_client = '".$mysqli->escape_string($login)."' AND id_recette = '".$mysqli->escape_string($indice)."'  ");
            if ($result->num_rows ==0) {
                $mysqli->query("INSERT INTO PANIER VALUES ('".$mysqli->escape_string($login)."','".$mysqli->escape_string($indice)."')");
            }

           

            
  
            
            
            //il n'y a qu'une ligne (1 ingrédient)
           /* while($row = $result-> fetch_row()){
                $ingredient = $mysqli->escape_string($row[0]);
            }*/
            
        }
     
        
    }
       //Affichage du panier client
       $result = $mysqli->query("SELECT p.id_client,r.titre_recette,r.id_recette FROM PANIER p, RECETTE r WHERE p.id_recette = r.id_recette  AND p.id_client = '".$mysqli->escape_string($login)."' ");
       while($row = $result-> fetch_row()){
           echo "<span style=\"color:blue; font-weight:bold; font-size:20px;\"> ".$mysqli->escape_string($row[1])."</span>";
           $valeur=$mysqli->escape_string($row[1]);
           echo "<br>";
           $image = preg_replace('/\s+/', '_', $valeur);	//remplace " " par _ 
           if (file_exists("Photos/".$image.".jpg")) {
                echo "<img src=\"Photos/".$image.".jpg\" width=\"150\" height=\"150\">";
            } else {
                echo "<img src=\"Photos/default.jpg\" width=\"150\" height=\"150\">";
            }
            echo "<br>";
            echo "<button class=\"buttonDelete\" onclick=\"supprimerPanier('".$mysqli->escape_string($row[2])."','".$mysqli->escape_string($row[1])."')\">Supprimer du Panier</button>";
  
           echo "<br>";
           echo "<p>---------------------------------------------------------------------</p>";
           echo "<br>"; 
           echo "<br>"; 
           //$ingredient = $mysqli->escape_string($row[1]);
       }
     
    $mysqli->close();
}






?>













<br />



    </main>
    <nav>
    <h3>Panier</h3>
    <p>L'abus d'alcool est dangeureux pour la santé,</p><p>Buvez avec modération,</p><p>LBMA </p>
   


    </nav>


</body>
</html>
