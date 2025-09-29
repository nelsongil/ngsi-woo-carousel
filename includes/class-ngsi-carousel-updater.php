<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NGSI_Carousel_Updater {
    
    private $plugin_slug;
    private $version;
    private $cache_key;
    private $cache_allowed;
    
    public function __construct() {
        $this->plugin_slug = plugin_basename( __FILE__ );
        $this->version = '1.0.0';
        $this->cache_key = 'ngsi_carousel_updater';
        $this->cache_allowed = false;
        
        add_filter( 'plugins_api', [ $this, 'info' ], 20, 3 );
        add_filter( 'site_transient_update_plugins', [ $this, 'update' ] );
        add_action( 'upgrader_process_complete', [ $this, 'purge' ], 10, 2 );
    }
    
    public function request() {
        $remote = get_transient( $this->cache_key );
        
        if ( false === $remote || ! $this->cache_allowed ) {
            $remote = wp_remote_get(
                'https://api.github.com/repos/[TU-USUARIO-GITHUB]/ngsi-woo-carousel/releases/latest',
                [
                    'timeout' => 10,
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                    ],
                ]
            );
            
            if ( is_wp_error( $remote ) || wp_remote_retrieve_response_code( $remote ) !== 200 || empty( wp_remote_retrieve_body( $remote ) ) ) {
                return false;
            }
            
            set_transient( $this->cache_key, $remote, 12 * HOUR_IN_SECONDS );
        }
        
        $remote = json_decode( wp_remote_retrieve_body( $remote ) );
        
        return $remote;
    }
    
    public function info( $res, $action, $args ) {
        if ( 'plugin_information' !== $action ) {
            return $res;
        }
        
        if ( $this->plugin_slug !== $args->slug ) {
            return $res;
        }
        
        $remote = $this->request();
        
        if ( ! $remote ) {
            return $res;
        }
        
        $res = new stdClass();
        $res->name = 'NGSI Woo Carousel';
        $res->slug = $this->plugin_slug;
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
        
        $remote = $this->request();
        
        if ( $remote && version_compare( $this->version, $remote->tag_name, '<' ) ) {
            $res = new stdClass();
            $res->slug = $this->plugin_slug;
            $res->plugin = $this->plugin_slug;
            $res->new_version = $remote->tag_name;
            $res->tested = '6.8';
            $res->package = $remote->zipball_url;
            
            if ( ! empty( $remote->assets ) && ! empty( $remote->assets[0] ) ) {
                $res->package = $remote->assets[0]->browser_download_url;
            }
            
            $transient->response[ $res->plugin ] = $res;
        }
        
        return $transient;
    }
    
    public function purge( $upgrader, $options ) {
        if ( $this->cache_allowed && 'update' === $options['action'] && 'plugin' === $options['type'] ) {
            delete_transient( $this->cache_key );
        }
    }
}

// Inicializar el sistema de actualizaciones
new NGSI_Carousel_Updater();
