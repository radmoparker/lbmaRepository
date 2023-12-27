<html>
<head>
	<title>Listes d�roulantes adaptatives</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="Javascript">
	

		//changement de la couleur du fond du dernier paragraphe
		//Au clique du bouton "Cliquez"
		function accueil(){
			document.location.href ="index.php?";
			
			//document.fgColor = "red";
		}
		function inscription(){
            document.location.href ="Connexion.php?type=inscription";

			//document.fgColor = "red";
		}
        function connexion(){
			document.location.href ="Connexion.php?type=connexion";
			
			document.fgColor = "red";
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
    <?php
	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

$mysqli->close();

?>
<?php
session_start();
if(!isset($_SESSION['login'])){

    //Affichage du formulaire de base si en mode connexion
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
        
        echo "<input type=\"submit\" value=\"Valider\" name=\"submit\"/>";
        echo "</form>";
    }

    //Affichage du formulaire de connexion
    if(isset($_GET['type']) && ($_GET['type'] == "connexion")){
    echo "<h3>CONNEXION</h3>";
    echo "<form method=\"post\" action=\"Connexion.php?type=resultatConnexion\" id=\"11\">";

    echo "Login :    
    <input type=\"text\" name=\"login\" required=\"required\" /><br />  ";
    echo "Mot de passe : 
    <input type=\"text\" name=\"mdp\" required=\"required\"/><br /> ";
    echo "<input type=\"submit\" value=\"Valider\" name=\"submit\"/>";
    echo "</form>";

    }

    //action de validation du formulaire d'inscripon
    if(isset($_GET['type']) && ($_GET['type'] == "resultatFormulaire")){
    	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}
    $ok = $mysqli->select_db("LBMA");
    $login = $_POST['login'];
    $result = $mysqli->query("SELECT * FROM CLIENT WHERE id_client ='".$mysqli->escape_string($login)."'");

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

            //INSERTION DES 2 VALEURS ALÉATOIRE ET DU RESTE SI IL ONT ÉTÉ ENTRÉS
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
            echo "<h3> Bienvenue dans notre site ".$login."</h3>";

        }else{
            echo "<h3>".$login." Existe déja !!!</h3>";
        }




    //$mysqli->query("UPDATE INTO CLIENT VALUES ('".$mysqli->escape_string($login)."','".$mysqli->escape_string($mdp)."',NULL,NULL,NULL,NULL,NULL,NULL)");

    $mysqli->close();
    }


    //action de validation du formulaire de connexion
    if(isset($_GET['type']) && ($_GET['type'] == "resultatConnexion")){
    	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}
    $ok = $mysqli->select_db("LBMA");

    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    $result = $mysqli->query("SELECT * FROM CLIENT WHERE id_client ='".$mysqli->escape_string($login)."' AND mdp ='".$mysqli->escape_string($mdp)."'");

        //SI LE CLIENT N'EXISTE PAS, ON LE CRÉE
        if($result->num_rows ==0) {
            echo "<h3>Utilisateur ".$login." Inconnu!!!</h3>";

        }else{
            echo "<h3> Bienvenue dans notre site ".$login."</h3>";
            //Démarrer une session
            session_start();

            // Enregistrer des données dans la session
            $_SESSION['login'] = $login;

        }




    //$mysqli->query("UPDATE INTO CLIENT VALUES ('".$mysqli->escape_string($login)."','".$mysqli->escape_string($mdp)."',NULL,NULL,NULL,NULL,NULL,NULL)");

    $mysqli->close();
    }

   

}else{
     //Si deconnexion
     if(isset($_GET['type']) && ($_GET['type'] == "deconnexion")){
        	$host = 'db-mysql-fra1-60708-do-user-15443973-0.c.db.ondigitalocean.com';
$port = 25060;
$username = 'doadmin';
$password = 'AVNS_0_3_USnXxaDGye-lb-w';
$database = 'defaultdb';
$sslmode = 'REQUIRED';

// Connexion à la base de données
$mysqli = mysqli_connect($host, $username, $password, $database, $port);
// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}
        $ok = $mysqli->select_db("LBMA");
    
        session_destroy();
        echo "<h3> Vous êtes déconnecté</h3>";

        $mysqli->close();
    }else{
        $login = $_SESSION['login'];
    echo "<h3> Bienvenue dans notre site ".$login."</h3>";

    echo "<form method=\"post\" action=\"Connexion.php?type=deconnexion\" id=\"13\">";


    echo "<input type=\"submit\" value=\"Se Deconnecter\" name=\"submit\"/>";
    echo "</form>";
    }
    
}



?>


 
	















<br />



    </main>
    <nav>
    <p>Ici connexion</p>
   


    </nav>


</body>
</html>
