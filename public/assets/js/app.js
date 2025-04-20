
(function ($) {
    "use strict";

    // Restaurar el tamaño guardado del sidebar desde localStorage
    const savedSidebarSize = localStorage.getItem("sidebarSize");
    if (savedSidebarSize) {
        document.body.setAttribute("data-sidebar-size", savedSidebarSize);
    }

    $(document).ready(function () {

        const body = document.body;

        // 🟢 Restaurar tema desde localStorage
        const savedTheme = localStorage.getItem("macasa-theme") || "light";
        body.setAttribute("data-bs-theme", savedTheme);
        body.setAttribute("data-topbar", savedTheme);
        body.setAttribute("data-sidebar", savedTheme);

        // 🟢 Restaurar tamaño del sidebar
        const savedSidebarSize = localStorage.getItem("sidebarSize");
        if (savedSidebarSize) {
            document.body.setAttribute("data-sidebar-size", savedSidebarSize);
        }

        const iconDark = document.getElementById("theme-icon-dark");
        const iconLight = document.getElementById("theme-icon-light");

        // Mostrar el icono correcto al cargar
        function syncThemeIcons(theme) {
            // Solo mostrar/ocultar, sin animar
            iconDark.style.display = (theme === "dark") ? "none" : "inline-block";
            iconLight.style.display = (theme === "dark") ? "inline-block" : "none";
        }
        


        syncThemeIcons(savedTheme); // al cargar



        // Sidebar menú
        $("#side-menu").metisMenu();

        // Toggle hamburguesa
        $("#vertical-menu-btn").on("click", function (e) {
            e.preventDefault();

            $("body").toggleClass("sidebar-enable");

            if ($(window).width() >= 992) {
                const currentSize = document.body.getAttribute("data-sidebar-size") || "lg";
                const newSize = currentSize === "lg" ? "sm" : "lg";
                document.body.setAttribute("data-sidebar-size", newSize);
                localStorage.setItem("sidebarSize", newSize);
            }
        });

        // Modo oscuro / claro
        $("#mode-setting-btn").on("click", function () {
            const isDark = body.getAttribute("data-bs-theme") === "dark";
            const newTheme = isDark ? "light" : "dark";
        
            body.setAttribute("data-bs-theme", newTheme);
            body.setAttribute("data-topbar", newTheme);
            body.setAttribute("data-sidebar", newTheme);
            localStorage.setItem("macasa-theme", newTheme);
        
            syncThemeIcons(newTheme); // ← esta ya no tiene animaciones internas
        
            // 🔁 Solo aquí se anima el botón
            const btn = document.getElementById("mode-setting-btn");
            btn.classList.add("animate-theme");
            setTimeout(() => {
                btn.classList.remove("animate-theme");
            }, 500);
        });
        




        function toggleThemeSwitch(id) {
            const el = document.getElementById(id);
            if (el) el.checked = true;
        }

        // Right bar toggle
        $(".right-bar-toggle").on("click", function () {
            $("body").toggleClass("right-bar-enabled");
        });

        $(document).on("click", function (e) {
            if (!$(e.target).closest(".right-bar, .right-bar-toggle").length) {
                $("body").removeClass("right-bar-enabled");
            }
        });

        // Feather icons
        if (typeof feather !== "undefined") {
            feather.replace();
        }

        // Preloader
        $(window).on("load", function () {
            $("#status").fadeOut();
            $("#preloader").delay(350).fadeOut("slow");
        });
    });
})(jQuery);
