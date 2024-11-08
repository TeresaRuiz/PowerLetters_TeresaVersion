<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class LibroHandler
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $titulo = null;
    protected $autor = null;
    protected $precio = null;
    protected $descripcion = null;
    protected $imagen = null;
    protected $clasificacion = null;
    protected $editorial = null;
    protected $existencias = null;
    protected $genero = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/libros/';

    /*
     * Métodos para realizar las operaciones CRUD (crear, leer, actualizar y eliminar).
     */

    /*
     * Método para buscar registros en la tabla tb_libros.
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.nombre AS nombre_autor,
            c.nombre AS nombre_clasificacion,
            e.nombre AS nombre_editorial,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        WHERE
            l.titulo LIKE ? OR
            l.descripcion LIKE ?
        ORDER BY
            l.titulo;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    /*
     * Método para crear un nuevo registro en la tabla tb_libros.
     */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_libros(titulo, id_autor, precio, descripcion, imagen, id_clasificacion, id_editorial, existencias, id_genero)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->titulo, $this->autor, $this->precio, $this->descripcion, $this->imagen, $this->clasificacion, $this->editorial, $this->existencias, $this->genero);
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para leer todos los registros de la tabla tb_libros.
     */
    public function readAll()
    {
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.id_autor,
            a.nombre AS nombre_autor,
            c.id_clasificacion,
            c.nombre AS nombre_clasificacion,
            e.id_editorial,
            e.nombre AS nombre_editorial,
            g.id_genero,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        ORDER BY
            l.titulo;';
        return Database::getRows($sql);
    }

    /*
     * Método para leer un registro específico de la tabla tb_libros por id.
     */
    public function readOne()
    {
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.id_autor,
            a.nombre AS nombre_autor,
            c.id_clasificacion,
            c.nombre AS nombre_clasificacion,
            e.id_editorial,
            e.nombre AS nombre_editorial,
            g.id_genero,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        WHERE id_libro = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
     * Método para leer el nombre de archivo de la imagen de un libro.
     */
    public function readFilename()
    {
        $sql = 'SELECT imagen FROM tb_libros WHERE id_libro = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
     * Método para actualizar un registro específico de la tabla tb_libros por id.
     */
    public function updateRow()
    {
        $sql = 'UPDATE tb_libros
            SET imagen = ?, titulo = ?, descripcion = ?, precio = ?, existencias = ?, id_autor = ?, id_clasificacion = ?, id_editorial = ?, id_genero = ?
            WHERE id_libro = ?';
        $params = array($this->imagen, $this->titulo, $this->descripcion, $this->precio, $this->existencias, $this->autor, $this->clasificacion, $this->editorial, $this->genero, $this->id);
        return Database::executeRow($sql, $params);
    }
    /*
     * Método para eliminar un registro específico de la tabla tb_libros por id.
     */
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_libros
            WHERE id_libro = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para actualizar existencias de la tabla tb_libros.
     */
    public function updateExistencias()
    {
        $sql = 'UPDATE tb_libros SET existencias = existencias - ? WHERE id_libro = ? AND existencias >= ?';
        $params = array($this->existencias, $this->id, $this->existencias);
        return Database::executeRow($sql, $params);
    }
    /*
     * Método para verificar existencias de la tabla tb_libros.
     */
    public function getExistencias()
    {
        $sql = 'SELECT existencias FROM tb_libros WHERE id_libro = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if ($data) {
            return $data['existencias'];
        } else {
            return false;
        }
    }
    /*
     * Método para obtener la distribución de libros por género, limitando a los 5 más populares.
     */
    public function readDistribucionLibrosPorGenero()
    {
        // Definir la consulta SQL para obtener la distribución de libros por género.
        $sql = 'SELECT 
                g.nombre AS genero, 
                COUNT(l.id_libro) AS cantidad 
            FROM 
                tb_generos g 
            LEFT JOIN 
                tb_libros l ON g.id_genero = l.id_genero 
            GROUP BY 
                g.nombre 
            ORDER BY 
                cantidad DESC 
            LIMIT 5';

        // Llamar al método getRows de la clase Database para ejecutar la consulta.
        return Database::getRows($sql);
    }

    /*
     * Método para obtener las evaluaciones de los libros.
     */
    public function readEvaluacionesLibros()
    {
        // Definir la consulta SQL para obtener las evaluaciones de los libros.
        $sql = 'SELECT 
                tb_libros.titulo, 
                AVG(tb_comentarios.calificacion) AS calificacion_promedio
            FROM 
                tb_libros
            INNER JOIN 
                tb_detalle_pedidos ON tb_libros.id_libro = tb_detalle_pedidos.id_libro
            INNER JOIN 
                tb_comentarios ON tb_detalle_pedidos.id_detalle = tb_comentarios.id_detalle
            GROUP BY 
                tb_libros.id_libro
            ORDER BY 
                calificacion_promedio DESC
            LIMIT 5';

        // No se requieren parámetros para esta consulta.
        $params = null;

        // Llamar al método getRows de la clase Database para ejecutar la consulta.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para generar reportes de libros de un género específico.
     */
    public function librosDeGenero()
    {
        // Definir la consulta SQL para obtener libros de un género específico.
        $sql = 'SELECT 
                l.titulo, 
                a.nombre AS nombre_autor, 
                l.precio, 
                l.existencias
            FROM 
                tb_libros l
            INNER JOIN 
                tb_autores a ON l.id_autor = a.id_autor  
            INNER JOIN 
                tb_generos g ON l.id_genero = g.id_genero
            WHERE 
                g.id_genero = ?
            ORDER BY 
                l.titulo';

        // Establecer los parámetros para la consulta (ID del género).
        $params = array($this->genero);

        // Llamar al método getRows de la clase Database para ejecutar la consulta.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para obtener el inventario de libros.
     */
    public function obtenerInventario()
    {
        // Definir la consulta SQL para obtener el inventario de libros.
        $sql = 'SELECT
                l.id_libro,
                l.titulo AS titulo_libro,
                a.nombre AS nombre_autor,
                g.nombre AS nombre_genero,
                e.nombre AS nombre_editorial,
                l.existencias,
                l.precio
            FROM
                tb_libros AS l
            INNER JOIN
                tb_autores AS a ON l.id_autor = a.id_autor
            INNER JOIN
                tb_editoriales AS e ON l.id_editorial = e.id_editorial
            INNER JOIN
                tb_generos AS g ON l.id_genero = g.id_genero
            ORDER BY 
                l.existencias ASC';

        // Llamar al método getRows de la clase Database para ejecutar la consulta.
        return Database::getRows($sql);
    }

    /*
     * Método para obtener el total de libros en inventario, sus existencias y el valor del inventario.
     */
    public function obtenerTotalLibros()
    {
        // Definir la consulta SQL para obtener el total de libros, existencias y valor del inventario.
        $sql = 'SELECT 
                COUNT(*) AS total_libros, 
                SUM(existencias) AS total_existencias,
                SUM(existencias * precio) AS valor_inventario
            FROM 
                tb_libros';

        // Llamar al método getRow de la clase Database para ejecutar la consulta.
        return Database::getRow($sql);
    }

    public function readByGenero()
{
    $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.id_autor,
            a.nombre AS nombre_autor,
            c.id_clasificacion,
            c.nombre AS nombre_clasificacion,
            e.id_editorial,
            e.nombre AS nombre_editorial,
            g.id_genero,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        WHERE
            l.id_genero = ?
        ORDER BY
            l.titulo';
    
    $params = array($this->genero);
    return Database::getRows($sql, $params);
}

}
