
<?php

require_once "config/db.php";

// Comprobar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Obtener y validar ID
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: index.php");
    exit;
}

// Recibir datos
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$email = trim($_POST["email"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$notas = trim($_POST["notas"] ?? "");

// Validar campos obligatorios
if ($nombre === "" || $apellido === "" || $telefono === "") {
    exit("Debes completar los campos obligatorios.");
}

// Validar longitudes
if (
    strlen($nombre) > 100 ||
    strlen($apellido) > 100 ||
    strlen($telefono) > 20 ||
    strlen($email) > 150 ||
    strlen($direccion) > 255
) {
    exit("Uno de los campos supera la longitud permitida.");
}

// Validar correo
if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("El correo electronico no es valido.");
}

try {

    // Buscar contacto actual
    $consulta = $conexion->prepare(
        "SELECT foto FROM contactos WHERE id = :id"
    );

    $consulta->execute(["id" => $id]);

    $contacto = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$contacto) {
        header("Location: index.php");
        exit;
    }

    // Conservar fotografia actual
    $nombreFoto = $contacto["foto"];
    $fotoNueva = false;
    $rutaFotoNueva = null;

    // Comprobar si se selecciono otra fotografia
    if (
        isset($_FILES["foto"]) &&
        $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
            exit("Ocurrio un error al subir la fotografia.");
        }

        // Validar tamaño maximo: 2 MB
        if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
            exit("La fotografia no debe superar los 2 MB.");
        }

        // Validar tipo real de imagen
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipoImagen = $finfo->file($_FILES["foto"]["tmp_name"]);

        $tiposPermitidos = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if (!isset($tiposPermitidos[$tipoImagen])) {
            exit("Solo se permiten imagenes JPG, PNG o WEBP.");
        }

        // Generar nombre seguro
        $extension = $tiposPermitidos[$tipoImagen];
        $nombreFoto = bin2hex(random_bytes(16)) . "." . $extension;

        $directorio = __DIR__ . "/archivos/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $rutaFotoNueva = $directorio . $nombreFoto;

        if (!move_uploaded_file(
            $_FILES["foto"]["tmp_name"],
            $rutaFotoNueva
        )) {
            exit("No fue posible guardar la nueva fotografia.");
        }

        $fotoNueva = true;
    }

    // Preparar consulta UPDATE
    $sql = "UPDATE contactos SET
            nombre = :nombre,
            apellido = :apellido,
            telefono = :telefono,
            foto = :foto,
            email = :email,
            direccion = :direccion,
            notas = :notas
            WHERE id = :id";

    $consulta = $conexion->prepare($sql);

    // Ejecutar actualizacion
    $consulta->execute([
        "nombre" => $nombre,
        "apellido" => $apellido,
        "telefono" => $telefono,
        "foto" => $nombreFoto,
        "email" => $email !== "" ? $email : null,
        "direccion" => $direccion !== "" ? $direccion : null,
        "notas" => $notas !== "" ? $notas : null,
        "id" => $id
    ]);

    // Eliminar fotografia anterior si se cambio
    if ($fotoNueva) {

        $fotoAnterior = basename($contacto["foto"]);
        $rutaAnterior = __DIR__ . "/archivos/" . $fotoAnterior;

        if (is_file($rutaAnterior)) {
            unlink($rutaAnterior);
        }
    }

    // Regresar al listado
    header("Location: index.php?mensaje=actualizado");
    exit;

} catch (PDOException $e) {

    // Eliminar nueva fotografia si falla la consulta
    if (
        isset($rutaFotoNueva) &&
        $rutaFotoNueva !== null &&
        is_file($rutaFotoNueva)
    ) {
        unlink($rutaFotoNueva);
    }

    error_log($e->getMessage());

    http_response_code(500);
    exit("No fue posible actualizar el contacto.");

}
