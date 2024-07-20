<?php
// Se incluye la clase para generar archivos PDF.
require_once ('../../libraries/fpdf185/fpdf.php');

class Report extends FPDF
{
    const CLIENT_URL = 'http://localhost/PowerLetters_TeresaVersion/Views/Private/';
    private $title = null;

    public function startReport($title)
    {
        session_start();
        if (isset($_SESSION['idAdministrador'])) {
            $this->title = $title;
            $this->setTitle('Power Letters - Reporte', true);
            $this->setMargins(10, 15, 15);
            $this->addPage('P', 'Letter');
            $this->aliasNbPages();

            // Aplicamos el color de fondo después de añadir la página
            $this->setFillColor(235, 238, 255); // #ebeeff
            $this->rect(0, 0, $this->getPageWidth(), $this->getPageHeight(), 'F');
            
            // Llamamos al header manualmente después de aplicar el fondo
            $this->header();
        } else {
            header('location:' . self::CLIENT_URL);
        }
    }

    public function encodeString($string)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    public function header()
    {
        // Guardamos la posición actual
        $this->setY(15);
        
        // Establecemos un color de fondo blanco para el área del encabezado
        $this->setFillColor(235, 238, 255); // #ebeeff
        $this->rect(0, 0, $this->getPageWidth(), $this->getPageHeight(), 'F');
        
        // Restablecemos la posición
        $this->setY(15);
        
        // Logo
        $this->image('../../images/logo_blanco.png', 15, 15, 20);
        
        // Título
        $this->setFont('Arial', 'B', 15);
        $this->cell(30); // Mover a la derecha
        $this->cell(0, 10, $this->encodeString($this->title), 0, 1, 'C');
        
        // Fecha/Hora y Usuario
        $this->setFont('Arial', '', 10);
        $this->cell(0, 10, $this->encodeString('Fecha/Hora: ' . date('d-m-Y H:i:s')), 0, 1, 'R');
        if (isset($_SESSION['nombreUsuario'])) {
            $this->cell(0, 10, $this->encodeString('Usuario: ' . $_SESSION['nombreUsuario']), 0, 1, 'R');
        }
        
        // Restablecemos el color de relleno
        $this->setFillColor(235, 238, 255); // #ebeeff
        
        $this->ln(10);
    }

    public function footer()
    {
        $this->setY(-15);
        $this->setFont('Arial', 'I', 8);
        $this->cell(0, 10, $this->encodeString('Página ') . $this->pageNo() . '/{nb}', 0, 0, 'C');
    }

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
}