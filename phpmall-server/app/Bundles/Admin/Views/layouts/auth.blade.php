<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>管理平台 - Powered by focms</title>
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/focite/layui-admin@master/component/pear/css/pear.css"/>
<link rel="stylesheet" href="{{ asset('assets/admin/css/login.css') }}" />
<script type="text/javascript" src="{{ asset('assets/vue/vue.min.js') }}"></script>
<script type="text/javascript">
if (window.parent != window) {
  window.top.location.href = location.href;
}
</script>
</head>

<body>
@yield('content')
</body>
</html>
