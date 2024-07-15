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
 
// Llamar a la función para generar el gráfico
graficoBarrasUsuarios();