
<?php

require_once "config/db.php";

$tituloPagina = "Detalle del contacto";

// Obtener ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: index.php");
    exit;
}

try {

    // Consultar contacto
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

    <h2 class="titulo-seccion">Detalle del contacto</h2>

    <div class="tarjeta-contacto detalle-contacto">

        <img
            src="archivos/<?= htmlspecialchars($contacto['foto'], ENT_QUOTES, 'UTF-8') ?>"
            alt="Fotografía del contacto"
            class="foto-contacto"
        >

        <h3>
            <?= htmlspecialchars($contacto['nombre'], ENT_QUOTES, 'UTF-8') ?>
            <?= htmlspecialchars($contacto['apellido'], ENT_QUOTES, 'UTF-8') ?>
        </h3>

        <div class="informacion-contacto">

            <p>
                <strong>Teléfono:</strong>
                <?= htmlspecialchars($contacto['telefono'], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Correo electrónico:</strong>
                <?= htmlspecialchars($contacto['email'] ?? 'No registrado', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Dirección:</strong>
                <?= htmlspecialchars($contacto['direccion'] ?? 'No registrada', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Notas:</strong>
                <?= nl2br(htmlspecialchars($contacto['notas'] ?? 'Sin notas', ENT_QUOTES, 'UTF-8')) ?>
            </p>

        </div>

        <div class="acciones-contacto">

            <a
                href="editar.php?id=<?= (int)$contacto['id'] ?>"
                class="btn btn-primario"
            >
                Editar contacto
            </a>

            <a href="index.php" class="btn btn-secundario">
                Regresar
            </a>

        </div>

    </div>

</section>

<?php require_once "includes/footer.php"; ?>
