<?php


    require_once 'navigation.php';


    $user = new User();
    if($user->isLoggedIn()) {
    ?>
    <div class="homeContainer" style="margin-bottom:1%; height:100%;">
        <div class="col-md-8 homeFrame">

            <div class="clearfix"></div>
            <div class="col-md-6" >

                <!-- il nome dell'utente viene ritornato tramite la funzione escape, per prevenire attachi di tipo cross site scripting  -->
                <div class="col-md-12 spacing">
                    <h3>Benvenuto, <a href="profile.php?username=<?php echo escape($user->data()->username);?>"><?php echo escape($user->data()->username); ?></a>! </h3>
                </div>
                    <div class="clearfix"></div><br>
                <?php
                if(!$user->activated()){
                    echo '
                    <div class="col-md-12 controlPanel inactive">
                        Attiva il tuo account
                        <div class="col-md-12 controlPanel">
                            <div class="col-md-2 panelImages"><a href="logout.php"><img src="img/logout.png" height="18px" ></div>
                            <div class="col-md-10 listSpacing">Log out</a></div>
                        </div>
                    </div>
                ';
                }else {
                ?>
                <div class="col-md-12 controlPanel">
                    <div class="col-md-2 panelImages" ><a href="update.php"><img src="img/update.png" height="20px" ></div>
                    <div class="col-md-10 listSpacing" >Aggiorna il profilo</a></div>
                </div>
                <div class="col-md-12 controlPanel" >
                    <div class="col-md-2 panelImages" ><a href="changepassword.php"><img src="img/password.png" height="20px" ></div>
                    <div class="col-md-10 listSpacing">Cambia Password</a></div>
                </div>
                <div class="col-md-12 controlPanel">
                    <div class="col-md-2 panelImages" ><a href="upload.php"><img src="img/upload.png" height="19px" ></div>
                    <div class="col-md-10 listSpacing">Upload CI e CF</a></div>
                </div>
                <div class="col-md-12 controlPanel">
                    <div class="col-md-2 panelImages"><a href="cartacredito.php"><img src="img/carta.png" height="18px" ></div>
                    <div class="col-md-10 listSpacing">Carta di credito</a></div>
                </div>
                <div class="col-md-12 controlPanel">
                    <div class="col-md-2 panelImages"><a href="logout.php"><img src="img/logout.png" height="18px" ></div>
                    <div class="col-md-10 listSpacing">Log out</a></div>
                </div>
    <?php
            };
    ?>
        </div>
<?php

    } else {
        include 'inc/paginaRiservata.php';
    }
if($_GET["password"]=="changed") {
    echo '<script type="text/javascript">alert("password modificata correttamente"); </script>';
}
?>

    </div>
</div>

<?php include_once 'footer.php'; ?>


