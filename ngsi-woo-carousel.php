<?php
/**
 * Plugin Name: NGSI Woo Carousel
 * Plugin URI: https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel
 * Description: Carrusel de productos WooCommerce con shortcode, interfaz visual de administración y personalización completa de colores
 * Version: 1.0.0
 * Author: Nelson Gil Olguín
 * Author URI: https://nelsongil.com
 * License: Freeware (Personal Use)
 * License URI: https://nelsongil.com/license
 * Text Domain: ngsi-woo-carousel
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * Network: false
 * GitHub Plugin URI: [TU-USUARIO-GITHUB]/ngsi-woo-carousel
 * GitHub Branch: main
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Seguridad: evita acceso directo
}

// Carga archivos necesarios
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ngsi-carousel-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ngsi-carousel-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ngsi-carousel-updater.php';

// Carga estilos y scripts
function ngsi_woo_carousel_assets() {
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0' );
    wp_enqueue_style( 'ngsi-carousel-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', [], time() );
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0', true );
    wp_enqueue_script( 'ngsi-carousel-script', plugin_dir_url( __FILE__ ) . 'assets/js/carousel.js', ['swiper-js'], time(), true );
}
add_action( 'wp_enqueue_scripts', 'ngsi_woo_carousel_assets' );