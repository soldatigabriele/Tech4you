<?php

//Includo tutti i file necessari
require_once 'inc/init.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <!-- <meta name="author" content="ScappinDebora&SoldatiGabriele">-->

    <title>Technology4U-Home</title>

    <!-- Normalize CSS -->
    <link href="css/normalize.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/technology4u-homepage.css" rel="stylesheet">
    
</head>



<body>


<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">


    <div class="testata">
        <!-- Brand and toggle get grouped for better mobile display -->
            <!--Logo-->
        <a href="index.php"><img src="img/technology4u.png" alt="Logo" style="max-width:60px; max-height:60px; padding-top: 8px; padding-bottom: 2px; margin-left:5px;margin-right:-10px;"/></a>


        <div class="navbar-header" style="margin-top:2px; margin-bottom:3px;">

            <!-- Disegno del bottone della modalità mobile -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse" style="margin-left:10px; margin-top:13px;">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


            <a class="navbar-brand" href="index.php" style="margin-left:5px; margin-top:5px; color:#BED63A;">Technology4U</a>

        </div>
            <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav" style="margin-top:8px;">

                <!--Riempimento menu orizzontale-->
                <?php
                   while($rsCategoria=mysql_fetch_array($resultCategoria)){
                       //modifico stile categoria selezionata
                       if($_GET['categoria']==$rsCategoria['idCategoria']){
                           $style = 'text-decoration:underline;font-weight:bold;';
                       }else{
                           $style = '';
                       }
                       echo '<li ><a style="'.$style.'" href="index.php?categoria='.$rsCategoria['idCategoria'].'">'.$rsCategoria['nome'].'</a></li>';
                   }
                ?>
                


            </ul>
        </div>


        <div id="headContLogin">

            <?php
                $user = new User();
                if(!$user->isLoggedIn()){ //se non è stato fatto il login
//                if(!isset($_SESSION['IDutente'])){ //se non è stato fatto il login
            ?>

                    <a href="home.php" class="accedidesktop"> <img src='img/accedidesktop.png' style="margin-top:12px; float:right;"></a>
                    <a href="home.php" class="accedimobile"> <img src='img/accedimobile.png' style="max-width:55px; max-height:55px; padding-top: 17px; padding-bottom: 6px; float:right;margin-right:-35px"></a>


            <?php
                }else{
                    echo "<p style='text-align: right; color:#D4D4D4;margin-top:5px;margin-right:-35px;'>Salve  <a href=home.php style='color:#FFB300'><b>".escape($user->data()->username)."</a><br><a href=logout.php style='color:#BED63A; font-style:italic; font-weight:normal;'>Logout</a>";
                    echo"<a href='carrello.php' title='Vai al tuo carrello' style='margin-left:5px;'><img src='img/logocarrello.png' height='25px'  /></a></b></p>"; //se sono collegato
                    /*if($_SESSION['admin']==1){
                        echo "<a href='panadmin.php' title='Vai al pannello amministrazione'><img src='img/admin.png' height='25px'  /></a></b>";
                    }*/

                }
            ?>

        </div>

        <!--PULSANTE CERCA IN BARRA DI NAVIGAZIONE PER VERSIONE MOBILE-->
        <div class="headContSearch" id="headContSearch">
            <a href="cercaMobile.php"> <img src='img/searchmobile.png' style="max-width:53px; max-height:53px; padding-top: 20px; padding-bottom: 6px; padding-right: 10px;float:right;"></a>
        </div>

    </div>
</nav>

<!--MENù VERTICALE A LATO COLLEGATO AL DB, TABELLA CATEGORIE -->
<div class="col-md-2">
    <p class="lead" style="padding-top:15px;">Categorie</p>
    <div id="list-group" class="list-group">

           <?php
               $resultCatMenu=mysql_query($MyCategoria);
               while($rsCatMenu=mysql_fetch_array($resultCatMenu)){
                   //modifico lo stile della categoria selezionata
                   if($_GET['categoria']==$rsCatMenu['idCategoria']){
                       $style = 'font-weight:bold; background-color:#D4D4D4;';
                   }else{
                       $style = '';
                   }
                   echo '<li><a class="list-group-item" style="'.$style.'" href="index.php?categoria='.$rsCatMenu[idCategoria].'">'.$rsCatMenu[nome].'</a></li>';
               }
           ?>

    </div>
</div>

<!--Search-->
<?php
    include_once("search.php");
?>


<!--CSS-->
<style type="text/css"><!--

    /*Gestione posizione menù (necessario per non fare andare a capo il form login)*/

    div#navbar-collapse {

        float:left;
    }
    
    /*Gestione menù verticale (toglie l'elenco puntato)*/
    div#list-group li a.list-group-item:active{
         font-weight: bold !impotant;
         background-color: darkgray;
     }
    div#list-group li {list-style: none;}

    /*nella versione mobile (smartphone e tablet) il menù verticale sparisce*/
    @media (max-width: 991px){
        .col-md-2{
            display: none;
        }
    }
    
    
    /*Gestione posizione form login*/
    div#headContLogin {
        float:right;
        margin-right: 50px;
    }

    @media (max-width: 991px){
        .accedidesktop{
            display: none;
        }

    }
    @media (min-width: 992px){

        .accedimobile{
            display: none;
        }

    }
    @media (min-width: 992px){
        .headContSearch{
            display: none;
        }
    }
    
    /*Gestione grafica della testata
    float:left -> Fa in modo che dopo il menù a tendina Categoria il resto del menù non vada a capo, ma continui sulla stessa riga*/
     @media (min-width:768px){
         .testata>a>img{
             float: left; /*sposto a sx il logo*/
             margin-left:10px;
        }
     }
     @media(max-width:767px){
         /*centro il logo*/
         .testata>a>img{
            position:absolute;
            left:42%;
            /*margin-left:-32px;*/
         }
     }

     .navbar-header{float:left;}
     /*inserimento di un nuovo font da cartella nel server*/
     @font-face {
             font-family: 'Centabel';
             src: url('../fonts/centabel.ttf');
     }

     .navbar-brand{
         /*font-family: 'Helvetica';*/
         font-family: 'centabel';
     /*,'Centabel';*/
         font-size:25px;
     }


    /*gestione contorno scritta comparsa tendina (es. riga sotto nome statico)*/
    .tendinalogindesktop {
        margin: 0;
        padding: 0;
        height: 2em;
        border-width: 0 0 1px 0;
    }

     /*gestione elementi interni tendina*/
    .tendinalogindesktop li {
        margin: 0;
        padding: 5;
        float: left;
        height: 2em;
        width: 10em;
        display: inline;
        list-style-type: none;
        position: relative;
        line-height: 2em;
    }
    /*gestione scritta comparsa tendina e scritte tendina*/
    .tendinalogindesktop li a:link,
    .tendinalogindesktop li a:visited {
        margin: 0;
        padding: 0;
        text-decoration: none;
        color: gray;
        background: transparent;
        float: left;
        width: 10em;
        text-align: center;
    }
    /*gestisco quando sono sopra col mouse*/
    .tendinalogindesktop li a:hover {
        background: transparent;
        color: #BED63A;
    }
    /*gestione scomparsa tendina*/
    .tendinalogindesktop li ul {
        display: none;
        margin: 0;
        padding: 0;
        background: #f1f1f1;
        color: gray; /*colore linee interne divisori tendina*/
        border: 1px solid;
        width: 10em;
    }


    .tendinalogindesktop li ul li {
        list-style: none;
        margin-left: 0;
        padding-left: 0;
        border-bottom: 1px solid;
    }

    /*gestione interno tendina*/
    .tendinalogindesktop li ul li a:link,
    .tendinalogindesktop li ul li a:hover {
        display: block;
        margin: 0;
        padding: 0;
        text-align: center;
        background: transparent;
        color: #000;
        width: 10em;
    }
    /*gestione colori scritte e sfondo selezionato in tendina*/
    .tendinalogindesktop li ul li a:hover {
        background: black; /*sfondo elemento selezionato in tendina*/
        color: #BED63A;
    }

    /*gestione tendina, quando sono sopra col mouse sta bloccata*/
    .tendinalogindesktop li:hover ul {
        display: block;
        position: absolute;
        top: 2em;
        left: 0;
        z-index:1;
        width:10em;
        padding: 0;
        margin: 0 0 0 30px;
        border:1px solid gray;
        border-radius: 4px; /*arrotonda gli angoli della tendina*/
        background: #D4D4D4; /*colore tendina*/
    }
    .tendinalogindesktop {
        float:right;
    }

     @media(max-width: 991px){
        .navbar-brand{display: none;}
     }
 --></style>
    


