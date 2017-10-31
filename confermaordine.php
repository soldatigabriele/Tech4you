<?php

//Navigation (Nagigation include le funzioni per la connessione al db, search e apertura html)
    include_once("navigation.php");
    include_once('inc/init.php');


    //QUERY CARRELLO
    $QueryCarrello="SELECT p.idProdotto, c.quantita, p.titolo, p.immagine, p.prezzo FROM prodotto p, carrello c WHERE p.idProdotto=c.fkProdotto AND ordine=0 AND fkUtente=$_SESSION[IDutente]";
    $resultCarrello=mysql_query($QueryCarrello);

    //QUERY ORDINI
    $QueryOrdini="SELECT o.idOrdine, o.data, o.fkCartaCredito, c.fkUtente FROM ordini o, carrello c WHERE c.fkUtente=$_SESSION[IDutente] AND o.idOrdine=c.fkOrdine";
    $resultOrdini=mysql_query($QueryOrdini);

    //QUERY METODI PAGAMENTO
//    $QueryMPagamento="SELECT idCircuito, tipo FROM circuito";
//    $resultMPagamento=mysql_query($QueryMPagamento);

    //$indirizzo= str_replace("'", "\'", $_POST['indirizzo']);
      $indirizzo=escape($_POST['indirizzo']);


    ?>



        <div class="homeContainer">
              
            <div class="col-md-8">
                <?php
                    $idUtente=$_SESSION['IDutente'];
                    if( $_POST['indirizzo']!='' && isset($_POST['Circuito'])  && isset($_POST['Procedi']) ){

                        if($_POST['codicepromo']!=''){ //Se è stato inserito un codice promozionale
                            //QUERY CODICI PROMOZIONALI
                            $codiciPromozionali="SELECT idCodicePromozionale, codice FROM codicepromozionale";
                            $resultCodiciPromo=mysql_query($codiciPromozionali);
                            
                            $trovato=false;
                            //if(count($rsCodiciPromo)!=0){ //Se sono disponibili codici promozionali
                                while ($row = mysql_fetch_assoc($resultCodiciPromo)) {
                                    if($row['codice']==$_POST['codicepromo']){
                                        $trovato=true; //il codice promozionale inserito esiste
                                        $idCodicePromo=$row['idCodicePromozionale']; //salvo l'id del codice promozionale inserito dall'utente
                                    }

                                }
                            
                                //Controllo che esista nella tabella codicepromozionale
                                if($trovato==true){
                                    
                                    //QUERY CODICI PROMOZIONALI UTILIZZATI
                                    $codiciPromozionaliUtilizzati="SELECT fkCodicePromozionale, fkUtente FROM utilizzato_codicepromozionale_utente WHERE fkUtente=$idUtente";

                                    $resultCodiciPromoUtilizzati=mysql_query($codiciPromozionaliUtilizzati);

                                    $ok=true;
                                    while ($row = mysql_fetch_assoc($resultCodiciPromoUtilizzati)) {
                                        if($row['fkCodicePromozionale']==$idCodicePromo){
                                            $ok=false; //il codice promozionale è già stato utilizzato
                                        }
                                    }
                                    
                                    //Controllo che non sia già stato utilizzato nella tabella utilizzato_codicepromozionale_utente (la coppia idUtente idCodicePromozionale non deve esistere)

                                    if($ok==true){
                                        
                                        $date=date('Y-m-d H:i:s');
                                        //Aggiungo l'ordine con codice promozionale
                                        $AggiungiOrdine="INSERT INTO `ordine` (`idOrdine`,`data`, `fkCartaCredito`, `indirizzo`, `fkStatoConsegna`, fkUtente, fkCodicePromozionale) VALUES ('0', '$date', '$_POST[Circuito]', '$indirizzo','1',$_SESSION[IDutente],'$idCodicePromo')"; /*BISOGNEREBBE FAR SCEGLIERE TRA GLI INDIRIZZI DELL'UTENTE*/
                                        mysql_query($AggiungiOrdine);
                                        
                                        /*$ordine = DB::getinstance();
                                        $ordine->insert('ordine', ['idOrdine'=>0, 'data'=>$date, 'fkCartaCredito'=>$_POST[Circuito],'indirizzo'=>$indirizzo,'fkStatoConsegna'=>1,'fkUtente'=>$_SESSION[IDutente],'fkCodicePromozionale'=>$idCodicePromo]);*/
                                        
                                        //Aggiungo utilizzo codice promozionale alla tabella utilizzato_codicepromozionale_utente
                                        $AggiungiUtilizzo="INSERT INTO `utilizzato_codicepromozionale_utente` (fkUtente, fkCodicePromozionale) VALUES ($_SESSION[IDutente],'$idCodicePromo')";
                                        mysql_query($AggiungiUtilizzo);
                                        
                                        $numordine = mysql_insert_id();
                                        //trasformo carrello in ordine
                                        $ModOrdiniCarrello="UPDATE carrello SET ordine=$numordine WHERE ordine=0 AND fkUtente=$_SESSION[IDutente]";
                                        mysql_query($ModOrdiniCarrello);

                                        echo "Un nuovo ordine è stato preso in carico dal nostro sistema, nel caso ci siano problemi vi preghiamo di contattare l'azienda.";


                                    }else{
                                        echo "Il codice promozionale inserito è già stato utilizzato.";
                                    }

                                }else{
                                    echo "Il codice promozionale inserito non esiste.";
                                }
                            /*}else{
                                echo "Il codice promozionale inserito non esiste.2";
                            }*/


                            
                        }else{
                            $date=date('Y-m-d H:i:s');
                            //Aggiungo l'ordine senza codice promozionale
                            $AggiungiOrdine="INSERT INTO `ordine` (`idOrdine`,`data`, `fkCartaCredito`, `indirizzo`, `fkStatoConsegna`, fkUtente) VALUES ('0', '$date', '$_POST[Circuito]', '$indirizzo','1',$_SESSION[IDutente])"; /*BISOGNEREBBE FAR SCEGLIERE TRA GLI INDIRIZZI DELL'UTENTE*/
                            mysql_query($AggiungiOrdine);
                            /*
                            $ordine = DB::getinstance();
                            $ordine->insert('ordine', ['idOrdine'=>0, 'data'=>$date, 'fkCartaCredito'=>$_POST[Circuito],'indirizzo'=>$indirizzo,'fkStatoConsegna'=>1,'fkUtente'=>$_SESSION[IDutente]]);*/
                            
                            $numordine = mysql_insert_id();
                            //trasformo carrello in ordine
                            $ModOrdiniCarrello="UPDATE carrello SET ordine=$numordine WHERE ordine=0 AND fkUtente=$_SESSION[IDutente]";
                            mysql_query($ModOrdiniCarrello);

                            echo "Un nuovo ordine è stato preso in carico dal nostro sistema, nel caso ci siano problemi vi preghiamo di contattare l'azienda.";

                        }
                           

                        
                    }else{ 
                        if ($_POST['indirizzo']=='' && isset($_POST['Procedi'])){
                            $error=true;
                        }
                        
                    ?>
<!--                        <form name="datipersonali" action="confermaordine.php" target="_top" method="post">                    -->
<!--                            Indirizzo per la spedizione: *<input name="indirizzo" type="text" maxlength="30" /><br />-->
<!--                            Metodo di pagamento: *<select name="Circuito">-->
<!--                                --><?php
//                                while($rsMPagamento=  mysql_fetch_array($resultMPagamento)){
//                                echo "<option value='$rsMPagamento[idCircuito]'>$rsMPagamento[tipo]</option>";
//                                } ?><!--        -->
<!--                            </select> <br />-->
<!--                            <input type="submit" src="img/carrello.png" Value='Procedi' Name='Procedi' />-->
<!--                        </form>-->
                <div class="col-md-8">
                    <form class="form-group" name="datipersonali" action="" target="_top" method="POST">
                        <div class="col-md-8">
                            Seleziona Carta di Credito:
                            <select class="form-control" name="Circuito" style="float:left;">

                                <?php
                                    $key = hash('md5', $user->data()->password);
                                    $crypt = new Encryption($key);
                                    $cc = DB::getInstance();
                                    $valori = $cc->get('cartacredito', ['fkUtente', '=', $user->data()->idUtente]);
                                    foreach ($valori->results() as $valore) {
                                        echo '<option value="'.$valore->idCartaCredito.'"> **** **** **** '. substr($crypt->decrypt($valore->numeroCarta), 12, 16) .'</option>';
                                    } 
                                ?>

                                ';
                            }
                            ?>
                            </select>
                        </div>
                        
                        <div class="clearfix"></div><br/> <!--A capo + spaziatura-->
                        
                        <div class="col-md-8 <?php if ($error){echo 'has-error';} ?>">
                            Indirizzo per la spedizione: *
                            <input class="form-control" name="indirizzo" type="text" maxlength="70" <?php if($error){echo 'placeholder="required"';}?> />
                        </div>
                        
                        <div class="clearfix"></div><br/> <!--A capo + spaziatura-->
                        
                        <div class="col-md-8">
                            Codice Promozionale: 
                            <input class="form-control" name="codicepromo" type="text" maxlength="10" />
                        </div>
                        
                        <div class="clearfix"></div><br/> <!--A capo + spaziatura-->
                        
                        <div class="col-md-8">
                            <input class="btn btn-success" type="submit" src="img/carrello.png" value="Procedi" name="Procedi" />
                        </div>
                    </form>
                </div>

                  <?php } ?>            
            </div>
        </div>
    <!-- fine container -->


    <!--Footer e chiusura html + JavaScript e jQuery-->
    <?php
        include_once("footer.php");
    ?>