
<?php

require_once "config/db.php";

$tituloPagina = "Inicio | Agenda de Contactos";

// Obtener el texto de busqueda
$busqueda = trim($_GET["buscar"] ?? "");

// Consultar contactos
if ($busqueda !== "") {

    $sql = "SELECT * FROM contactos
            WHERE nombre LIKE :nombre
            OR apellido LIKE :apellido
            OR telefono LIKE :telefono
            ORDER BY id DESC";

    $consulta = $conexion->prepare($sql);

    $termino = "%".$busqueda."%";

    $consulta->execute([
        "nombre" => $termino,
        "apellido" => $termino,
        "telefono" => $termino
    ]);

} else {

    $sql = "SELECT * FROM contactos ORDER BY id DESC";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

}

// Obtener resultados
$contactos = $consulta->fetchAll();

// Incluir encabezado
require_once "includes/header.php";

?>

<section>

    <h2 class="titulo-seccion">Mis contactos</h2>

    <p class="descripcion-seccion">
        Administra y organiza tus contactos desde un solo lugar.
    </p>


<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] === "creado"): ?>

    <div class="mensaje mensaje-exito">
        ¡Contacto registrado correctamente!
    </div>

<?php endif; ?>

    <!-- BUSCADOR -->

    <form action="index.php" method="GET" class="buscador">

        <input
            type="search"
            name="buscar"
            placeholder="Buscar por nombre, apellido o telefono..."
            value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>"
        >

        <button type="submit" class="btn btn-primario">
            Buscar
        </button>

        <a href="index.php" class="btn btn-secundario">
            Limpiar
        </a>

    </form>

    <!-- LISTADO DE CONTACTOS -->

    <div class="lista-contactos">

        <?php if (count($contactos) > 0): ?>

            <?php foreach ($contactos as $contacto): ?>

                <article class="tarjeta-contacto">

                    <img
                        src="archivos/<?= htmlspecialchars($contacto['foto'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="Foto de <?= htmlspecialchars($contacto['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                        class="foto-contacto"
                    >

                    <h3>
                        <?= htmlspecialchars($contacto["nombre"], ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($contacto["apellido"], ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($contacto["telefono"], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p>
                        <?= htmlspecialchars($contacto["email"] ?? "", ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <!-- ACCIONES -->

                    <div class="acciones-contacto">

                        <a
                            href="ver.php?id=<?= (int)$contacto['id'] ?>"
                            class="btn btn-primario"
                        >
                            Ver
                        </a>

                        <a
                            href="editar.php?id=<?= (int)$contacto['id'] ?>"
                            class="btn btn-secundario"
                        >
                            Editar
                        </a>

                        <a
                            href="eliminar.php?id=<?= (int)$contacto['id'] ?>"
                            class="btn btn-peligro"
                        >
                            Eliminar
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="mensaje mensaje-vacio">

                <?php if ($busqueda !== ""): ?>

                    No se encontraron contactos con esa busqueda.

                <?php else: ?>

                    Todavia no tienes contactos registrados.
                    ¡Agrega tu primer contacto!

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] === "actualizado"): ?>

    <div class="mensaje mensaje-exito">
        ¡Contacto actualizado correctamente!
    </div>

<?php endif; ?>


<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] === "eliminado"): ?>

    <div class="mensaje mensaje-exito">
        ¡Contacto eliminado correctamente!
    </div>

<?php endif; ?>


<?php require_once "includes/footer.php"; ?>
