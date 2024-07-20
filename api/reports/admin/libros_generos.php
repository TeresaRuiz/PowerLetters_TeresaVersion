<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para el género, de lo contrario se muestra un mensaje.
if (isset($_GET['idGenero'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../models/data/genero_data.php');
    require_once('../../models/data/libros_data.php');
    
    // Se instancian las entidades correspondientes.
    $generos = new GeneroData;
    $libros = new LibroData;
    
    // Se establece el valor del género, de lo contrario se muestra un mensaje.
    if ($generos->setId($_GET['idGenero']) && $libros->setGenero($_GET['idGenero'])) {
        // Se verifica si el género existe, de lo contrario se muestra un mensaje.
        if ($rowGenero = $generos->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Libros del género ' . $rowGenero['nombre']);
            
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataLibros = $libros->librosDeGenero()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 11);
                
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(86, 10, 'Titulo del libro', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Autor', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Existencias', 1, 1, 'C', 1);
                
                // Se establece la fuente para los datos de los libros.
                $pdf->setFont('Arial', '', 11);
                
                $totalLibros = 0;
                $valorTotal = 0;
                
                // Se recorren los registros fila por fila.
                foreach ($dataLibros as $rowLibros) {
                    // Se imprimen las celdas con los datos de los libros.
                    $pdf->cell(86, 10, $pdf->encodeString($rowLibros['titulo']), 1, 0);
                    $pdf->cell(40, 10, $pdf->encodeString($rowLibros['nombre_autor']), 1, 0);
                    $pdf->cell(30, 10, number_format($rowLibros['precio'], 2), 1, 0, 'R');
                    $pdf->cell(30, 10, $rowLibros['existencias'], 1, 1, 'C');
                }
                
                // Se imprime el total
                $pdf->setFont('Arial', 'B', 11);
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay libros para este género'), 1, 1, 'C');
            }
            
            // Se envía el documento al navegador web.
            $pdf->output('I', 'libros_por_genero.pdf');
        } else {
            $pdf->startReport('Error');
            $pdf->cell(0, 10, $pdf->encodeString('Género inexistente'), 1, 1, 'C');
            $pdf->output('I', 'error.pdf');
        }
    } else {
        $pdf->startReport('Error');
        $pdf->cell(0, 10, $pdf->encodeString('Género incorrecto'), 1, 1, 'C');
        $pdf->output('I', 'error.pdf');
    }
} else {
    $pdf->startReport('Error');
    $pdf->cell(0, 10, $pdf->encodeString('Debe seleccionar un género'), 1, 1, 'C');
    $pdf->output('I', 'error.pdf');
}