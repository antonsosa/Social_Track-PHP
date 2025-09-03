<?php
    require_once 'auth.php';

    // Verificar que el usuario tiene el rol de administrador (rol id 1)
    verificarRol(1);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Inicio - Administrador de Laboratorios</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Links de Boostraps, iconoes, etc -->
        <?php include_once './includes/headLinks.php' ?>
        
        <!-- JavaScript Libraries y Template-->
        <?php include './includes/scriptsLibrerias.php'; ?>
    </head>

    <body>


        <!-- Navbar o barra de navegacion con menu -->
        <?php $currentPage = 'adminInicio.php';
        include('includes/navBarAdmin.php'); ?>

        <!-- Carousel Start -->
        <?php include_once './includes/carousel.php'; ?>

        <div class="container-fluid py-1">
            <div class="container py-1">
                <div class="row g-5">
                    <!-- Misión -->
                    <div class="col-lg-6 col-md-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="about-item overflow-hidden">
                            <h1 class="display-5 mb-2 text-center">Misión</h1>
                            <div>
                                <p class="fs-5" style="text-align: justify;">
                                    La Universidad ... de El Salvador existe para brindar a amplios sectores poblacionales, 
                                    innovadores servicios educativos, promoviendo su capacidad crítica y su responsabilidad social; utilizando metodologías y recursos académicos 
                                    apropiados, desarrollando institucionalmente: investigación pertinente y proyección social, todo consecuente con su 
                                    filosofía y su legado cultural. 
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Visión -->
                    <div class="col-lg-6 col-md-12 wow fadeInUp" data-wow-delay=".6s">
                        <div class="about-item overflow-hidden">
                            <h1 class="display-5 mb-2 text-center">Visión</h1>
                            
                            <div>
                                <p class="fs-5" style="text-align: justify;">
                                    Ser reconocida como una de las mejores universidades privadas de la región, 
                                    a través de sus egresados y de sus esmerados procesos institucionales de construcción y aplicación del conocimiento, 
                                    proponiendo soluciones pertinentes a las necesidades de amplios sectores de la sociedad.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>