
<?php

session_start();

require_once "config/db.php";

$tituloPagina = "Eliminar contacto";

// Crear token CSRF
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Obtener ID según el método
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
} else {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
}

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

    // Procesar eliminacion solamente mediante POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $token = $_POST["csrf_token"] ?? "";

        // Verificar token CSRF
        if (
            !is_string($token) ||
            !hash_equals($_SESSION["csrf_token"], $token)
        ) {
            http_response_code(403);
            exit("Solicitud no autorizada.");
        }

        // Eliminar contacto
        $sql = "DELETE FROM contactos WHERE id = :id";

        $consulta = $conexion->prepare($sql);
        $consulta->execute(["id" => $id]);

        // Eliminar fotografia del servidor
        $nombreFoto = basename($contacto["foto"]);
        $rutaFoto = __DIR__ . "/archivos/" . $nombreFoto;

        if (is_file($rutaFoto)) {
            unlink($rutaFoto);
        }

        // Renovar token
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

        header("Location: index.php?mensaje=eliminado");
        exit;
    }

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);
    exit("No fue posible eliminar el contacto.");

}

require_once "includes/header.php";

?>

<section>

    <h2 class="titulo-seccion">Eliminar contacto</h2>

    <div class="tarjeta-contacto detalle-contacto">

        <h3>¿Estás seguro de eliminar este contacto?</h3>

        <p>
            Esta acción no se puede deshacer.
        </p>

        <h3>
            <?= htmlspecialchars($contacto["nombre"], ENT_QUOTES, "UTF-8") ?>
            <?= htmlspecialchars($contacto["apellido"], ENT_QUOTES, "UTF-8") ?>
        </h3>

        <img
            src="archivos/<?= htmlspecialchars($contacto["foto"], ENT_QUOTES, "UTF-8") ?>"
            alt="Fotografía del contacto"
            class="foto-contacto"
        >

        <form action="eliminar.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?= (int)$contacto["id"] ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>"
            >

            <button type="submit" class="btn btn-primario">
                Sí, eliminar contacto
            </button>

            <a href="index.php" class="btn btn-secundario">
                Cancelar
            </a>

        </form>

    </div>

</section>

<?php require_once "includes/footer.php"; ?>
