

    <!-- Navigation -->
    <?php
        include_once("navigation.php");
    ?>
    
    <!-- Page Content -->
    <div class="homeContainer" id="homeContainer">

        <div class="row" style="margin-left:3px;">

            <div class="col-md-9">

                <div class="thumbnail">
                    <?php $rs=mysql_fetch_array($result);?>
                    <img class="img-responsive" src="img/products/<?php echo "$rs[immagine]";?>" alt="Immagine non disponibile" style="height: 200px; weight: auto; max-height=100%; float: central;"/>
                    <div class="caption-full" style="margin-top:30px;">
                        <h4 class="pull-right">€<?php echo"$rs[prezzo]";?></h4>
                        <h4><?php echo"<a href=item.php?idProdotto=$rs[idProdotto]>$rs[titolo]</a>"?></h4></br>
                        <p>Modello: <?php echo"$rs[modello]";?></p>
                        <p><?php echo"$rs[descrizione]";?></p>
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
                
                
                
                    <div class="ratings" >
                        <p class="pull-right">3 reviews</p>
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            4.0 stars
                        </p>
                    </div>
                </div>

                <div class="well">

                    <div class="text-right">
                        <a class="btn btn-success">Lascia una recensione</a>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Anonymous
                            <span class="pull-right">10 days ago</span>
                            <p>This product was great in terms of quality. I would definitely buy another!</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Anonymous
                            <span class="pull-right">12 days ago</span>
                            <p>I've alredy ordered another one!</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Anonymous
                            <span class="pull-right">15 days ago</span>
                            <p>I've seen some better than this, but not at this price. I definitely recommend this item.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    
    <!--Footer-->
    <?php
        include_once("footer.php");
    ?>


    <style type="text/css"><!--
        @media (min-width: 992px){
            div#homeContainer{
                padding-top:50px;
            }
        }

        @media (max-width: 991px){
            div#homeContainer{
                padding-top:15px:
            }
        }

    --></style>
