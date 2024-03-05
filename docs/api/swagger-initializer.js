window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container
  window.ui = SwaggerUIBundle({
    urls: [
        {name: '认证模块', url: './auth.json'},
        {name: '运营模块', url: './manager.json'},
        {name: '卖家模块', url: './seller.json'},
        {name: '买家模块', url: './user.json'},
        {name: '门户模块', url: './portal.json'}
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
