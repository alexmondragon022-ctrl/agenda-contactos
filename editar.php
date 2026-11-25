
<?php

require_once "config/db.php";

$tituloPagina = "Editar contacto | Agenda de Contactos";

// Obtener ID del contacto
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: index.php");
    exit;
}

try {

    // Buscar contacto
    $sql = "SELECT * FROM contactos WHERE id = :id";

    $consulta = $conexion->prepare($sql);
    $consulta->execute(["id" => $id]);

    $contacto = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$contacto) {
        header("Location: index.php");
        exit;
    }

} catch (PDOException $e) {

    error_log($e->getMessage());
    exit("No fue posible consultar el contacto.");

}

require_once "includes/header.php";

?>

<section>

    <h2 class="titulo-seccion">Editar contacto</h2>

    <p class="descripcion-seccion">
        Modifica la información del contacto seleccionado.
    </p>

    <form action="actualizar.php" method="POST" enctype="multipart/form-data" class="formulario">

        <input type="hidden" name="id" value="<?= (int)$contacto['id'] ?>">

        <div class="grupo-formulario">
            <label for="nombre">Nombre *</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                value="<?= htmlspecialchars($contacto['nombre'], ENT_QUOTES, 'UTF-8') ?>"
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
                value="<?= htmlspecialchars($contacto['apellido'], ENT_QUOTES, 'UTF-8') ?>"
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
                value="<?= htmlspecialchars($contacto['telefono'], ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="grupo-formulario">
            <label>Fotografía actual</label>

            <img
                src="archivos/<?= htmlspecialchars($contacto['foto'], ENT_QUOTES, 'UTF-8') ?>"
                alt="Fotografía del contacto"
                class="foto-contacto"
            >
        </div>

        <div class="grupo-formulario">
            <label for="foto">Cambiar fotografía</label>
            <input
                type="file"
                id="foto"
                name="foto"
                accept="image/jpeg,image/png,image/webp"
            >
            <small>Si no seleccionas otra fotografía, se conservará la actual.</small>
        </div>

        <div class="grupo-formulario">
            <label for="email">Correo electrónico</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                value="<?= htmlspecialchars($contacto['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="grupo-formulario">
            <label for="direccion">Dirección</label>
            <input
                type="text"
                id="direccion"
                name="direccion"
                maxlength="255"
                value="<?= htmlspecialchars($contacto['direccion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="grupo-formulario">
            <label for="notas">Notas</label>
            <textarea
                id="notas"
                name="notas"
            ><?= htmlspecialchars($contacto['notas'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primario">
            Actualizar contacto
        </button>

        <a href="index.php" class="btn btn-secundario">
            Cancelar
        </a>

    </form>

</section>

<?php require_once "includes/footer.php"; ?>
