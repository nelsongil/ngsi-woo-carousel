<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NGSI_Carousel_Admin {
    
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_action( 'wp_ajax_ngsi_get_category_products', [ $this, 'ajax_get_category_products' ] );
        add_action( 'wp_ajax_ngsi_preview_carousel', [ $this, 'ajax_preview_carousel' ] );
        add_action( 'wp_ajax_ngsi_save_carousel', [ $this, 'ajax_save_carousel' ] );
        add_action( 'wp_ajax_ngsi_load_carousel', [ $this, 'ajax_load_carousel' ] );
        add_action( 'wp_ajax_ngsi_delete_carousel', [ $this, 'ajax_delete_carousel' ] );
        add_action( 'wp_ajax_ngsi_get_saved_carousels', [ $this, 'ajax_get_saved_carousels' ] );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'NGSI Carruseles',
            'Carruseles',
            'manage_options',
            'ngsi-carousels',
            [ $this, 'admin_page' ],
            'dashicons-images-alt2',
            30
        );
    }
    
    public function enqueue_admin_scripts( $hook ) {
        if ( $hook !== 'toplevel_page_ngsi-carousels' ) {
            return;
        }
        
        // Cargar los mismos estilos que el frontend para la vista previa
        wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0' );
        wp_enqueue_style( 'ngsi-carousel-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/style.css', [], time() );
        wp_enqueue_style( 'ngsi-admin-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin.css', [], time() );
        
        wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0', true );
        wp_enqueue_script( 'ngsi-carousel-script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/carousel.js', ['swiper-js'], time(), true );
        wp_enqueue_script( 'ngsi-admin-script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/admin.js', ['jquery'], time(), true );
        
        wp_localize_script( 'ngsi-admin-script', 'ngsi_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'ngsi_carousel_nonce' ),
        ]);
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <div class="ngsi-plugin-header">
                <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/media/icon.svg'; ?>" alt="NGSI Woo Carousel" class="ngsi-plugin-icon">
                <div class="ngsi-plugin-info">
                    <h1>🚀 NGSI Carruseles de Productos</h1>
                    <div class="ngsi-author-info">
                        <p><strong>Autor:</strong> Nelson Gil Olguín | <strong>Web:</strong> <a href="https://nelsongil.com" target="_blank">nelsongil.com</a> | <strong>Licencia:</strong> Freeware (Uso Personal)</p>
                    </div>
                </div>
            </div>
            
            <div class="ngsi-admin-container">
                <div class="ngsi-admin-sidebar">
                    <div class="ngsi-admin-panel">
                        <h2>Configuración del Carrusel</h2>
                        
                        <form id="ngsi-carousel-form">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="carousel_title">Título del Carrusel</label>
                                    </th>
                                    <td>
                                        <input type="text" id="carousel_title" name="carousel_title" class="regular-text" placeholder="Dejar vacío para usar nombre de categoría">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="product_category">Categoría de Productos</label>
                                    </th>
                                    <td>
                                        <select id="product_category" name="product_category">
                                            <option value="">Todas las categorías</option>
                                            <?php
                                            $categories = get_terms( [
                                                'taxonomy' => 'product_cat',
                                                'hide_empty' => false,
                                            ] );
                                            
                                            foreach ( $categories as $category ) {
                                                echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">Navegación</th>
                                    <td>
                                        <label>
                                            <input type="checkbox" id="show_navigation" name="show_navigation" checked>
                                            Mostrar botones de navegación
                                        </label>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">Paginación</th>
                                    <td>
                                        <label>
                                            <input type="checkbox" id="show_pagination" name="show_pagination" checked>
                                            Mostrar puntos de paginación
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            
                            <h3>🎨 Personalización de Colores</h3>
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="title_color">Color del Título</label>
                                    </th>
                                    <td>
                                        <input type="color" id="title_color" name="title_color" value="#ffd700">
                                        <span class="description">Color del título del carrusel</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="product_name_color">Color del Nombre del Producto</label>
                                    </th>
                                    <td>
                                        <input type="color" id="product_name_color" name="product_name_color" value="#ffd700">
                                        <span class="description">Color del nombre del producto</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="price_color">Color del Precio</label>
                                    </th>
                                    <td>
                                        <input type="color" id="price_color" name="price_color" value="#333333">
                                        <span class="description">Color del precio del producto</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="button_bg_color">Color de Fondo de Botones</label>
                                    </th>
                                    <td>
                                        <input type="color" id="button_bg_color" name="button_bg_color" value="#0073aa">
                                        <span class="description">Color de fondo de los botones</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="button_text_color">Color del Texto de Botones</label>
                                    </th>
                                    <td>
                                        <input type="color" id="button_text_color" name="button_text_color" value="#ffffff">
                                        <span class="description">Color del texto de los botones</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <h3>💾 Guardar Configuración</h3>
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="carousel_name">Nombre del Carrusel</label>
                                    </th>
                                    <td>
                                        <input type="text" id="carousel_name" name="carousel_name" class="regular-text" placeholder="Ej: Carrusel de Anillos">
                                        <button type="button" id="save-carousel" class="button button-secondary">
                                            💾 Guardar Carrusel
                                        </button>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="ngsi-admin-actions">
                                <button type="button" id="preview-carousel" class="button button-primary">
                                    👁️ Vista Previa
                                </button>
                                <button type="button" id="generate-shortcode" class="button button-secondary">
                                    📋 Generar Shortcode
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="ngsi-admin-panel">
                        <h2>Shortcode Generado</h2>
                        <div id="generated-shortcode" class="ngsi-shortcode-display">
                            <code>[ngsi_carousel]</code>
                        </div>
                        <button type="button" id="copy-shortcode" class="button button-small">
                            📋 Copiar
                        </button>
                    </div>
                    
                    <div class="ngsi-admin-panel">
                        <h2>💾 Carruseles Guardados</h2>
                        <div id="saved-carousels" class="ngsi-saved-carousels">
                            <p>Carga un carrusel guardado para reutilizar su configuración</p>
                        </div>
                    </div>
                </div>
                
                <div class="ngsi-admin-main">
                    <div class="ngsi-admin-panel">
                        <h2>Vista Previa</h2>
                        <div id="carousel-preview" class="ngsi-preview-container">
                            <div class="ngsi-preview-placeholder">
                                <p>Selecciona una categoría para ver la vista previa</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ngsi-admin-panel">
                        <h2>Información de la Categoría</h2>
                        <div id="category-info" class="ngsi-category-info">
                            <p>Selecciona una categoría para ver información detallada</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function ajax_get_category_products() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $category = sanitize_text_field( $_POST['category'] ?? '' );
        
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ];
        
        if ( ! empty( $category ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category,
                ],
            ];
        }
        
        $query = new WP_Query( $args );
        $products = [];
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                global $product;
                
                $image_url = '';
                $image_id = $product->get_image_id();
                if ( $image_id ) {
                    $image_url = wp_get_attachment_image_url( $image_id, 'medium' );
                }
                
                $products[] = [
                    'id'    => get_the_ID(),
                    'title' => get_the_title(),
                    'price' => $product->get_price_html(),
                    'image' => $image_url,
                    'url'   => get_permalink(),
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success( [
            'products' => $products,
            'count'    => count( $products ),
        ] );
    }
    
    public function ajax_preview_carousel() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $category = sanitize_text_field( $_POST['category'] ?? '' );
        $title = sanitize_text_field( $_POST['title'] ?? '' );
        $visible = intval( $_POST['visible'] ?? 4 );
        $navigation = $_POST['navigation'] === 'true';
        $pagination = $_POST['pagination'] === 'true';
        $title_color = sanitize_hex_color( $_POST['title_color'] ?? '#ffd700' );
        $product_name_color = sanitize_hex_color( $_POST['product_name_color'] ?? '#ffd700' );
        $price_color = sanitize_hex_color( $_POST['price_color'] ?? '#333333' );
        $button_bg_color = sanitize_hex_color( $_POST['button_bg_color'] ?? '#0073aa' );
        $button_text_color = sanitize_hex_color( $_POST['button_text_color'] ?? '#ffffff' );
        
        // Generar el shortcode para la vista previa
        $shortcode_atts = [];
        if ( ! empty( $category ) ) $shortcode_atts[] = 'category="' . esc_attr( $category ) . '"';
        if ( ! empty( $title ) ) $shortcode_atts[] = 'title="' . esc_attr( $title ) . '"';
        // visible siempre es 4, no lo añadimos al shortcode
        if ( ! $navigation ) $shortcode_atts[] = 'navigation="false"';
        if ( ! $pagination ) $shortcode_atts[] = 'pagination="false"';
        if ( $title_color !== '#ffd700' ) $shortcode_atts[] = 'title_color="' . esc_attr( $title_color ) . '"';
        if ( $product_name_color !== '#ffd700' ) $shortcode_atts[] = 'product_name_color="' . esc_attr( $product_name_color ) . '"';
        if ( $price_color !== '#333333' ) $shortcode_atts[] = 'price_color="' . esc_attr( $price_color ) . '"';
        if ( $button_bg_color !== '#0073aa' ) $shortcode_atts[] = 'button_bg_color="' . esc_attr( $button_bg_color ) . '"';
        if ( $button_text_color !== '#ffffff' ) $shortcode_atts[] = 'button_text_color="' . esc_attr( $button_text_color ) . '"';
        
        $shortcode = '[ngsi_carousel' . ( ! empty( $shortcode_atts ) ? ' ' . implode( ' ', $shortcode_atts ) : '' ) . ']';
        
        // Ejecutar el shortcode para obtener el HTML
        $html = do_shortcode( $shortcode );
        
        wp_send_json_success( [
            'html' => $html,
            'shortcode' => $shortcode,
        ] );
    }
    
    public function ajax_save_carousel() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $name = sanitize_text_field( $_POST['name'] ?? '' );
        $config = [
            'category' => sanitize_text_field( $_POST['category'] ?? '' ),
            'title' => sanitize_text_field( $_POST['title'] ?? '' ),
            'visible' => intval( $_POST['visible'] ?? 4 ),
            'navigation' => $_POST['navigation'] === 'true',
            'pagination' => $_POST['pagination'] === 'true',
            'title_color' => sanitize_hex_color( $_POST['title_color'] ?? '#ffd700' ),
            'product_name_color' => sanitize_hex_color( $_POST['product_name_color'] ?? '#ffd700' ),
            'price_color' => sanitize_hex_color( $_POST['price_color'] ?? '#333333' ),
            'button_bg_color' => sanitize_hex_color( $_POST['button_bg_color'] ?? '#0073aa' ),
            'button_text_color' => sanitize_hex_color( $_POST['button_text_color'] ?? '#ffffff' ),
        ];
        
        if ( empty( $name ) ) {
            wp_send_json_error( 'El nombre del carrusel es requerido' );
        }
        
        $saved_carousels = get_option( 'ngsi_saved_carousels', [] );
        $saved_carousels[ sanitize_key( $name ) ] = [
            'name' => $name,
            'config' => $config,
            'date' => current_time( 'mysql' ),
        ];
        
        update_option( 'ngsi_saved_carousels', $saved_carousels );
        
        wp_send_json_success( 'Carrusel guardado correctamente' );
    }
    
    public function ajax_load_carousel() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $carousel_id = sanitize_key( $_POST['carousel_id'] ?? '' );
        
        error_log('NGSI Debug - Cargando carrusel ID: ' . $carousel_id);
        
        if ( empty( $carousel_id ) ) {
            error_log('NGSI Debug - ID del carrusel vacío');
            wp_send_json_error( 'ID del carrusel requerido' );
        }
        
        $saved_carousels = get_option( 'ngsi_saved_carousels', [] );
        
        error_log('NGSI Debug - Carruseles guardados: ' . print_r($saved_carousels, true));
        
        if ( ! isset( $saved_carousels[ $carousel_id ] ) ) {
            error_log('NGSI Debug - Carrusel no encontrado: ' . $carousel_id);
            wp_send_json_error( 'Carrusel no encontrado' );
        }
        
        error_log('NGSI Debug - Carrusel encontrado, enviando datos');
        wp_send_json_success( $saved_carousels[ $carousel_id ] );
    }
    
    public function ajax_delete_carousel() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $carousel_id = sanitize_key( $_POST['carousel_id'] ?? '' );
        
        if ( empty( $carousel_id ) ) {
            wp_send_json_error( 'ID del carrusel requerido' );
        }
        
        $saved_carousels = get_option( 'ngsi_saved_carousels', [] );
        
        if ( isset( $saved_carousels[ $carousel_id ] ) ) {
            unset( $saved_carousels[ $carousel_id ] );
            update_option( 'ngsi_saved_carousels', $saved_carousels );
            wp_send_json_success( 'Carrusel eliminado correctamente' );
        } else {
            wp_send_json_error( 'Carrusel no encontrado' );
        }
    }
    
    public function ajax_get_saved_carousels() {
        check_ajax_referer( 'ngsi_carousel_nonce', 'nonce' );
        
        $saved_carousels = get_option( 'ngsi_saved_carousels', [] );
        
        wp_send_json_success( $saved_carousels );
    }
}

// Inicializar el admin
new NGSI_Carousel_Admin();
