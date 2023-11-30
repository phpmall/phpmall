{layout name="layout/app" /}

login page.
<form class="layui-form" lay-filter="loginForm" action="" method="post">
    {:token_field()}
<div class="layui-form-item">
    <label class="layui-form-label">输入框</label>
    <div class="layui-input-block">
        <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">输入框</label>
    <div class="layui-input-block">
        <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">输入框</label>
    <div class="layui-input-inline">
        <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
        <img src="{:url('/captcha')}" alt="captcha" />
    </div>
</div>

 <div class="layui-form-item">
  <div class="layui-input-block">
    <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
  </div>
</div>

</form>
