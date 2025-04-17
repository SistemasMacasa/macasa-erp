
(function ($) {
    "use strict";

    // Restaurar el tamaño guardado del sidebar desde localStorage
    const savedSidebarSize = localStorage.getItem("sidebarSize");
    if (savedSidebarSize) {
        document.body.setAttribute("data-sidebar-size", savedSidebarSize);
    }

    $(document).ready(function () {
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
        const body = document.body;
        $("#mode-setting-btn").on("click", function () {
            const isDark = body.getAttribute("data-bs-theme") === "dark";  // ✅ ESTA LÍNEA FALTABA
            const newTheme = isDark ? "light" : "dark";
        
            body.setAttribute("data-bs-theme", newTheme);
            body.setAttribute("data-topbar", newTheme);
            body.setAttribute("data-sidebar", newTheme);
            localStorage.setItem("macasa-theme", newTheme);
        
            toggleThemeSwitch("layout-mode-" + newTheme);
            toggleThemeSwitch("sidebar-color-" + newTheme);
            toggleThemeSwitch("topbar-color-" + newTheme);
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
