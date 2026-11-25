
<?php

$tituloPagina = "Nuevo contacto | Agenda de Contactos";

require_once "includes/header.php";

?>

<section>

    <h2 class="titulo-seccion">Agregar nuevo contacto</h2>

    <p class="descripcion-seccion">
        Completa los siguientes datos para registrar un nuevo contacto.
    </p>

    <form action="guardar.php" method="POST" enctype="multipart/form-data" class="formulario">

        <div class="grupo-formulario">
            <label for="nombre">Nombre *</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                placeholder="Escribe el nombre"
                required
            >
        </div>

        <div class="grupo-formulario">
            <label for="apellido">Apellido *</label>
            <input
                type="text"
                id="apellido"
                name="apellido"
                maxlength="100"
                placeholder="Escribe el apellido"
                required
            >
        </div>

        <div class="grupo-formulario">
            <label for="telefono">Teléfono *</label>
            <input
                type="tel"
                id="telefono"
                name="telefono"
                maxlength="20"
                placeholder="Ejemplo: 9611234567"
                required
            >
        </div>

        <div class="grupo-formulario">
            <label for="foto">Fotografía *</label>
            <input
                type="file"
                id="foto"
                name="foto"
                accept="image/jpeg,image/png,image/webp"
                required
            >
        </div>

        <div class="grupo-formulario">
            <label for="email">Correo electrónico</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                placeholder="ejemplo@correo.com"
            >
        </div>

        <div class="grupo-formulario">
            <label for="direccion">Dirección</label>
            <input
                type="text"
                id="direccion"
                name="direccion"
                maxlength="255"
                placeholder="Escribe la dirección"
            >
        </div>

        <div class="grupo-formulario">
            <label for="notas">Notas</label>
            <textarea
                id="notas"
                name="notas"
                placeholder="Información adicional del contacto"
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primario">
            Guardar contacto
        </button>

        <a href="index.php" class="btn btn-secundario">
            Cancelar
        </a>

    </form>

</section>

<?php require_once "includes/footer.php"; ?>
