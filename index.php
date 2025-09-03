<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Sistema de control de servicio social en los laboratorios de informática</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- Link para cargar carpeta local de estilos -->
    <link rel="stylesheet" href="./css/estiloLogin.css">  
     <!-- Link para cargar librerias de bootstrap y js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </head>

  <body>
    <section class="vh-100">
      <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-md-9 col-lg-6 col-xl-4">
            <img src="./img/SocialTrackLogo.png"
              class="img-fluid" alt="logoUtec" >
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <form action="autenticacion.php" method="post">
              <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0" style="margin-top: -10px;";><font size="8">Registro y Control <br> Academico<br> de Horas Sociales!</font></p>
              </div><br><br>
              
              <!-- Input para ingresar email -->
              <div data-mdb-input-init class="form-outline mb-4">
                <input name="email_encargado" type="email" id="email_encargado" class="form-control form-control-lg" placeholder="Correo electrónico" />             
              </div>
              
              <!-- Input para ingresar password -->
              <div data-mdb-input-init class="form-outline mb-3">
                <input name="password_encargado" type="password" id="password_encargado" class="form-control form-control-lg" placeholder="Contraseña" />             
              </div>
              <div class="d-flex justify-content-between align-items-center"></div>
              <!-- Guarda variables de sesion y muestra mensaje de error -->
              <script>
                <?php
                  session_start();
                  if (isset($_SESSION['error_message'])) {
                      echo 'alert("' . $_SESSION['error_message'] . '");';
                      unset($_SESSION['error_message']); // Limpiar el mensaje de error de la sesión
                  }
                ?>
              </script>

              <div class="text-center text-lg-start mt-4 pt-2">
                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn-custom">Login</button>
              </div>                        
            </form>
          </div>
        </div>
      </div>
      <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5" 
        style="background-color: #1E3A8A;">
        <div class="text-white mb-3 mb-md-0">    Copyright © 2025. All rights reserved.   </div>
      </div> 
    </section>
  </body>
</html>
