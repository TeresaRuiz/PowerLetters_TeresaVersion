// Constante para completar la ruta de la API.
const USUARIO_API = 'services/public/usuario.php';

// Función para obtener los datos y generar el gráfico de barras.
const graficoBarrasUsuarios = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(USUARIO_API, 'readUsuariosPorMes');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let meses = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            meses.push(row.mes);
            cantidades.push(row.total);
        });
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart1', meses, cantidades, 'Usuarios registrados', 'Cantidad de usuarios por Mes');
    } else {
        document.getElementById('chart1').remove();
        console.log(DATA.error);
    }
}

// Función para generar y mostrar un gráfico de barras.
const barGraph = (id, labels, data, title, subtitle) => {
    // Obtener el elemento canvas
    const canvas = document.getElementById(id);
    const ctx = canvas.getContext('2d');
    
    // Configurar el gráfico de barras
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: subtitle,
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: title
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
};

// Llamar a la función para generar el gráfico
graficoBarrasUsuarios();