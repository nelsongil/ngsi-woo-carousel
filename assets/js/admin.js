jQuery(document).ready(function($) {
    'use strict';
    
    // Variables globales
    let currentCategory = '';
    let currentProducts = [];
    
    // Inicializar eventos
    initEvents();
    
    function initEvents() {
        // Cambio de categoría
        $('#product_category').on('change', function() {
            currentCategory = $(this).val();
            loadCategoryProducts();
        });
        
        // Cambio en cualquier configuración
        $('#ngsi-carousel-form input, #ngsi-carousel-form select').on('change', function() {
            updateShortcode();
        });
        
        // Vista previa
        $('#preview-carousel').on('click', function() {
            generatePreview();
        });
        
        // Generar shortcode
        $('#generate-shortcode').on('click', function() {
            updateShortcode();
        });
        
        // Copiar shortcode
        $('#copy-shortcode').on('click', function() {
            copyShortcode();
        });
        
        // Guardar carrusel
        $('#save-carousel').on('click', function() {
            saveCarousel();
        });
        
        // Cargar carruseles guardados al inicializar
        loadSavedCarousels();
        
    }
    
    function loadCategoryProducts() {
        if (!currentCategory) {
            showCategoryInfo('Selecciona una categoría para ver información detallada');
            showPreviewPlaceholder();
            return;
        }
        
        showLoading('#category-info');
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ngsi_get_category_products',
                category: currentCategory,
                nonce: ngsi_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    currentProducts = response.data.products;
                    showCategoryInfo(response.data);
                } else {
                    showCategoryInfo('Error al cargar los productos');
                }
            },
            error: function() {
                showCategoryInfo('Error de conexión');
            }
        });
    }
    
    function showCategoryInfo(data) {
        let html = '';
        
        if (typeof data === 'string') {
            html = '<p>' + data + '</p>';
        } else {
            const categoryName = $('#product_category option:selected').text();
            html = `
                <h3>📂 ${categoryName}</h3>
                <div class="product-count">${data.count} productos encontrados</div>
            `;
            
            if (data.products.length > 0) {
                html += '<div class="product-list">';
                data.products.slice(0, 10).forEach(function(product) {
                    const imageHtml = product.image ? 
                        `<img src="${product.image}" alt="${product.title}" style="width: 100%; height: 100%; object-fit: cover;">` :
                        '<span>📦</span>';
                    
                    html += `
                        <div class="product-item">
                            <div class="product-image">${imageHtml}</div>
                            <div class="product-details">
                                <div class="product-title">${product.title}</div>
                                <div class="product-price">${product.price}</div>
                            </div>
                        </div>
                    `;
                });
                
                if (data.products.length > 10) {
                    html += `<div style="text-align: center; padding: 10px; color: #666; font-style: italic;">
                        ... y ${data.products.length - 10} productos más
                    </div>`;
                }
                
                html += '</div>';
            } else {
                html += '<p style="color: #d63638; font-style: italic;">No hay productos en esta categoría</p>';
            }
        }
        
        $('#category-info').html(html);
    }
    
    function generatePreview() {
        if (!currentCategory) {
            showPreviewPlaceholder('Selecciona una categoría para ver la vista previa');
            return;
        }
        
        showLoading('#carousel-preview');
        
        const formData = {
            action: 'ngsi_preview_carousel',
            category: currentCategory,
            title: $('#carousel_title').val(),
            visible: 4, // Valor fijo ya que quitamos el campo
            navigation: $('#show_navigation').is(':checked'),
            pagination: $('#show_pagination').is(':checked'),
            title_color: $('#title_color').val(),
            product_name_color: $('#product_name_color').val(),
            price_color: $('#price_color').val(),
            button_bg_color: $('#button_bg_color').val(),
            button_text_color: $('#button_text_color').val(),
            nonce: ngsi_ajax.nonce
        };
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#carousel-preview').html(response.data.html);
                    updateShortcode(response.data.shortcode);
                } else {
                    showPreviewPlaceholder('Error al generar la vista previa');
                }
            },
            error: function() {
                showPreviewPlaceholder('Error de conexión');
            }
        });
    }
    
    function showPreviewPlaceholder(message) {
        message = message || 'Selecciona una categoría para ver la vista previa';
        $('#carousel-preview').html(`
            <div class="ngsi-preview-placeholder">
                <p>${message}</p>
            </div>
        `);
    }
    
    function showLoading(selector) {
        $(selector).html('<div style="text-align: center; padding: 40px;"><div class="ngsi-loading"></div><p>Cargando...</p></div>');
    }
    
    function updateShortcode(shortcode) {
        if (!shortcode) {
            const formData = {
                category: $('#product_category').val(),
                title: $('#carousel_title').val(),
                visible: 4, // Valor fijo
                navigation: $('#show_navigation').is(':checked'),
                pagination: $('#show_pagination').is(':checked'),
                title_color: $('#title_color').val(),
                product_name_color: $('#product_name_color').val(),
                price_color: $('#price_color').val(),
                button_bg_color: $('#button_bg_color').val(),
                button_text_color: $('#button_text_color').val()
            };
            
            const attrs = [];
            if (formData.category) attrs.push(`category="${formData.category}"`);
            if (formData.title) attrs.push(`title="${formData.title}"`);
            // visible siempre es 4, no lo añadimos al shortcode
            if (!formData.navigation) attrs.push('navigation="false"');
            if (!formData.pagination) attrs.push('pagination="false"');
            if (formData.title_color !== '#ffd700') attrs.push(`title_color="${formData.title_color}"`);
            if (formData.product_name_color !== '#ffd700') attrs.push(`product_name_color="${formData.product_name_color}"`);
            if (formData.price_color !== '#333333') attrs.push(`price_color="${formData.price_color}"`);
            if (formData.button_bg_color !== '#0073aa') attrs.push(`button_bg_color="${formData.button_bg_color}"`);
            if (formData.button_text_color !== '#ffffff') attrs.push(`button_text_color="${formData.button_text_color}"`);
            
            shortcode = '[ngsi_carousel' + (attrs.length ? ' ' + attrs.join(' ') : '') + ']';
        }
        
        $('#generated-shortcode code').text(shortcode);
    }
    
    function copyShortcode() {
        const shortcode = $('#generated-shortcode code').text();
        
        // Crear elemento temporal para copiar
        const tempInput = document.createElement('input');
        tempInput.value = shortcode;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Mostrar feedback visual
        const button = $('#copy-shortcode');
        const originalText = button.text();
        button.addClass('ngsi-copy-success').text('Copiado!');
        
        setTimeout(function() {
            button.removeClass('ngsi-copy-success').text(originalText);
        }, 2000);
    }
    
    function saveCarousel() {
        const name = $('#carousel_name').val().trim();
        
        if (!name) {
            alert('Por favor, introduce un nombre para el carrusel');
            return;
        }
        
        const formData = {
            action: 'ngsi_save_carousel',
            name: name,
            category: $('#product_category').val(),
            title: $('#carousel_title').val(),
            visible: 4, // Valor fijo
            navigation: $('#show_navigation').is(':checked'),
            pagination: $('#show_pagination').is(':checked'),
            title_color: $('#title_color').val(),
            product_name_color: $('#product_name_color').val(),
            price_color: $('#price_color').val(),
            button_bg_color: $('#button_bg_color').val(),
            button_text_color: $('#button_text_color').val(),
            nonce: ngsi_ajax.nonce
        };
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Carrusel guardado correctamente');
                    $('#carousel_name').val('');
                    loadSavedCarousels();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    }
    
    function loadSavedCarousels() {
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ngsi_get_saved_carousels',
                nonce: ngsi_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    displaySavedCarousels(response.data);
                } else {
                    $('#saved-carousels').html('<p>No hay carruseles guardados</p>');
                }
            },
            error: function() {
                $('#saved-carousels').html('<p>Error al cargar carruseles guardados</p>');
            }
        });
    }
    
    function displaySavedCarousels(carousels) {
        if (Object.keys(carousels).length === 0) {
            $('#saved-carousels').html('<p>No hay carruseles guardados</p>');
            return;
        }
        
        let html = '<div class="ngsi-saved-list">';
        
        Object.keys(carousels).forEach(function(carouselId) {
            const carousel = carousels[carouselId];
            const date = new Date(carousel.date).toLocaleDateString();
            
            html += `
                <div class="ngsi-saved-item">
                    <div class="ngsi-saved-info">
                        <h4>${carousel.name}</h4>
                        <p>Categoría: ${carousel.config.category || 'Todas'}</p>
                        <p>Guardado: ${date}</p>
                    </div>
                    <div class="ngsi-saved-actions">
                        <button type="button" class="button button-small" onclick="loadCarousel('${carouselId}')">
                            📂 Cargar
                        </button>
                        <button type="button" class="button button-small button-link-delete" onclick="deleteCarousel('${carouselId}')">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        $('#saved-carousels').html(html);
    }
    
    function deleteCarousel(carouselId) {
        if (!confirm('¿Estás seguro de que quieres eliminar este carrusel?')) {
            return;
        }
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ngsi_delete_carousel',
                carousel_id: carouselId,
                nonce: ngsi_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('Carrusel eliminado correctamente');
                    loadSavedCarousels();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    }
    
    function loadCarousel(carouselId) {
        console.log('Cargando carrusel:', carouselId);
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ngsi_load_carousel',
                carousel_id: carouselId,
                nonce: ngsi_ajax.nonce
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.success) {
                    const carousel = response.data;
                    const config = carousel.config;
                    
                    console.log('Configuración cargada:', config);
                    
                    // Cargar configuración en el formulario
                    $('#carousel_title').val(config.title);
                    $('#product_category').val(config.category);
                    // visible siempre es 4, no hay campo que actualizar
                    $('#show_navigation').prop('checked', config.navigation);
                    $('#show_pagination').prop('checked', config.pagination);
                    $('#title_color').val(config.title_color);
                    $('#product_name_color').val(config.product_name_color);
                    $('#price_color').val(config.price_color);
                    $('#button_bg_color').val(config.button_bg_color);
                    $('#button_text_color').val(config.button_text_color);
                    
                    // Actualizar vista previa y shortcode
                    generatePreview();
                    updateShortcode();
                    
                    // También actualizar la categoría actual para que funcione la vista previa
                    currentCategory = config.category;
                    
                    console.log('Carrusel cargado correctamente');
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    }
    
    function deleteCarousel(carouselId) {
        if (!confirm('¿Estás seguro de que quieres eliminar este carrusel?')) {
            return;
        }
        
        $.ajax({
            url: ngsi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ngsi_delete_carousel',
                carousel_id: carouselId,
                nonce: ngsi_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('Carrusel eliminado correctamente');
                    loadSavedCarousels();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    }
    
    // Hacer funciones globales para que funcionen desde HTML
    window.generatePreview = generatePreview;
    window.updateShortcode = updateShortcode;
    window.loadSavedCarousels = loadSavedCarousels;
    window.loadCarousel = loadCarousel;
    window.deleteCarousel = deleteCarousel;
    
    // Test de funciones globales después de definirlas
    console.log('NGSI Debug - Funciones globales disponibles:');
    console.log('generatePreview:', typeof window.generatePreview);
    console.log('updateShortcode:', typeof window.updateShortcode);
    console.log('loadSavedCarousels:', typeof window.loadSavedCarousels);
    console.log('loadCarousel:', typeof window.loadCarousel);
    console.log('deleteCarousel:', typeof window.deleteCarousel);
    
    // Inicializar con valores por defecto
    updateShortcode();
});
