
<script type="module" src="JS/PdfRender.js"></script>
<script src="JS/zoomAndNavigation.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script type="module">

    import { PdfRender } from './JS/PdfRender.js';

    var isMultipage = false;
    var pageNumber = 1;
    var toggleTools = true;
    var toolsDivWidth = "15%";

    function hideDrawingTools(){

        $("#segment-color").hide();
        $("#segment-width").hide();
        $("#tool-selector").hide();
        $("#save-button").hide();

    }

    function toggleToolsAndButtons(toggle){

        if(toggle){

            $("#zoom-in").hide();
            $("#zoom-out").hide();
            $("#prev-page").hide();
            $("#next-page").hide();
            $("#page-counter-div").hide();
            $("#multipage-button").hide();

            $(".tools-and-buttons").animate({
                "flex-basis": "3%"
            });

            $("#hide-tools-button-icon").removeClass("fa-bars-staggered");
            $("#hide-tools-button-icon").addClass("fa-bars");

        }
        else{

            

            $(".tools-and-buttons").animate({
                "flex-basis": toolsDivWidth
            });

            $("#zoom-in").show();
            $("#zoom-out").show();
            $("#prev-page").show();
            $("#next-page").show();
            $("#page-counter-div").show();
            $("#multipage-button").show();


            $("#hide-tools-button-icon").removeClass("fa-bars");
            $("#hide-tools-button-icon").addClass("fa-bars-staggered");

        }

    }

    async function printPages(pdfObject, pdfCanvasesDivId){

        console.log("during print: "+pageNumber);

        let canvases = $(pdfCanvasesDivId).children();

        for (let [index, currentCanvas] of canvases.toArray().entries()) {

            let htmlCanvas = $(currentCanvas).get(0);  // Ottieni l'elemento DOM

            if((pageNumber-1) < pdfObject.pageMaxNumber){

                $(currentCanvas).show();

                pdfObject.setCanvas(htmlCanvas);  // Imposta il canvas
                await pdfObject.renderPage(pageNumber);  // Attende che la pagina venga renderizzata
            
                pageNumber++;  // Incrementa il numero di pagina
            }
            else{

                $(currentCanvas).hide();

            }
        }

        $('#page-num').val(pageNumber-1);

    }

    async function zoomReadMode(pdfObject, scaleChange, pdfCanvasesDivId, numberOfPagesInView, isZoomIn){

        if (isZoomIn) {

            pdfObject.scale += scaleChange;

        } else {

            pdfObject.scale -= scaleChange;

        }

        pageNumber -= numberOfPagesInView;

        await printPages(pdfObject, pdfCanvasesDivId);

    }

    async function loadContent() {

        <?php echo ('let url = "' . base64_encode($fileUrl) . '";'); ?>

        let vp = null;

        let canvas = document.getElementById("pdf-canvas");

        var pdfRender = new PdfRender(atob(url), 0.7, null);

        let firstSvg;
    
        let svgDictionary;

        let numberOfPagesInView = 1;

    
        await pdfRender.getPdfInfo();

        hideDrawingTools();

        await printPages(pdfRender, "#pdf-canvas-div");

        $("#canvas-div").css("border", "solid 0px black");
        $("#pdf-canvas-div").css("border", "solid 0px black");


        
        //------------ EVENTI -------------

        $("#prev-page").click(async function () {

            if ((pageNumber-1) > numberOfPagesInView) {

                pageNumber -= numberOfPagesInView + 1;

                await printPages(pdfRender, "#pdf-canvas-div");

            }

        });

        $("#next-page").click(async function () {

            if ((pageNumber-1) < pdfRender.pageMaxNumber) {

                await printPages(pdfRender, "#pdf-canvas-div");

            }
        });

    // Zoom In/Out
        $("#zoom-in").click(async function () {

            if (pdfRender.scale < 1.7) {

                await zoomReadMode(pdfRender, 0.1, "#pdf-canvas-div", numberOfPagesInView, true);

            }

            console.log("zoom in: " + pdfRender.scale);

        });

        $("#zoom-out").click(async function () {

            if (pdfRender.scale > 0.7) {

                await zoomReadMode(pdfRender, 0.1, "#pdf-canvas-div", numberOfPagesInView, false);

            }

            console.log("zoom out: " + pdfRender.scale);

        });

        $('#page-num').change(async function (){

            let value = $('#page-num').val();

            if(value <= pdfRender.pageMaxNumber && value > 0){

                pageNumber = parseInt(value);

                await printPages(pdfRender, "#pdf-canvas-div");

                $("#output-label").text("");
                $("#output-label").css("color", "white");

            }
            else{

                $("#output-label").text("Pagina inesistente");
                $("#output-label").css("color", "red");

            }

        });

        $("#multipage-button").click(async function () {

            if(!isMultipage){

                isMultipage = true;

                pageNumber -= numberOfPagesInView;

                $("#multipage-button-icon").removeClass("fa-solid");
                $("#multipage-button-icon").addClass("fa-regular");

                $('#pdf-canvas-div').append('<canvas id="pdf-canvas2"></canvas>');

                await printPages(pdfRender, "#pdf-canvas-div");

                numberOfPagesInView = 2;

            }
            else{

                pageNumber -= numberOfPagesInView;

                isMultipage = false;
                $("#multipage-button-icon").removeClass("fa-regular");
                $("#multipage-button-icon").addClass("fa-solid");
                
                $('#pdf-canvas2').remove();

                await printPages(pdfRender, "#pdf-canvas-div");

                numberOfPagesInView = 1;

            }

        });

        $("#hide-tools-button").click(function () {

            toggleToolsAndButtons(toggleTools);
            toggleTools = !toggleTools;

        });

    }

    $(window).on("load", async function (){

        $("#body-div").hide();

        // Simula il caricamento e poi nasconde il loader
        setTimeout(function () {
            document.querySelector('.loader-container').style.display = 'none';
            document.querySelector('.content').style.display = 'block';
        }, 3000); // 3 secondi di attesa per il caricamento

        await loadContent();

        $("#loading-screen-section").hide();
        $("#body-div").show();

    });
</script>
