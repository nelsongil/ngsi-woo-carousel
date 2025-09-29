# NGSI Woo Carousel

**Carrusel de productos WooCommerce con shortcode, interfaz visual de administración y personalización completa de colores**

[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-Compatible-green.svg)](https://woocommerce.com/)
[![License](https://img.shields.io/badge/License-Freeware%20(Personal%20Use)-orange.svg)](LICENSE.txt)

## 🚀 Características

- ✅ **Shortcode simple** para usar en cualquier página
- ✅ **Interfaz visual de administración** para configurar carruseles
- ✅ **Vista previa en tiempo real** antes de usar
- ✅ **Personalización completa de colores** (título, nombre, precio, botones)
- ✅ **Sistema de guardado** para reutilizar configuraciones
- ✅ **Sin límites de productos** - muestra todos los disponibles
- ✅ **Compatible con DIVI** Visual Builder
- ✅ **Responsive** y adaptable a cualquier tema
- ✅ **Actualizaciones automáticas** desde GitHub

## 📦 Instalación

### Método 1: Instalación Manual
1. Descarga el plugin desde [GitHub Releases](https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel/releases)
2. Sube la carpeta `ngsi-woo-carousel` a `/wp-content/plugins/`
3. Activa el plugin desde WordPress Admin > Plugins

### Método 2: Instalación desde GitHub (Desarrolladores)
```bash
cd wp-content/plugins/
git clone https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel.git
```

## 🎯 Uso

### Shortcode Básico
```
[ngsi_carousel]
```

### Shortcode con Parámetros
```
[ngsi_carousel 
    category="anillos" 
    title="Anillos de Oro" 
    title_color="#ff6b35" 
    product_name_color="#2c3e50" 
    price_color="#e74c3c" 
    button_bg_color="#3498db" 
    button_text_color="#ffffff"
]
```

### Interfaz de Administración
1. Ve a **WordPress Admin** → **Carruseles**
2. Configura tu carrusel con la interfaz visual
3. Ve la vista previa en tiempo real
4. Guarda configuraciones para reutilizar
5. Copia el shortcode generado

## 🎨 Personalización

### Parámetros Disponibles

| Parámetro | Descripción | Valores | Por defecto |
|-----------|-------------|---------|-------------|
| `category` | Slug de la categoría | Cualquier slug | Todas las categorías |
| `title` | Título personalizado | Cualquier texto | Nombre de categoría |
| `title_color` | Color del título | Código hexadecimal | #ffd700 |
| `product_name_color` | Color del nombre | Código hexadecimal | #ffd700 |
| `price_color` | Color del precio | Código hexadecimal | #333333 |
| `button_bg_color` | Fondo de botones | Código hexadecimal | #0073aa |
| `button_text_color` | Texto de botones | Código hexadecimal | #ffffff |
| `navigation` | Botones de navegación | true/false | true |
| `pagination` | Puntos de paginación | true/false | true |

### Ejemplos de Uso

#### Carrusel de categoría específica
```
[ngsi_carousel category="pulseras" title="Pulseras de Oro"]
```

#### Carrusel con colores personalizados
```
[ngsi_carousel 
    category="anillos" 
    title_color="#e74c3c" 
    product_name_color="#2c3e50" 
    button_bg_color="#27ae60"
]
```

#### Sin navegación
```
[ngsi_carousel navigation="false" pagination="false"]
```

## 🔧 Requisitos

- **WordPress**: 6.0 o superior
- **PHP**: 7.4 o superior
- **WooCommerce**: Cualquier versión
- **DIVI**: Opcional (compatible)

## 📁 Estructura del Plugin

```
ngsi-woo-carousel/
├── assets/
│   ├── css/
│   │   ├── style.css          # Estilos del carrusel
│   │   └── admin.css          # Estilos de administración
│   └── js/
│       ├── carousel.js        # JavaScript del carrusel
│       └── admin.js           # JavaScript de administración
├── includes/
│   ├── class-ngsi-carousel-shortcode.php  # Lógica del shortcode
│   ├── class-ngsi-carousel-admin.php     # Panel de administración
│   └── class-ngsi-carousel-updater.php    # Sistema de actualizaciones
├── ngsi-woo-carousel.php      # Archivo principal
├── LICENSE.txt                # Licencia del plugin
└── README.md                  # Este archivo
```

## 🚀 Actualizaciones Automáticas

El plugin incluye un sistema de actualizaciones automáticas desde GitHub:

- ✅ **Detección automática** de nuevas versiones
- ✅ **Actualización desde WordPress Admin**
- ✅ **Información detallada** de cada versión
- ✅ **Changelog** automático desde GitHub

## 📄 Licencia

### Uso Personal Gratuito
Este plugin está disponible de forma gratuita para uso personal en sitios web individuales.

### Uso Comercial
Si deseas incluir este plugin en proyectos comerciales, paquetes de software o servicios de hosting, debes contactar con el autor para obtener una licencia comercial.

**Contacto**: [nelsongil.com](https://nelsongil.com) | contacto@nelsongil.com

## 👨‍💻 Autor

**Nelson Gil Olguín**
- 🌐 Web: [nelsongil.com](https://nelsongil.com)
- 📧 Email: contacto@nelsongil.com
- 🐙 GitHub: [@nelsongil](https://github.com/nelsongil)

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Changelog

### 1.0.0
- ✅ Lanzamiento inicial
- ✅ Shortcode básico y avanzado
- ✅ Interfaz de administración visual
- ✅ Personalización completa de colores
- ✅ Sistema de guardado de configuraciones
- ✅ Vista previa en tiempo real
- ✅ Compatibilidad con DIVI
- ✅ Actualizaciones automáticas desde GitHub

## 🐛 Reportar Bugs

Si encuentras algún bug, por favor:

1. Verifica que no esté ya reportado en [Issues](https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel/issues)
2. Crea un nuevo issue con:
   - Descripción detallada del problema
   - Pasos para reproducirlo
   - Versión de WordPress y PHP
   - Capturas de pantalla si es necesario

## 📞 Soporte

Para soporte técnico o consultas:

- 📧 Email: contacto@nelsongil.com
- 🌐 Web: [nelsongil.com](https://nelsongil.com)
- 🐙 GitHub Issues: [Reportar problema](https://github.com/[TU-USUARIO-GITHUB]/ngsi-woo-carousel/issues)

---

**© 2024 Nelson Gil Olguín. Todos los derechos reservados.**
