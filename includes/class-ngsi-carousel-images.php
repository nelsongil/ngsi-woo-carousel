<?php
/**
 * Configuración de imagen del plugin NGSI Woo Carousel
 * 
 * Este archivo configura la imagen del plugin para WordPress
 */

// Función para obtener la URL del icono del plugin
function ngsi_get_plugin_icon_url() {
    return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/media/icon.svg';
}

// Función para obtener la URL del banner del plugin
function ngsi_get_plugin_banner_url() {
    return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/media/banner.svg';
}

// Añadir información del plugin al admin
add_action( 'admin_head', 'ngsi_add_plugin_info' );
function ngsi_add_plugin_info() {
    $screen = get_current_screen();
    if ( $screen && strpos( $screen->id, 'ngsi-carousels' ) !== false ) {
        ?>
        <style>
        .ngsi-plugin-meta {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .ngsi-plugin-meta h3 {
            margin-top: 0;
            color: #0073aa;
        }
        .ngsi-plugin-meta p {
            margin: 5px 0;
            font-size: 14px;
        }
        </style>
        <?php
    }
}

// Añadir meta información del plugin
add_action( 'admin_notices', 'ngsi_plugin_meta_notice' );
function ngsi_plugin_meta_notice() {
    $screen = get_current_screen();
    if ( $screen && strpos( $screen->id, 'ngsi-carousels' ) !== false ) {
        ?>
        <div class="ngsi-plugin-meta">
            <h3>📦 Información del Plugin</h3>
            <p><strong>Versión:</strong> 1.0.0</p>
            <p><strong>Autor:</strong> Nelson Gil Olguín</p>
            <p><strong>Web:</strong> <a href="https://nelsongil.com" target="_blank">nelsongil.com</a></p>
            <p><strong>Licencia:</strong> Freeware (Uso Personal)</p>
            <p><strong>GitHub:</strong> <a href="https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel" target="_blank">Ver en GitHub</a></p>
        </div>
        <?php
    }
}
