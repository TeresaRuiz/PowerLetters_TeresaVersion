<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class PedidoHandler
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $id_usuario = null;
    protected $direccion = null;
    protected $estado = null;
    protected $fecha = null;

    protected $fechaInicio = null;
    protected $fechaFin = null;
    protected $libro = null;
    protected $cantidad = null;
    protected $id_detalle = null;

    public function getOrder()
    {
        // Establece el estado del pedido como 'PENDIENTE'.
        $this->estado = 'PENDIENTE';
        // Consulta SQL para seleccionar el ID del pedido de la tabla de pedidos,
        // uniendo con la tabla de usuarios para asegurar que el pedido pertenece al usuario actual y está en estado 'PENDIENTE'.
        $sql = 'SELECT p.id_pedido
                 FROM tb_pedidos AS p
                 JOIN tb_usuarios AS u ON p.id_usuario = u.id_usuario
                 WHERE p.estado = ? AND u.id_usuario = ?';
        // Parámetros para la consulta SQL: el estado 'PENDIENTE' y el ID del usuario actual.
        $params = array($this->estado, $_SESSION['idUsuario']);
        // Ejecuta la consulta SQL utilizando el método getRow de la clase Database.
        // getRow devuelve la primera fila del resultado de la consulta como un array asociativo.
        if ($data = Database::getRow($sql, $params)) {
            // Si se obtiene un resultado, se guarda el ID del pedido en la sesión.
            $_SESSION['idPedido'] = $data['id_pedido'];
            // Retorna true indicando que se encontró un pedido pendiente.
            return true;
        } else {
            // Si no se obtiene ningún resultado, retorna false indicando que no hay pedidos pendientes.
            return false;
        }
    }


    public function startOrder()
    {
        // Llama a la función getOrder para verificar si ya existe un pedido en estado 'PENDIENTE' para el usuario actual.
        if ($this->getOrder()) {
            return true;// Si ya existe un pedido pendiente, retorna true.
        } else {
            // Si no existe un pedido pendiente, crea uno nuevo.
            $sql = 'INSERT INTO tb_pedidos(direccion_pedido, id_usuario)
                VALUES((SELECT direccion_usuario FROM tb_usuarios WHERE id_usuario = ?), ?)';
            // Parámetros para la consulta SQL: el ID del usuario actual dos veces.
            $params = array($_SESSION['idUsuario'], $_SESSION['idUsuario']);
            // Ejecuta la consulta SQL para insertar un nuevo pedido y obtiene el ID del último pedido insertado.
            if ($_SESSION['idPedido'] = Database::getLastRow($sql, $params)) {
                return true; // Si la inserción fue exitosa y se obtiene el ID del nuevo pedido, retorna true.
            } else {
                return false;  // Si la inserción falla, retorna false.
            }
        }
    }


    public function createDetail()
    {
        // Consulta SQL para insertar un nuevo detalle de pedido.
        $sql = 'INSERT INTO tb_detalle_pedidos(id_libro, cantidad, precio, id_pedido)
            VALUES(?, ?, (SELECT precio FROM tb_libros WHERE id_libro = ?), ?)';
        // Parámetros para la consulta SQL: el ID del libro, la cantidad, el ID del libro nuevamente para obtener el precio, y el ID del pedido actual.
        $params = array($this->libro, $this->cantidad, $this->libro, $_SESSION['idPedido']);
        // Ejecuta la consulta SQL para insertar el detalle del pedido.
        return Database::executeRow($sql, $params);
    }


    /*
     * Método para obtener los productos que se encuentran en el carrito de compras.
     */
    public function readDetail()
    {
        // Definir la consulta SQL para obtener los detalles del pedido (productos en el carrito).
        $sql = 'SELECT 
                dp.id_detalle,
                l.imagen, 
                l.titulo AS nombre_producto, 
                dp.precio, 
                dp.cantidad
            FROM 
                tb_detalle_pedidos AS dp
            INNER JOIN 
                tb_pedidos AS p ON dp.id_pedido = p.id_pedido
            INNER JOIN 
                tb_libros AS l ON dp.id_libro = l.id_libro
            WHERE 
                p.id_pedido = ?';

        // Establecer los parámetros para la consulta (ID del pedido).
        $params = array($_SESSION['idPedido']);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los detalles del pedido.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para finalizar el pedido.
     */
    public function finishOrder()
    {
        // Establecer el estado del pedido a 'FINALIZADO'.
        $this->estado = 'FINALIZADO';

        // Definir la consulta SQL para actualizar el estado del pedido y establecer la fecha de pedido actual.
        $sql = 'UPDATE 
                tb_pedidos
            SET 
                estado = ?, 
                fecha_pedido = NOW()
            WHERE 
                id_pedido = ?';

        // Establecer los parámetros para la consulta (estado y ID del pedido).
        $params = array($this->estado, $_SESSION['idPedido']);

        // Llamar al método executeRow de la clase Database para ejecutar la actualización del pedido.
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para leer el historial de pedidos finalizados y entregados.
     */
    public function readHistorial($idUsuario)
    {
        // Definir la consulta SQL para obtener el historial de pedidos finalizados y entregados.
        $sql = 'SELECT 
                p.id_pedido, 
                p.fecha_pedido, 
                p.direccion_pedido, 
                p.estado, 
                l.imagen,
                l.titulo AS nombre_libro, 
                dp.precio, 
                dp.cantidad, 
                (dp.precio * dp.cantidad) AS subtotal
            FROM 
                tb_pedidos AS p
            INNER JOIN 
                tb_detalle_pedidos AS dp ON p.id_pedido = dp.id_pedido
            INNER JOIN 
                tb_libros AS l ON dp.id_libro = l.id_libro
            WHERE 
                p.id_usuario = ? 
                AND p.estado IN ("FINALIZADO", "ENTREGADO")
            ORDER BY 
                p.fecha_pedido DESC';

        // Establecer los parámetros para la consulta (ID del usuario).
        $params = array($idUsuario);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver el historial de pedidos.
        return Database::getRows($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        // Obtener las existencias disponibles del libro asociado al detalle del pedido
        $sqlExistencias = 'SELECT existencias FROM tb_libros WHERE id_libro = (
                        SELECT id_libro FROM tb_detalle_pedidos WHERE id_detalle = ?)';
        $paramsExistencias = array($this->id_detalle);
        $existencias = Database::getRow($sqlExistencias, $paramsExistencias)['existencias'];

        // Verificar si la cantidad es igual o menor que las existencias disponibles
        if ($this->cantidad <= $existencias) {
            // Actualizar la cantidad en el detalle del pedido
            $sqlUpdate = 'UPDATE tb_detalle_pedidos
                      SET cantidad = ?
                      WHERE id_detalle = ? AND id_pedido = ?';
            $paramsUpdate = array($this->cantidad, $this->id_detalle, $_SESSION['idPedido']);
            return Database::executeRow($sqlUpdate, $paramsUpdate);
        } else {
            // La cantidad es mayor que las existencias disponibles, devolver un mensaje de error
            return "La cantidad especificada excede las existencias disponibles del libro.";
        }
    }

    public function searchRows()
    {
        // Obtener el valor de búsqueda y envolverlo con comodines para usar con LIKE
        $value = '%' . Validator::getSearchValue() . '%';

        // Definir la consulta SQL para buscar coincidencias en las tablas tb_pedidos, tb_detalle_pedidos y tb_comentarios
        $sql = 'SELECT
                p.id_pedido,
                p.id_usuario,
                u.nombre_usuario,
                p.direccion_pedido,
                p.estado,
                p.fecha_pedido,
                dp.id_detalle,
                dp.id_libro,
                dp.cantidad,
                dp.precio,
                c.id_comentario,
                c.comentario,
                c.calificacion,
                c.estado_comentario
            FROM
                tb_pedidos AS p
            INNER JOIN
                tb_usuarios AS u ON p.id_usuario = u.id_usuario
            LEFT JOIN
                tb_detalle_pedidos AS dp ON p.id_pedido = dp.id_pedido
            LEFT JOIN
                tb_comentarios AS c ON dp.id_detalle = c.id_detalle
            WHERE
                p.id_pedido LIKE ? OR
                CAST(p.id_usuario AS CHAR) LIKE ? OR
                u.nombre_usuario LIKE ? OR
                p.direccion_pedido LIKE ? OR
                p.estado LIKE ? OR
                p.fecha_pedido LIKE ? OR
                dp.id_detalle LIKE ? OR
                dp.id_libro LIKE ? OR
                dp.cantidad LIKE ? OR
                dp.precio LIKE ? OR
                c.id_comentario LIKE ? OR
                c.comentario LIKE ? OR
                c.calificacion LIKE ? OR
                c.estado_comentario LIKE ?
            ORDER BY
                p.fecha_pedido;';

        // Establecer los parámetros para la consulta (el término de búsqueda)
        $params = array(
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value,
            $value
        );

        // Ejecutar la consulta y devolver las filas resultantes
        return Database::getRows($sql, $params);
    }

    /*
     * Método para leer todos los registros de la tabla tb_pedidos.
     */
    public function readAll()
    {
        // Definir la consulta SQL para obtener todos los registros
        $sql = 'SELECT
                p.id_pedido,
                p.id_usuario,
                u.nombre_usuario,
                p.direccion_pedido,
                p.estado,
                p.fecha_pedido,
                dp.id_detalle,
                dp.id_libro,
                dp.cantidad,
                dp.precio,
                c.id_comentario,
                c.comentario,
                c.calificacion,
                c.estado_comentario
            FROM
                tb_pedidos AS p
            INNER JOIN
                tb_usuarios AS u ON p.id_usuario = u.id_usuario
            LEFT JOIN
                tb_detalle_pedidos AS dp ON p.id_pedido = dp.id_pedido
            LEFT JOIN
                tb_comentarios AS c ON dp.id_detalle = c.id_detalle
            ORDER BY
                p.fecha_pedido;';

        // Ejecutar la consulta y devolver las filas resultantes
        return Database::getRows($sql);
    }

    /*
     * Método para leer un registro específico de la tabla tb_pedidos por su id.
     */
    public function readOne()
    {
        // Definir la consulta SQL para obtener un registro específico por id
        $sql = 'SELECT
                    p.id_pedido,
                    p.id_usuario,
                    u.nombre_usuario,
                    p.direccion_pedido,
                    p.estado,
                    p.fecha_pedido,
                    dp.id_detalle,
                    dp.id_libro,
                    dp.cantidad,
                    dp.precio,
                    l.titulo,
                    l.imagen AS imagen_libro,
                    c.id_comentario,
                    c.comentario,
                    c.calificacion,
                    c.estado_comentario
                FROM
                    tb_pedidos AS p
                INNER JOIN
                    tb_usuarios AS u ON p.id_usuario = u.id_usuario
                LEFT JOIN
                    tb_detalle_pedidos AS dp ON p.id_pedido = dp.id_pedido
                LEFT JOIN
                    tb_libros AS l ON dp.id_libro = l.id_libro
                LEFT JOIN
                    tb_comentarios AS c ON dp.id_detalle = c.id_detalle
                WHERE
                    p.id_pedido = ?';

        // Establecer los parámetros para la consulta (id)
        $params = array($this->id);

        // Ejecutar la consulta y devolver el resultado
        return Database::getRow($sql, $params);
    }
    /*
     * Método para actualizar un registro específico de la tabla tb_pedidos por su id.
     */
    public function updateRow()
    {
        // Definir la consulta SQL para actualizar los campos dirección y estado
        $sql = 'UPDATE tb_pedidos
                SET direccion_pedido = ?, estado = ?
                WHERE id_pedido = ?';

        // Establecer los parámetros para la consulta (dirección, estado y id)
        $params = array($this->direccion, $this->estado, $this->id);

        // Ejecutar la consulta y devolver el resultado
        return Database::executeRow($sql, $params);
    }
    /*
     * Método para eliminar un detalle del pedido.
     */
    public function deleteDetail()
    {
        // Definir la consulta SQL para eliminar un detalle específico del pedido.
        $sql = 'DELETE FROM tb_detalle_pedidos
            WHERE id_detalle = ? AND id_pedido = ?';

        // Establecer los parámetros para la consulta (ID del detalle y ID del pedido).
        $params = array($this->id_detalle, $_SESSION['idPedido']);

        // Llamar al método executeRow de la clase Database para ejecutar la eliminación del detalle del pedido.
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para obtener la distribución de pedidos por estado.
     */
    public function readDistribucionPedidosPorEstado()
    {
        // Definir la consulta SQL para obtener la cantidad de pedidos por estado.
        $sql = 'SELECT estado, COUNT(id_pedido) AS cantidad
            FROM tb_pedidos
            GROUP BY estado';

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver la distribución de pedidos por estado.
        return Database::getRows($sql);
    }

    /*
     * Método para obtener la evolución de los pedidos por estado de un usuario específico.
     */
    public function readEvolucionPedidosPorEstadoPorUsuario()
    {
        // Definir la consulta SQL para obtener la cantidad de pedidos por estado de un usuario específico.
        $sql = 'SELECT estado, COUNT(id_pedido) AS total_pedidos
            FROM tb_pedidos
            WHERE id_usuario = ?
            GROUP BY estado
            ORDER BY total_pedidos DESC';

        // Establecer los parámetros para la consulta (ID del usuario).
        $params = array($this->id_usuario);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver la evolución de los pedidos por estado.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para obtener las ventas por período.
     */
    public function ventasPorPeriodo()
    {
        // Definir la consulta SQL para obtener las ventas por período.
        $sql = 'SELECT p.fecha_pedido, u.nombre_usuario, u.apellido_usuario, 
                   SUM(dp.cantidad * dp.precio) AS total_venta
            FROM tb_pedidos p
            INNER JOIN tb_usuarios u ON p.id_usuario = u.id_usuario
            INNER JOIN tb_detalle_pedidos dp ON p.id_pedido = dp.id_pedido
            WHERE p.fecha_pedido BETWEEN ? AND ?
              AND p.estado = "FINALIZADO"
            GROUP BY p.id_pedido
            ORDER BY p.fecha_pedido';

        // Establecer los parámetros para la consulta (fechas de inicio y fin del período).
        $params = array($this->fechaInicio, $this->fechaFin);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver las ventas por período.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para obtener el total de ventas y pedidos por período.
     */
    public function totalVentasYPedidos()
    {
        // Definir la consulta SQL para obtener el número total de pedidos y el total de ventas por período.
        $sql = 'SELECT COUNT(DISTINCT p.id_pedido) AS numero_pedidos,
                   SUM(dp.cantidad * dp.precio) AS total_ventas
            FROM tb_pedidos p
            INNER JOIN tb_detalle_pedidos dp ON p.id_pedido = dp.id_pedido
            WHERE p.fecha_pedido BETWEEN ? AND ?
              AND p.estado = "FINALIZADO"';

        // Establecer los parámetros para la consulta (fechas de inicio y fin del período).
        $params = array($this->fechaInicio, $this->fechaFin);

        // Llamar al método getRow de la clase Database para ejecutar la consulta y devolver el total de ventas y pedidos.
        return Database::getRow($sql, $params);
    }

    /*
     * Método para obtener los libros más vendidos por período.
     */
    public function librosMasVendidos()
    {
        // Definir la consulta SQL para obtener los libros más vendidos por período.
        $sql = 'SELECT l.titulo, SUM(dp.cantidad) AS total_vendido
            FROM tb_libros l
            INNER JOIN tb_detalle_pedidos dp ON l.id_libro = dp.id_libro
            INNER JOIN tb_pedidos p ON dp.id_pedido = p.id_pedido
            WHERE p.fecha_pedido BETWEEN ? AND ?
              AND p.estado = "FINALIZADO"
            GROUP BY l.id_libro
            ORDER BY total_vendido DESC
            LIMIT 5';

        // Establecer los parámetros para la consulta (fechas de inicio y fin del período).
        $params = array($this->fechaInicio, $this->fechaFin);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los libros más vendidos.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para obtener los pedidos pendientes.
     */
    public function obtenerPedidosPendientes()
    {
        // Definir la consulta SQL para obtener los pedidos pendientes.
        $sql = 'SELECT 
                p.id_pedido,
                p.fecha_pedido,
                u.nombre_usuario,
                u.apellido_usuario,
                p.direccion_pedido,
                SUM(dp.cantidad * dp.precio) AS total_pedido
            FROM 
                tb_pedidos p
            INNER JOIN 
                tb_usuarios u ON p.id_usuario = u.id_usuario
            INNER JOIN 
                tb_detalle_pedidos dp ON p.id_pedido = dp.id_pedido
            WHERE 
                p.estado = "PENDIENTE"
            GROUP BY 
                p.id_pedido
            ORDER BY 
                p.fecha_pedido DESC';

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los pedidos pendientes.
        return Database::getRows($sql);
    }

    /*
     * Método para obtener el total de pedidos pendientes.
     */
    public function obtenerTotalPedidosPendientes()
    {
        // Definir la consulta SQL para obtener el total de pedidos y el valor total de los pedidos pendientes.
        $sql = 'SELECT 
                COUNT(DISTINCT p.id_pedido) AS total_pedidos,
                SUM(dp.cantidad * dp.precio) AS valor_total_pendiente
            FROM 
                tb_pedidos p
            INNER JOIN 
                tb_detalle_pedidos dp ON p.id_pedido = dp.id_pedido
            WHERE 
                p.estado = "PENDIENTE"';

        // Llamar al método getRow de la clase Database para ejecutar la consulta y devolver el total de pedidos pendientes.
        return Database::getRow($sql);
    }

    /*
     * Método para obtener los productos del último pedido finalizado realizado por el usuario y los datos del usuario.
     */
    public function readDetailReport()
    {
        // Definir la consulta SQL para obtener los detalles del último pedido finalizado realizado por el usuario.
        $sql = 'SELECT 
                u.nombre_usuario AS NOMBRE_USUARIO,
                u.apellido_usuario AS APELLIDO_USUARIO,
                u.correo_usuario AS CORREO,
                u.telefono_usuario AS TELEFONO,
                u.direccion_usuario AS DIRECCION,
                u.dui_usuario AS DUI,
                dp.id_detalle AS ID,
                l.imagen AS IMAGEN,
                l.titulo AS NOMBRE,
                dp.cantidad AS CANTIDAD,
                dp.precio AS PRECIO,
                ROUND(dp.precio * dp.cantidad, 2) AS TOTAL
            FROM tb_detalle_pedidos dp
            JOIN tb_libros l ON dp.id_libro = l.id_libro
            JOIN tb_pedidos p ON dp.id_pedido = p.id_pedido
            JOIN tb_usuarios u ON p.id_usuario = u.id_usuario
            WHERE dp.id_pedido = (
                SELECT id_pedido
                FROM tb_pedidos
                WHERE id_usuario = ? AND estado = "FINALIZADO"
                ORDER BY id_pedido DESC
                LIMIT 1
            );';

        // Establecer los parámetros para la consulta (ID del usuario).
        $params = array($_SESSION['idUsuario']);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los detalles del último pedido finalizado.
        return Database::getRows($sql, $params);
    }

    /*
     * Método para obtener todos los usuarios únicos que han realizado pedidos.
     */
    public function readAllUniqueUsers()
    {
        // Definir la consulta SQL para obtener todos los usuarios únicos que han realizado pedidos.
        $sql = 'SELECT DISTINCT
                u.id_usuario,
                u.nombre_usuario,
                (SELECT MAX(fecha_pedido) FROM tb_pedidos WHERE id_usuario = u.id_usuario) AS ultima_fecha_pedido
            FROM
                tb_usuarios AS u
            INNER JOIN
                tb_pedidos AS p ON u.id_usuario = p.id_usuario
            ORDER BY
                ultima_fecha_pedido DESC';

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los usuarios únicos.
        return Database::getRows($sql);
    }

    /*
     * Método para obtener todos los pedidos de un usuario específico.
     */
    public function readPedidosByUser()
    {
        // Definir la consulta SQL para obtener todos los pedidos de un usuario específico.
        $sql = 'SELECT
                p.id_pedido,
                p.direccion_pedido,
                p.estado,
                p.fecha_pedido
            FROM
                tb_pedidos AS p
            WHERE
                p.id_usuario = ?
            ORDER BY
                p.fecha_pedido DESC';

        // Establecer los parámetros para la consulta (ID del usuario).
        $params = array($this->id_usuario);

        // Llamar al método getRows de la clase Database para ejecutar la consulta y devolver los pedidos del usuario.
        return Database::getRows($sql, $params);
    }
    public function readVentasDiarias()
    {
        $sql = 'SELECT DATE(fecha_pedido) AS fecha, 
        COUNT(*) AS total_pedidos FROM tb_pedidos 
        WHERE fecha_pedido >= CURDATE() - INTERVAL 7 DAY 
        AND estado = "FINALIZADO" GROUP BY DATE(fecha_pedido) ORDER BY fecha';
        return Database::getRows($sql);
    }


}
