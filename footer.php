<div class="footer">

    <!-- Footer -->
    <footer>
        <div class="row" style="margin-top:40px;">
            <div class="col-lg-12">
                <div class="footerdesktop">
                    <div class="col-md-4">
                        <li class="item">
                            <p><h4>Informazioni</h4></p>                       
                            <ul><a href="#">Contattaci</a></ul>
                            <ul><a href="#">Spedizioni</a></ul>
                            <ul><a href="#">Resi</a></ul>
                            <ul><a href="#">Pagamenti</a></ul>
                            <ul><a href="#">Condizioni di vendita</a></ul>
                            <ul><a href="#">Chi siamo</a></ul>
                            <ul><a href="#">Privacy</a></ul>
                        </li>
                    </div>
                    <div class="col-md-4">
                        <li class="item">
                            <p><h4>Il mio account</h4></p>
                            <ul><a href="carrello.php">Ordini</a></ul>
                            <ul><a href="cartacredito.php">Carte di credito</a></ul>
                            <ul><a href="home.php">Informazioni personali</a></ul>

                        </li>
                    </div>
                    <div class="col-md-4">
                        <li class="item">
                            <p><h4>Informazioni negozio</h4></p>
                            <ul>
                                <p>Technology4U s.p.a.</p>
                                <p>via Garibaldi 58/A</p>
                                <p>Pordenone (PN) 33170 Italia</p>
                                <p>Contattaci subito: 123-456789</p>
                                <p>E-mail: <a href="mailto:admin@technology4u.it">admin@technology4u.it</a></p></br></br>
                                <p style="font-style:italic;">Copyright &copy; 2016</p>
                            </ul>

                        </li>                    
                    </div>
                </div>
                
                <div class="footermobile">
                    <p><h4>Technology4U</h4></p>
                    <li class="item">
                        <a href="home.php" title="AccountMobile">
                            Il mio account
                        </a>
                    </li>
                    <li class="item">
                        <a href="#" title="InformazioniMobile">
                            Informazioni                        
                        </a>
                    </li>
                    <li class="item">
                        <a href="#" title="NegozioMobile">
                            Informazioni negozio
                        </a>
                    </li>
                    </br></br>
                    <p style="font-style:italic;">Copyright &copy; 2016</p>

                </div>
                
            </div>
        </div>
    </footer>
    
</div>


<!-- File jQuery -->
<script src="js/jquery.js"></script>

<!-- File JavaScript -->
<script src="js/javascript.js"></script>

</body>

<!--foglio di stile-->
<style type="text/css"><!--
    
    .footer{
        background: black;
        padding: 1px;
        color:gray;
    }
    
    .footer a{
        color: lightgray;
    }
    
    .footer ul{
        margin-left: -25px;
    }
    
    .footer li{
        list-style: none; /*toglie i puntini dell'elenco*/
    }
    

    /*suddivido footer in tre colonne per desktop*/
    @media (min-width:768px){
        .footerdesktop div{
            position:relative;
            float: left;
            margin-left:7%;
            width: 26%;
            width: 26%;
            width: 26%;
            padding: 0 2%;
        }
        
        .footermobile{
            display: none;
        }
    }
    
    /*nascondo questa versione del footer per versione mobile*/
    @media (max-width:767px){
        .footerdesktop div{
            display:none;
        }
        .footermobile {
            text-align: center;
        }
    }
    
    div#carousel-inner {
        width: 800px;
        height: 300px;
        overflow: hidden;
    }

    @media (max-width: 767px){
        .thumbnail{
            max-width:100%;
            height:auto;
        }
    }
    /*nella versione mobile le immagini che scorrono non ci sono*/
    @media (max-width: 480px){
        .carosello{
            display: none;
        }
    }
    /*nella verione desktop le immagini prodotto hanno una grandezza fissa per avere un ordine specifico dei box*/
    /*nella versione mobile le immagini prodotto invece sono riscalate in proporzione alla dimensione dello schermo, come specificato nello style dell'img*/
    @media (min-width: 768px){
        .thumbnail > img{
            height: 150px;
            width: 320px;
        }

    }


    --></style>

</html>

