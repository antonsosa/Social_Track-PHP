<?php
    session_start();

    // Conectando con la base de datos 
    require_once "conex.php";

    // Verificar si se ha enviado información
    if (!isset($_POST['email_encargado'], $_POST['password_encargado'])) {
        // Redireccionar si no hay datos
        header('Location: index.php');
        exit; // Salir del script después de la redirección
    }

    // Evitar inyección SQL
    if ($stmt = $conexion->prepare('SELECT e.id_encargado, e.nombres_encargado, e.apellidos_encargado, e.password_encargado, r.id_rol, r.nombre_rol 
                                    FROM encargados e 
                                    INNER JOIN roles r ON e.id_rol_encargado = r.id_rol 
                                    WHERE e.email_encargado = ?')) {
        $stmt->bind_param('s', $_POST['email_encargado']);
        $stmt->execute();

        // Validar si lo ingresado coincide con la base de datos
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_encargado, $nombres_encargado, $apellidos_encargado, $password_encargado, $id_rol, $nombre_rol);
            $stmt->fetch();

            // Validar contraseña con pasword_verify()
            if (password_verify($_POST['password_encargado'], $password_encargado)) { 
                // La conexión es exitosa, se crea la sesión
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['email'] = $_POST['email_encargado'];
                $_SESSION['id'] = $id_encargado;
                $_SESSION['nombre'] = $nombres_encargado;
                $_SESSION['apellido'] = $apellidos_encargado;
                $_SESSION['roles'] = $id_rol;
                $_SESSION['rol'] = $nombre_rol;

                // Redirigir según el rol
                if ($id_rol === 1) {
                    header('Location: adminInicio.php');
                } elseif ($id_rol === 2) {
                    header('Location: encargadoInicio.php');
                } else {
                    // Manejar otro rol si es necesario
                    // Por ejemplo, redirigir a una página de error
                    header('Location: error.php');
                }
                exit; // Salir del script después de la redirección
            }
        }

        // Usuario o contraseña incorrectos
        $_SESSION['error_message'] = "Error de correo o contraseña.";
        header('Location: index.php');
        exit; // Salir del script después de la redirección
    }
?>
