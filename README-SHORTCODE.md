# NGSI Woo Carousel - Shortcode

## Descripción
Carrusel de productos WooCommerce usando shortcode de WordPress. Compatible con DIVI Visual Builder.

## Uso

### Shortcode básico
```
[ngsi_carousel]
```

### Shortcode con parámetros
```
[ngsi_carousel category="anillos" title="Mis Anillos" visible="6" navigation="true" pagination="true"]
```

## Parámetros disponibles

| Parámetro | Descripción | Valores | Por defecto |
|-----------|-------------|---------|-------------|
| `category` | Slug de la categoría de productos | Cualquier slug de categoría | Vacío (todas las categorías) |
| `title` | Título personalizado del carrusel | Cualquier texto | Nombre de la categoría o "Productos destacados" |
| `visible` | Productos visibles simultáneamente en el carrusel | 1-6 | 4 |
| `navigation` | Mostrar botones de navegación | true/false | true |
| `pagination` | Mostrar puntos de paginación | true/false | true |

## Ejemplos de uso

### Mostrar todos los productos
```
[ngsi_carousel]
```

### Mostrar productos de una categoría específica
```
[ngsi_carousel category="anillos"]
```

### Carrusel personalizado
```
[ngsi_carousel category="pulseras" title="Pulseras de Oro" visible="3"]
```

### Carrusel con pocos productos visibles
```
[ngsi_carousel visible="2" navigation="false"]
```

### Sin navegación ni paginación
```
[ngsi_carousel navigation="false" pagination="false"]
```

## 🎛️ Módulo de Administración

El plugin incluye un módulo de administración completo para crear y gestionar carruseles:

### Acceso al módulo
1. Ve a **WordPress Admin** → **Carruseles**
2. Configura tu carrusel con la interfaz visual
3. Ve la vista previa en tiempo real
4. Copia el shortcode generado

### Características del módulo
- ✅ **Interfaz visual** para configurar carruseles
- ✅ **Vista previa en tiempo real** de la categoría seleccionada
- ✅ **Información detallada** de productos por categoría
- ✅ **Generación automática** de shortcodes
- ✅ **Copia rápida** del código generado
- ✅ **Sin límites** de productos - muestra todos los disponibles

## Uso en DIVI Visual Builder

1. Añade un módulo "Código" o "Texto"
2. Inserta el shortcode con los parámetros deseados
3. El carrusel se mostrará con previsualización completa

## Ventajas del sistema

- ✅ **Sin límites de productos** - Muestra TODOS los productos disponibles
- ✅ **Módulo de administración** con interfaz visual completa
- ✅ **Vista previa en tiempo real** antes de usar
- ✅ **Previsualización completa** en DIVI Visual Builder
- ✅ **Sin errores críticos** de módulos complejos
- ✅ **Fácil de usar** con parámetros simples
- ✅ **Compatible** con cualquier tema de WordPress
- ✅ **Mantenible** y fácil de actualizar
