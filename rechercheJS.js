
/*Ajoute un ingrédient à la liste des ingredients "AVEC"*/
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
/*Ajoute un ingrédient à la liste des ingredients "SANS"*/
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
/*REMISE À 0 DES LISTES AVEC/SANS D'INGREDIENTS*/
function reinitialiserIngredients(){
    var spanA = document.getElementById("spanAvec");
    var spanB = document.getElementById("spanSans");
        spanA.innerHTML="";
        spanB.innerHTML="";
}
/* 	AJOUT DE L'IMAGE D'UNE RECETTE ET DE SON BOUTON
 !! ATTENTION : CETTE FONCTION EST ESSENTIELLE
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
     //addClass pour pouvoir génerer le css
     var button2 = $('<button>').addClass('buttonComposition').text('Composition');
     button2.on('click', function() {
        composition(id,name);    //Définie dans le fichier rechercher.php 
     });
     newDiv.append(button2);
     newDiv.append("<br>");
}


/*Récupère le tableau ARRAYRESULT DU FICHIER recherchePHP contenant les recettes contenant et ne contenant pas les ingredients AVEC/SANS
AINSI QUE LEUR DEGRÉ DE SATISFACTION (correspondant au nombre d'ingrédient AVEC que la recette satisfait (contient)*/
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
           //Sert à ne pas executé la première fois le premier if dans la boucle for pourquoi 10000 et pas 0 ? J'ai oublié mais sûrement un au cas ou
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
                    var p_elem;
                    if(j==1){   //UNIQUEMENT POUR LE TITRE
                        p_elem = $('<p>').text(elem).css({
                            'font-weight': 'bold',
                            'font-size': '20px'
                        });
                    }else{
                        var texxt="";

                        if (j==3) {     //Degré de satisfaction d'une recette
                            texxt= 'SATISFACTION : ';
                            p_elem = $('<p>').text(texxt+elem).css({
		                    'font-weight': 'bold',
		                    'font-size': '18px' ,
		                    'color':'blue'
		                });
		        }else{	//PREPARATION ou autre données basique
		        p_elem = $('<p>').text(texxt+elem);
		        }
                        
                        
                    }
                    newDiv.append(p_elem);

                    u++;
                }
                
            
                // Ajout de l'image
                var picture = recet[1];
                var image = picture.replace(/\s+/g, '_');
                var imagePath = "Photos/" + image + ".jpg";
            
                // Ajouter la nouvelle div à #divMain (contenant principal)
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




