import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js';

export class PdfRender {

    constructor(pdfUrl, scale, pdfCanvas) {
        
        this.pdfjsLib = window['pdfjs-dist/build/pdf'];

        this.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        this.pageNum = 1;
        this.scale = scale;
        this.url = pdfUrl;
        this.canvas = pdfCanvas;
        this.ctx = pdfCanvas.getContext("2d");

        this.pdf = null;
        this.viewport = null;
        this.pageMaxNumber = 0;

        this.pdfjsLib.getDocument(this.url).promise.then(pdfDoc => {


            //capire come mettere in attributo pdfDoc
            this.pdf = pdfDoc;
            this.pageMaxNumber = this.pdf.numPages;

        });

        console.log(this.pdf);

    }


    getViewport(page) {
        return page.getViewport({ scale: this.scale });
    }

    setScale(newScale) {
        this.scale = newScale;
    }

    renderPage(num) {

        this.pdf.getPage(1).then((page) => { // Usa la funzione freccia per mantenere il contesto di `this`
            this.viewport = page.getViewport(this.scale); // Ottieni il viewport

            this.canvas.height = this.viewport.height;
            this.canvas.width = this.viewport.width;

            // Rendering della pagina del PDF
            const renderContext = {
                canvasContext: this.ctx,
                viewport: this.viewport
            };

            page.render(renderContext); // Renderizza la pagina
        });
 
    }
}
