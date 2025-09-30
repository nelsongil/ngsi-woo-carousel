<?php
/**
 * Generador de imagen del plugin NGSI Woo Carousel
 * Crea una imagen PNG de 128x128 píxeles para el plugin
 */

// Crear imagen
$width = 128;
$height = 128;
$image = imagecreatetruecolor($width, $height);

// Colores
$bg_color = imagecolorallocate($image, 102, 126, 234); // Azul gradiente
$card_color = imagecolorallocate($image, 255, 255, 255); // Blanco
$gold_color = imagecolorallocate($image, 255, 215, 0); // Dorado
$orange_color = imagecolorallocate($image, 255, 107, 53); // Naranja
$dark_color = imagecolorallocate($image, 44, 62, 80); // Azul oscuro
$green_color = imagecolorallocate($image, 39, 174, 96); // Verde
$text_color = imagecolorallocate($image, 255, 255, 255); // Blanco

// Fondo
imagefill($image, 0, 0, $bg_color);

// Carrusel base
imagefilledrectangle($image, 20, 40, 108, 88, $card_color);

// Tarjetas de productos
imagefilledrectangle($image, 28, 48, 44, 80, $gold_color);
imagefilledrectangle($image, 48, 48, 64, 80, $orange_color);
imagefilledrectangle($image, 68, 48, 84, 80, $dark_color);
imagefilledrectangle($image, 88, 48, 104, 80, $green_color);

// Flechas de navegación
$arrow_points_left = [
    15, 56,
    25, 52,
    25, 60
];
$arrow_points_right = [
    113, 56,
    103, 52,
    103, 60
];
imagefilledpolygon($image, $arrow_points_left, 3, $text_color);
imagefilledpolygon($image, $arrow_points_right, 3, $text_color);

// Puntos de paginación
imagefilledellipse($image, 50, 92, 4, 4, $bg_color);
imagefilledellipse($image, 64, 92, 4, 4, $bg_color);
imagefilledellipse($image, 78, 92, 4, 4, $bg_color);

// Texto
imagestring($image, 3, 45, 15, 'NGSI', $text_color);
imagestring($image, 2, 40, 105, 'Woo Carousel', $text_color);

// Guardar imagen
imagepng($image, 'assets/media/plugin-icon.png');
imagedestroy($image);

echo "Imagen del plugin creada: assets/media/plugin-icon.png\n";
?>
