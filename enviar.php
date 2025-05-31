<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';
require __DIR__ . '/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mostrarMensaje($titulo, $mensaje, $clase = 'success') {
    $color = $clase === 'success' ? '#4CAF50' : '#f44336';
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>$titulo</title>
      <style>
        body {
          background-color: #f9f6f1;
          font-family: Arial, sans-serif;
          color: #333;
          margin: 0;
          display: flex;
          height: 100vh;
          justify-content: center;
          align-items: center;
          text-align: center;
          padding: 1rem;
        }
        .mensaje {
          background: white;
          border-radius: 8px;
          box-shadow: 0 2px 12px rgba(0,0,0,0.1);
          padding: 2rem 3rem;
          max-width: 400px;
          width: 100%;
        }
        h1 {
          color: $color;
          margin-bottom: 1rem;
        }
        p {
          font-size: 1.1rem;
          margin-bottom: 2rem;
        }
        a {
          display: inline-block;
          background-color: $color;
          color: white;
          padding: 0.6rem 1.5rem;
          text-decoration: none;
          border-radius: 4px;
          font-weight: bold;
          transition: background-color 0.3s;
        }
        a:hover {
          background-color: #333;
        }
      </style>
    </head>
    <body>
      <div class="mensaje">
        <h1>$titulo</h1>
        <p>$mensaje</p>
        <a href="index.html">Volver a la página principal</a>
      </div>
    </body>
    </html>
    HTML;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $horarios = $_POST['horarios'] ?? '';
    $zona = $_POST['zona'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $alarma = $_POST['alarma'] ?? '';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tumail@gmail.com';
        $mail->Password = 'clave aplicacion google';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tumail@gmail.com', 'Formulario Web');
        $mail->addAddress('tumail@gmail.com');

        $mail->isHTML(false);
        $mail->Subject = 'Nuevo contacto desde formulario';
        $mail->Body = "Nombre: $nombre\nTeléfono: $telefono\nHorarios para contactarte: $horarios\nZona: $zona\nTipo: $tipo\n¿Tiene alarma?: $alarma";

        $mail->send();
        mostrarMensaje('¡Gracias por contactarnos!', 'Te responderemos pronto.');
    } catch (Exception $e) {
        mostrarMensaje('Error al enviar el mensaje', 'Por favor, intenta de nuevo más tarde.<br>Error: ' . htmlspecialchars($mail->ErrorInfo), 'error');
    }
} else {
    header("Location: index.html");
    exit;
}
?>