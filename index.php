<?php

// Verificar si se ha enviado un número de palabras
if (isset($_POST["number_of_words"]) && is_numeric($_POST["number_of_words"])) {
    $number_of_words = intval($_POST["number_of_words"]);
} else {
    $number_of_words = 1; // Valor predeterminado
}

// URL de la API
$url = 'https://clientes.api.greenborn.com.ar/public-random-word';

$words = [];

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


function utf8_str_shuffle($str) {
    $array = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
    shuffle($array);
    return implode('', $array);
}

$words_disordered = array();

foreach ($words as $word) {
    $word_disordered = utf8_str_shuffle($word);
    $words_disordered[] = $word_disordered;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de palabras</title>
</head>
<style>
body {
    font-family: 'Comic Sans MS', cursive, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 0;
    animation: backgroundAnimation 10s infinite alternate;
}

@keyframes backgroundAnimation {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.8);
    padding: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    border-radius: 20px;
    transform: rotate(2deg);
    transition: transform 0.5s;
}

.container:hover {
    transform: rotate(-2deg);
}

h1 {
    text-align: center;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 2px 2px #333;
    animation: colorChange 5s infinite alternate;
}

@keyframes colorChange {
    0% { color: #333; }
    100% { color: #007bff; }
}

p {
    text-align: center;
    color: #666;
    font-style: italic;
    margin: 10px 0;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

input[type="number"], input[type="text"] {
    padding: 15px;
    border: 2px solid #ccc;
    border-radius: 10px;
    width: 80%;
    max-width: 300px;
    font-size: 18px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

input[type="number"]:focus, input[type="text"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.5);
}

button {
    padding: 15px 30px;
    background-color: #ff5733;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    text-transform: uppercase;
    letter-spacing: 2px;
}

button:hover {
    background-color: #c70039;
    transform: scale(1.1);
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
}

.words-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.words-list p {
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    padding: 15px;
    border-radius: 10px;
    font-size: 20px;
    margin: 0;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
}

.words-list p:hover {
    transform: rotate(5deg) scale(1.1);
}

.disorderedWords {
    font-size: 22px;
    font-weight: bold;
    color: #007bff;
    animation: bounce 1s infinite alternate;
}

@keyframes bounce {
    0% { transform: translateY(0); }
    100% { transform: translateY(-10px); }
}

.hidden-inputs input {
    display: none;
}

@keyframes formAnimation {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

form {
    animation: formAnimation 1s ease-out;
}

</style>

<body>

<h1>Juego de palabras</h1>

<p>Las siguientes palabras están desordenadas. Intente adivinar cuál es la palabra original.</p>

<p> Con cuántas palabras te gustaría jugar </p>

<form action="main.php" method="post">
    <input type="number"   name="number_of_words" value="<?php echo $number_of_words; ?>">
    <button type="submit">Jugar</button>
</form>

<?php
foreach ($words_disordered as $word_disordered) {
    echo "<p class= 'disorderedWords'>$word_disordered</p>";
}
?>

<form autocomplete="off" action="analisis.php" method="post">
    <?php
    // Añadir campos ocultos para enviar las palabras originales
    foreach ($words as $index => $word) {
        echo "<input type='hidden' name='palabra_original_$index' value='$word'>";
    }
    // Generar campos de texto para las palabras desordenadas
    for ($i = 0; $i < count($words_disordered); $i++) {
        echo "<input type='text' name='palabra$i' placeholder='Palabra desordenada $i'><br>";
    }
    ?>
    <button type="submit">Enviar</button>
</form>
</body>
</html>
