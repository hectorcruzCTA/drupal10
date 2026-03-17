# CUAAD — Sitio Web Drupal 10

**Centro Universitario de Arte, Arquitectura y Diseño — Universidad de Guadalajara**

Sitio oficial del CUAAD desarrollado en Drupal 10 con tema Bootstrap 3 personalizado (`drudg8b3`).

---

## 🧰 Requisitos previos

| Herramienta | Versión mínima |
|-------------|---------------|
| PHP | 8.2+ (con extensiones: `mbstring`, `pdo_mysql`, `xml`, `gd`) |
| MySQL / MariaDB | 8.0+ |
| Composer | 2.x |
| Git | 2.x |

---

## 🚀 Instalación local

### 1. Clonar el repositorio

```bash
git clone git@github.com:hectorcruzCTA/drupal10.git drupal100
cd drupal100
```

### 2. Instalar dependencias PHP

```bash
composer install
```

> Esto descargará Drupal core, módulos contrib, temas contrib y otras librerías.
> Puede tardar varios minutos la primera vez.

### 3. Configurar settings.php

```bash
cp sites/default/settings.php.example sites/default/settings.php
```

Edita `sites/default/settings.php` y configura:
- Credenciales de tu base de datos local
- Un `hash_salt` único (ejecuta: `vendor/bin/drush php-eval 'echo Drupal\Component\Utility\Crypt::randomBytesBase64(55);'`)
- Tu `trusted_host_patterns` (ej. `localhost`)

### 4. Crear la base de datos

```bash
mysql -u root -p -e "CREATE DATABASE drupal100 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root -p -e "GRANT ALL ON drupal100.* TO 'drupal_user'@'localhost' IDENTIFIED BY 'tu_password';"
```

### 5. Importar el dump de base de datos

Solicita al equipo el dump actualizado de producción e impórtalo:

```bash
mysql -u drupal_user -p drupal100 < dump_produccion_YYYYMMDD.sql
```

### 6. Crear directorios necesarios

```bash
mkdir -p sites/default/files sites/default/files/config_sync private
chmod -R 775 sites/default/files
chmod -R 775 private
```

### 7. Limpiar caché y verificar

```bash
vendor/bin/drush cr
vendor/bin/drush status
```

Abre `http://localhost/drupal100` en el navegador.

---

## 📁 Estructura del proyecto

```
drupal100/
├── core/                     # Drupal core (gestionado por Composer, NO editar)
├── modules/
│   ├── contrib/              # Módulos de la comunidad (Composer, NO editar)
│   └── custom/               # Módulos propios del CUAAD ← editar aquí
│       ├── udg_liston/       # Bloques de la homepage ("listones")
│       ├── d7_migration/     # Migración desde Drupal 7
│       └── migrate_noticias/ # Migración de noticias D7 → D10
├── themes/
│   ├── contrib/              # Temas contrib (Bootstrap 3, Composer, NO editar)
│   └── drudg8b3/             # Tema activo del CUAAD (hijo de Bootstrap 3) ← editar aquí
│       ├── css/              # Estilos personalizados
│       ├── js/               # Scripts personalizados
│       └── templates/        # Templates Twig
├── sites/default/
│   ├── settings.php          # ⚠️ NO en git — configurar desde settings.php.example
│   ├── settings.php.example  # Plantilla de configuración para devs
│   └── files/                # Archivos subidos (NO en git)
├── composer.json             # Dependencias del proyecto
└── vendor/                   # Librerías PHP (NO en git, generado por Composer)
```

---

## 🔄 Flujo de trabajo Git

Usamos la rama `main` como base. Para cada tarea:

```bash
# 1. Actualizar tu rama local
git pull origin main

# 2. Crear rama para tu feature o fix
git checkout -b feat/nombre-descriptivo
# o: git checkout -b fix/nombre-del-bug

# 3. Desarrollar y hacer commits descriptivos
git add modules/custom/mi_modulo/
git commit -m "feat: descripción del cambio"

# 4. Limpiar caché después de cambios PHP/Twig
vendor/bin/drush cr

# 5. Subir tu rama y crear Pull Request
git push origin feat/nombre-descriptivo
```

**Tipos de commit:** `feat:`, `fix:`, `style:`, `docs:`, `refactor:`

---

## ⚙️ Comandos útiles Drush

```bash
vendor/bin/drush cr                    # Limpiar caché
vendor/bin/drush cex -y                # Exportar configuración
vendor/bin/drush cim -y                # Importar configuración
vendor/bin/drush updb -y               # Actualizar base de datos
vendor/bin/drush sql:dump > dump.sql   # Exportar BD

# Migraciones (solo cuando sea necesario)
vendor/bin/drush migrate:status
vendor/bin/drush migrate:import [id]
```

---

## 🏗️ Tipos de contenido principales

| Tipo | Machine name | Uso |
|------|-------------|-----|
| Noticia | `noticia` | Noticias institucionales |
| Página | `page` | Páginas estáticas |
| Directorio | `directorio` | Directorio de personal |
| Evento | `evento_de_agenda` | Agenda de eventos |
| Galería | `galeria_de_imagenes` | Galerías fotográficas |

---
