<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>

<script>
    document.getElementById('PDF').addEventListener('click', function() {
        exportToPDF();
    });

    document.getElementById('CSV').addEventListener('click', function() {
        exportToCSV();
    });

    async function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Ajouter le titre
        doc.text("Graphique de Consommation", 10, 10);

        // Ajouter le graphique (vous pouvez utiliser une capture d'écran ou dessiner le graphique)
        const canvas = document.getElementById('chartjs-bar');
        const imgData = canvas.toDataURL('image/png');
        doc.addImage(imgData, 'PNG', 10, 20, 180, 80);

        // Sauvegarder le PDF
        doc.save('graphique.pdf');
    }

    async function exportToCSV() {
        const data = await fetchData(); // Réutilisez la fonction fetchData pour obtenir les données

        // Convertir les données en CSV
        const csv = Papa.unparse(data);

        // Créer un blob à partir des données CSV
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);

        // Créer un lien pour télécharger le fichier CSV
        const a = document.createElement('a');
        a.href = url;
        a.download = 'donnees.csv';
        document.body.appendChild(a);
        a.click();

        // Nettoyer
        URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }

    async function fetchData() {
        const response = await fetch('data.php');
        return await response.json();
    }
</script>
