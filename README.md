# SocialTrack – Sistema de Control de Horas Sociales

## 📌 Descripción
**SocialTrack** es un sistema web desarrollado en **PHP, MySQL y JavaScript** para controlar y registrar las **horas sociales de estudiantes en los centros de cómputo  universidades o colegios**.  
Fue creado como parte de un proyecto académico, pero puede adaptarse fácilmente a otros contextos donde se requiera llevar control de asistencia o servicio social.

---

## 🚀 Características principales
- Registro y gestión de estudiantes.
- Asignación y control de horas sociales en laboratorios de informática.
- Roles de usuario: **Administrador** y **Encargado de laboratorio**.
- Reportes de horas acumuladas y cumplimiento de requisitos.
- Validaciones de formularios con JavaScript.

---

## 🛠️ Tecnologías utilizadas
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7+
- **Base de Datos:** MySQL con **Stored Procedures y Triggers**

---

## 📂 Estructura del Proyecto
- `/css` → estilos
- `/js` → validaciones
- `/includes` → archivos auxiliares de conexión y consultas
- `/img` → logos, iconos, imagenes del sistema web
- `/admin` → panel de administración
- `index.php` → pantalla de inicio/login

---

## ⚠️ Nota importante sobre la base de datos
Este proyecto utiliza **Stored Procedures (SP) y Triggers** en MySQL para mayor robustez y seguridad.  
👉 Debido a esto, **no es posible desplegarlo en hostings gratuitos** como InfinityFree u otros, ya que estos no permiten SP ni triggers por restricciones técnicas.

---

## 💻 Cómo ejecutarlo en local
1. Instala [XAMPP](https://www.apachefriends.org/) o similar (PHP + MySQL).  
2. Clona este repositorio en la carpeta `htdocs`.  
   ```bash
   git clone https://github.com/tuusuario/socialtrack.git
3. En directorio **SQL** encontrarás la version demo de la base de datos ya con información para realizar pruebas.
4. Abrir phpMyAdmin > crear la base de datos bajo el nombre db_sistema_horas > Importar base de datos.
