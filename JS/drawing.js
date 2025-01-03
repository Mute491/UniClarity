

var drawing = false;
var lastX = 0, lastY = 0;


function getSVGCoordinates(svg, event) {
    const point = svg.createSVGPoint();  // Crea un punto SVG
    point.x = event.clientX;
    point.y = event.clientY;

    // Trasforma il punto nelle coordinate del sistema SVG
    const transformedPoint = point.matrixTransform(svg.getScreenCTM().inverse());
    return transformedPoint;
}

function caricaEventi() {
    // Eventi per disegnare su SVG
    $("#draw-svg-div").children().on("mousedown", function (e) {
        drawing = true;

        // Trasformare le coordinate del mouse per adattarsi al sistema di coordinate SVG
        const svg = e.target.closest("svg");
        const point = getSVGCoordinates(svg, e);

        lastX = point.x;
        lastY = point.y;
    });

    $("#draw-svg-div").children().on("mousemove", function (e) {

        const svg = e.target.closest("svg");
        const point = getSVGCoordinates(svg, e);

        if (!drawing){
            
            lastX = point.x;
            lastY = point.y;
            
            return;
        }   
        
        let line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', lastX);
        line.setAttribute('y1', lastY);
        line.setAttribute('x2', point.x);
        line.setAttribute('y2', point.y);
        line.setAttribute('stroke', $("#segment-color").val());
        line.setAttribute('stroke-width', $("#segment-width").val() / 10);
        line.setAttribute('stroke-linecap', 'round');

        if($("#tool-selector").val() === "2"){

            line.setAttribute('stroke-opacity', 0.02);

        }
        // Verifica se il tool selezionato è "gomma" (assumendo "3" come gomma)
        if ($("#tool-selector").val() === "3") {
        // Ottieni l'elemento SVG sotto il cursore
            const elementUnderCursor = document.elementFromPoint(e.clientX, e.clientY);
        
        // Se è una linea, rimuovila
            if (elementUnderCursor && elementUnderCursor.tagName === 'line') {
                elementUnderCursor.remove();
            }
        return; // Esci dalla funzione per evitare di disegnare una nuova linea
    }

        this.appendChild(line);

        lastX = point.x;
        lastY = point.y;
    });

    $("#draw-svg-div").children().on("mouseup", function () {
        drawing = false;
    });

    $("#draw-svg-div").children().on("mouseleave", function () {
        drawing = false;
    });
}

function generateSvgDictionary(num) {

    let svgList = {};

    for (let i = 0; i < num; i++) {
        let svgElement = $(document.createElementNS("http://www.w3.org/2000/svg", "svg"));
        svgElement.attr({
            id: 'svgN' + i,
            viewBox: '0 0 100 100',
            preserveAspectRatio: 'xMidYMid meet',
            class: 'draw-svg'
        });

        svgList["svgN"+i] = svgElement.get(0);

        //$("#draw-svg-div").append($("<svg id='svgN"+i+"' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid meet' class='draw-svg'></svg>"));
    }

    return svgList;
}

function addExistingSvg(svgStringArray, svgDictionary){

    let parser = new DOMParser();
    svgStringArray.forEach(element => {
        
        let svgDocument = parser.parseFromString(element, "image/svg+xml");

        var svgElement = svgDocument.documentElement;
        svgDictionary[svgElement.id] = svgElement;

    });

    // drawSvg = $("#draw-svg-div").children();
    
    // drawSvg.each(function () {
    //     this.width = $("#canvas-div").width();
    //     this.height = $("#canvas-div").height();
    // });

    return svgDictionary;

}