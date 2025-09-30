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

            // Calcular configuración dinámica basada en el número de slides
            const totalSlides = slides.length;
            const slidesPerView = Math.min(visibleProducts, 4);
            const totalPages = Math.ceil(totalSlides / slidesPerView);
            
            console.log('📊 NGSI Carousel: Total slides:', totalSlides);
            console.log('📊 NGSI Carousel: Slides por vista:', slidesPerView);
            console.log('📊 NGSI Carousel: Total páginas:', totalPages);

            // Crear Swiper con configuración optimizada
            try {
                const swiper = new Swiper(carousel, {
                    slidesPerView: slidesPerView,
                    spaceBetween: 20,
                    loop: false,
                    centeredSlides: false,
                    allowTouchMove: true,
                    watchSlidesProgress: true,
                    watchSlidesVisibility: true,
                    navigation: {
                        nextEl: carousel.querySelector('.swiper-button-next'),
                        prevEl: carousel.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: carousel.querySelector('.swiper-pagination'),
                        clickable: true,
                        dynamicBullets: true,
                        dynamicMainBullets: 3,
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
                        init: function () {
                            console.log('✅ NGSI Carousel: Swiper inicializado correctamente');
                            console.log('📊 NGSI Carousel: Slides totales:', this.slides.length);
                            console.log('📊 NGSI Carousel: Slides por vista:', this.params.slidesPerView);
                            
                            // Actualizar estado inicial de los botones
                            this.updateNavigationButtons();
                        },
                        reachEnd: function () {
                            console.log('🏁 NGSI Carousel: Llegó al final');
                            this.updateNavigationButtons();
                        },
                        reachBeginning: function () {
                            console.log('🏁 NGSI Carousel: Llegó al inicio');
                            this.updateNavigationButtons();
                        },
                        slideChange: function () {
                            console.log('🔄 NGSI Carousel: Cambio de slide - Activo:', this.activeIndex);
                            this.updateNavigationButtons();
                        },
                        updateNavigationButtons: function () {
                            const nextBtn = carousel.querySelector('.swiper-button-next');
                            const prevBtn = carousel.querySelector('.swiper-button-prev');
                            
                            if (nextBtn && prevBtn) {
                                // Deshabilitar botón siguiente si está al final
                                if (this.isEnd) {
                                    nextBtn.classList.add('swiper-button-disabled');
                                } else {
                                    nextBtn.classList.remove('swiper-button-disabled');
                                }
                                
                                // Deshabilitar botón anterior si está al inicio
                                if (this.isBeginning) {
                                    prevBtn.classList.add('swiper-button-disabled');
                                } else {
                                    prevBtn.classList.remove('swiper-button-disabled');
                                }
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