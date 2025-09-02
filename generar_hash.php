<?php
// Este script genera un hash seguro para una contraseña dada
// Úsalo para actualizar la contraseña en la base de datos para hacer el primer inicio de sesión de tu admin
$password_plana = 'admin123!'; // Reemplaza esto con tu contraseña real
$hash_seguro = password_hash($password_plana, PASSWORD_DEFAULT);
echo "Tu hash es: " . $hash_seguro;
?>