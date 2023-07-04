window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container
  window.ui = SwaggerUIBundle({
    urls: [
        {name: '运营模块', url: '/swagger/admin.yaml'},
        {name: '认证模块', url: '/swagger/passport.yaml'},
        {name: '门户模块', url: '/swagger/portal.yaml'},
        {name: '卖家模块', url: '/swagger/seller.yaml'},
        {name: '供应商模块', url: '/swagger/supplier.yaml'},
        {name: '买家模块', url: '/swagger/user.yaml'}
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
