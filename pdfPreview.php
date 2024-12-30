
<?php

$_POST["fileUrl"] = "https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf";
if (isset($_POST["fileUrl"])) {

    $fileUrl = $_POST["fileUrl"];

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

            async function zoomPages(pdfRender, isTwoPages){

                let canvasPage1 = document.getElementById("page-1");
                let canvasPage2 = document.getElementById("page-2");
                
                pdfRender.setCanvas(canvasPage1);
                var value = $(this).val();  // Ottieni il valore dell'input range
                pdfRender.setScale(value);
                await pdfRender.renderPage(1);


                if(isTwoPages){
                    pdfRender.setCanvas(canvasPage2);
                    await pdfRender.renderPage(2);
                }

            }


            async function loadContent(){

                <?php echo ('let url = "' . base64_encode($fileUrl) . '";'); ?>

                let canvasPage1 = document.getElementById("page-1");
                let canvasPage2 = document.getElementById("page-2");

                var pdfRender = new PdfRender(atob(url), 0.7, canvasPage1);
                await pdfRender.getPdfInfo();

                await pdfRender.renderPage(1);

                if(pdfRender.pageMaxNumber > 1){

                    pdfRender.setCanvas(canvasPage2);
                    await pdfRender.renderPage(2);

                    $('#zoom').on('input', zoomPages(pdfRender, true));
                }
                else{

                    $('#zoom').on('input', zoomPages(pdfRender, false));
                }

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

                <div class="page">

                    <canvas id="page-1"></canvas>

                </div>

                <div class="page">

                    <canvas id="page-2"></canvas>

                </div>
        
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