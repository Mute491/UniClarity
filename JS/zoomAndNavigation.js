
async function zoom(pdfObject, pageNo, scaleChange, svg, isZoomIn) {

    if (isZoomIn) {
        pdfObject.scale += scaleChange;
    } else {
        pdfObject.scale -= scaleChange;
    }
    console.log(pdfObject.scale);
    await pdfObject.renderPage(pageNo); 
    // Scala anche il livello SVG per mantenerlo sincronizzato con il PDF
    
    updateSizes(pdfObject, svg);

}

function updateSizes(pdfObject, svg){

    let vp = pdfObject.viewport;

    $("#canvas-div").css({
        "height": vp.height + "px",
        "width": vp.width + "px"
    });

    $("#draw-svg-div").css({
        "height": vp.height + "px",
        "width": vp.width + "px"
    });

    svg.css({
        width: $("#canvas-div").width(),
        height: $("#canvas-div").height()
    });

    console.log("dimensioni aggiornate");

}

async function changePage(pageNumber, jqueryElement, pdfObject){

    updateSizes(pdfObject, jqueryElement);

    $("#draw-svg-div").empty();
    $("#draw-svg-div").append(jqueryElement);

    await pdfObject.renderPage(pageNumber);

    caricaEventi();

}


async function saveSvg(acquistiId, svgDictionary){

    let svgStringList = {};
    let counter = 0;

    for (var key in svgDictionary){

        if(svgDictionary[key].childElementCount > 0){
            svgStringList[key] = new XMLSerializer().serializeToString(svgDictionary[key]);
        }

    }

    console.log(svgStringList);

    // svgDictionary.each(function () {

    //     svgStringList[counter.toString()] = new XMLSerializer().serializeToString(this);
    //     counter++;

    // });
    
    // console.log("contatto il webhook");
    // const response = await fetch("https://hooks.zapier.com/hooks/catch/20680064/25varql/", {
    //     method: "POST",
    //     body: JSON.stringify({
    //         acquistiId: acquistiId, 
    //         content: [JSON.stringify(svgStringList)]
    //     }),
    //   });
      
    // console.log(response);

    // if (response.ok) {
    //     $("#output-label").text("Appunti salvati con successo!");
    //     $("#output-label").css("color", "green");
    // } else {
    //     $("#output-label").text("Errore durante il salvataggio degli appunti");
    //     $("#output-label").css("color", "red");
    //     console.error("Errore nella richiesta:", statusCode);
    // }
    

}