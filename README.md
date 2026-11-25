#  Agenda de Contactos CRUD

Sistema web desarrollado para administrar contactos personales mediante operaciones CRUD (Crear, Leer, Actualizar y Eliminar).

##  Integrantes del equipo

- Alex Eduardo Almaraz Mondragón
- José Carlos Pineda García
- Eduardo Alfredo García Antonio

##  Información académica

- **Institución:** Instituto Tecnológico de Tuxtla Gutiérrez
- **Materia:** Programación Web
- **Docente:** Jorge Octavio Guzmán Sánchez
- **Grupo:** S5B

##  Descripción del proyecto

Agenda de Contactos es un sistema web que permite registrar, consultar, editar y eliminar contactos personales desde una interfaz sencilla y fácil de utilizar.

El sistema permite almacenar información como nombre, apellido, teléfono, fotografía, correo electrónico, dirección y notas adicionales.

La información se administra mediante una base de datos MySQL, utilizando PHP y PDO para realizar las operaciones de manera segura.

##  Tecnologías utilizadas

- **PHP:** Desarrollo de la lógica del sistema.
- **MySQL:** Administración de la base de datos.
- **PDO:** Conexión y ejecución de consultas SQL.
- **HTML5:** Estructura de las páginas.
- **CSS3:** Diseño y presentación visual.
- **Git y GitHub:** Control de versiones y almacenamiento del código.
- **XAMPP:** Entorno de desarrollo local.

##  Funcionalidades implementadas

El sistema cuenta con las siguientes funcionalidades:

1. **Crear contactos:** Registrar nuevos contactos con sus respectivos datos y fotografía.
2. **Consultar contactos:** Visualizar el listado de contactos registrados.
3. **Ver detalles:** Consultar la información completa de cada contacto.
4. **Editar contactos:** Modificar la información de los contactos existentes.
5. **Eliminar contactos:** Eliminar contactos mediante una confirmación previa.
6. **Buscar contactos:** Localizar contactos por nombre, apellido o teléfono.

##  Estructura del proyecto

```text
agenda-contactos/
│
├── archivos/
│
├── config/
│   ├── db.php
│   └── db.local.php
│
├── css/
│   └── styles.css
│
├── database/
│   └── agenda.sql
│
├── includes/
│   ├── header.php
│   ├── menu.php
│   └── footer.php
│
├── index.php
├── crear.php
├── guardar.php
├── editar.php
├── actualizar.php
├── ver.php
├── eliminar.php
├── .gitignore
└── README.md
```

##  Base de datos

La base de datos utilizada se llama `agenda_contactos`.

Para instalarla:

1. Crear una base de datos llamada `agenda_contactos` en MySQL.
2. Importar el archivo `database/agenda.sql`.
3. Configurar los datos de conexión en `config/db.local.php`.
4. Verificar que PHP pueda conectarse correctamente mediante PDO.

##  Instalación y ejecución local

1. Instalar XAMPP.
2. Copiar la carpeta `agenda-contactos` dentro de `htdocs`.
3. Iniciar Apache y MySQL.
4. Crear e importar la base de datos.
5. Configurar las credenciales de conexión.
6. Abrir el navegador y acceder a:

```text
http://localhost/agenda-contactos/
```

##  Seguridad

El sistema implementa medidas de seguridad como:

- Conexión a la base de datos mediante PDO.
- Sentencias preparadas con `prepare()` y `execute()`.
- Sanitización de información mediante `htmlspecialchars()`.
- Validaciones de datos en los formularios.
- Manejo de errores sin mostrar información sensible al usuario.

##  Repositorio

Código fuente disponible en GitHub:

https://github.com/alexmondragon022-ctrl/agenda-contactos

---

**Proyecto académico – Programación Web**

Instituto Tecnológico de Tuxtla Gutiérrez

Grupo S5B
