<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NGSI_Carousel_Updater {
    
    private $plugin_slug;
    private $version;
    private $cache_key;
    private $cache_allowed;
    private $github_user;
    private $github_repo;
    private $debug;
    
    public function __construct() {
        // Usar la constante del plugin principal
        $this->plugin_slug = defined( 'NGSI_WOO_CAROUSEL_BASENAME' ) ? NGSI_WOO_CAROUSEL_BASENAME : plugin_basename( dirname( __DIR__ ) . '/ngsi-woo-carousel.php' );
        $this->version = defined( 'NGSI_WOO_CAROUSEL_VERSION' ) ? NGSI_WOO_CAROUSEL_VERSION : '1.0.0';
        $this->cache_key = 'ngsi_carousel_updater';
        $this->cache_allowed = false;
        $this->debug = defined( 'WP_DEBUG' ) && WP_DEBUG;
        
        // Configuración de GitHub - ACTUALIZA ESTOS VALORES CON TU USUARIO
        $this->github_user = 'nelsongil'; // Cambia esto por tu usuario de GitHub
        $this->github_repo = 'ngsi-woo-carousel';
        
        add_filter( 'plugins_api', [ $this, 'info' ], 20, 3 );
        add_filter( 'site_transient_update_plugins', [ $this, 'update' ] );
        add_action( 'upgrader_process_complete', [ $this, 'purge' ], 10, 2 );
        add_action( 'admin_init', [ $this, 'force_check' ] );
        
        // Debug info
        $this->log( 'Updater iniciado' );
        $this->log( 'Plugin slug: ' . $this->plugin_slug );
        $this->log( 'Versión actual: ' . $this->version );
    }
    
    /**
     * Registrar mensajes de debug
     */
    private function log( $message ) {
        if ( $this->debug ) {
            error_log( 'NGSI Carousel Updater: ' . $message );
        }
    }
    
    public function request() {
        $remote = get_transient( $this->cache_key );
        
        if ( false === $remote || ! $this->cache_allowed ) {
            $api_url = sprintf( 
                'https://api.github.com/repos/%s/%s/releases/latest',
                $this->github_user,
                $this->github_repo
            );
            
            $this->log( 'Solicitando info de GitHub: ' . $api_url );
            
            $remote = wp_remote_get(
                $api_url,
                [
                    'timeout' => 10,
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                    ],
                ]
            );
            
            if ( is_wp_error( $remote ) ) {
                $this->log( 'Error en request: ' . $remote->get_error_message() );
                return false;
            }
            
            $response_code = wp_remote_retrieve_response_code( $remote );
            $this->log( 'Código de respuesta: ' . $response_code );
            
            if ( $response_code !== 200 ) {
                $this->log( 'Error: código de respuesta no es 200' );
                return false;
            }
            
            if ( empty( wp_remote_retrieve_body( $remote ) ) ) {
                $this->log( 'Error: cuerpo de respuesta vacío' );
                return false;
            }
            
            set_transient( $this->cache_key, $remote, 12 * HOUR_IN_SECONDS );
            $this->log( 'Respuesta guardada en caché' );
        } else {
            $this->log( 'Usando respuesta en caché' );
        }
        
        $remote = json_decode( wp_remote_retrieve_body( $remote ) );
        
        if ( isset( $remote->tag_name ) ) {
            $this->log( 'Última versión en GitHub: ' . $remote->tag_name );
        }
        
        return $remote;
    }
    
    public function info( $res, $action, $args ) {
        if ( 'plugin_information' !== $action ) {
            return $res;
        }
        
        // Obtener el slug sin la extensión .php
        $plugin_slug_parts = explode( '/', $this->plugin_slug );
        $plugin_dir = isset( $plugin_slug_parts[0] ) ? $plugin_slug_parts[0] : '';
        
        // Comparar con el slug solicitado
        if ( empty( $args->slug ) || ( $args->slug !== $plugin_dir && $args->slug !== $this->plugin_slug ) ) {
            return $res;
        }
        
        $remote = $this->request();
        
        if ( ! $remote ) {
            return $res;
        }
        
        $res = new stdClass();
        $res->name = 'NGSI Woo Carousel';
        $res->slug = $plugin_dir;
        $res->plugin = $this->plugin_slug;
        $res->version = $remote->tag_name;
        $res->tested = '6.8';
        $res->requires = '6.0';
        $res->author = '<a href="https://nelsongil.com">Nelson Gil Olguín</a>';
        $res->author_profile = 'https://nelsongil.com';
        $res->download_link = $remote->zipball_url;
        $res->trunk = $remote->zipball_url;
        $res->requires_php = '7.4';
        $res->last_updated = $remote->published_at;
        $res->sections = [
            'description' => 'Carrusel de productos WooCommerce con shortcode, interfaz visual de administración y personalización completa de colores.',
            'installation' => 'Instala el plugin desde WordPress Admin > Plugins > Añadir nuevo.',
            'changelog' => $remote->body,
        ];
        
        if ( ! empty( $remote->assets ) && ! empty( $remote->assets[0] ) ) {
            $res->download_link = $remote->assets[0]->browser_download_url;
        }
        
        return $res;
    }
    
    public function update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }
        
        $this->log( 'Comprobando actualizaciones...' );
        
        $remote = $this->request();
        
        if ( ! $remote ) {
            $this->log( 'No se pudo obtener información de GitHub' );
            return $transient;
        }
        
        // Limpiar el tag_name (remover 'v' si existe)
        $remote_version = ltrim( $remote->tag_name, 'v' );
        
        $this->log( 'Comparando versiones: ' . $this->version . ' vs ' . $remote_version );
        
        // Comparar versiones
        if ( version_compare( $this->version, $remote_version, '<' ) ) {
            $this->log( '¡Nueva versión disponible! ' . $remote_version );
            
            $res = new stdClass();
            $res->slug = dirname( $this->plugin_slug );
            $res->plugin = $this->plugin_slug;
            $res->new_version = $remote_version;
            $res->tested = '6.8';
            $res->package = $remote->zipball_url;
            $res->url = 'https://github.com/' . $this->github_user . '/' . $this->github_repo;
            
            // Preferir assets si están disponibles
            if ( ! empty( $remote->assets ) && is_array( $remote->assets ) ) {
                foreach ( $remote->assets as $asset ) {
                    if ( isset( $asset->name ) && strpos( $asset->name, '.zip' ) !== false ) {
                        $res->package = $asset->browser_download_url;
                        $this->log( 'Usando asset: ' . $asset->name );
                        break;
                    }
                }
            }
            
            $this->log( 'URL de descarga: ' . $res->package );
            
            $transient->response[ $res->plugin ] = $res;
        } else {
            $this->log( 'Plugin actualizado - no hay nuevas versiones' );
        }
        
        return $transient;
    }
    
    public function purge( $upgrader, $options ) {
        if ( 'update' === $options['action'] && 'plugin' === $options['type'] ) {
            // Limpiar el caché después de cualquier actualización de plugin
            delete_transient( $this->cache_key );
            $this->log( 'Caché de actualizaciones limpiado' );
        }
    }
    
    /**
     * Forzar verificación de actualizaciones (útil para debug)
     * Agregar ?ngsi_force_update_check=1 a cualquier URL del admin
     */
    public function force_check() {
        if ( isset( $_GET['ngsi_force_update_check'] ) && current_user_can( 'update_plugins' ) ) {
            delete_transient( $this->cache_key );
            delete_site_transient( 'update_plugins' );
            wp_update_plugins();
            
            wp_redirect( admin_url( 'plugins.php?ngsi_update_checked=1' ) );
            exit;
        }
        
        if ( isset( $_GET['ngsi_update_checked'] ) ) {
            add_action( 'admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Verificación de actualizaciones forzada completada.</p></div>';
            });
        }
    }
}

// Inicializar el sistema de actualizaciones
new NGSI_Carousel_Updater();
