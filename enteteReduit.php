<?php
    function getNbArticlePanier($idclient){
        global $dbh, $prefix;
        //$nb = $dbh->query("SELECT sum(quantite) FROM {$prefix}_panier")->fetchColumn(); //nombre d'article * quantite dans le panier 
        $nb = $dbh->query("SELECT count(*) FROM {$prefix}_panier WHERE idclient=$idclient")->fetchColumn(); //nombre d'article dans le panier
        return $nb;
    }
    $allarticle = $dbh->query('SELECT * from '.$prefix.'_article ORDER BY idarticle DESC');
    if(isset($_GET['s']) AND !empty($_GET['s'])) {
        $recherche = htmlspecialchars($_GET['s']);
        $allarticle = $dbh->query("SELECT nom FROM ".$prefix."_article WHERE nom = '".$recherche."' ORDER BY idarticle DESC");
    }
    if(isset($_POST["deconnexion"])){
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        unset($_SESSION["idclient"]);
        header("Location: ./index.php");    

    }
    $keywords="";

?>

<link rel="stylesheet" type="text/css" href="style/header.css"/>
<link rel="stylesheet" type="text/css" href="style/footer.css"/>

<div class="haut">      
    <a href="index.php"><img class="logo" src="images/logo/logofiniresizes.png" alt="logo alizon" title="logo alizon" ></a>
    <h1><a href="index.php">ALIZON</a></h1>
    <nav class="navbar">
      <div class="navbar-container container">
          <input type="checkbox" name="" id="">
          <div class="hamburger-lines">
              <span class="line line1"></span>
              <span class="line line2"></span>
              <span class="line line3"></span>
          </div>
          <ul class="menu-items">
            <li><a href="index.php">Home</a></li>
          <?php
            $categories = $dbh->query("SELECT libelle from {$prefix}_categorie");
            echo'<li> <a href="categorie.php"> Catégorie <a></li>';

            foreach($categories as $c){
                echo '<li><a href="categorie.php?categorie='.$c["libelle"].'">'.$c["libelle"].'</a></li>';
            }
            ?>
          </ul>
          
      </div>
  </nav>
    <div class="searchbar">
        <form name="fo" method="GET" action="recherche.php">
            <input class="txtbar" type="text" name="keywords" value="<?php echo $keywords ?>" placeholder="Rechercher un article" required>
            <input class="imgsearch" type="image" src="images/icon/loupeIcon.png" width="25" height="25" alt="Submit" required>
        </form>
        
    </div>
    
    <div style="position: relative;">
        <a href="panier.php">
            <img class="panier" src="images/icon/panierLogo.png"  alt="logo panier" title="logo panier" >
            <?php
                if(isset($_SESSION["idclient"])){
                    if(getNbArticlePanier($_SESSION["idclient"]) != 0){ //affiche le nombre d'article dans le panier uniquement si il y a des articles dans le panier
                        echo '<p class="nombrepanier">'. getNbArticlePanier($_SESSION["idclient"]).'</p>';
                    }
                }else{ //affiche le panier avec la variable de session
                    if(empty($_SESSION["panier"])){
                        $nb_article=0;
                    }
                    else{
                        $nb_article = sizeof($_SESSION["panier"]);
                    }
                    if($nb_article != 0){ 
                        echo '<p class="nombrepanier">'.$nb_article.'</p>';
                    }
                }
            ?>
        </a>
    </div>
    
    <?php
        if(isset($_SESSION["idclient"])){
            echo'<div class="dropconn">';
            echo '<a href="monProfil.php"><img class="iconprof" src="images/icon/monProfil.png" alt="logo monProfil" title="logo monProfil"></a>';
            echo'<div class="dropprofil">
                    <a href="monProfil.php">Mon profil</a>
                    <a href="mesCommandes.php">Mes commandes</a>
                    <form action="" method="POST"> 
                    <input type="hidden" name="deconnexion" value="true"/>
                    <input class="bouton" type="submit" name="deco" value="Déconnexion" ></input>
                    </form>
                    </div>
                </div>';
        }else{
            echo'<div class="dropconn">';
            echo '<a  href="connexion.php"><img class="connexion" src="images/icon/creationCompte.png" alt="logo connexion" title="logo connexion"></a>';
                echo'<div class="dropconnexion">
                    <a href="inscription.php">Inscription</a>
                    <a href="connexion.php">Connexion</a>
                    </div>
                </div>';
        }
    ?>
</div> 
