
function ajouterAvec(){
    var spanA = document.getElementById("spanAvec");
    var txtBarreRecherche = document.getElementById("txtRecherche");

    if (txtBarreRecherche.value !="") {
        if (spanA.innerHTML == ""){
            spanA.innerHTML = spanA.innerHTML+txtBarreRecherche.value;
        }else{
            spanA.innerHTML = spanA.innerHTML+","+txtBarreRecherche.value;
        }
        txtBarreRecherche.value = "";
    }
}

function ajouterSans(){
    var spanB = document.getElementById("spanSans");
    var txtBarreRecherche = document.getElementById("txtRecherche");

    if (txtBarreRecherche.value !="") {
        if (spanB.innerHTML == ""){
            spanB.innerHTML = spanB.innerHTML+txtBarreRecherche.value;
        }else{
            spanB.innerHTML = spanB.innerHTML+","+txtBarreRecherche.value;
        }
        txtBarreRecherche.value = "";
    }

}
function reinitialiserIngredients(){
    var spanA = document.getElementById("spanAvec");
    var spanB = document.getElementById("spanSans");
        spanA.innerHTML="";
        spanB.innerHTML="";
}
/*  !! ATTENTION : CETTE FONCTION EST ESSENTIELLE
    lorsqu'on charge les image directement dans la boucle for (en meme temps que le parcours des recettes)
    , comme les images son chargée de manière asynchrone les images ne sont pas chargée dans leur div correspondante
    En utilisant une fonction séparément on est sur que la fonction est executée avec les paramètre adéquat
 */
function loadImage(id, imagePath,newDiv,name) {
    var img = new Image();
    img.onload = function() {
        img.width = 150;  // Défini la largeur souhaitée
        img.height = 150; // Défini la hauteur souhaitée
        $('#' + id).append(img); // Ajoute l'image  à la div correspondante qui est l'id de la recette
        $('#' + id).append("<br>");
    };
    img.onerror = function() {
        var defaultImgElement = $('<img>').attr({
            'src': 'Photos/default.jpg',
            'width': 150,
            'height': 150
        }); //Ajoute une image par défault sinon
        $('#' + id).append(defaultImgElement);
    };
    img.src = imagePath;
    // Crée le bouton et l'ajoute à la div correspondante
    createButton(newDiv, id, name);
}
/*
On crée le boutton de la même facon (par sécurité car pas essayé de le créer dans la boucle for)
*/
function createButton(newDiv, id, name) {
    //addClass pour pouvoir génerer le css
    var button = $('<button>').addClass('buttonPanier').text('Ajouter au panier');
    button.on('click', function() {
        ajouterPanier(id, name);    //Définie dans le fichier rechercher.php 
    });
    newDiv.append(button); // Ajoute le bouton à la div
}



function bRecherche(){
    jQuery.ajax({
        type: "GET",
        url: "recherchePHP.php",
        data: { 'avec' : document.getElementById("spanAvec").innerHTML, 'sans': document.getElementById("spanSans").innerHTML}
        })
        .done( function(data) {
            console.log(data);
            //var data = jQuery.parseJSON(data1);
            $('#divMain').empty();
            /*data.result = resultat du script recherchePHP.php ie l'intersection des
            recettes avec/sans
            */
           var cpt = 1000;
            for (var toto in data.result) {
                if(cpt>1000){
                    $('#divMain').append("<br>");$('#divMain').append("<br>");
                }
                var recet = data.result[toto];
                var u = 0;
            
                // Crée une nouvelle div avec l'identifiant recet[0] = id_recette
                var newDiv = $('<div>').attr('id', recet[0]);
            
                for (let j = 1; j < recet.length; j++) {
                    var elem = recet[j];
                    if(j==1){   //UNIQUEMENT POUR LE TITRE
                        var p_elem = $('<p>').text(elem).css({
                            'font-weight': 'bold',
                            'font-size': '20px'
                        });
                    }else{
                        var p_elem = $('<p>').text(elem);
                    }
                    newDiv.append(p_elem);
                        //newDiv.append("<br>");
                    u++;
                }
                
            
                // Ajout de l'image
                var picture = recet[1];
                var image = picture.replace(/\s+/g, '_');
                var imagePath = "Photos/" + image + ".jpg";
            
                // Ajouter la nouvelle div à #divMain
                $('#divMain').append(newDiv);
                //Ajout de la ligne de séparation des recettes
                var separateDiv = $('<div>').attr('id', cpt)
                var p_separation = $('<p>').text("----------------------------------------------------------");
                separateDiv.append(p_separation);
                $('#divMain').append(separateDiv);
                cpt++;
            
                // Charge l'image de manière asynchrone en utilisant la fonction définie
                //param ordre id_recette , imagePath ,#id_div, nom recette
                loadImage(recet[0], imagePath,newDiv,recet[1]);
                
                
            }
        })    
        .fail( function() {
            $('#divMain').text("La requête est rejectée")});
}




//AFFICHAGE DE TOUTES LES RECETTES DE BASE (SANS AVOIR CLIQUÉ SUR IUN INGRÉDIENT)
/*
    foreach ($bb as $rec){
        
        if (file_exists("Photos/".$rec[1].".jpg")) {
            echo "<img src=\"Photos/".$rec[1].".jpg\" width=\"150\" height=\"150\">";
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
*/
/* Chargement d'une image en javascript 
    const image = new Image();
    image.src = "image.jpg";
    image.onload = function() {
    document.body.appendChild(image);
    };
*/

/*  Closure fonctionne mais trop compliqué et non maîtrisée pour utilisation


*/
