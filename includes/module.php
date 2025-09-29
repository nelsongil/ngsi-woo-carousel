<?php

function ngsi_register_divi_module() {
    if ( class_exists( 'ET_Builder_Module' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'class-ngsi-carousel-module.php';
        new NGSI_Carousel_Module();
    }
}
add_action( 'et_builder_ready', 'ngsi_register_divi_module' );

