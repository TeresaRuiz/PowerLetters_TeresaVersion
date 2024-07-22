// Constantes para completar las rutas de la API.
const GENERO_API = 'services/admin/genero.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_GENERO = document.getElementById('idGenero'),
    NOMBRE_Gen = document.getElementById('nombreGEN'),
    IMAGEN_Gen = document.getElementById('nombreIMG');
    
// Se establece el título de la página web.
document.querySelector('title').textContent = 'Género de libros';

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
    CerrarModalGrafica();
});

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    const action = (ID_GENERO.value) ? 'updateRow' : 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(GENERO_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        closeModal();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        // Se muestra un mensaje de error.
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    const action = (form) ? 'searchRows' : 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(GENERO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
            <tr>
                <td>${row.nombre}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="action-icons">
                    <a onclick="openUpdate(${row.id_genero})">
                        <i class="ri-edit-line"></i>
                    </a>
                    <a onclick="openDelete(${row.id_genero})">
                        <i class="ri-delete-bin-line"></i>
                    </a>
                    <a onclick="openChart(${row.id_genero})">
                    <i class="ri-line-chart-line"></i>
                    </a>
                     <a onclick="openReport(${row.id_genero})">
                    <i class="ri-file-pdf-2-fill"></i>
                    </a>
                </td>
            </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        // Se muestra un mensaje de error.
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    modal.style.display = "block";
    MODAL_TITLE.textContent = 'Agregar un nuevo género';
    // Se prepara el formulario.
    SAVE_FORM.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idGenero', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(GENERO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_GENERO.value = ROW.id_genero;
        NOMBRE_Gen.value = ROW.nombre;
        AbrirModal();
        MODAL_TITLE.textContent = 'Actualizar un género';
    } else {
        // Se muestra un mensaje de error.
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el género de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idGenero', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(GENERO_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

const openChart = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idGenero', id);

    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(GENERO_API, 'getTopLibrosPorGenero', FORM);

    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con el error.
    if (DATA.status) {
        // Abre el modal para mostrar la gráfica.
        AbrirModalGrafica();

        // Declara los arreglos para guardar los datos a graficar.
        let titulos = [];
        let totalVentas = [];

        // Recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Agrega los datos a los arreglos.
            titulos.push(row.titulo);
            totalVentas.push(row.total_ventas);
        });

        // Agrega la etiqueta canvas al contenedor de la modal.
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;

        // Llama a la función para generar y mostrar un gráfico de barras.
        barGraph('chart', titulos, totalVentas, 'Cantidad de ventas', '');
    } else {
        sweetAlert(4, DATA.error, true);
    }
};

/*
*   Función para abrir un reporte parametrizado.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openReport = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/libros_generos.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('idGenero', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}