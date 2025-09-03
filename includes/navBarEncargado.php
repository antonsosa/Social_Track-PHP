    <!-- Navbar o barra de navegacion con menu -->
    <div class="container-fluid" style="background-color: #1E3A8A;">
            <div class="container">
                <nav class="navbar navbar-dark navbar-expand-lg py-lg-0">
                    <a href="encargadoInicio.php" class="navbar-brand">
                        <h1 class="text-primary mb-0 display-5"><span class="text-white">SocialTrack</span><img src=""></h1>
                    </a>
                    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-dark"></span>
                    </button>


                    <div class="collapse navbar-collapse me-n3" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="encargadoInicio.php" class="nav-item nav-link<?php echo ($currentPage === 'adminInicio.php') ? ' active' : ''; ?>">Inicio</a>
                            
                            <!-- Enlace "Agregar" con elemento desplegable -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Agregar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="encargadoAgregarAlumno.php" class="dropdown-item<?php echo ($currentPage === 'encargadoAgregarAlumno.php') ? ' active' : ''; ?>">Agregar Datos de Alumnos</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Modificar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="encargadoModificarAlumno.php" class="dropdown-item<?php echo ($currentPage === 'encargadoModificarAlumno.php') ? ' active' : ''; ?>">Modificar Datos de Alumnos</a></li>
                                    <li><a href="encargadoCambioPassword.php" class="dropdown-item<?php echo ($currentPage === 'encargadoCambioPassword.php') ? ' active' : ''; ?>">Cambio de Contraseña</a></li>
                                </ul>
                            </li>
                     
                            <a href="encargadoMarcacion.php" class="nav-item nav-link<?php echo ($currentPage === 'encargadoMarcacion.php') ? ' active' : ''; ?>">Marcacion</a>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Consultas
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="reporteAlumnosEncargado.php" class="dropdown-item<?php echo ($currentPage === 'reporteAlumnosEncargado.php') ? ' active' : ''; ?>">Consultar Alumnos</a></li> 
                                    <li><a href="reporteEncargadosLabsEncargado.php" class="dropdown-item<?php echo ($currentPage === 'reporteEncargadosLabsEncargado.php') ? ' active' : ''; ?>">Laboratorios Asignados</a></li>
                                    <li><a href="reporteLabsEncargado.php" class="dropdown-item<?php echo ($currentPage === 'reporteLabsEncargado.php') ? ' active' : ''; ?>">Información de Laboratorios</a></li>
                                </ul>
                            </li>
                            
                            <a href="cerrar-sesion.php" class="nav-item nav-link">Salir</a>
                        </div>
                    </div>
                </nav>
            </div>
    </div>
