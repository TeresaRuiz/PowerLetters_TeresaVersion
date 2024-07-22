<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla COMENTARIOS.
 */
class ComentarioHandlerPublic
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;          // ID del comentario.
    protected $comentario = null;  // Contenido del comentario.
    protected $calificacion = null;// Calificación asociada al comentario.
    protected $libros = null;      // ID del libro asociado al comentario.
    protected $estados = null;     // Estado del comentario (activo o bloqueado).

    /*
     * Método para insertar un nuevo registro en la tabla tb_comentarios.
     */
    public function createRow()
    {
        // Definir la consulta SQL para insertar un nuevo comentario.
        $sql = 'INSERT INTO tb_comentarios(comentario, calificacion)
                VALUES(?, ?)';
        // Establecer los parámetros para la consulta (comentario y calificación).
        $params = array($this->comentario, $this->calificacion);
        // Ejecutar la consulta y devolver el resultado.
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para actualizar un registro existente en la tabla tb_comentarios.
     */
    public function updateRow()
    {
        // Definir la consulta SQL para actualizar la calificación del comentario.
        $sql = 'UPDATE tb_comentarios
                SET calificacion = ?
                WHERE id_comentario = ?';
        // Establecer los parámetros para la consulta (calificación y id).
        $params = array($this->calificacion, $this->id);
        // Ejecutar la consulta y devolver el resultado.
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para leer todas las filas de la tabla tb_comentarios.
     */
    public function readAll()
    {
        // Definir la consulta SQL para obtener todos los registros.
        $sql = 'SELECT 
                    c.id_comentario,
                    c.comentario,
                    c.calificacion,
                    c.estado_comentario,
                    u.nombre_usuario,
                    u.apellido_usuario
                FROM 
                    tb_comentarios AS c
                INNER JOIN 
                    tb_detalle_pedidos AS dp ON c.id_detalle = dp.id_detalle
                INNER JOIN 
                    tb_pedidos AS p ON dp.id_pedido = p.id_pedido
                INNER JOIN 
                    tb_usuarios AS u ON p.id_usuario = u.id_usuario';
        // Ejecutar la consulta y devolver las filas resultantes.
        return Database::getRows($sql);
    }

    /*
     * Método para leer los comentarios de un solo libro específico.
     */
    public function readOneComent()
    {
        // Definir la consulta SQL para obtener los comentarios de un libro específico.
        $sql = 'SELECT 
                    c.id_comentario,
                    c.comentario,
                    c.calificacion,
                    c.estado_comentario,
                    u.nombre_usuario,
                    dp.id_detalle,
                    dp.id_libro,
                    dp.cantidad,
                    dp.precio,
                    l.titulo,
                    u.imagen, 
                    CASE 
                        WHEN c.estado_comentario = 1 THEN "ACTIVO" 
                        WHEN c.estado_comentario = 0 THEN "BLOQUEADO" 
                    END AS "ESTADO"
                FROM 
                    tb_comentarios AS c
                INNER JOIN 
                    tb_detalle_pedidos AS dp ON c.id_detalle = dp.id_detalle
                INNER JOIN 
                    tb_pedidos AS p ON dp.id_pedido = p.id_pedido
                INNER JOIN 
                    tb_usuarios AS u ON p.id_usuario = u.id_usuario
                INNER JOIN 
                    tb_libros AS l ON dp.id_libro = l.id_libro
                WHERE 
                    c.estado_comentario = "ACTIVO" 
                    AND l.id_libro = ?;';
        // Establecer los parámetros para la consulta (ID del libro).
        $params = array($this->id);
        // Ejecutar la consulta y devolver las filas resultantes.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para leer una fila específica de la tabla tb_comentarios por id.
     */
    public function readOne()
    {
        // Definir la consulta SQL para obtener un registro específico por id.
        $sql = 'SELECT
                    c.id_comentario,
                    c.comentario,
                    c.calificacion,
                    c.estado_comentario,
                    u.nombre_usuario,
                    dp.id_detalle,
                    dp.id_libro,
                    dp.cantidad,
                    dp.precio,
                    l.titulo,
                    l.imagen
                FROM
                    tb_comentarios AS c
                JOIN
                    tb_detalle_pedidos AS dp ON c.id_detalle = dp.id_detalle
                JOIN
                    tb_pedidos AS p ON dp.id_pedido = p.id_pedido
                JOIN
                    tb_usuarios AS u ON p.id_usuario = u.id_usuario
                JOIN
                    tb_libros AS l ON dp.id_libro = l.id_libro
                WHERE
                    c.id_comentario = ?';
        // Establecer los parámetros para la consulta (ID del comentario).
        $params = array($this->id);
        // Ejecutar la consulta y devolver la fila resultante.
        return Database::getRow($sql, $params);
    }

    /*
     * Método para verificar si un usuario ha comprado un libro específico.
     */
    public function verifyPurchase()
    {
        // Definir la consulta SQL para verificar si el usuario ha comprado el libro.
        $sql = 'SELECT 
                    dp.id_detalle as id
                FROM 
                    tb_detalle_pedidos dp
                INNER JOIN 
                    tb_pedidos p ON dp.id_pedido = p.id_pedido
                WHERE 
                    p.id_usuario = ?
                    AND dp.id_libro = ?
                    AND p.estado = "ENTREGADO"
                ORDER BY 
                    dp.id_detalle DESC
                LIMIT 1;';
        // Establecer los parámetros para la consulta (ID del usuario y ID del libro).
        $params = array($_SESSION['idUsuario'], $this->libros);
        // Ejecutar la consulta y verificar si se encontró un resultado.
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['idDetalle'] = $data['id']; // Almacenar el ID del detalle en la sesión.
            $_SESSION['libro'] = $this->libros;   // Almacenar el ID del libro en la sesión.
            return true;
        } else {
            return false;
        }
    }

    /*
     * Método para crear un comentario después de verificar la compra.
     */
    public function createComment()
    {
        // Verificar si el usuario ha comprado el libro.
        if ($this->verifyPurchase()) {
            // Definir la consulta SQL para insertar un nuevo comentario.
            $sql = 'INSERT INTO tb_comentarios(calificacion, comentario, id_detalle)
                    VALUES(?, ?, ?)';
            // Establecer los parámetros para la consulta (calificación, comentario y ID del detalle).
            $params = array($this->calificacion, $this->comentario, $_SESSION['idDetalle']);
            // Ejecutar la consulta y devolver el resultado.
            return Database::executeRow($sql, $params);
        } else {
            return false;
        }
    }
}
