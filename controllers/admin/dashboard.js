// Constante para completar la ruta de la API.
const USUARIOS_API = 'services/public/usuario.php';
const LIBROS_API = 'services/admin/libros.php';
const PEDIDO_API = 'services/admin/pedido.php';

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la funciones que generan los gráficos en la página web.
    graficoBarrasUsuarios();
    graficoLineaVentasDiarias();
    graficoPastelDistribucionLibrosPorGenero();
    graficoRadarEvaluacionesLibros();
    graficoPolarDistribucionPedidos();
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
        barGraph('chart1', meses, cantidades, 'Usuarios registrados', '');
    } else {
        document.getElementById('chart1').remove();
        console.error(DATA.error);
    }
}

// Función para obtener y mostrar el gráfico de líneas de ventas diarias
const graficoLineaVentasDiarias = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(PEDIDO_API, 'readVentasDiarias');

    // Se comprueba si la respuesta es satisfactoria.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let fechas = [];
        let cantidades = [];

        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            fechas.push(row.fecha);
            cantidades.push(row.total_pedidos);  // Asegúrate de que el campo en la base de datos y en el código coincidan.
        });

        // Llamada a la función para generar y mostrar un gráfico de líneas.
        lineGraph('lineChart', fechas, cantidades, 'Ventas Diarias', 'Ventas Diarias de Libros');
    } else {
        // Si no hay datos, se remueve el canvas y se muestra un mensaje de error.
        document.getElementById('lineChart').remove();
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
        pieGraph('pieChart', generos, cantidades, '');
    } else {
        document.getElementById('pieChart').remove();
        console.error(DATA.error);
    }
}


// Función para obtener y mostrar el gráfico de radar de evaluaciones de libros
const graficoRadarEvaluacionesLibros = async () => {
    const DATA = await fetchData(LIBROS_API, 'readEvaluacionesLibros');
    if (DATA.status) {
        let libros = [];
        let calificaciones = [];
        DATA.dataset.forEach(row => {
            libros.push(row.titulo);
            calificaciones.push(row.calificacion_promedio);
        });
        radarGraph('radarChart', libros, calificaciones, '');
    } else {
        document.getElementById('radarChart').remove();
        console.log(DATA.exception);
    }
}

/*
*   Función asíncrona para obtener y mostrar el gráfico de polar de distribución de pedidos por estado.
*   Requiere la función fetchData para obtener los datos desde la API.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoPolarDistribucionPedidos = async () => {
    const DATA = await fetchData(PEDIDO_API, 'readDistribucionPedidosPorEstado');

    if (DATA.status) {
        let estados = [];
        let cantidades = [];
        DATA.dataset.forEach(row => {
            estados.push(row.estado);
            cantidades.push(row.cantidad);
        });
        polarGraph('polarChart', estados, cantidades, '');
    } else {
        document.getElementById('polarChart').remove();
        console.error(DATA.exception);
    }
}