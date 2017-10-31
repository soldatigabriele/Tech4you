
<!--Navigation (Nagigation include le funzioni per la connessione al db, search e apertura html)-->

<?php
    include_once("navigation.php");
?>

    <!-- Page Content -->
    <div class="homeContainer">

        <div class="row" style="margin-left:15px;">
            
            <div class="col-md-9" style="max-width:920px; margin-top: 12px;">

              <?php
              // nasconde il carosello nelle categorie
              if(!isset($_GET['categoria'])){
                  ?>
                <div class="row carousel-holder">

                    <div class="col-md-12 carosello">
                        <div id="carousel-head" class="carousel slide" data-ride="carousel">
                            
                            <!--PALLINI SOTTO ALLE IMMAGINI CHE SCORRONO -->
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-head" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-head" data-slide-to="1"></li>
                                <li data-target="#carousel-head" data-slide-to="2"></li>
                            </ol>
                            
                            <!-- IMMAGINI CHE SCORRONO -->
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img class="slide-image" src="img/intestation/apple.jpg" alt="Apple" style="max-width:1000px;max-height:400px;"/>
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="img/intestation/samsung.jpg" alt="Samsung" style="max-width:1000px;max-height:400px;"/>
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="img/intestation/smartWatch.jpg" alt="Smart Watch" style="max-width:1000px;max-height:400px;"/>
                                </div>
                            </div>
                            
                        
                            <!--FRECCE SX E DX PER FAR SCORRERE LE IMMAGINI -->
                            <a class="left carousel-control" href="#carousel-head" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-head" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>
                                    <?php }?>

                <!--CONTENUTO DINAMICO DELLA PAGINA-->
                <div class="row">

                    <?php

                        if ($numRighe != 0){
                            while($rs=mysql_fetch_array($result)){ //$result contiene l'esecuzione della query di popolamento della pagina
                    ?>
                                <div class="col-sm-4 col-lg-4 col-md-4">
                                    <div class="thumbnail">

                                        <img src="img/products/<?php echo "$rs[immagine]";?>" alt="Immagine non disponibile" style="max-height=100%; float: central;"/>

                                        <div class="caption">
                                            <!--<h4><a href="#"><?php /*echo"$rs[titolo]";*/?></a></h4></br>-->
                                            <h4><?php echo"<a href=item.php?idProdotto=$rs[idProdotto]>$rs[titolo]</a>"?></h4></br>
                                            <h4 class="pull-right">€<?php echo"$rs[prezzo]";?></h4></br>

                                        </div>

                                        <!--Pulsante aggiungi al carrello-->
                                        <form name="aggiungicarrello" action="carrello.php" target="_top" method="post">
                                            <p style="padding-left:10px;">Quantità:
                                                <select Name="numeroDisponibilita">
                                                    <?php
                                                        $disp=min(5,$rs[numeroDisponibilita]);
                                                        for($n=1; $n<=$disp; $n++){
                                                            echo"<option value=$n>$n</option>";                                        
                                                        }
                                                    ?>
                                                </select>
                                                <input Name="codProdotto" type="hidden" value="<?php echo"$rs[idProdotto]"; ?>" />
                                            </p>
                                            <?php
                                                if ($rs[numeroDisponibilita]>0){
                                            ?>
                                                    <input type="image" src="img/addtocart.png" Value='Aggiungi' Name='Carrello' />
                                            <?php 
                                                }else{
                                            ?>
                                                    <img src="img/addtocartEsaurito.png" style="width:150px; height:47px; margin-bottom:7px;">
                                            <?php    
                                                }
                                            ?>
                                        </form>

                                        <!--Recensioni-->
                                        <div class="ratings">
                                            <p class="pull-right">31 reviews</p>
                                            <p>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star-empty"></span>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                    <?php 
                            }

                        }else{
                            echo "Nessun prodotto trovato.";
                        }

                    ?>
                </div>
        
                <!--Se le pagine totali sono più di 1, allora stampo i link per andare avanti e indietro tra le diverse pagine -->
                <div id="scorrimentopag" style="margin: 15px; text-align:center;">
                    <?php
                        if ($all_pages > 1){
                            //per regolare i link della paginazione                              
                                $post=explode("&pag",$_SERVER['QUERY_STRING']); 
                                $cong = "?" . $post[0] . "&pag=";  


                          if ($pag > 1){
                            echo "<a href=$cong" . ($pag - 1) . ">";
                            echo "Indietro</a>&nbsp; ";
                          } 
                          if ($all_pages > $pag){
                            echo "<a href=$cong" . ($pag + 1) . ">";
                            echo "Avanti</a>";
                          } 
                        }
                        ?>
                </div>
        </div>
    </div>
</div>

    <!-- fine container -->


    <!--Footer e chiusura html + JavaScript e jQuery-->
    <?php
        include_once("footer.php");
    ?>
 