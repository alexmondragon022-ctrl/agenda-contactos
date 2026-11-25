
<?php
$tituloPagina = $tituloPagina ?? "Agenda de Contactos";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="/agenda-contactos/css/styles.css">
</head>

<body>

<div class="app-container">

<?php require_once __DIR__ . "/menu.php"; ?>

<main class="contenido-principal">
