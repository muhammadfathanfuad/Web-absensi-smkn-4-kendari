/**
* Theme: Taplox- Responsive Bootstrap 5 Admin Dashboard
* Author: Stackbros
* Module/App: Theme Config Js
*/


(function () {

     var savedConfig = sessionStorage.getItem("__TAPLOX_CONFIG__");

     var html = document.getElementsByTagName("html")[0];

     var defaultConfig = {
          theme: "light",

          topbar: {
               color: "light",
          },

          menu: {
               size: "default",
               color: "light",
          },
     };

     var html = document.getElementsByTagName("html")[0];
        var config = Object.assign(JSON.parse(JSON.stringify(defaultConfig)), {});


     config.theme = html.getAttribute('data-bs-theme') || defaultConfig.theme;
     config.topbar.color = html.getAttribute('data-topbar-color') || defaultConfig.topbar.color;
     config.menu.color = html.getAttribute('data-sidebar-color') || defaultConfig.menu.color;
     config.menu.size = html.getAttribute('data-sidebar-size') || defaultConfig.menu.size;

     window.defaultConfig = JSON.parse(JSON.stringify(config));

     if (savedConfig !== null) {
          config = JSON.parse(savedConfig);
     }

     window.config = config;

     if (config) {
          html.setAttribute("data-bs-theme", config.theme);
          html.setAttribute("data-topbar-color", config.topbar.color);
          html.setAttribute("data-sidebar-color", config.menu.color);

          if (window.innerWidth <= 1140) {
               html.setAttribute("data-sidebar-size", "hidden");
          } else {
               html.setAttribute("data-sidebar-size", config.menu.size);
          }
     }
})();
