<?php
// Se incluye la clase del modelo.
require_once('../../models/data/usuario_data.php');
require_once('../../services/admin/mail_config.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new UsuarioData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'recaptcha' => 0, 'message' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como usuario para realizar las acciones correspondientes.
    if (isset($_SESSION['idUsuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un usuario ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['correoUsuario'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['correoUsuario'];
                    $result['name'] = $usuario->readOneCorreo($_SESSION['correoUsuario']);
                } else {
                    $result['error'] = 'Correo de usuario indefinido';
                    $result['name'] = 'No se pudo obtener el usuario';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $usuario->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$usuario->setNombre($_POST['nombre_usuario']) or
                    !$usuario->setApellido($_POST['apellido_usuario']) or
                    !$usuario->setCorreo($_POST['correo_usuario']) or
                    !$usuario->setDireccion($_POST['direccion_usuario']) or
                    !$usuario->setDUI($_POST['dui_usuario']) or
                    !$usuario->setNacimiento($_POST['nacimiento_usuario']) or
                    !$usuario->setTelefono($_POST['telefono_usuario']) or
                    !$usuario->setImagen($_FILES['imagen']) or
                    !$usuario->setClave($_POST['clave_usuario'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($_POST['clave_usuario'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el usuario';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen usuarios registrados';
                }
                break;
            case 'readOne':
                if (!$usuario->setId($_POST['id_usuario'])) {
                    $result['error'] = 'Usuario incorrecto';
                } elseif ($result['dataset'] = $usuario->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Usuario inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$usuario->setId($_POST['id_usuario']) or
                    !$usuario->setEstado($_POST['estado_cliente'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el usuario';
                }
                break;
            case 'deleteRow':
                if ($_POST['idUsuario'] == $_SESSION['idUsuario']) {
                    $result['error'] = 'No se puede eliminar a sí mismo';
                } elseif (!$usuario->setId($_POST['idUsuario'])) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el usuario';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $usuario->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$usuario->setNombre($_POST['nombre_usuario']) or
                    !$usuario->setApellido($_POST['apellido_usuario']) or
                    !$usuario->setCorreo($_POST['correo_usuario']) or
                    !$usuario->setDireccion($_POST['direccion_usuario']) or
                    !$usuario->setDUI($_POST['dui_usuario']) or
                    !$usuario->setNacimiento($_POST['nacimiento_usuario']) or
                    !$usuario->setTelefono($_POST['telefono_usuario'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el perfil';
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->checkPassword($_POST['claveActual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$usuario->setClave($_POST['claveNueva'])) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            case 'readUsuariosPorMes':
                if ($result['dataset'] = $usuario->readUsuariosPorMes()) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['error'] = 'No hay usuarios registrados por el momento';
                }
                break;
            case 'getUsuariosActivosInactivos':
                if ($result['dataset'] = $usuario->getUsuariosActivosInactivos()) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['error'] = 'No hay usuarios registrados por el momento';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el usuario no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                // Se establece la clave secreta para el reCAPTCHA de acuerdo con la cuenta de Google.
                $secretKey = '6LdBzLQUAAAAAL6oP4xpgMao-SmEkmRCpoLBLri-';
                // Se establece la dirección IP del servidor.
                $ip = $_SERVER['REMOTE_ADDR'];
                // Se establecen los datos del reCAPTCHA.
                $data = array('secret' => $secretKey, 'response' => $_POST['gRecaptchaResponse'], 'remoteip' => $ip);
                // Se establecen las opciones del reCAPTCHA.
                $options = array(
                    'http' => array('header' => 'Content-type: application/x-www-form-urlencoded\r\n', 'method' => 'POST', 'content' => http_build_query($data)),
                    'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
                );

                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $context = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                $captcha = json_decode($response, true);

                if (!$captcha['success']) {
                    $result['recaptcha'] = 1;
                    $result['error'] = 'No eres humano';
                } elseif (!isset($_POST['condicion'])) {
                    $result['error'] = 'Debe marcar la aceptación de términos y condiciones';
                } elseif (
                    !$usuario->setNombre($_POST['nombre_usuario']) or
                    !$usuario->setApellido($_POST['apellido_usuario']) or
                    !$usuario->setCorreo($_POST['correo_usuario']) or
                    !$usuario->setDireccion($_POST['direccion_usuario']) or
                    !$usuario->setDUI($_POST['dui_usuario']) or
                    !$usuario->setNacimiento($_POST['nacimiento_usuario']) or
                    !$usuario->setTelefono($_POST['telefono_usuario']) or
                    !$usuario->setImagen($_FILES['imagen']) or
                    !$usuario->setClave($_POST['clave_usuario'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($_POST['clave_usuario'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->checkUser($_POST['correo_usuario'], $_POST['clave_usuario'])) {
                    $result['error'] = 'Datos incorrectos';
                } elseif ($usuario->checkStatus()) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'La cuenta ha sido desactivada';
                }
                break;

            case 'signUpMovil':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$usuario->setNombre($_POST['nombre_usuario']) or
                    !$usuario->setApellido($_POST['apellido_usuario']) or
                    !$usuario->setCorreo($_POST['correo_usuario']) or
                    !$usuario->setDireccion($_POST['direccion_usuario']) or
                    !$usuario->setDUI($_POST['dui_usuario']) or
                    !$usuario->setNacimiento($_POST['nacimiento_usuario']) or
                    !$usuario->setTelefono($_POST['telefono_usuario']) or
                    !$usuario->setClave($_POST['clave_usuario'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($_POST['clave_usuario'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;
            //Metodos para recuperación de contraseña en movil
            case 'solicitarPinRecuperacion':
                $_POST = Validator::validateForm($_POST);
                if (!isset($_POST['correo'])) {
                    $result['error'] = 'Falta el correo electrónico';
                } elseif (!$usuario->setCorreos($_POST['correo'])) {
                    $result['error'] = 'Correo electrónico inválido';
                } else {
                    // Verificar si el correo existe en la base de datos
                    $checkCorreoSql = 'SELECT COUNT(*) as count, nombre_usuario FROM tb_usuarios WHERE correo_usuario = ?';
                    $checkCorreoParams = array($_POST['correo']);
                    $checkCorreoResult = Database::getRow($checkCorreoSql, $checkCorreoParams);

                    if ($checkCorreoResult['count'] == 0) {
                        $result['error'] = 'No existe una cuenta asociada a este correo electrónico';
                    } elseif ($pin = $usuario->generarPinRecuperacion($_POST['correo'])) {
                        $result['status'] = 1;
                        $result['message'] = 'PIN generado con éxito';

                        // Enviar correo con el PIN
                        $email = $_POST['correo'];
                        $nombre = $checkCorreoResult['nombre_usuario'];
                        $subject = "Recuperacion de clave - Power Letters";
                        $body = "
                            <p>Estimado/a {$nombre},</p>
                            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Power Letters.</p>
                            <p>Tu PIN de recuperación es: <strong>{$pin}</strong></p>
                            <p>Este PIN es válido por los próximos 30 minutos. Si no solicitaste este cambio, por favor ignora este mensaje.</p>
                            <p>Para completar el proceso de recuperación de contraseña, ingresa este PIN en la aplicación.</p>
                            <p>Si tienes alguna pregunta o necesitas ayuda adicional, no dudes en contactarnos.</p>
                            <p>Saludos cordiales,<br>
                            El equipo de Power Letters$</p>
                        ";

                        $emailResult = sendEmail($email, $subject, $body);
                        if ($emailResult !== true) {
                            $result['message'] .= ' Sin embargo, no se pudo enviar el correo con el PIN.';
                        }
                    } else {
                        $result['error'] = 'No se pudo generar el PIN';
                    }
                }
                break;
            case 'verificarPin':
                if (!isset($_POST['correo']) || !isset($_POST['pin'])) {
                    $result['error'] = 'Faltan datos necesarios';
                } elseif (!$usuario->setCorreo($_POST['correo'])) {
                    $result['error'] = 'Correo electrónico inválido';
                } else {
                    $id_usuario = $usuario->verificarPinRecuperacion($_POST['pin']);
                    if ($id_usuario) {
                        $result['status'] = 1;
                        $result['id_usuario'] = $id_usuario;
                        $result['message'] = 'PIN verificado correctamente';
                    } else {
                        $result['error'] = 'PIN inválido o expirado';
                    }
                }
                break;
            case 'cambiarClaveConPin':
                $_POST = Validator::validateForm($_POST);
                if (!isset($_POST['id_usuario']) || !isset($_POST['nuevaClave'])) {
                    $result['error'] = 'Faltan datos necesarios';
                } else {
                    $id_usuario = $_POST['id_usuario'];
                    $nuevaClave = $_POST['nuevaClave'];
                    if ($usuario->cambiarClaveConPin($id_usuario, $nuevaClave)) {
                        $result['status'] = 1;
                        $result['message'] = 'Contraseña cambiada exitosamente';
                        $usuario->resetearPin(); // Resetea el PIN para que no se pueda usar nuevamente
                    } else {
                        $result['error'] = 'No se pudo cambiar la contraseña';
                    }
                }
                break;

            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print (json_encode($result));
} else {
    print (json_encode('Recurso no disponible'));
}
