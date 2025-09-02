<style>
.texto-izquierda {
    text-align: right;
}
.mi-contenedor {
    text-align: right;
}
</style>
        <!-- Page Header Start -->
        <div class="container-fluid page-header py-3">
            <div class="container text-center py-3">
            <div class="mi-contenedor">
                <h5 class="texto-izquierda text-white mb-4 animated slideInDown">   
                    <p><?= $_SESSION['rol'] ?> <?= $_SESSION['apellido'] ?></p>
                </h5> 
            </div>
                <h1 class="display-4 text-white mb-4 animated slideInDown"><?php echo $bannerTitle; ?></h1>
            </div>
        </div>
        <!-- Page Header End -->