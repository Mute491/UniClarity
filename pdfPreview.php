
<?php

$_POST["fileUrl"] = "https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf";

$_POST["numPages"] = "20";

if (isset($_POST["fileUrl"]) && isset($_POST["numPages"])) {

    $fileUrl = $_POST["fileUrl"];

    $numPages = $_POST["numPages"];

} else {

    header("Location: accessNegated.html");

    // Termina l'esecuzione dello script
    exit();

}

?>


<!DOCTYPE html>
<html lang="it">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="Css/stylePdfPreviewer.css">
        <link rel="stylesheet" herf="Css/styleLoadingPopUp.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <title>Preview pdf</title>

        <script type="module" src="JS/PdfRender.js"></script>

        <script type="module">

            import { PdfRender } from './JS/PdfRender.js';

            async function zoomPages(pdfRender) {
                
                let scale = $('#zoom').val();
                let pages = $('#pages .page'); // Seleziona tutte le pagine

                for (let i = 0; i < pages.length; i++) {
                    console.log(i + 1);

                    // Seleziona il canvas all'interno della div corrente
                    let canvas = $(pages[i]).find('canvas');
                    // Converte il canvas in un oggetto JavaScript nativo
                    let canvasElement = canvas.get(0);

                    pdfRender.setCanvas(canvasElement);
                    pdfRender.setScale(scale);

                    // Aspetta il rendering della pagina prima di passare alla successiva
                    await pdfRender.renderPage(i + 1);
                }
            }

            async function addNewPages(pdfRender){

                <?php

                    if($numPages != "all"){

                        echo("let numberOfCanvas = ".$_POST["numPages"].";");
                        echo("for (let i = 0; i < numberOfCanvas && i < pdfRender.pageMaxNumber; i++) {");

                    }
                    else{

                        echo("for (let i = 0; i < pdfRender.pageMaxNumber; i++) {");

                    }
                    
                    
                ?>
            
                    // Crea un elemento <canvas> senza attributi
                    let canvas = $('<canvas></canvas>');

                    // Crea una div con classe "page" e inserisci il canvas all'interno
                    let pageDiv = $('<div></div>')
                        .addClass('page') // Aggiungi la classe "page"
                        .append(canvas); // Appendi il canvas
                    
                    pdfRender.setCanvas(canvas.get(0));
                    await pdfRender.renderPage(i+1);

                    // Appendi la div con il canvas al contenitore
                    $('#pages').append(pageDiv);
                }

            }

            async function loadContent(){

                <?php echo ('let url = "' . base64_encode($fileUrl) . '";'); ?>

                var pdfRender = new PdfRender(atob(url), 0.7, null);
                await pdfRender.getPdfInfo();

                $("#zoom").change(async function () {
                    await zoomPages(pdfRender);
                });

                await addNewPages(pdfRender);

            }

            $(window).on("load", async function (){

                $("#content").hide();

                // Simula il caricamento e poi nasconde il loader
                setTimeout(function () {
                    document.querySelector('.loader-container').style.display = 'none';
                    document.querySelector('.content').style.display = 'block';
                }, 3000); // 3 secondi di attesa per il caricamento

                await loadContent();

                $("#loading-screen-section").hide();
                $("#content").show();

            });

        </script>

    </head>
    <body>
    
        <section id="loading-screen-section">

            <div id="loading-screen">

                <!-- Schermata di caricamento -->
                <div class="loader-container">
                    <div class="logo"></div>
                </div>

                <!-- Contenuto del sito -->
                <div class="content" style="display:none;">
                    <h1>Caricamento del file</h1>
                    <p>Attendi...</p>
                </div>

            </div>

        </section>
    
        <section id="content">

            <section id="pages">
        
            </section>

            <section id="tools">

                <div id="zoom-div">
                    
                    <span>Zoom:</span>
                    <p> 
                        <i class="fa-solid fa-minus"></i>
                        <input type="range" name="zoom" id="zoom" min="0.7" max="1.7" step="0.1" value="0.7">
                        <i class="fa-solid fa-plus"></i>
                    </p>
                    

                </div>

            </section>

        </section>

    </body>

</html>