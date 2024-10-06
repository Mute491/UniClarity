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

function generateCanvas(num) {
    for (let i = 0; i < num; i++) {
        $("#draw-svg-div").append($("<svg viewBox='0 0 100 100' preserveAspectRatio='xMidYMid meet' class='draw-svg'></svg>"));
    }

    let drawSvg = $("#draw-svg-div").children();
    drawSvg.each(function () {
        this.width = $("#canvas-div").width();
        this.height = $("#canvas-div").height();
    });


    caricaEventi();
}