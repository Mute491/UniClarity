import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js';

export class PdfRender {

    constructor(pdfUrl, scale, pdfCanvas) {

        this.pdfjsLib = window['pdfjs-dist/build/pdf'];

        this.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        this.pageNum = 1;
        this.scale = scale;
        this.url = pdfUrl;
        this.canvas = pdfCanvas;
        if(pdfCanvas !== null){
            this.ctx = pdfCanvas.getContext("2d");
        }
        

        this.pdf = null;
        this.viewport = null;
        this.pageMaxNumber = 0;

    }

    async getPdfInfo(){

        await this.pdfjsLib.getDocument(this.url).promise.then(pdfDoc => {

            //capire come mettere in attributo pdfDoc
            this.pdf = pdfDoc;
            this.pageMaxNumber = this.pdf.numPages;

        });

    }

    setScale(newScale) {

        this.scale = newScale;
    }

    setCanvas(newCanvas){

        this.canvas = newCanvas;
        this.ctx = newCanvas.getContext("2d");

    }

    async renderPage(num) {

        //se pdf non è stato ancora caricato allora aspetta
        while(this.pdf === null){
            console.log("aspetto...");
            await new Promise(resolve => setTimeout(resolve, 500)); // Aspetta mezzo secondo
        }

        await this.pdf.getPage(num).then((page) => { // Usa la funzione freccia per mantenere il contesto di `this`
            this.viewport = page.getViewport({scale: this.scale}); // Ottieni il viewport

            this.canvas.height = this.viewport.height;
            this.canvas.width = this.viewport.width;

            // Rendering della pagina del PDF
            const renderContext = {
                canvasContext: this.ctx,
                viewport: this.viewport
            };

            return page.render(renderContext).promise; // Renderizza la pagina
        }).then(function(){

            console.log("render concluso");

        });
        
    }
}
