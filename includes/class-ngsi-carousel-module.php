<?php

class NGSI_Carousel_Module extends ET_Builder_Module {
    public $slug       = 'ngsi_woo_carousel';
    public $vb_support = 'on';

    protected $module_credits = [
        'module_uri' => '',
        'author'     => 'Tu Nombre',
        'author_uri' => '',
    ];

    public function init() {
        $this->name = esc_html__( 'NGSI Woo Carousel', 'ngsi-woo-carousel' );
        $this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';
        $this->main_css_element = '%%order_class%%';
        
        // Habilitar campos avanzados de DIVI
        $this->advanced_fields = [
            'background' => [
                'css' => [
                    'main' => '%%order_class%%',
                ],
            ],
            'borders' => [
                'css' => [
                    'main' => '%%order_class%%',
                ],
            ],
            'box_shadow' => [
                'css' => [
                    'main' => '%%order_class%%',
                ],
            ],
            'fonts' => [
                'title' => [
                    'label' => esc_html__( 'Título', 'ngsi-woo-carousel' ),
                    'css' => [
                        'main' => '%%order_class%% .ngsi-carousel-title h2',
                    ],
                ],
            ],
            'text' => [
                'css' => [
                    'main' => '%%order_class%%',
                ],
            ],
            'margin_padding' => [
                'css' => [
                    'main' => '%%order_class%%',
                ],
            ],
        ];
    }

    public function get_fields() {
        $categories = $this->get_product_categories();
        $categories = array_merge( [ '' => esc_html__( 'Todas las categorías', 'ngsi-woo-carousel' ) ], $categories );
        
        return [
            'carousel_title' => [
                'label'           => esc_html__( 'Título del carrusel', 'ngsi-woo-carousel' ),
                'type'            => 'text',
                'default'         => '',
                'description'     => esc_html__( 'Deja vacío para usar el nombre de la categoría automáticamente.', 'ngsi-woo-carousel' ),
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
            ],
            'product_category' => [
                'label'           => esc_html__( 'Categoría de productos', 'ngsi-woo-carousel' ),
                'type'            => 'select',
                'options'         => $categories,
                'description'     => esc_html__( 'Selecciona una categoría de productos o deja vacío para mostrar todos.', 'ngsi-woo-carousel' ),
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
            ],
            'products_per_page' => [
                'label'           => esc_html__( 'Número de productos', 'ngsi-woo-carousel' ),
                'type'            => 'range',
                'default'         => '10',
                'range_settings'  => [
                    'min'  => '1',
                    'max'  => '20',
                    'step' => '1',
                ],
                'description'     => esc_html__( 'Número máximo de productos a mostrar', 'ngsi-woo-carousel' ),
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
            ],
            'show_navigation' => [
                'label'           => esc_html__( 'Mostrar navegación', 'ngsi-woo-carousel' ),
                'type'            => 'yes_no_button',
                'options'         => [
                    'on'  => esc_html__( 'Sí', 'ngsi-woo-carousel' ),
                    'off' => esc_html__( 'No', 'ngsi-woo-carousel' ),
                ],
                'default'         => 'on',
                'description'     => esc_html__( 'Mostrar botones de navegación', 'ngsi-woo-carousel' ),
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
            ],
            'show_pagination' => [
                'label'           => esc_html__( 'Mostrar paginación', 'ngsi-woo-carousel' ),
                'type'            => 'yes_no_button',
                'options'         => [
                    'on'  => esc_html__( 'Sí', 'ngsi-woo-carousel' ),
                    'off' => esc_html__( 'No', 'ngsi-woo-carousel' ),
                ],
                'default'         => 'on',
                'description'     => esc_html__( 'Mostrar puntos de paginación', 'ngsi-woo-carousel' ),
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
            ],
        ];
    }

    private function get_product_categories() {
        $terms = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] );
        $options = [];
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }

    /**
     * Obtiene la URL de la imagen por defecto con fallback multiplataforma
     */
    private function get_default_image_url() {
        // Obtener rutas del plugin usando funciones de WordPress
        $plugin_dir = plugin_dir_path( dirname( dirname( __FILE__ ) ) );
        $plugin_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );
        
        // Normalizar la ruta del archivo para el sistema operativo actual
        $image_path = wp_normalize_path( $plugin_dir . 'assets/media/producto.png' );
        $image_url = $plugin_url . 'assets/media/producto.png';
        
        // Debug temporal - eliminar después
        echo '<!-- DEBUG IMAGEN: Plugin Dir: ' . esc_html( $plugin_dir ) . ' -->';
        echo '<!-- DEBUG IMAGEN: Plugin URL: ' . esc_html( $plugin_url ) . ' -->';
        echo '<!-- DEBUG IMAGEN: Image Path: ' . esc_html( $image_path ) . ' -->';
        echo '<!-- DEBUG IMAGEN: Image URL: ' . esc_html( $image_url ) . ' -->';
        echo '<!-- DEBUG IMAGEN: File exists: ' . (file_exists( $image_path ) ? 'YES' : 'NO') . ' -->';
        echo '<!-- DEBUG IMAGEN: Is readable: ' . (is_readable( $image_path ) ? 'YES' : 'NO') . ' -->';
        
        // Verificar si el archivo existe y es accesible
        if ( file_exists( $image_path ) && is_readable( $image_path ) ) {
            echo '<!-- DEBUG IMAGEN: Using real image -->';
            return $image_url;
        }
        
        // Intentar URL alternativa si la primera falla
        $alternative_url = $this->get_alternative_image_url();
        if ( $alternative_url ) {
            echo '<!-- DEBUG IMAGEN: Using alternative URL -->';
            return $alternative_url;
        }
        
        // Fallback: imagen SVG generada dinámicamente
        echo '<!-- DEBUG IMAGEN: Using placeholder SVG -->';
        return $this->get_placeholder_image();
    }

    /**
     * Intenta obtener una URL alternativa para la imagen
     */
    private function get_alternative_image_url() {
        // Construir URL manualmente basándose en la estructura del servidor
        $site_url = get_site_url();
        $plugin_name = 'NGSI Woo Carousel';
        
        // URL alternativa con espacios codificados
        $alternative_url = $site_url . '/wp-content/plugins/' . rawurlencode( $plugin_name ) . '/assets/media/producto.png';
        
        echo '<!-- DEBUG IMAGEN: Alternative URL: ' . esc_html( $alternative_url ) . ' -->';
        
        // Verificar si la URL es accesible haciendo una petición HTTP
        $response = wp_remote_head( $alternative_url );
        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            return $alternative_url;
        }
        
        return false;
    }

    /**
     * Genera una imagen placeholder SVG como base64
     */
    private function get_placeholder_image() {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
            <defs>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#f8f9fa;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#e9ecef;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="200" height="200" fill="url(#grad1)" stroke="#dee2e6" stroke-width="1"/>
            <circle cx="100" cy="80" r="25" fill="#6c757d" opacity="0.3"/>
            <path d="M85 100 L100 85 L115 100 L100 115 Z" fill="#6c757d" opacity="0.5"/>
            <text x="100" y="150" text-anchor="middle" font-family="Arial, sans-serif" font-size="12" fill="#6c757d">Sin imagen</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode( $svg );
    }

    /**
     * Obtiene el título del carrusel
     */
    private function get_carousel_title( $custom_title, $category_slug ) {
        // Si hay un título personalizado, usarlo
        if ( ! empty( $custom_title ) ) {
            return sanitize_text_field( $custom_title );
        }
        
        // Si hay una categoría seleccionada, usar su nombre
        if ( ! empty( $category_slug ) ) {
            $term = get_term_by( 'slug', $category_slug, 'product_cat' );
            if ( $term && ! is_wp_error( $term ) ) {
                return $term->name;
            }
        }
        
        // Título por defecto
        return esc_html__( 'Productos destacados', 'ngsi-woo-carousel' );
    }

    /**
     * Previsualización simple para DIVI
     */
    public function get_preview( $attrs ) {
        return '<div style="background: #0073aa; color: white; padding: 20px; text-align: center; border-radius: 8px;">
            <h2 style="margin: 0;">🚀 NGSI Woo Carousel</h2>
            <p style="margin: 10px 0 0 0;">Carrusel de productos WooCommerce</p>
        </div>';
    }


    protected function render( $attrs, $content = null, $render_slug ) {
        // Usar $this->props en lugar de $attrs para DIVI
        $category = isset( $this->props['product_category'] ) ? sanitize_text_field( $this->props['product_category'] ) : '';
        $custom_title = isset( $this->props['carousel_title'] ) ? sanitize_text_field( $this->props['carousel_title'] ) : '';
        $products_per_page = isset( $this->props['products_per_page'] ) ? intval( $this->props['products_per_page'] ) : 10;
        $show_navigation = isset( $this->props['show_navigation'] ) ? $this->props['show_navigation'] : 'on';
        $show_pagination = isset( $this->props['show_pagination'] ) ? $this->props['show_pagination'] : 'on';
        
        // Obtener el título del carrusel
        $carousel_title = $this->get_carousel_title( $custom_title, $category );
    
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $products_per_page,
            'post_status'    => 'publish',
        ];
        
        // Solo añadir tax_query si se seleccionó una categoría
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
    
        // Calcular productos visibles por defecto
        $visible_products = min( $products_per_page, 4 );
        
        // Construir HTML usando sprintf en lugar de echo
        $html = '<div class="ngsi-carousel swiper" data-visible="' . esc_attr( $visible_products ) . '">';
        
        // Título del carrusel dentro del DIV
        $html .= '<div class="ngsi-carousel-title">';
        $html .= '<h2>' . esc_html( $carousel_title ) . '</h2>';
        $html .= '</div>';
        
        $html .= '<div class="swiper-wrapper">';
    
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                global $product;
    
                $html .= '<div class="swiper-slide ngsi-card">';
                $html .= '<div class="ngsi-card-inner">';
    
                // Imagen
                $image_id = $product->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

                if ( ! $image_url ) {
                    // Usar la función auxiliar para obtener imagen por defecto
                    $image_url = $this->get_default_image_url();
                }

                $html .= '<div class="ngsi-card-image">';
                $html .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '" style="width:100%; height:200px; object-fit:cover;" />';
                $html .= '</div>';
    
                // Nombre
                $html .= '<h3 class="ngsi-card-title">' . get_the_title() . '</h3>';
    
                // Precio
                $html .= '<div class="ngsi-card-price">' . $product->get_price_html() . '</div>';
    
                // Botones
                $html .= '<div class="ngsi-card-buttons">';
                $html .= '<a href="' . get_permalink() . '" class="ngsi-btn-view">Ver producto</a>';
                if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
                    ob_start();
                    woocommerce_template_loop_add_to_cart();
                    $html .= ob_get_clean();
                } else {
                    $html .= '<a href="' . get_permalink() . '" class="ngsi-btn-add-cart">Añadir al carrito</a>';
                }
                $html .= '</div>';
    
                $html .= '</div></div>'; // Cierre tarjeta
            }
            wp_reset_postdata();
        } else {
            // Contenido por defecto cuando no hay productos
            $html .= '<div class="swiper-slide ngsi-card">';
            $html .= '<div class="ngsi-card-inner">';
            $html .= '<div class="ngsi-card-image">';
            $html .= '<div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#666;">';
            $html .= '<span>📦 Sin productos</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<h3 class="ngsi-card-title">No hay productos disponibles</h3>';
            $html .= '<div class="ngsi-card-price">--</div>';
            $html .= '<div class="ngsi-card-buttons">';
            $html .= '<span style="color:#999; font-size:0.9rem;">Añade productos a WooCommerce</span>';
            $html .= '</div>';
            $html .= '</div></div>';
        }
    
        $html .= '</div>'; // swiper-wrapper
        
        // Mostrar paginación solo si está habilitada
        if ( $show_pagination === 'on' ) {
            $html .= '<div class="swiper-pagination"></div>';
        }
        
        // Mostrar navegación solo si está habilitada
        if ( $show_navigation === 'on' ) {
            $html .= '<div class="swiper-button-prev"></div>';
            $html .= '<div class="swiper-button-next"></div>';
        }
        
        $html .= '</div>'; // swiper
    
        return $html;
    }
}