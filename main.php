<?php

// URL de la API
$url = 'https://clientes.api.greenborn.com.ar/public-random-word';

$words = [];
$number_of_words = 10; // Number of words you want to fetch

for ($i = 0; $i < $number_of_words; $i++) {
    // Inicializar cURL
    $curl = curl_init($url);

    // Configurar opciones de cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    // Ejecutar la solicitud
    $response = curl_exec($curl);

    // Verificar si ocurrió un error
    if ($response === false) {
        // Si hay un error, mostrar el mensaje de error
        $error_message = curl_error($curl);
        echo "Error al hacer la solicitud: $error_message\n";
        break;
    } else {
        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Verificar si la decodificación fue exitosa
        if ($data === null) {
            echo "Error al decodificar la respuesta JSON\n";
            break;
        } else {
            // Verificar si el índice 0 existe y tiene un valor
            if (isset($data[0])) {
                $words[] = $data[0];
            } else {
                echo "La clave '0' no se encontró en la respuesta.\n";
                break;
            }
        }
    }

    // Cerrar cURL
    curl_close($curl);
}

if (!empty($words)) {
    echo "Palabras aleatorias:\n";
    foreach ($words as $word) {
        echo "- " . $word . "\n";
    }
} else {
    echo "No se encontraron palabras.";
}

function utf8_str_shuffle($str) {
    $array = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
    shuffle($array);
    return implode('', $array);
}

$words_disordered = array();

foreach ($words as $word) {
    $word_disordered = utf8_str_shuffle($word);
    $words_disordered[] = $word_disordered;
    echo "Palabra desordenada: " . $word_disordered . "\n";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de palabras</title>
</head>
<body>
<?php
foreach ($words_disordered as $word_disordered) {
    echo "<p>$word_disordered</p>";
}
?>
<form action="analisis.php" method="post">
    <input type='text' name="palabra1">
    <input type='text' name="palabra2">
    <input type='text' name="palabra3">
    <button type="submit">Enviar</button>
</form>
</body>
</html>
