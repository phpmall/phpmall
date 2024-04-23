window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container
  window.ui = SwaggerUIBundle({
    urls: [
      {name: '认证接口',  url: "./auth.json"},
      {name: '公共接口',  url: "./common.json"},
      {name: '运营接口',  url: "./manager.json"},
      {name: '买家接口',  url: "./member.json"},
      {name: '卖家接口',  url: "./seller.json"}
    ],
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  });

  //</editor-fold>
};
