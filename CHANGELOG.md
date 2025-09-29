# Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-29

### Añadido
- ✅ **Shortcode básico** `[ngsi_carousel]` para mostrar todos los productos
- ✅ **Shortcode avanzado** con parámetros de personalización
- ✅ **Interfaz visual de administración** para configurar carruseles
- ✅ **Vista previa en tiempo real** antes de usar
- ✅ **Sistema de guardado** para reutilizar configuraciones
- ✅ **Personalización completa de colores**:
  - Color del título del carrusel
  - Color del nombre del producto
  - Color del precio
  - Color de fondo de botones
  - Color del texto de botones
- ✅ **Sistema de actualizaciones automáticas** desde GitHub
- ✅ **Compatibilidad con DIVI** Visual Builder
- ✅ **Sin límites de productos** - muestra todos los disponibles
- ✅ **Responsive** y adaptable a cualquier tema
- ✅ **Información del autor** y licencia

### Características Técnicas
- ✅ **Swiper.js** para funcionalidad del carrusel
- ✅ **AJAX** para vista previa y gestión de configuraciones
- ✅ **WordPress Shortcode API** integrada
- ✅ **Sistema de opciones** para guardar configuraciones
- ✅ **Sanitización** de todos los inputs
- ✅ **Nonce** para seguridad AJAX
- ✅ **Hooks de WordPress** para integración

### Parámetros del Shortcode
- `category` - Slug de la categoría de productos
- `title` - Título personalizado del carrusel
- `title_color` - Color del título (hexadecimal)
- `product_name_color` - Color del nombre del producto (hexadecimal)
- `price_color` - Color del precio (hexadecimal)
- `button_bg_color` - Color de fondo de botones (hexadecimal)
- `button_text_color` - Color del texto de botones (hexadecimal)
- `navigation` - Mostrar botones de navegación (true/false)
- `pagination` - Mostrar puntos de paginación (true/false)

### Requisitos
- WordPress 6.0+
- PHP 7.4+
- WooCommerce (cualquier versión)
- DIVI (opcional)

### Licencia
- Freeware para uso personal
- Licencia comercial requerida para uso comercial
- Autor: Nelson Gil Olguín
- Web: https://nelsongil.com

---

**© 2024 Nelson Gil Olguín. Todos los derechos reservados.**
