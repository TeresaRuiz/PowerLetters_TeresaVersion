<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class AdministradorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;         // ID del administrador.
    protected $nombre = null;     // Nombre del administrador.
    protected $apellido = null;   // Apellido del administrador.
    protected $correo = null;     // Correo electrónico del administrador.
    protected $alias = null;      // Alias del administrador.
    protected $clave = null;      // Contraseña del administrador (almacenada en forma de hash).

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */

    // Método para verificar el usuario y contraseña.
    public function checkUser($username, $password)
    {
        // Consulta SQL para seleccionar el ID, alias y clave del administrador basándose en el alias.
        $sql = 'SELECT id_administrador, alias_administrador, clave_administrador
                FROM administrador
                WHERE alias_administrador = ?';
        $params = array($username); // Parámetros para la consulta.
        // Obtiene una fila de la base de datos que coincida con el alias proporcionado.
        if (!($data = Database::getRow($sql, $params))) {
            return false; // Devuelve false si no se encuentra el usuario.
        } elseif (password_verify($password, $data['clave_administrador'])) {
            // Si la contraseña proporcionada coincide con el hash almacenado.
            $_SESSION['idAdministrador'] = $data['id_administrador']; // Almacena el ID del administrador en la sesión.
            $_SESSION['aliasAdministrador'] = $data['alias_administrador']; // Almacena el alias del administrador en la sesión.
            return true; // Devuelve true si la contraseña es correcta.
        } else {
            return false; // Devuelve false si la contraseña es incorrecta.
        }
    }

    // Método para verificar la contraseña actual.
    public function checkPassword($password)
    {
        // Consulta SQL para seleccionar la clave del administrador basado en el ID de la sesión.
        $sql = 'SELECT clave_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']); // Parámetros para la consulta.
        $data = Database::getRow($sql, $params); // Obtiene la fila correspondiente al ID del administrador en la sesión.
        // Se verifica si la contraseña proporcionada coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_administrador'])) {
            return true; // Devuelve true si la contraseña es correcta.
        } else {
            return false; // Devuelve false si la contraseña es incorrecta.
        }
    }

    // Método para cambiar la contraseña.
    public function changePassword()
    {
        // Consulta SQL para actualizar la clave del administrador.
        $sql = 'UPDATE administrador
                SET clave_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->clave, $_SESSION['idAdministrador']); // Parámetros para la consulta.
        return Database::executeRow($sql, $params); // Ejecuta la actualización de la contraseña.
    }

    // Método para leer el perfil del administrador.
    public function readProfile()
    {
        // Consulta SQL para seleccionar el perfil del administrador basado en el ID de la sesión.
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, alias_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']); // Parámetros para la consulta.
        return Database::getRow($sql, $params); // Devuelve los datos del perfil del administrador.
    }

    // Método para editar el perfil del administrador.
    public function editProfile()
    {
        // Consulta SQL para actualizar los datos del perfil del administrador.
        $sql = 'UPDATE administrador
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?, alias_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $_SESSION['idAdministrador']); // Parámetros para la consulta.
        return Database::executeRow($sql, $params); // Ejecuta la actualización del perfil del administrador.
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar administradores por nombre o apellido.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%'; // Obtiene el valor de búsqueda y lo formatea para usar en la consulta.
        // Consulta SQL para seleccionar administradores que coincidan con el nombre o apellido proporcionado.
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, alias_administrador
                FROM administrador
                WHERE apellido_administrador LIKE ? OR nombre_administrador LIKE ?
                ORDER BY apellido_administrador';
        $params = array($value, $value); // Parámetros para la consulta.
        return Database::getRows($sql, $params); // Devuelve los resultados de la búsqueda.
    }

    // Método para crear un nuevo administrador.
    public function createRow()
    {
        // Consulta SQL para insertar un nuevo administrador.
        $sql = 'INSERT INTO administrador(nombre_administrador, apellido_administrador, correo_administrador, alias_administrador, clave_administrador)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $this->clave); // Parámetros para la consulta.
        return Database::executeRow($sql, $params); // Ejecuta la inserción de un nuevo administrador.
    }

    // Método para leer todos los administradores.
    public function readAll()
    {
        // Consulta SQL para seleccionar todos los administradores.
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, alias_administrador
                FROM administrador
                ORDER BY apellido_administrador';
        return Database::getRows($sql); // Devuelve todos los administradores.
    }

    // Método para leer un administrador específico.
    public function readOne()
    {
        // Consulta SQL para seleccionar un administrador específico basado en su ID.
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, alias_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($this->id); // Parámetros para la consulta.
        return Database::getRow($sql, $params); // Devuelve los datos de un administrador específico.
    }

    // Método para actualizar un administrador.
    public function updateRow()
    {
        // Consulta SQL para actualizar los datos de un administrador.
        $sql = 'UPDATE administrador
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id); // Parámetros para la consulta.
        return Database::executeRow($sql, $params); // Ejecuta la actualización de un administrador.
    }

    // Método para eliminar un administrador.
    public function deleteRow()
    {
        // Consulta SQL para eliminar un administrador basado en su ID.
        $sql = 'DELETE FROM administrador
                WHERE id_administrador = ?';
        $params = array($this->id); // Parámetros para la consulta.
        return Database::executeRow($sql, $params); // Ejecuta la eliminación de un administrador.
    }
}
