<?php
$archivo_xml = __DIR__ . DIRECTORY_SEPARATOR . 'tareas.xml';

if (!file_exists($archivo_xml)) {
    $xml_inicial = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><tareas></tareas>";
    file_put_contents($archivo_xml, $xml_inicial, LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tarea'])) {
        // Agregar tarea
        $tarea = trim($_POST['tarea']);
        if ($tarea !== '') {
            $xml = simplexml_load_file($archivo_xml);
            $xml->addChild('tarea', htmlspecialchars($tarea, ENT_XML1, 'UTF-8'));
            $xml->asXML($archivo_xml);
        }
    } elseif (isset($_POST['limpiar'])) {
        // Limpiar tareas
        $xml_inicial = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><tareas></tareas>";
        file_put_contents($archivo_xml, $xml_inicial, LOCK_EX);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$xml = simplexml_load_file($archivo_xml);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Lista de tareas</title>
</head>
<body>
  <h1>Lista de tareas</h1>

  <ul>
    <?php if (isset($xml->tarea) && count($xml->tarea) > 0): ?>
      <?php foreach ($xml->tarea as $t): ?>
        <li><?= htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    <?php else: ?>
      <li>No hay tareas aún.</li>
    <?php endif; ?>
  </ul>

  <!-- Formulario para agregar -->
  <form method="post">
    <input type="text" name="tarea" placeholder="Nueva tarea" required>
    <button type="submit">Agregar</button>
  </form>

  <!-- Formulario para limpiar -->
  <form method="post">
    <input type="hidden" name="limpiar" value="1">
    <button type="submit">Limpiar tareas</button>
  </form>
</body>
</html>