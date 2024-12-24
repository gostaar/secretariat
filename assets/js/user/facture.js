export function facture() {
    // Gestion des clics globaux sur le document
    document.addEventListener('click', function handleFactureClick(e) {
        if (e.target.classList.contains('facture')) {
        handleFactureDetails(e);
        }

        if (e.target.id === 'btnPdfFacture') {
            generatePdf();
            window.appState.endLoadingState = true;
        }

        if (e.target.id === 'btnPrintFacture') {
            previewAndPrintPdf();
        }
    });
}

let factureId;

// Mise à jour des champs de formulaire avec les données de la facture
function handleFactureDetails(e) {
    e.preventDefault();
    e.stopPropagation();

    factureId = e.target.getAttribute('data-id');
    const factureMontant = e.target.getAttribute('data-montant');
    const factureDateFacture = e.target.getAttribute('data-date_facture') || new Date().toISOString().split('T')[0];
    // const factureDatePaiement = e.target.getAttribute('data-date_paiement');
    // const factureStatus = e.target.getAttribute('data-status');
    // const factureCommentaire = e.target.getAttribute('data-commentaire');
    // const factureIsActive = e.target.getAttribute('data-is_active') === 'true';

    const dateFacture = new Date(factureDateFacture);
    dateFacture.setMonth(dateFacture.getMonth() + 1);
    const factureDateLimite = dateFacture.toISOString().split('T')[0];

    document.getElementById('factureId').textContent = factureId;
    document.getElementById('factureId2').textContent = factureId;
    document.getElementById('factureMontant').textContent = factureMontant;
    document.getElementById('factureMontant2').textContent = factureMontant;
    document.getElementById('factureDate_facture').textContent = factureDateFacture;
    document.getElementById('factureDateLimite').textContent = factureDateLimite;
    // document.getElementById('factureDate_paiement').textContent = factureDatePaiement;
    // document.getElementById('factureStatus').textContent = factureStatus;
    // document.getElementById('factureCommentaire').textContent = factureCommentaire;
    // document.getElementById('factureIs_active').textContent = factureIsActive ? 'Active' : 'Inactive';
}

// Génération du fichier PDF
function generatePdf() {
    const content = document.getElementById('facturehtml2print');
    content.classList.remove('d-none');
    content.classList.add('d-flex');

    const options = {
        margin: 1,
        filename: `Facture_${factureId}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 }, 
        jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(options).from(content).toPdf().get('pdf')
    .then((pdf) => {
        const pageCount = pdf.internal.getNumberOfPages();
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        pdf.setFontSize(12);
        for (let i = 1; i <= pageCount; i++) {
            pdf.setPage(i);
            pdf.text(`Page ${i} de ${pageCount}`, pageWidth / 2, pageHeight - 1, { align: 'center' });
        }

        pdf.save(`Facture_${factureId}.pdf`);

    }).catch((error) => {
        console.error("Erreur lors de la génération du PDF :", error); 
    }).finally(() => {
        content.classList.add('d-none');
        content.classList.remove('d-flex');
    });
}


function previewAndPrintPdf() {
    const content = document.getElementById('facturehtml2print');
    content.classList.remove('d-none');
    content.classList.add('d-flex');

    const options = {
        margin: 1,
        filename: `Facture_${factureId}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 1 },
        jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf()
        .set(options)
        .from(content)
        .toPdf()
        .get('pdf')
        .then((pdf) => {
            const pageCount = pdf.internal.getNumberOfPages();
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            pdf.setFontSize(12);
            for (let i = 1; i <= pageCount; i++) {
                pdf.setPage(i);
                pdf.text(`Page ${i} de ${pageCount}`, pageWidth / 2, pageHeight - 1, { align: 'center' });
            }

            const pdfBlob = pdf.output('blob');
            const pdfUrl = URL.createObjectURL(pdfBlob);
            const previewWindow = window.open('', '_blank');

            if (previewWindow) {
                previewWindow.document.write(`
                    <html>
                        <head><title>Aperçu PDF</title></head>
                        <body>
                            <embed width="100%" height="100%" src="${pdfUrl}" type="application/pdf">
                        </body>
                    </html>
                `);
                previewWindow.document.close();

                previewWindow.onload = () => previewWindow.print();
            }
        })
        .finally(() => {
            content.classList.add('d-none');
            content.classList.remove('d-flex');
        });
}


    