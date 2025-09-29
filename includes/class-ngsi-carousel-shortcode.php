<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NGSI_Carousel_Shortcode {
    
    public function __construct() {
        add_action( 'init', [ $this, 'init' ] );
    }
    
    public function init() {
        add_shortcode( 'ngsi_carousel', [ $this, 'render_carousel' ] );
    }
    
    public function render_carousel( $atts ) {
        // Atributos por defecto
        $atts = shortcode_atts( [
            'category'     => '',
            'title'        => '',
            'visible'      => '4',   // Productos visibles en el carrusel
            'navigation'   => 'true',
            'pagination'   => 'true',
            'title_color'  => '#ffd700',
            'product_name_color' => '#ffd700',
            'price_color'  => '#333333',
            'button_bg_color' => '#0073aa',
            'button_text_color' => '#ffffff',
        ], $atts );
        
        // Sanitizar atributos
        $category = sanitize_text_field( $atts['category'] );
        $custom_title = sanitize_text_field( $atts['title'] );
        $visible_products = intval( $atts['visible'] );
        $show_navigation = $atts['navigation'] === 'true';
        $show_pagination = $atts['pagination'] === 'true';
        $title_color = sanitize_hex_color( $atts['title_color'] );
        $product_name_color = sanitize_hex_color( $atts['product_name_color'] );
        $price_color = sanitize_hex_color( $atts['price_color'] );
        $button_bg_color = sanitize_hex_color( $atts['button_bg_color'] );
        $button_text_color = sanitize_hex_color( $atts['button_text_color'] );
        
        // Obtener el título del carrusel
        $carousel_title = $this->get_carousel_title( $custom_title, $category );
        
        // Consulta de productos - SIN LÍMITES
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1, // Sin límite - mostrar todos
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
        
        // Generar ID único para los estilos
        $carousel_id = 'ngsi-carousel-' . uniqid();
        
        // Generar HTML con estilos personalizados
        ob_start();
        ?>
        <style>
        .<?php echo esc_attr( $carousel_id ); ?> .ngsi-carousel-title h2 {
            color: <?php echo esc_attr( $title_color ); ?> !important;
        }
        .<?php echo esc_attr( $carousel_id ); ?> .ngsi-card-title {
            color: <?php echo esc_attr( $product_name_color ); ?> !important;
        }
        .<?php echo esc_attr( $carousel_id ); ?> .ngsi-card-price {
            color: <?php echo esc_attr( $price_color ); ?> !important;
        }
        .<?php echo esc_attr( $carousel_id ); ?> .ngsi-card-buttons a {
            background-color: <?php echo esc_attr( $button_bg_color ); ?> !important;
            color: <?php echo esc_attr( $button_text_color ); ?> !important;
        }
        .<?php echo esc_attr( $carousel_id ); ?> .swiper-button-next,
        .<?php echo esc_attr( $carousel_id ); ?> .swiper-button-prev {
            color: <?php echo esc_attr( $title_color ); ?> !important;
            border-color: <?php echo esc_attr( $title_color ); ?> !important;
        }
        </style>
        <div class="ngsi-carousel <?php echo esc_attr( $carousel_id ); ?> swiper" data-visible="<?php echo esc_attr( $visible_products ); ?>">
            <div class="ngsi-carousel-title">
                <h2><?php echo esc_html( $carousel_title ); ?></h2>
            </div>
            <div class="swiper-wrapper">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : ?>
                        <?php $query->the_post(); ?>
                        <?php global $product; ?>
                        
                        <div class="swiper-slide ngsi-card">
                            <div class="ngsi-card-inner">
                                <div class="ngsi-card-image">
                                    <?php
                                    $image_id = $product->get_image_id();
                                    $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
                                    
                                    if ( ! $image_url ) {
                                        $image_url = $this->get_default_image_url();
                                    }
                                    ?>
                                    <img src="<?php echo esc_url( $image_url ); ?>" 
                                         alt="<?php echo esc_attr( get_the_title() ); ?>" 
                                         style="width:100%; height:200px; object-fit:cover;" />
                                </div>
                                
                                <h3 class="ngsi-card-title"><?php echo get_the_title(); ?></h3>
                                <div class="ngsi-card-price"><?php echo $product->get_price_html(); ?></div>
                                
                                <div class="ngsi-card-buttons">
                                    <a href="<?php echo get_permalink(); ?>" class="ngsi-btn-view">Ver producto</a>
                                    <?php if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) : ?>
                                        <?php woocommerce_template_loop_add_to_cart(); ?>
                                    <?php else : ?>
                                        <a href="<?php echo get_permalink(); ?>" class="ngsi-btn-add-cart">Añadir al carrito</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <div class="swiper-slide ngsi-card">
                        <div class="ngsi-card-inner">
                            <div class="ngsi-card-image">
                                <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#666;">
                                    <span>📦 Sin productos</span>
                                </div>
                            </div>
                            <h3 class="ngsi-card-title">No hay productos disponibles</h3>
                            <div class="ngsi-card-price">--</div>
                            <div class="ngsi-card-buttons">
                                <span style="color:#999; font-size:0.9rem;">Añade productos a WooCommerce</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ( $show_pagination ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
            
            <?php if ( $show_navigation ) : ?>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            <?php endif; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    private function get_carousel_title( $custom_title, $category ) {
        if ( ! empty( $custom_title ) ) {
            return $custom_title;
        }
        
        if ( ! empty( $category ) ) {
            $term = get_term_by( 'slug', $category, 'product_cat' );
            if ( $term && ! is_wp_error( $term ) ) {
                return $term->name;
            }
        }
        
        return 'Productos destacados';
    }
    
    private function get_default_image_url() {
        $default_image_path = plugin_dir_path( dirname( __FILE__ ) ) . 'assets/media/producto.png';
        $default_image_url = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/media/producto.png';
        
        if ( file_exists( $default_image_path ) && is_readable( $default_image_path ) ) {
            return $default_image_url;
        }
        
        // Fallback: imagen SVG base64
        return 'data:image/svg+xml;base64,' . base64_encode( '
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
                <rect width="200" height="200" fill="#f0f0f0"/>
                <text x="100" y="100" text-anchor="middle" dy=".3em" font-family="Arial, sans-serif" font-size="24" fill="#666">📦</text>
            </svg>
        ' );
    }
}

// Inicializar el shortcode
new NGSI_Carousel_Shortcode();
