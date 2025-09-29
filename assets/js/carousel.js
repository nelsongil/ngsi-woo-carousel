// NGSI Carousel - Versión simplificada
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 NGSI Carousel: Iniciando...');
    
    // Esperar un poco para asegurar que todo esté cargado
    setTimeout(function() {
        const carousels = document.querySelectorAll('.ngsi-carousel');
        console.log('🔍 NGSI Carousel: Encontrados', carousels.length, 'carruseles');
        
        if (carousels.length === 0) {
            console.error('❌ NGSI Carousel: No se encontraron carruseles');
            return;
        }
        
        carousels.forEach((carousel, index) => {
            console.log('⚙️ NGSI Carousel: Procesando carrusel', index + 1);
            
            const slides = carousel.querySelectorAll('.swiper-slide');
            console.log('📦 NGSI Carousel: Slides encontrados:', slides.length);
            
            if (slides.length === 0) {
                console.warn('⚠️ NGSI Carousel: No hay slides en el carrusel', index + 1);
                return;
            }
            
            // Obtener número de productos visibles desde el atributo data-visible
            const visibleProducts = parseInt(carousel.getAttribute('data-visible')) || 4;
            console.log('📊 NGSI Carousel: Productos visibles configurados:', visibleProducts);

            // Crear Swiper de forma más simple
            try {
                const swiper = new Swiper(carousel, {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    loop: false,
                    centeredSlides: false,
                    allowTouchMove: true,
                    navigation: {
                        nextEl: carousel.querySelector('.swiper-button-next'),
                        prevEl: carousel.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: carousel.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    breakpoints: {
                        1200: {
                            slidesPerView: Math.min(visibleProducts, 4),
                            spaceBetween: 20,
                        },
                        992: {
                            slidesPerView: Math.min(visibleProducts, 3),
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: Math.min(visibleProducts, 2),
                            spaceBetween: 15,
                        },
                        480: {
                            slidesPerView: 1,
                            spaceBetween: 10,
                        }
                    },
                    on: {
                        reachEnd: function () {
                            console.log('NGSI Carousel: Llegó al final');
                            // Deshabilitar botón siguiente
                            const nextBtn = carousel.querySelector('.swiper-button-next');
                            if (nextBtn) {
                                nextBtn.classList.add('swiper-button-disabled');
                            }
                        },
                        reachBeginning: function () {
                            console.log('NGSI Carousel: Llegó al inicio');
                            // Deshabilitar botón anterior
                            const prevBtn = carousel.querySelector('.swiper-button-prev');
                            if (prevBtn) {
                                prevBtn.classList.add('swiper-button-disabled');
                            }
                        },
                        slideChange: function () {
                            // Rehabilitar botones cuando no esté en los extremos
                            const nextBtn = carousel.querySelector('.swiper-button-next');
                            const prevBtn = carousel.querySelector('.swiper-button-prev');
                            
                            if (nextBtn && prevBtn) {
                                nextBtn.classList.remove('swiper-button-disabled');
                                prevBtn.classList.remove('swiper-button-disabled');
                            }
                        }
                    }
                });
                
                console.log('✅ NGSI Carousel: Swiper creado exitosamente');
            } catch (error) {
                console.error('❌ NGSI Carousel: Error al crear Swiper:', error);
            }
        });
    }, 100);
});