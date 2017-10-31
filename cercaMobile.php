<?php
include_once 'navigation.php'; ?>

<!-- FUNZIONE DI RICERCA -->
<div class="ricercaMobile" id="ricercaMobile">
    <li>
        <a href='#'>
          <form name="search" action="index.php" target="_top" method="get" style="margin: 5px 5px auto auto;">
              
              <input type="image" src="img/search.png" Value='Cerca' Name='Cerca' />
              <input Name="ricerca" style="margin-right:40px; width:270px; border: 0; padding: 0; height: 20px; border-radius: 8px; vertical-align: top; background-color: #D4D4D4;"/>
              

        </a>
                <ul>
                    <li>
                        <select Name="categoria" class="form-control" style="margin: 2px 15px; width: 250px;">
                           <option value=0>Scegli categoria</option>
                           <?php
                           $resultCatMenu=mysql_query($MyCategoria);
                           while($rsCatMenu=mysql_fetch_array($resultCatMenu)){
                               echo"<option value=$rsCatMenu[idCategoria]>$rsCatMenu[nome]</option>";
                           }
                           ?>
                        </select>
                     </li>
                     <li>
                       <select Name="marca" class="form-control" style="margin: 2px 15px; width: 250px;">
                           <option value=0>Scegli marca</option>
                           <?php
                           $resultMarMenu=mysql_query($MyMarca);
                           while($rsMarMenu=mysql_fetch_array($resultMarMenu)){
                               echo"<option value=$rsMarMenu[idMarca]>$rsMarMenu[nome]</option>";
                           }
                           ?>                                        
                        </select>
                     </li>
                     <li>
                        <select Name="prezzo" class="form-control" style="margin: 2px 15px; width: 250px;">
                            <option value=0>Scegli fascia di prezzo</option>
                            <option value=1>fino a 20.00</option>
                            <option value=2>da 20.01 a 50.00</option>
                            <option value=3>da 50.01 a 100.00</option>
                            <option value=4>da 100.01 a 500.00</option>
                            <option value=5>più di 500.00</option>
                        </select>
                     </li>
                </ul>    
            </form>
    </li>
</div>
<!--FINE FUNZIONE DI RICERCA-->

<!--Gestione posizione barra ricerca, permette inoltre di visualizzare la tendina solo quando ci si passa sopra col mouse --> 
<style type="text/css"><!--
    
    @media (max-width: 991px){
        div#ricercaMobile li ul {display: none}
        div#ricercaMobile li {list-style: none;}
        div#ricercaMobile ul {list-style-type:none;}

        div#ricercaMobile li:hover ul {
            display: block;
            position: absolute;
            z-index:1;
            width:285px; /*lunghezza riquadro a scomparsa*/
            padding: 0;
            margin: 0 0 1 -1px;
            border:2px solid gray;
            border-radius: 4px; /*arrotonda gli angoli della tendina*/
            background: #D4D4D4;
        }
        div#ricercaMobile {

            /*float:center;*/

            padding-top: 50px;
            padding-bottom: 400px;

            width: 250px;
            height: 100px;
            margin: 0 auto;
            /*margin-right: 8px;*/
        }
    
    }
    
    
    @media (min-width: 992px){
        .ricercaMobile{
            display: none;
        }
    }
--></style>

<?php include_once("footer.php");
?>