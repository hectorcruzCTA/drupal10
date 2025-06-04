(function ($) {
    $(document).ready(function () {
        // Crear el contenedor principal del menú
        $('#navbar').prepend('<div id="udg_menu_principal"></div>');
        $('#udg_menu_principal').prepend('<div id="udg_menu_principal_contenedor" class="container"></div>');
        $('#udg_menu_principal_contenedor').prepend('<ul id="udg_menu_principal_ul"></ul>');

        // Agregar botón toggle para el menú en móviles
        $('#udg_menu_principal_ul').before('<button id="menu-toggle" class="btn btn-primary">Menú Principal UDG</button>');

        // Función para agregar elementos al menú
        function agregarElemento(id, texto, url, parent) {
            let elemento = `<li id="${id}"><a href="${url}">${texto}</a></li>`;
            $(parent).append(elemento);
        }

        function agregarSubmenu(id, texto, parent) {
            let submenu = `<li id="${id}">${texto}<ul id="${id}_submenu"></ul></li>`;
            $(parent).append(submenu);
            return `#${id}_submenu`; // Retorna el selector del nuevo submenú
        }

        // Red Universitaria
        let redUniversitaria = agregarSubmenu("udg_menu_red_universidad_link", "Red universitaria", "#udg_menu_principal_ul");
        agregarElemento("universidad", "Universidad de Guadalajara", "https://www.udg.mx", redUniversitaria);
        agregarElemento("directorio", "Directorio Oficial", "https://www.udg.mx/directorio", redUniversitaria);

        let tematicos = agregarSubmenu("tematicos", "Centros Universitarios Temáticos", redUniversitaria);
        agregarElemento("cuaad", "CUAAD - Arte, Arquitectura y Diseño", "http://www.cuaad.udg.mx", tematicos);
        agregarElemento("cucba", "CUCBA - Ciencias Biológicas y Agropecuarias", "http://www.cucba.udg.mx", tematicos);
        agregarElemento("cucea", "CUCEA - Ciencias Económico Administrativas", "http://www.cucea.udg.mx", tematicos);
        agregarElemento("cucei", "CUCEI - Ciencias Exactas e Ingenierías", "http://www.cucei.udg.mx", tematicos);
        agregarElemento("cucs", "CUCS - Ciencias de la Salud", "http://www.cucs.udg.mx", tematicos);
        agregarElemento("cucsh", "CUCSH - Ciencias Sociales y Humanidades", "http://www.cucsh.udg.mx", tematicos);

        // Centros Universitarios Regionales
        let regionales = agregarSubmenu("regionales", "Centros Universitarios Regionales", redUniversitaria);
        agregarElemento("cualtos", "CUALTOS - Tepatitlán de Morelos, Jalisco", "http://www.cualtos.udg.mx", regionales);
        agregarElemento("cucienega", "CUCIÉNEGA - Ocotlán, Atotonilco, La Barca, Jalisco", "http://cuci.udg.mx", regionales);
        agregarElemento("cucosta", "CUCOSTA - Puerto Vallarta, Tomatlán, Jalisco", "http://www.cuc.udg.mx", regionales);
        agregarElemento("cugdl", "CUGDL - Centro Universitario de Guadalajara", "https://cugdl.udg.mx/", regionales);
        agregarElemento("cucsur", "CUCSUR - Autlán de Navarro, Cihuatlán, Jalisco", "http://www.cucsur.udg.mx", regionales);

        // Administración y Gobierno
        let administracion = agregarSubmenu("udg_menu_administracion_general_link", "Administración y Gobierno", "#udg_menu_principal_ul");
        agregarElemento("hcgu", "Consejo General Universitario", "http://www.hcgu.udg.mx", administracion);
        agregarElemento("rectoria", "Rectoría General", "http://www.rectoria.udg.mx", administracion);
        agregarElemento("vicerrectoria", "Vicerrectoría Ejecutiva", "http://www.vicerrectoria.udg.mx/", administracion);
        agregarElemento("secretaria", "Secretaría General", "http://www.secgral.udg.mx/", administracion);

        // Otros sitios UdeG
        let otrosSitios = agregarSubmenu("udg_menu_otros_sitios_link", "Otros sitios UdeG", "#udg_menu_principal_ul");
        agregarElemento("bibliotecas", "Bibliotecas", "https://www.udg.mx/servicios/bibliotecas", otrosSitios);
        agregarElemento("cartelera", "Cartelera UDG", "https://comsoc.udg.mx/cartelera-udeg", otrosSitios);
        agregarElemento("cultura", "Cultura UDG", "http://www.cultura.udg.mx/", otrosSitios);

        // Evento para el botón de menú en móviles
        $('#menu-toggle').click(function () {
            $('#udg_menu_principal_ul').toggleClass('show');
        });

        // Evento para mostrar submenús al hacer hover
        $('#udg_menu_principal_ul li').hover(
            function () {
                $(this).find('ul').first().stop(true, true).fadeIn(200);
            },
            function () {
                $(this).find('ul').first().stop(true, true).fadeOut(200);
            }
        );
    });
})(jQuery);

