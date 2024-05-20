<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $palabras_originales = [];
    $palabras_ingresadas = [];
    $resultados = [];

    // Recuperar las palabras originales enviadas a través del formulario
    for ($i = 0; isset($_POST["palabra_original_$i"]); $i++) {
        $palabras_originales[] = $_POST[strtolower("palabra_original_$i")];
    }

    // Recuperar las palabras ingresadas por el usuario
    for ($i = 0; isset($_POST["palabra$i"]); $i++) {
        $palabras_ingresadas[] = $_POST[strtolower("palabra$i")];
    }

    // Comparar palabras ingresadas con las originales
    foreach ($palabras_ingresadas as $index => $palabra_ingresada) {
        // Aplicar ucfirst() fuera de las comillas
        $palabra_ingresada_con_primera_mayuscula = ucfirst($palabra_ingresada);
        
        if (in_array($palabra_ingresada_con_primera_mayuscula, $palabras_originales)) {
            $resultados[] = "La palabra '$palabra_ingresada_con_primera_mayuscula' es correcta.";
        } else {
            $resultados[] = "La palabra '$palabra_ingresada_con_primera_mayuscula' es incorrecta.";
        }
    }
}
?>

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Juego de Palabras</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Resultados</h1>
    <?php if (!empty($palabras_originales)): ?>
        <h2>Palabras originales:</h2>
        <?php foreach ($palabras_originales as $palabra): ?>
            <p><?php echo htmlspecialchars($palabra); ?></p>
        <?php endforeach; ?>

        <h2>Palabras ingresadas por el usuario y resultados:</h2>
        <?php foreach ($resultados as $resultado): ?>
            <p><?php echo htmlspecialchars($resultado); ?></p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se recibieron palabras para analizar.</p>
    <?php endif; ?>
</div>
</body>
</html>
