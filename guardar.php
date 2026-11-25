
<?php

require_once "config/db.php";

// Comprobar que el formulario se envio mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear.php");
    exit;
}

// Recibir los datos
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

// Validar correo electronico
if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("El correo electronico no tiene un formato valido.");
}

// Comprobar que se envio una fotografia
if (
    !isset($_FILES["foto"]) ||
    $_FILES["foto"]["error"] !== UPLOAD_ERR_OK
) {
    exit("Debes seleccionar una fotografia valida.");
}

// Validar tamaño maximo: 2 MB
if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
    exit("La fotografia no debe superar los 2 MB.");
}

// Validar tipo real de imagen
$archivoTemporal = $_FILES["foto"]["tmp_name"];

$tiposPermitidos = [
    "image/jpeg" => "jpg",
    "image/png" => "png",
    "image/webp" => "webp"
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$tipoImagen = $finfo->file($archivoTemporal);

if (!isset($tiposPermitidos[$tipoImagen])) {
    exit("Solo se permiten imagenes JPG, PNG o WEBP.");
}

// Crear nombre seguro para la fotografia
$extension = $tiposPermitidos[$tipoImagen];
$nombreFoto = bin2hex(random_bytes(16)) . "." . $extension;

// Directorio donde se guardaran las fotografias
$directorio = __DIR__ . "/archivos/";

if (!is_dir($directorio)) {
    mkdir($directorio, 0755, true);
}

$rutaFoto = $directorio . $nombreFoto;

// Guardar fotografia
if (!move_uploaded_file($archivoTemporal, $rutaFoto)) {
    exit("No fue posible guardar la fotografia.");
}

try {

    // Preparar consulta INSERT
    $sql = "INSERT INTO contactos
            (nombre, apellido, telefono, foto, email, direccion, notas)
            VALUES
            (:nombre, :apellido, :telefono, :foto, :email, :direccion, :notas)";

    $consulta = $conexion->prepare($sql);

    // Ejecutar consulta
    $consulta->execute([
        "nombre" => $nombre,
        "apellido" => $apellido,
        "telefono" => $telefono,
        "foto" => $nombreFoto,
        "email" => $email !== "" ? $email : null,
        "direccion" => $direccion !== "" ? $direccion : null,
        "notas" => $notas !== "" ? $notas : null
    ]);

    // Regresar al listado
    header("Location: index.php?mensaje=creado");
    exit;

} catch (PDOException $e) {

    // Eliminar fotografia si falla el registro
    if (file_exists($rutaFoto)) {
        unlink($rutaFoto);
    }

    error_log($e->getMessage());

    http_response_code(500);
    exit("No fue posible guardar el contacto. Intenta nuevamente.");

}
