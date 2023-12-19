window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container
  window.ui = SwaggerUIBundle({
    urls: [
        {name: '认证模块', url: '/api/Auth.json'},
        {name: '基础模块', url: '/api/Common.json'},
        {name: '运营模块', url: '/api/Manager.json'},
        {name: '卖家模块', url: '/api/Seller.json'},
        {name: '供应商模块', url: '/api/Supplier.json'},
        {name: '买家模块', url: '/api/User.json'},
        {name: '门户模块', url: '/api/Portal.json'}
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
