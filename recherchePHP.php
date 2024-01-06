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
    $ok = $mysqli->select_db("LBMA");
	$avec = $_GET['avec'];
	
	$sans = $_GET['sans'];
	if(($avec == "") && ($sans != "")){
	$avec = 'Aliment';
	}

	/*Récupère toutes les recettes contenant l'ingredient $hier inci que les ingrédient descendant de $hier*/
	function getAllRecipies2($hier,$mysqli,&$listRecette){
	
		$hierarchie=$hier;
		//$listRecette = array();
		//get all recipies avec la hierarchie actuelle si elles existent 
		$result = $mysqli->query("SELECT r.id_recette,r.titre_recette,r.preparation
		FROM RECETTE r, COMPOSITION c
		WHERE r.id_recette = c.id_recette
		AND c.nom_ingredient ='".$mysqli->escape_string($hierarchie)."'");
		while($row = $result-> fetch_row()){
	
			$uneRecette = array();
			foreach($row as $attribut){ 
				$uneRecette[]=$attribut;
			}
			$listRecette[$row[0]]=$uneRecette;
	
		}
		//get all subs 
	
		$result2 = $mysqli->query("SELECT DISTINCT h.id_hierarchie, i.nom_ingredient
		FROM HIERARCHIE h, INGREDIENT i
		WHERE i.id_ingredient = h.id_hierarchie
		AND h.id_hierarchie = (
		SELECT hi.cat_inf
		FROM HIERARCHIE hi
		WHERE hi.nom_hierarchie = '".$mysqli->escape_string($hierarchie)."'
		AND hi.cat_inf = h.id_hierarchie
		)");
	
		if($result2->num_rows ==0) {
			return;
	
		}else{
	
			while($row2 = $result2-> fetch_row()){
				getAllRecipies2($row2[1],$mysqli,$listRecette);
			}
		
		}		
	}

	/**/
	function my_sort($a, $b) {
		if ($a[3] == $b[3]) return 0;
		return ($a[3] < $b[3]) ? 1 : -1;
	}

    function f($avec,$sans,$mysqli){
        $avecExploded = explode(",",$avec);
        $sansExploded = explode(",",$sans);
        $arrayDeTout = array();
		$arrayDeToutdeSans = array();
        $arrayResult = array();

		
        foreach ($avecExploded as $ingredientAvec) {
            $aux = array();
            getAllRecipies2($ingredientAvec,$mysqli,$aux);
            $arrayDeTout[]=$aux;
        }
		foreach($sansExploded as $ingredientSans){
			$aux2 = array();
			getAllRecipies2($ingredientSans, $mysqli, $aux2);
			$arrayDeToutdeSans = $aux2;
		}
		/* !!!! SEULEMENT SI ON VEUT LA RECHERCHE SANS APPROXIMATION (VERSION PRECEDENTE)
		$arrayResult = $arrayDeTout[0];

		if (count($avecExploded) > 1) {
			
			for ($i=1; $i < count($arrayDeTout) ; $i++) {
				$arrayResult = array_intersect_key($arrayResult,$arrayDeTout[$i]);
			}		
			
		}
		
		*/
		//incrementation du score de satisfaction des recettes à chaque fois qu'un de ses ingredients était dans la liste des ingredients "AVEC"
		for ($i=0; $i < count($arrayDeTout) ; $i++) {
			//print_r($i);
			foreach ($arrayDeTout[$i] as $idRecette => $recette) {
				//print_r($idRecette);
				if (!isset($arrayResult[$idRecette])) {
					$arrayResult[$idRecette] = $recette;
					$arrayResult[$idRecette][] = 0;
				}
				$arrayResult[$idRecette][3]=$arrayResult[$idRecette][3]+1;
			}
		}	

		if (count($sansExploded)>0){
			$arrayResult = array_diff_key($arrayResult,$arrayDeToutdeSans);
		}

		  
		usort($arrayResult, "my_sort");
		//print_r($arrayResult);
        return $arrayResult;

    }
    /*Enregistrement json pour pouvoir récupérer dans rechercheJS.js*/
	$arrayResult=f($avec,$sans,$mysqli);

	header('Content-type: application/json');
	echo json_encode(array('result'=>$arrayResult));
	//return json_encode(array('result'=>$arrayResult));
?>
