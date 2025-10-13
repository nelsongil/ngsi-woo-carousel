# 🔄 Guía para Probar el Sistema de Actualizaciones

## 📋 Resumen de los Cambios Realizados

### Problemas Corregidos

1. ✅ **Plugin Slug Incorrecto**: 
   - Antes usaba `plugin_basename(__FILE__)` que apuntaba al archivo del updater
   - Ahora usa la constante `NGSI_WOO_CAROUSEL_BASENAME` del archivo principal

2. ✅ **Versión Hardcoded**: 
   - Antes la versión estaba duplicada en el updater
   - Ahora usa la constante `NGSI_WOO_CAROUSEL_VERSION` del archivo principal

3. ✅ **URLs de GitHub**: 
   - Reemplazadas todas las referencias `[TU-USUARIO-GITHUB]` por `nelsongil`

4. ✅ **Constantes Definidas**: 
   - Agregadas constantes para facilitar el acceso a datos del plugin
   - `NGSI_WOO_CAROUSEL_VERSION`
   - `NGSI_WOO_CAROUSEL_FILE`
   - `NGSI_WOO_CAROUSEL_PATH`
   - `NGSI_WOO_CAROUSEL_URL`
   - `NGSI_WOO_CAROUSEL_BASENAME`

5. ✅ **Sistema de Logs**: 
   - Agregados logs detallados para facilitar el debugging
   - Los logs solo se activan si `WP_DEBUG` está habilitado

6. ✅ **Mejora en Comparación de Versiones**: 
   - Ahora limpia el prefijo 'v' de las versiones de GitHub
   - Comparación más robusta de versiones

7. ✅ **Mejor Manejo de Assets**: 
   - Busca archivos .zip en los assets del release
   - Fallback a zipball_url si no hay assets

## 🧪 Cómo Probar el Sistema de Actualizaciones

### Opción 1: Verificación Forzada (Método Rápido)

1. **Habilitar WP_DEBUG** en `wp-config.php`:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );
   ```

2. **Forzar verificación de actualizaciones**:
   - Ve a: `tu-sitio.com/wp-admin/plugins.php?ngsi_force_update_check=1`
   - Esto limpiará el caché y forzará una verificación inmediata

3. **Revisar los logs**:
   - Abre `/wp-content/debug.log`
   - Busca líneas que comienzan con `NGSI Carousel Updater:`
   - Verás información detallada del proceso

### Opción 2: Crear un Release de Prueba en GitHub

1. **Subir el código a GitHub**:
   ```bash
   cd "NGSI Woo Carousel"
   git add .
   git commit -m "Fix: Sistema de actualizaciones corregido"
   git push origin master
   ```

2. **Crear un Release de Prueba**:
   - Ve a: `https://github.com/nelsongil/ngsi-woo-carousel/releases/new`
   - Tag version: `v1.0.1` (o cualquier versión superior a 1.0.0)
   - Release title: `Test Release v1.0.1`
   - Descripción: "Release de prueba para verificar el sistema de actualizaciones"
   - Opcional: Sube un archivo .zip del plugin
   - Haz clic en "Publish release"

3. **Verificar en WordPress**:
   - Ve a WordPress Admin → Plugins
   - Debería aparecer una notificación de actualización disponible
   - Si no aparece, usa el método de verificación forzada

### Opción 3: Verificación Manual del Código

1. **Verificar las constantes**:
   ```php
   // Agregar este código temporalmente en ngsi-woo-carousel.php
   add_action('admin_notices', function() {
       if (current_user_can('update_plugins')) {
           echo '<div class="notice notice-info">';
           echo '<p><strong>NGSI Debug Info:</strong></p>';
           echo '<p>Version: ' . NGSI_WOO_CAROUSEL_VERSION . '</p>';
           echo '<p>Basename: ' . NGSI_WOO_CAROUSEL_BASENAME . '</p>';
           echo '</div>';
       }
   });
   ```

2. **Verificar la conexión con GitHub**:
   ```php
   // Probar la API de GitHub manualmente
   $response = wp_remote_get('https://api.github.com/repos/nelsongil/ngsi-woo-carousel/releases/latest');
   if (!is_wp_error($response)) {
       $data = json_decode(wp_remote_retrieve_body($response));
       echo '<pre>';
       print_r($data);
       echo '</pre>';
   }
   ```

## 🔍 Interpretando los Logs

### Logs Normales (Sin actualizaciones disponibles)

```
NGSI Carousel Updater: Updater iniciado
NGSI Carousel Updater: Plugin slug: NGSI Woo Carousel/ngsi-woo-carousel.php
NGSI Carousel Updater: Versión actual: 1.0.0
NGSI Carousel Updater: Comprobando actualizaciones...
NGSI Carousel Updater: Solicitando info de GitHub: https://api.github.com/repos/nelsongil/ngsi-woo-carousel/releases/latest
NGSI Carousel Updater: Código de respuesta: 200
NGSI Carousel Updater: Respuesta guardada en caché
NGSI Carousel Updater: Última versión en GitHub: v1.0.0
NGSI Carousel Updater: Comparando versiones: 1.0.0 vs 1.0.0
NGSI Carousel Updater: Plugin actualizado - no hay nuevas versiones
```

### Logs con Actualización Disponible

```
NGSI Carousel Updater: Updater iniciado
NGSI Carousel Updater: Plugin slug: NGSI Woo Carousel/ngsi-woo-carousel.php
NGSI Carousel Updater: Versión actual: 1.0.0
NGSI Carousel Updater: Comprobando actualizaciones...
NGSI Carousel Updater: Solicitando info de GitHub: https://api.github.com/repos/nelsongil/ngsi-woo-carousel/releases/latest
NGSI Carousel Updater: Código de respuesta: 200
NGSI Carousel Updater: Respuesta guardada en caché
NGSI Carousel Updater: Última versión en GitHub: v1.0.1
NGSI Carousel Updater: Comparando versiones: 1.0.0 vs 1.0.1
NGSI Carousel Updater: ¡Nueva versión disponible! 1.0.1
NGSI Carousel Updater: Usando asset: ngsi-woo-carousel-v1.0.1.zip
NGSI Carousel Updater: URL de descarga: https://github.com/nelsongil/ngsi-woo-carousel/releases/download/v1.0.1/ngsi-woo-carousel-v1.0.1.zip
```

### Logs con Errores

```
NGSI Carousel Updater: Error en request: cURL error 28: Connection timed out
```
o
```
NGSI Carousel Updater: Error: código de respuesta no es 200
```
o
```
NGSI Carousel Updater: No se pudo obtener información de GitHub
```

## 🐛 Solución de Problemas

### El plugin no detecta actualizaciones

1. **Verificar que el release existe en GitHub**:
   - Ve a: `https://github.com/nelsongil/ngsi-woo-carousel/releases`
   - Debe haber al menos un release publicado

2. **Limpiar cachés**:
   ```
   tu-sitio.com/wp-admin/plugins.php?ngsi_force_update_check=1
   ```

3. **Verificar el nombre del directorio del plugin**:
   - Debe ser exactamente: `NGSI Woo Carousel`
   - Si es diferente, actualiza el código en consecuencia

4. **Revisar los logs**:
   - Habilita `WP_DEBUG` y revisa `/wp-content/debug.log`

### Error 404 en la API de GitHub

- Verifica que el usuario y repositorio sean correctos
- El repositorio debe ser público
- Debe tener al menos un release publicado

### El plugin se descarga pero no se instala

- Asegúrate de subir un archivo .zip como asset en el release
- El .zip debe contener la carpeta del plugin con todos los archivos
- Estructura correcta:
  ```
  ngsi-woo-carousel.zip
  └── NGSI Woo Carousel/
      ├── ngsi-woo-carousel.php
      ├── includes/
      ├── assets/
      └── ...
  ```

## ✅ Checklist de Verificación

- [ ] Constantes definidas en el archivo principal
- [ ] Plugin slug correcto (incluye nombre del directorio)
- [ ] Versión sincronizada entre archivo principal y updater
- [ ] URLs de GitHub correctas
- [ ] Usuario de GitHub correcto
- [ ] Repositorio es público
- [ ] Al menos un release publicado en GitHub
- [ ] WP_DEBUG habilitado para ver logs
- [ ] Cache limpiado después de cambios

## 📝 Notas Importantes

1. **Caché de 12 horas**: Por defecto, WordPress cachea las comprobaciones de actualización por 12 horas. Usa el método de verificación forzada para pruebas.

2. **Formato de versiones**: 
   - En el archivo principal: `1.0.0`
   - En GitHub tags: `v1.0.0` o `1.0.0` (ambos funcionan)

3. **Assets vs Zipball**:
   - Si subes un .zip como asset, se usará ese
   - Si no, se usará el zipball_url generado automáticamente por GitHub

4. **Nombre del directorio**: 
   - El nombre del directorio del plugin es crítico
   - Debe coincidir con el basename usado en el código
   - Si cambias el nombre del directorio, actualiza el código

## 🚀 Próximos Pasos

1. Subir el código a GitHub
2. Crear el primer release (v1.0.0)
3. Probar el sistema con un release de prueba (v1.0.1)
4. Una vez confirmado que funciona, puedes desactivar WP_DEBUG

---

**© 2024 Nelson Gil Olguín. Todos los derechos reservados.**

