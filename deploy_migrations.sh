#!/bin/bash
# deploy_migrations.sh

# 1) Push de cambios a GitHub (ya está hecho anteriormente por Codex)
# 2) Conectar vía SSH y correr Drush en servidor

  cd /var/www/html/drupal100
  drush cr
  # Importar cada migración de menú por separado en orden alfabético:
  drush migrate:import d7_menu_links_main-menu
  drush migrate:import d7_menu_links_management
  drush migrate:import d7_menu_links_menu-alumnos
  drush migrate:import d7_menu_links_menu-arpafil
  drush migrate:import d7_menu_links_menu-artes-esc-nicas-para-exp-te
  drush migrate:import d7_menu_links_menu-articulos-del-cuaad-en-gace
  drush migrate:import d7_menu_links_menu-asinea-100
  drush migrate:import d7_menu_links_menu-comunidad-cuaad
  drush migrate:import d7_menu_links_menu-convocatorias
  drush migrate:import d7_menu_links_menu-cta
  drush migrate:import d7_menu_links_menu-cuaad-protegido
  drush migrate:import d7_menu_links_menu-cuaadarte
  drush migrate:import d7_menu_links_menu-dihs
  drush migrate:import d7_menu_links_menu-dis
  drush migrate:import d7_menu_links_menu-dise-o-arte-y-tecnolog-as-l
  drush migrate:import d7_menu_links_menu-dise-o-para-la-comunicaci-n
  drush migrate:import d7_menu_links_menu-doctorado-ciudad-territorio
  drush migrate:import d7_menu_links_menu-doctorado-interinstituciona
  drush migrate:import d7_menu_links_menu-expresion-plastica
  drush migrate:import d7_menu_links_menu-index
  drush migrate:import d7_menu_links_menu-laboratorios
  drush migrate:import d7_menu_links_menu-lempro-servicios
  drush migrate:import d7_menu_links_menu-licenciatura-en-artes-visua
  drush migrate:import d7_menu_links_menu-licenciatura-en-dise-o-de-i
  drush migrate:import d7_menu_links_menu-licenciatura-en-dise-o-de-m
  drush migrate:import d7_menu_links_menu-licenciatura-en-dise-o-indu
  drush migrate:import d7_menu_links_menu-licenciatura-en-musica
  drush migrate:import d7_menu_links_menu-ma-diseno-info-com-digital
  drush migrate:import d7_menu_links_menu-maestr-a-en-ciencias-de-la-
  drush migrate:import d7_menu_links_menu-maestr-a-en-dise-o-e-innova
  drush migrate:import d7_menu_links_menu-maestr-a-en-lexicograf-a-y-
  drush migrate:import d7_menu_links_menu-maestr-a-en-m-sica-tradicio
  drush migrate:import d7_menu_links_menu-maestr-a-en-urbanismo-y-ter
  drush migrate:import d7_menu_links_menu-maestria-d-y-d-de-n-prod
  drush migrate:import d7_menu_links_menu-maestria-estudios-cinemato
  drush migrate:import d7_menu_links_menu-men-
  drush migrate:import d7_menu_links_menu-men-acad-micos
  drush migrate:import d7_menu_links_menu-men-acerca-de-cuaad
  drush migrate:import d7_menu_links_menu-men-aspirantes
  drush migrate:import d7_menu_links_menu-men-becas-y-apoyos-para-est
  drush migrate:import d7_menu_links_menu-men-bibliotecas
  drush migrate:import d7_menu_links_menu-men-centro-de-auto-acceso-c
  drush migrate:import d7_menu_links_menu-men-centros-e-institutos-de
  drush migrate:import d7_menu_links_menu-men-control-escolar
  drush migrate:import d7_menu_links_menu-men-cuerpo-acad-mico
  drush migrate:import d7_menu_links_menu-men-cursos-en-l-nea
  drush migrate:import d7_menu_links_menu-men-de-artes-esc-nicas-para
  drush migrate:import d7_menu_links_menu-men-de-licenciatura-arquite
  drush migrate:import d7_menu_links_menu-men-directorio
  drush migrate:import d7_menu_links_menu-men-doctorados
  drush migrate:import d7_menu_links_menu-men-extensi-n-y-difusi-n
  drush migrate:import d7_menu_links_menu-men-institutos
  drush migrate:import d7_menu_links_menu-men-intercambio-acad-mico
  drush migrate:import d7_menu_links_menu-men-investigaci-n
  drush migrate:import d7_menu_links_menu-men-licenciatura-artes-visu
  drush migrate:import d7_menu_links_menu-men-licenciatura-en-urban-s
  drush migrate:import d7_menu_links_menu-men-oferta-acad-mica
  drush migrate:import d7_menu_links_menu-men-practicasprofesionales
  drush migrate:import d7_menu_links_menu-men-servicio-social
  drush migrate:import d7_menu_links_menu-men-servicios
  drush migrate:import d7_menu_links_menu-men-tutor-a-acad-mica
  drush migrate:import d7_menu_links_menu-menu-agenda
  drush migrate:import d7_menu_links_menu-menu-centros-de-inv
  drush migrate:import d7_menu_links_menu-menu-gestion-desarrollo-cul
  drush migrate:import d7_menu_links_menu-menu-infraestructura-tecnol
  drush migrate:import d7_menu_links_menu-menu-m-etnomusicologia
  drush migrate:import d7_menu_links_menu-menu-maestria-e-y-e-a
  drush migrate:import d7_menu_links_menu-menu-maestria-en-ergonomia
  drush migrate:import d7_menu_links_menu-menu-maestria-p-ex-gr-p-a-u
  drush migrate:import d7_menu_links_menu-menu-recorridos-virtuales
  drush migrate:import d7_menu_links_menu-mil-no-vigente
  drush migrate:import d7_menu_links_menu-nivelaciones
  drush migrate:import d7_menu_links_menu-oferta-acad-mica
  drush migrate:import d7_menu_links_menu-oferta-acad-mica-doctorado
  drush migrate:import d7_menu_links_menu-oferta-acad-mica-maestrias
  drush migrate:import d7_menu_links_menu-planeacion
  drush migrate:import d7_menu_links_menu-programas-del-departamento-
  drush migrate:import d7_menu_links_menu-resicuaad
  drush migrate:import d7_menu_links_menu-segunda-galeria-fotografica
  drush migrate:import d7_menu_links_navigation
  drush migrate:import d7_menu_links_shortcut-set-1
  drush migrate:import d7_menu_links_shortcut-set-2
  drush migrate:import d7_menu_links_user-menu
  drush cr

