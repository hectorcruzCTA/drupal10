// Asegurar que el script se ejecuta cuando el DOM está cargado
document.addEventListener("DOMContentLoaded", function () {
    let menuToggle = document.querySelector(".menu-toggle");
    let menuContent = document.getElementById("menu-content");

    if (menuToggle && menuContent) {
        menuContent.style.display = "none"; // Ocultar menú al cargar

        menuToggle.addEventListener("click", function () {
            menuContent.style.display = (menuContent.style.display === "none") ? "block" : "none";
        });
    }

    // Habilitar hover en submenús con Vanilla JS
    let menuItems = document.querySelectorAll("#udg_menu_principal_ul li");
    menuItems.forEach(item => {
        let subMenu = item.querySelector("ul");

        if (subMenu) {
            item.addEventListener("mouseenter", function () {
                subMenu.style.display = "block";
            });

            item.addEventListener("mouseleave", function () {
                subMenu.style.display = "none";
            });
        }
    });
});

// Implementación correcta de `once()` en Drupal 10+
(function ($, Drupal, once) {
    Drupal.behaviors.menuHoverFix = {
        attach: function (context, settings) {
            console.log("✅ Drupal.behaviors.menuHoverFix.attach() ejecutándose");

            $("#menu-principal li", context).each(function () {
                let $submenu = $(this).find("ul").first();

                if ($submenu.length) {
                    console.log("🔹 Submenú detectado:", $submenu);

                    once("menu-hover", this, function () {
                        $(this).on("mouseenter", function () {
                            console.log("🔹 Mouse over en:", this);
                            $submenu.stop(true, true).slideDown(200);
                        }).on("mouseleave", function () {
                            console.log("🔹 Mouse out en:", this);
                            $submenu.stop(true, true).slideUp(200);
                        });
                    });
                } else {
                    console.log("⚠️ No hay submenú en:", this);
                }
            });
        }
    };
})(jQuery, Drupal, once);
