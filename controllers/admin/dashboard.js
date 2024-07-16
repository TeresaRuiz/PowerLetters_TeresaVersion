// Constante para completar la ruta de la API.
const USUARIOS_API = 'services/public/usuario.php';
const LIBROS_API = 'services/admin/libros.php';

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la funciones que generan los gráficos en la página web.
    graficoBarrasUsuarios();
    graficoLineasVentasDiarias();
    graficoPastelDistribucionLibrosPorGenero();
});

// Función para obtener los datos y generar el gráfico de barras.
const graficoBarrasUsuarios = async () => {
    const DATA = await fetchData(USUARIOS_API, 'readUsuariosPorMes');
    if (DATA.status) {
        let meses = [];
        let cantidades = [];
        DATA.dataset.forEach(row => {
            meses.push(row.mes);
            cantidades.push(row.total);
        });
        barGraph('chart1', meses, cantidades, 'Usuarios registrados', 'Cantidad de usuarios registrados por mes');
    } else {
        document.getElementById('chart1').remove();
        console.error(DATA.error);
    }
}

// Función para generar datos ficticios de ventas diarias de libros
const generarDatosFicticios = () => {
    let ventasDiarias = [];
    let fechaActual = new Date();
    let numDias = 10; // Por ejemplo, para los últimos 30 días

    for (let i = 0; i < numDias; i++) {
        // Generar una fecha retrocediendo días aleatorios
        let fecha = new Date(fechaActual);
        fecha.setDate(fecha.getDate() - i);
        let fechaFormateada = fecha.toISOString().slice(0, 10); // Formato YYYY-MM-DD

        // Generar cantidad de ventas aleatoria entre 1 y 10 (para simular)
        let ventas = Math.floor(Math.random() * 10) + 1;

        ventasDiarias.push({ fecha: fechaFormateada, cantidad: ventas });
    }

    return ventasDiarias;
}

// Función para obtener y mostrar el gráfico de líneas de ventas diarias
const graficoLineasVentasDiarias = () => {
    // Obtener datos ficticios
    const datos = generarDatosFicticios();

    // Se comprueba si hay datos
    if (datos.length > 0) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let fechas = [];
        let cantidades = [];

        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        datos.forEach(row => {
            // Se agregan los datos a los arreglos.
            fechas.push(row.fecha);
            cantidades.push(row.cantidad);
        });

        // Llamada a la función para generar y mostrar un gráfico de líneas.
        lineGraph('lineChart', fechas, cantidades, 'Ventas diarias', 'Ventas diarias de libros');
    } else {
        document.getElementById('lineChart').remove();
        console.log('No hay datos disponibles para mostrar el gráfico.');
    }
}

// Función para obtener los datos de distribución de libros por género y mostrar el gráfico de pastel
const graficoPastelDistribucionLibrosPorGenero = async () => {
    const DATA = await fetchData(LIBROS_API, 'readDistribucionLibrosPorGenero');
    if (DATA.status) {
        let generos = [];
        let cantidades = [];
        DATA.dataset.forEach(row => {
            generos.push(row.genero);
            cantidades.push(row.cantidad);
        });
        pieGraph('pieChart', generos, cantidades, 'Top 5 de géneros literarios más populares');
    } else {
        document.getElementById('pieChart').remove();
        console.error(DATA.error);
    }
}
