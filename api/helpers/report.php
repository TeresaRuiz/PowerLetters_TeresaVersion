<?php
// Se incluye la clase para generar archivos PDF.
require_once('../../libraries/fpdf185/fpdf.php');

// Se incluye la clase Database para acceder a la base de datos.
require_once('../../helpers/database.php');

// Se define una clase llamada Report que extiende de FPDF
class Report extends FPDF
{
    // Se define una constante con la URL del cliente
    const CLIENT_URL = 'http://localhost/PowerLetters_TeresaVersion/Views/Private/';
    // Se declara una propiedad privada para el título
    private $title = null;

    // Método para iniciar el reporte
    public function startReport($title)
    {
        // Se inicia la sesión
        session_start();
        // Se verifica si existe una sesión de administrador
        if (isset($_SESSION['idAdministrador'])) {
            // Se asigna el título
            $this->title = $title;
            // Se configura el título del documento PDF
            $this->setTitle('Power Letters - Reporte', true);
            // Se configuran los márgenes
            $this->setMargins(15, 15, 15);
            // Se añade una página
            $this->addPage('P', 'Letter');
            // Se configura la numeración de páginas
            $this->aliasNbPages();

            // Se configura el color de fondo
            $this->setFillColor(235, 238, 255); // #ebeeff
            // Se dibuja un rectángulo que cubre toda la página
            $this->rect(0, 0, $this->getPageWidth(), $this->getPageHeight(), 'F');
            
            // Se llama al método header manualmente
            $this->header();
        } else {
            // Si no hay sesión, se redirecciona
            header('location:' . self::CLIENT_URL);
        }
    }

    // Método para codificar strings a ISO-8859-1
    public function encodeString($string)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    // Método para generar el encabezado del reporte
    public function header()
    {
        // Se establece la posición Y
        $this->setY(15);
        
        // Se configura el color de fondo
        $this->setFillColor(235, 238, 255); // #ebeeff
        // Se dibuja un rectángulo que cubre toda la página
        $this->rect(0, 0, $this->getPageWidth(), $this->getPageHeight(), 'F');
        
        // Se restablece la posición Y
        $this->setY(15);
        
        // Se añade el logo
        $this->image('../../images/logo_blanco.png', 15, 15, 20);
        
        // Se configura y añade el título
        $this->setFont('Arial', 'B', 15);
        $this->cell(30); // Mover a la derecha
        $this->cell(0, 10, $this->encodeString($this->title), 0, 1, 'C');
        
        // Se añade la fecha/hora
        $this->setFont('Arial', '', 10);
        $this->cell(0, 10, $this->encodeString('Fecha/Hora: ' . date('d-m-Y H:i:s')), 0, 1, 'R');

        // Se obtiene y añade el nombre del administrador
        $adminName = $this->getAdminName();
        if ($adminName) {
            $this->cell(0, 10, $this->encodeString('Administrador: ' . $adminName), 0, 1, 'R');
        }
        
        // Se restablece el color de relleno
        $this->setFillColor(235, 238, 255); // #ebeeff
        
        // Se añade un salto de línea
        $this->ln(10);
    }

    // Método para generar el pie de página
    public function footer()
    {
        $this->setY(-15);
        $this->setFont('Arial', 'I', 8);
        $this->cell(0, 10, $this->encodeString('Página ') . $this->pageNo() . '/{nb}', 0, 0, 'C');
    }

    // Método para crear el encabezado de la tabla
    public function createTableHeader($headers)
    {
        $this->setFillColor(73, 96, 212); // Color #4960d4
        $this->setTextColor(255); // Texto blanco para el encabezado
        $this->setFont('Arial', 'B', 11);
        
        foreach ($headers as $header) {
            $this->cell($header['width'], 10, $this->encodeString($header['text']), 1, 0, 'C', 1);
        }
        $this->ln();
        
        $this->setTextColor(0); // Restablecer el color del texto a negro
        $this->setFont('Arial', '', 11);
    }

    // Método privado para obtener el nombre del administrador
    private function getAdminName()
    {
        $idAdministrador = $_SESSION['idAdministrador'];
        $query = "SELECT nombre_administrador, apellido_administrador FROM administrador WHERE id_administrador = ?";
        $result = Database::getRow($query, [$idAdministrador]);
        
        if ($result) {
            return $result['nombre_administrador'] . ' ' . $result['apellido_administrador'];
        } else {
            return null;
        }
    }
}