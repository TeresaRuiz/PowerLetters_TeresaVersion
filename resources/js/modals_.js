var modal = document.getElementById("myModal");
var modal_ = document.getElementById("myModalView");
var modalGrafica = document.getElementById("chartModal");
var modal_pedido = document.getElementById("modalPedidosUsuario");
var MODAL_TITLE = document.getElementById("modalTitle");
var btn = document.querySelector(".add-button");

// Ocultar el modal al cargar la página
modal.style.display = "none";
// Ocultar el modal al cargar la página
modal_.style.display = "none";
// Ocultar el modal "Grafica" al cargar la página
modalGrafica.style.display = "none";

modal_pedido.style.display = "none";

// Abrir el modal al hacer click en el botón de añadir
function AbrirModal() {
    modal.style.display = "block";
};

function AbrirModalVista() {
    modal_.style.display = "block";
};

function AbrirModalGrafica() {
    modalGrafica.style.display = "block";
};
function AbrirModalPedido() {
    modal_pedido.style.display = "block";
};

// Cerrar el modal de añadir al hacer click en el botón de cierre
function closeModal() {
    modal.style.display = "none";
}

function closeModalDetalles() {
    modal_.style.display = "none";
}

function CerrarModalGrafica() {
    modalGrafica.style.display = "none";
}
function CerrarModalPedidosUsuario() {
    modal_pedido.style.display = "none";
}

document.getElementById('toggleReporteForm').addEventListener('click', function() {
    var form = document.getElementById('reporteVentasForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
});
