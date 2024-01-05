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
		function composition(id,nom){
			document.location.href ="composition.php?id="+id+"&nom="+nom;
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
		
    <p style="color:blue; font-weight:bold; font-size:24px;">DETAIL DU COKTAIL</p>
<?php

    if(isset($_GET['id']) && isset($_GET['nom'])){

        
        
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
        //On récupère d'abord larecettes

        $result = $mysqli->query("SELECT r.titre_recette,r.preparation,r.id_recette
            FROM RECETTE r
            WHERE r.id_recette ='".$mysqli->escape_string($_GET['id'])."'");
            while($row = $result-> fetch_row()){
                //$cpt=0;
                echo "<p style=\"font-weight:bold;font-size:20px;\">".$row[0]."</p>";
                $image = preg_replace('/\s+/', '_', $row[0]);	//remplace " " par _ 
                if (file_exists("Photos/".$image.".jpg")) {
                    echo "<img src=\"Photos/".$image.".jpg\" width=\"300\" height=\"300\">";
                } else {
                    echo "<img src=\"Photos/default.jpg\" width=\"300\" height=\"300\">";
                }
                echo "<br><br>";
                echo "<button class=\"buttonPanier\" onclick=\"ajouterPanier('".$row[2]."','".$row[0]."')\">Ajouter au panier</button>";

                echo "<p style=\"font-weight:bold;font-size:20px;\">Preparation</p>";
                echo "<p style=\"font-weight:bold;color:rgb(77, 80, 77);\">".$row[1]."</p>";
                echo "<p style=\"font-weight:bold;font-size:20px;\">Composition</p>";
               /* foreach($row as $attribut){ 
                    echo "<p> ".$cpt." ".$attribut."</p>";
                    $cpt++;
                }
                */
                //echo $row[0]."   ";
        
            }
            $result = $mysqli->query("SELECT c.qtt_ingredient,c.nom_ingredient
            FROM RECETTE r,COMPOSITION c
            WHERE c.id_recette = r.id_recette 
            AND r.id_recette ='".$mysqli->escape_string($_GET['id'])."'");

            while($row = $result-> fetch_row()){
                echo "<span style=\"font-weight:bold;color:brown;\">".$row[1]." => </span>";
                echo "<span style=\"font-weight:bold;color:rgb(77, 80, 77);\"> ".$row[0]."</span>";
                echo "<br>";
                /*$cpt=0;
                foreach($row as $attribut){ 
                    echo "<p> ".$cpt." ".$attribut."</p>";
                    $cpt++;
                }*/
            }

            $mysqli->close();
    }



        /*
         $result = $mysqli->query("SELECT r.id_recette,r.titre_recette,r.preparation
            FROM RECETTE r, COMPOSITION c
            WHERE r.id_recette = c.id_recette
            AND c.id_ingredient ='".$mysqli->escape_string($hierarchie)."'");
        */
	?>
     </main>
	<!--NAVIGATION À GAUCHE DU SITE-->
    <nav>
    <h3>Detail</h3>
    <p>L'abus d'alcool est dangeureux pour la santé,</p><p>Buvez avec modération,</p><p>LBMA </p>
   


    </nav>
</body>
</html>
