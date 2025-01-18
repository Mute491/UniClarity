
<script type="module" src="JS/PdfRender.js"></script>
<script src="JS/drawing.js"></script>
<script src="JS/zoomAndNavigation.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script type="module">

    import { PdfRender } from './JS/PdfRender.js';

    async function loadContent() {

        <?php echo ('let url = "' . base64_encode($fileUrl) . '";'); ?>

        let vp = null;
        let pageNumber = 1;

        let canvas = document.getElementById("pdf-canvas");

        var pdfRender = new PdfRender(atob(url), 0.7, canvas);

        let firstSvg;
    
        let svgDictionary;
    
        await pdfRender.getPdfInfo();

        svgDictionary = generateSvgDictionary(pdfRender.pageMaxNumber);

        <?php

        if ($_POST["svgData"] != "{}") {

            $inputParameter = "[";
            $len = count($svgArray) - 1;

            foreach ($svgArray as $key => $svgValue) {

                $inputParameter .= "'$svgValue'";

                if ($key < $len) {

                    $inputParameter .= ", ";

                }

            }
            $inputParameter .= "]";

            echo ("svgDictionary = addExistingSvg(" . $inputParameter . ", svgDictionary);");
        }

        ?>

        await pdfRender.renderPage(pageNumber);

        vp = pdfRender.viewport;

        $("#canvas-div").css({
            "height": vp.height + "px",
            "width": vp.width + "px"
        });

        $("#pdf-canvas-div").css({
            "height": vp.height + "px",
            "width": vp.width + "px"
        });


        $('#page-num').val(pageNumber);

        firstSvg = $(svgDictionary["svgN"+(pageNumber-1)]);

        // firstSvg.css({
        //     width: $("#canvas-div").width(),
        //     height: $("#canvas-div").height(),
        // });

        $("#draw-svg-div").append(firstSvg);

        $("#multipage-button").hide();

        caricaEventi();

        //------------ EVENTI -------------

        $("#prev-page").click(async function () {

            let jqueryElement;

            if (pageNumber > 1) {

                pageNumber--;

                jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                await changePage(pageNumber, jqueryElement, pdfRender);

                $('#page-num').val(pageNumber);

            }
        });

        $("#next-page").click(async function () {

            let jqueryElement;

            if (pageNumber < pdfRender.pageMaxNumber) {

                pageNumber++;

                jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                await changePage(pageNumber, jqueryElement, pdfRender);

                $('#page-num').val(pageNumber);

            }
        });

    // Zoom In/Out
        $("#zoom-in").click(async function () {

            if (pdfRender.scale < 1.7) {

                await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), true);

            }

            console.log("zoom in: " + pdfRender.scale);

        });

        $("#zoom-out").click(async function () {

            if (pdfRender.scale > 0.7) {

                await zoom(pdfRender, pageNumber, 0.1, $("#svgN"+(pageNumber-1)), false);

            }
            console.log("zoom out: " + pdfRender.scale);

        });

        $("#save-button").click(function () {

            <?php echo ("saveSvg(" . $acquistiId . ", svgDictionary);"); ?>

        });

        $('#page-num').change(async function (){

            let jqueryElement;

            let value = $('#page-num').val();

            if(value <= pdfRender.pageMaxNumber && value > 0){

                pageNumber = parseInt(value);

                jqueryElement = $(svgDictionary["svgN"+(pageNumber-1)]);

                await changePage(pageNumber, jqueryElement, pdfRender);

            }
            else{

                $("#output-label").text("Pagina inesistente");
                $("#output-label").css("color", "red");

            }

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
