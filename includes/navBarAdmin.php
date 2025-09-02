    <!-- Navbar o barra de navegacion con menu -->
        <div class="container-fluid" style="background-color: #5a1533;">
            <div class="container">
                <nav class="navbar navbar-dark navbar-expand-lg py-lg-0">
                    <a href="adminInicio.php" class="navbar-brand">
                        <h1 class="text-primary mb-0 display-5"><span class="text-white">UTEC </span><img src=""></h1>
                    </a>
                    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-dark"></span>
                    </button>


                    <div class="collapse navbar-collapse me-n3" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="adminInicio.php" class="nav-item nav-link<?php echo ($currentPage === 'adminInicio.php') ? ' active' : ''; ?>">Inicio</a>
                            
                            <!-- Enlace "Agregar" con elemento desplegable -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Agregar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="adminAgregarEncargado.php" class="dropdown-item<?php echo ($currentPage === 'adminAgregarEncargado.php') ? ' active' : ''; ?>">Agregar Datos de Encargados</a></li>
                                    <li><a href="adminAgregarAlumno.php" class="dropdown-item<?php echo ($currentPage === 'adminAgregarAlumno.php') ? ' active' : ''; ?>">Agregar Datos de Alumnos</a></li>
                                    <li><a href="adminAgregarLabs.php" class="dropdown-item<?php echo ($currentPage === 'adminAgregarLabs.php') ? ' active' : ''; ?>">Agregar Datos de Laboratorios</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Modificar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="adminModificarE.php" class="dropdown-item<?php echo ($currentPage === 'adminModificarE.php') ? ' active' : ''; ?>">Modificar Datos de Encargados</a></li>
                                    <li><a href="adminModificarAlumno.php" class="dropdown-item<?php echo ($currentPage === 'adminModificarAlumno.php') ? ' active' : ''; ?>">Modificar Datos de Alumnos</a></li>
                                    <li><a href="adminCicloEnCurso.php" class="dropdown-item<?php echo ($currentPage === 'adminCicloEnCurso.php') ? ' active' : ''; ?>">Ciclo en Curso</a></li>
                                    <li><a href="adminCambioPassword.php" class="dropdown-item<?php echo ($currentPage === 'adminCambioPassword.php') ? ' active' : ''; ?>">Cambio de Contraseña</a></li>
                                    <li><a href="adminModificarLabs.php" class="dropdown-item<?php echo ($currentPage === 'adminModificarLabs.php') ? ' active' : ''; ?>">Modificar Datos de Laboratorios</a></li>
                                    <li><a href="adminModificarMarcacion.php" class="dropdown-item<?php echo ($currentPage === 'adminModificarMarcacion.php') ? ' active' : ''; ?>">Modificar Datos de Marcación</a></li>
                                    
                                </ul>
                            </li>
                     
                            <a href="adminMarcacion.php" class="nav-item nav-link<?php echo ($currentPage === 'adminMarcacion.php') ? ' active' : ''; ?>">Marcación</a>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Reportes
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a href="reporteCiclo.php" class="dropdown-item<?php echo ($currentPage === 'reporteCiclo.php') ? ' active' : ''; ?>">Reporte por Ciclo</a></li>
                                    <li><a href="reporteAlumnosAdmin.php" class="dropdown-item<?php echo ($currentPage === 'reporteAlumnosAdmin.php') ? ' active' : ''; ?>">Reporte Alumnos (Ciclo)</a></li>
                                    <li><a href="reporteGeneral.php" class="dropdown-item<?php echo ($currentPage === 'reporteGeneral.php') ? ' active' : ''; ?>">Reporte Alumnos (General)</a></li>
                                    <li><a href="reporteLabsDetalle.php" class="dropdown-item<?php echo ($currentPage === 'reporteLabsDetalle.php') ? ' active' : ''; ?>">Reporte Laboratorios (Detalle)</a></li>
                                    <li><a href="reporteLabsGeneral.php" class="dropdown-item<?php echo ($currentPage === 'reporteLabsGeneral.php') ? ' active' : ''; ?>">Reporte Laboratorios (General)</a></li>
                                    <li><a href="reporteEncargadosLabs.php" class="dropdown-item<?php echo ($currentPage === 'reporteEncargadosLabs.php') ? ' active' : ''; ?>">Reporte Encargados (Laboratorios)</a></li>
                                    <li><a href="reporteLabs.php" class="dropdown-item<?php echo ($currentPage === 'reporteLabs.php') ? ' active' : ''; ?>">Información de Laboratorios</a></li>
                                </ul>
                            </li>
                            <a href="cerrar-sesion.php" class="nav-item nav-link">Salir</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
