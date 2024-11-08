<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *	Clase para manejar el comportamiento de los datos de la tabla usuario.
 */
class UsuarioHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $telefono = null;
    protected $dui = null;
    protected $nacimiento = null;
    protected $direccion = null;
    protected $clave = null;
    protected $estado = null;
    protected $imagen = null;

    //Establecer donde se guardaran imagenes de usuarios
    const RUTA_IMAGEN = '../../images/usuarios/';

    /*
     *   Métodos para gestionar la cuenta del usuario.
     */
    public function checkUser($mail, $password)
    {
        $sql = 'SELECT id_usuario, correo_usuario, clave_usuario, estado_cliente
                FROM tb_usuarios
                WHERE correo_usuario = ?';
        $params = array($mail);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave_usuario'])) {
            $this->id = $data['id_usuario'];
            $this->correo = $data['correo_usuario'];
            $this->estado = $data['estado_cliente'];
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['idUsuario']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_usuario'])) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Método para verificar el estado del usuario y establecer variables de sesión.
     */
    public function checkStatus()
    {
        // Verificar si el estado del usuario está definido y es verdadero.
        if ($this->estado) {
            // Si el estado es verdadero, establecer las variables de sesión con el ID y correo del usuario.
            $_SESSION['idUsuario'] = $this->id;
            $_SESSION['correoUsuario'] = $this->correo;

            // Devolver verdadero indicando que el estado es válido.
            return true;
        } else {
            // Si el estado no es verdadero, devolver falso indicando que el estado no es válido.
            return false;
        }
    }

    /*
     *   Métodos para cambiar la contraseña
     */
    public function changePassword()
    {
        $sql = 'UPDATE tb_usuarios
                SET clave_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->clave, $_SESSION['idUsuario']);
        return Database::executeRow($sql, $params);
    }
    /*
     *   Métodos para leer el perfil
     */
    public function readProfile()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, telefono_usuario, nacimiento_usuario, direccion_usuario, estado_cliente, imagen
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['idUsuario']);
        return Database::getRow($sql, $params);
    }

    /*
     *   Métodos para editar el perfil
     */
    public function editProfile()
    {
        $sql = 'UPDATE tb_usuarios
                SET nombre_usuario = ?, apellido_usuario = ?, correo_usuario = ?, dui_usuario = ?, telefono_usuario = ?, nacimiento_usuario = ?, direccion_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->dui, $this->telefono, $this->nacimiento, $this->direccion, $_SESSION['idUsuario']);
        return Database::executeRow($sql, $params);
    }

    /*
     *   Métodos para cambiar el estado
     */

    public function changeStatus()
    {
        $sql = 'UPDATE tb_usuarios
                SET estado_cliente = ?
                WHERE id_usuario = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, telefono_usuario, nacimiento_usuario, direccion_usuario
                FROM tb_usuarios
                WHERE apellido_usuario LIKE ? OR nombre_usuario LIKE ? OR correo_usuario LIKE ?
                ORDER BY apellido_usuario';
        $params = array($value, $value, $value);
        return Database::getRows($sql, $params);
    }

    /*
     *   Métodos para crear usuarios
     */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_usuarios(nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, telefono_usuario, nacimiento_usuario, direccion_usuario, clave_usuario, imagen)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->dui, $this->telefono, $this->nacimiento, $this->direccion, $this->clave, $this->imagen);
        return Database::executeRow($sql, $params);
    }


    /*
     *   Métodos para leer todo
     */
    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, estado_cliente, imagen
                FROM tb_usuarios
                ORDER BY apellido_usuario';
        return Database::getRows($sql);
    }


    /*
     *   Métodos para leer solo uno
     */
    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, estado_cliente, imagen
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
     *   Métodos para actualizar
     */
    public function updateRow()
    {
        $sql = 'UPDATE tb_usuarios
                SET estado_cliente = ?
                WHERE id_usuario = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
     *   Métodos para eliminar
     */
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Metodo para validar que no se ingresen datos iguales
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_usuario
                FROM tb_usuarios
                WHERE dui_usuario = ? OR correo_usuario = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }
    //Metodo para leer las imagenes
    public function readFilename()
    {
        $sql = 'SELECT imagen FROM tb_usuarios WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para obtener los usuarios registrados por mes del año actual
    public function readUsuariosPorMes()
    {
        // Consulta SQL para obtener el número de usuarios registrados por mes en el año 2024
        $sql = 'SELECT ELT(MONTH(fecha_registro), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") AS mes, 
               COUNT(*) AS total
            FROM tb_usuarios
            WHERE YEAR(fecha_registro) = 2024
            GROUP BY mes
            ORDER BY MIN(fecha_registro) ASC';

        // Ejecutar la consulta y devolver las filas resultantes
        return Database::getRows($sql);
    }

    // Método para obtener los clientes frecuentes y sus estadísticas
    public function clientesFrecuentes()
    {
        // Consulta SQL para obtener los usuarios con el mayor número de pedidos finalizados y el monto total de sus compras
        $sql = 'SELECT u.nombre_usuario, u.apellido_usuario, COUNT(p.id_pedido) AS total_pedidos,
                   SUM(dp.cantidad * dp.precio) AS monto_total
            FROM tb_usuarios u
            INNER JOIN tb_pedidos p ON u.id_usuario = p.id_usuario
            INNER JOIN tb_detalle_pedidos dp ON p.id_pedido = dp.id_pedido
            WHERE p.estado = "FINALIZADO"
            GROUP BY u.id_usuario
            ORDER BY total_pedidos DESC
            LIMIT 10';

        // Ejecutar la consulta y devolver las filas resultantes
        return Database::getRows($sql);
    }

    // Método para obtener el número de usuarios activos e inactivos
    public function getUsuariosActivosInactivos()
    {
        // Consulta SQL para contar los usuarios activos e inactivos agrupados por su estado
        $sql = 'SELECT 
                CASE 
                    WHEN estado_cliente = 1 THEN "Activos" 
                    ELSE "Inactivos" 
                END as estado_usuario,
                COUNT(*) as total_usuarios
            FROM tb_usuarios
            GROUP BY estado_usuario';

        // Ejecutar la consulta y devolver las filas resultantes
        return Database::getRows($sql);
    }
    public function readOneCorreo($correo)
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, dui_usuario, telefono_usuario, nacimiento_usuario, direccion_usuario, estado_cliente
        FROM tb_usuarios
        WHERE correo_usuario = ?';
        $params = array($correo);
        return Database::getRow($sql, $params);
    }

    public function generarPinRecuperacion()
    {
        $pin = sprintf("%06d", mt_rand(1, 999999)); // Genera un PIN de 6 dígitos
        $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes')); // 30 minutos desde ahora

        $sql = "UPDATE tb_usuarios SET recovery_pin = ?, pin_expiry = ? WHERE correo_usuario = ?";
        $params = array($pin, $expiry, $this->correo);

        if (Database::executeRow($sql, $params)) {
            return $pin; // Retorna el PIN para enviarlo al usuario
        } else {
            // Manejo de errores
            error_log("Error al generar el PIN de recuperación para el correo: " . $this->correo);
        }
        return false;
    }

    public function verificarPinRecuperacion($pin)
    {
        $sql = "SELECT id_usuario FROM tb_usuarios 
            WHERE correo_usuario = ? AND recovery_pin = ? AND pin_expiry > NOW()";
        $params = array($this->correo, $pin);

        $result = Database::getRow($sql, $params);

        if ($result) {
            return $result['id_usuario'];
        } else {
            // Manejo de errores
            error_log("Error al verificar el PIN de recuperación para el correo: " . $this->correo);
        }
        return false;
    }

    public function resetearPin()
    {
        $sql = "UPDATE tb_usuarios SET recovery_pin = NULL, pin_expiry = NULL WHERE id_usuario = ?";
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function cambiarClaveConPin($id_usuario, $nuevaClave)
    {
        $sql = 'UPDATE tb_usuarios SET clave_usuario = ? WHERE id_usuario = ?';
        $params = array(password_hash($nuevaClave, PASSWORD_DEFAULT), $id_usuario);
        return Database::executeRow($sql, $params);
    }

}
