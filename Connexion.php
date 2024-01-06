<html>
<head>
	<title>Connexion LBMA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="Javascript">
	

		//Revenir à l'accueil
		function accueil(){
			document.location.href ="index.php?";
			

		}
		//Page d'inscription
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";


		}
	//Page de connection
        function connexion(){
			document.location.href ="Connexion.php?type=connexion";
			

		}
		//Accès au panier
		function panier(){
			document.location.href ="panier.php?";
			

		}
		 //Charge rechercher.php : l'interface de recherche de recette
		function rechercher(){
			document.location.href ="rechercher.php?";
			
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
    	//CONNEXION À LA BD (EN COMMENTAIRE LE CODE POUR LA VERSION SERVEUR)
	    
	    
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

$mysqli->close();

?>
<?php
//Ici session_start sert à reprendre la session en cours. Doit etre à chaque début du script php
session_start();

//Si on n'est pas connecté
if(!isset($_SESSION['login'])){

    //Si mode inscription et que l'on est pas connecté (interface d'inscription)
    if(isset($_GET['type']) && ($_GET['type'] == "inscription")){
    
        echo "<h3>INSCRIPTION</h3>";
        
        echo "<form method=\"post\" action=\"Connexion.php?type=resultatFormulaire\" id=\"11\">";
        
        echo "<legend>Veuillez remplir le formulaire</legend>";

        echo "Login :    
        <input type=\"text\" name=\"login\" required=\"required\" /><br />  ";
        echo "Mot de passe : 
        <input type=\"text\" name=\"mdp\" required=\"required\"/><br /> ";

        echo "Nom :    
        <input type=\"text\" name=\"nom\"  /><br />  ";
        echo "Prénom : 
        <input type=\"text\" name=\"prenom\" /><br /> ";
        echo "Adresse :
        <input type=\"text\" name=\"adresse\" /><br /> ";
        echo "Code Postal :
        <input type\text\" name=\"postal\" /><br /> ";
        echo "Ville : 
        <input type=\"text\" name=\"ville\" /><br /> ";
        
        echo "Sexe :
        <input type=\"radio\" name=\"sexe\" value=\"homme\" id=\"homme\" />
        <label for=\"homme\">Homme</label>
        <input type=\"radio\" name=\"sexe\" value=\"femme\" id=\"femme\" />
        <label for=\"femme\">Femme</label><br />";
        echo "Adresse électronique:
        <input type=\"email\" name=\"email\" /><br />";
        echo "Date de naissance:
        <input type=\"date\" name=\"date_naissance\" /><br />";
        echo "Numéro de téléphone:
        <input type=\"tel\" name=\"telephone\" /><br />";
        
        echo "<input class=\"buttonConnexion\" type=\"submit\" value=\"Valider\" name=\"submit\"/>";
        echo "</form>";
    }

    //Si Mode Connexion et que l'on est déconnecté (interface de connexion)
    if(isset($_GET['type']) && ($_GET['type'] == "connexion")){
    echo "<h3>CONNEXION</h3>";
    echo "<form method=\"post\" action=\"Connexion.php?type=resultatConnexion\" id=\"11\">";

    echo "Login :    
    <input type=\"text\" name=\"login\" required=\"required\" /><br />  ";
    echo "Mot de passe : 
    <input type=\"text\" name=\"mdp\" required=\"required\"/><br /> ";
    echo "<input class=\"buttonConnexion\" type=\"submit\" value=\"Valider\" name=\"submit\"/>";
    echo "</form>";

    }

    //Si on a validé un formulaire d'inscription
    if(isset($_GET['type']) && ($_GET['type'] == "resultatFormulaire")){
    //CONNEXION À LA BD (EN COMMENTAIRE LE CODE POUR LA VERSION SERVEUR)
    	 
	    
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
    $login = $_POST['login'];
    
    // VERIFIACATION SI L'UTILISATEUR EXISTE DEJA DANS LA BD
	    $stmt = $mysqli->prepare("SELECT * FROM CLIENT WHERE id_client = ?");
	    $stmt->bind_param("s", $login);
	    $stmt->execute();
	$result = $stmt->get_result();
    

        //SI LE CLIENT N'EXISTE PAS, ON LE CRÉE
        if($result->num_rows ==0) {
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse = $_POST['adresse'];
            $ville = $_POST['ville'];
            $sexe = $_POST['sexe'];
            $mail = $_POST['email'];
            $naissance = $_POST['date_naissance'];
            $telephone = $_POST['telephone'];

            //INSERTION DU COUPLE LOGIN MDP ET DU RESTE SI IL ONT ÉTÉ ENTRÉS
            $mysqli->query("INSERT INTO CLIENT VALUES ('".$mysqli->escape_string($login)."','".$mysqli->escape_string($mdp)."',NULL,NULL,NULL,NULL,NULL,NULL)");
            if($nom != ""){
                $mysqli->query("UPDATE  CLIENT SET nom = ".$mysqli->escape_string($nom)."");
            }
            if($prenom != ""){
                $mysqli->query("UPDATE  CLIENT SET prenom = ".$mysqli->escape_string($prenom)."");
            }
            if($adresse != ""){
                $mysqli->query("UPDATE  CLIENT SET adresse = ".$mysqli->escape_string($adresse)."");
            }
            if($sexe != ""){
                $mysqli->query("UPDATE  CLIENT SET sexe = ".$mysqli->escape_string($sexe)."");
            }
            if($mail != ""){
                $mysqli->query("UPDATE  CLIENT SET mail = ".$mysqli->escape_string($mail)."");
            }
            if($naissance != ""){
                $mysqli->query("UPDATE  CLIENT SET naissance = ".$mysqli->escape_string($naissance)."");
            }
             //Démarrer une session
            session_start();

            // Enregistrer des données dans la session (LOGIN)
            $_SESSION['login'] = $login;
            echo "<h3> Bienvenue dans notre site ".$login."</h3>";

        }else{	//ON A TROUVÉ LE LOGIN DANS LA BASE, ON NE PEUT S'INSCRIRE AVEC CE LOGIN
            echo "<h3>".$login." Existe déja !!!</h3>";
        }

    $mysqli->close();
    }


    //Le formulaire de connexion a ete validé
    if(isset($_GET['type']) && ($_GET['type'] == "resultatConnexion")){
    //CONNEXION À LA BD (EN COMMENTAIRE LE CODE POUR LA VERSION SERVEUR)
    	
    
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

//RÉCUPÉRATION DES INFOS DE CONNEXION POUR VOIR SI CELA CORRESPOND À UN CLIENT DANS LA BD (REQUETE PRÉPARÉE)
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    
    // Requête préparée
	    $stmt = $mysqli->prepare("SELECT * FROM CLIENT WHERE id_client = ? AND mdp = ?");
	    $stmt->bind_param("ss", $login,$mdp);
	    $stmt->execute();
	$result = $stmt->get_result();


        //SI LE CLIENT N'EXISTE PAS, ON INDIQUE À L'UTILISATEUR L'ERREUR
        if($result->num_rows ==0) {
            echo "<h3>Aucun utilisateur ".$login." avec ce mot de passe n'est connu!!!</h3>";

        }else{	//LA CONNEXION FONCTIONNE
            echo "<h3> Bienvenue dans notre site ".$login."</h3>";
            //Démarrer une session
            session_start();

            // Enregistrer des données dans la session
            $_SESSION['login'] = $login;

        }






    $mysqli->close();
    }

   

}else{
     //Si on à cliqué sur deconnexion
     if(isset($_GET['type']) && ($_GET['type'] == "deconnexion")){
     //CONNEXION À LA BD (EN COMMENTAIRE LE CODE POUR LA VERSION SERVEUR)
    	
	    
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
    	//DESTRUCTION DE LA SESSION
        session_destroy();
        echo "<h3> Vous êtes déconnecté</h3>";

        $mysqli->close();
    }else{	//ON EST CONNECTÉ ET ON A PAS CLIQUÉ SUR DECONNEXION (AFFICHAGE DU BOUTON DE DECONNEXION)
        $login = $_SESSION['login'];
    echo "<h3> Bienvenue dans notre site ".$login."</h3>";

    echo "<form method=\"post\" action=\"Connexion.php?type=deconnexion\" id=\"13\">";


    echo "<input class=\"buttonDeconnexion\" type=\"submit\" value=\"Se Deconnecter\" name=\"submit\"/>";
    echo "</form>";
    }
    
}



?>


 
	















<br />



    </main>
    <nav>
    <?php
    //Affichage d'information dans le menu navigation de connexion
    if(isset($_GET['type']) && ($_GET['type'] == "connexion" || $_GET['type'] == "resultatConnexion")){
    	echo "<h3>Connexion</h3>";
    //Si on n'est pas connecté
	if(!isset($_SESSION['login'])){
		echo "<p>Connectez vous pour accéder à tous vos coktails !!</p>";
	}else{
		$login = $_SESSION['login'];
		echo "<p>Vous êtes connecté ".$login." !</p>";
	}
    }
    //Affichage de déconnexion
    if(isset($_GET['type']) && ( $_GET['type'] == "deconnexion")){
    echo "<h3>Connexion</h3>";
    	echo "<p>Connectez vous pour accéder à tous vos coktails !!</p>";
    }
    //Affichage d'information dans le menu navigation d'inscription
    if(isset($_GET['type']) && ($_GET['type'] == "inscription" || $_GET['type'] == "resultatFormulaire")){
    echo "<h3>Inscription</h3>";
    //Si on n'est pas connecté
	if(!isset($_SESSION['login'])){
		echo "<p>Inscrivez vous afin de découvrir tous nos coktails !!</p>";
	}else{
		$login = $_SESSION['login'];
		echo "<p>Vous êtes inscrit ".$login." !</p>";
	}
    }
    	
    ?>
   


    </nav>


</body>
</html>
