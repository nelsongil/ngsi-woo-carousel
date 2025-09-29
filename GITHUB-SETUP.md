# 🚀 Instrucciones para Configurar GitHub

## 📋 Pasos para subir el plugin a GitHub

### 1. Crear el repositorio en GitHub
1. Ve a [GitHub.com](https://github.com)
2. Haz clic en **"New repository"**
3. Nombre del repositorio: `ngsi-woo-carousel`
4. Descripción: `Carrusel de productos WooCommerce con shortcode y interfaz visual`
5. Marca como **Público**
6. **NO** inicialices con README (ya tenemos uno)
7. Haz clic en **"Create repository"**

### 2. Configurar el repositorio local
```bash
# Navega a la carpeta del plugin
cd "NGSI Woo Carousel"

# Inicializa Git
git init

# Añade todos los archivos
git add .

# Primer commit
git commit -m "Initial commit: NGSI Woo Carousel v1.0.0"

# Conecta con GitHub (reemplaza TU-USUARIO-GITHUB)
git remote add origin https://github.com/TU-USUARIO-GITHUB/ngsi-woo-carousel.git

# Sube al repositorio
git push -u origin main
```

### 3. Actualizar URLs en el código
Después de crear el repositorio, reemplaza `[TU-USUARIO-GITHUB]` con tu usuario real:

#### Archivos a actualizar:
1. **`ngsi-woo-carousel.php`** (líneas 4 y 17)
2. **`includes/class-ngsi-carousel-updater.php`** (línea 30)
3. **`README.md`** (múltiples líneas)

#### Comando de búsqueda y reemplazo:
```bash
# Reemplaza TU-USUARIO-GITHUB con tu usuario real
sed -i 's/\[TU-USUARIO-GITHUB\]/TU-USUARIO-REAL/g' ngsi-woo-carousel.php
sed -i 's/\[TU-USUARIO-GITHUB\]/TU-USUARIO-REAL/g' includes/class-ngsi-carousel-updater.php
sed -i 's/\[TU-USUARIO-GITHUB\]/TU-USUARIO-REAL/g' README.md
```

### 4. Crear el primer Release
1. Ve a tu repositorio en GitHub
2. Haz clic en **"Releases"**
3. Haz clic en **"Create a new release"**
4. Tag version: `v1.0.0`
5. Release title: `NGSI Woo Carousel v1.0.0`
6. Descripción:
```
## 🎉 Primera versión del plugin

### Características principales:
- ✅ Shortcode básico y avanzado
- ✅ Interfaz visual de administración
- ✅ Personalización completa de colores
- ✅ Sistema de guardado de configuraciones
- ✅ Vista previa en tiempo real
- ✅ Compatible con DIVI
- ✅ Actualizaciones automáticas desde GitHub

### Requisitos:
- WordPress 6.0+
- PHP 7.4+
- WooCommerce

### Licencia:
Freeware para uso personal. Contacto requerido para uso comercial.
```
7. Haz clic en **"Publish release"**

### 5. Configurar GitHub Pages (Opcional)
1. Ve a **Settings** del repositorio
2. Scroll hasta **"Pages"**
3. Source: **"Deploy from a branch"**
4. Branch: **"main"**
5. Folder: **"/ (root)"**
6. Haz clic en **"Save"**

## 🔧 Sistema de Actualizaciones Automáticas

Una vez configurado GitHub, el sistema de actualizaciones funcionará automáticamente:

### Cómo funciona:
1. **Detección**: WordPress detecta nuevas versiones en GitHub
2. **Notificación**: Aparece notificación en WordPress Admin
3. **Actualización**: Un clic actualiza el plugin
4. **Información**: Changelog automático desde GitHub

### Para crear nuevas versiones:
1. Haz cambios en el código
2. Actualiza la versión en `ngsi-woo-carousel.php`
3. Actualiza `CHANGELOG.md`
4. Commit y push:
```bash
git add .
git commit -m "Update: Nueva funcionalidad"
git push
```
5. Crea nuevo release en GitHub

## 📁 Estructura final del repositorio

```
ngsi-woo-carousel/
├── .github/
│   └── workflows/
│       └── ci.yml
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── admin.css
│   └── js/
│       ├── carousel.js
│       └── admin.js
├── includes/
│   ├── class-ngsi-carousel-shortcode.php
│   ├── class-ngsi-carousel-admin.php
│   └── class-ngsi-carousel-updater.php
├── .gitignore
├── CHANGELOG.md
├── LICENSE.txt
├── README.md
├── ngsi-woo-carousel.php
└── README-SHORTCODE.md
```

## ✅ Checklist final

- [ ] Repositorio creado en GitHub
- [ ] Código subido al repositorio
- [ ] URLs actualizadas con tu usuario GitHub
- [ ] Primer release v1.0.0 creado
- [ ] GitHub Pages configurado (opcional)
- [ ] Sistema de actualizaciones probado

## 🎯 Próximos pasos

1. **Probar el plugin** en un sitio WordPress
2. **Crear documentación adicional** si es necesario
3. **Configurar CI/CD** con GitHub Actions
4. **Promocionar el plugin** en comunidades WordPress

---

**¡Tu plugin está listo para GitHub!** 🚀
