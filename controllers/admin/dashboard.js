// Constante para completar la ruta de la API.
const USUARIOS_API = 'services/public/usuario.php';

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la funciones que generan los gráficos en la página web.
    graficoBarrasUsuarios();
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
        barGraph('chart1', meses, cantidades, 'Usuarios registrados', 'Cantidad de usuarios por Mes');
    } else {
        document.getElementById('chart1').remove();
        console.error(DATA.error);
    }
}

