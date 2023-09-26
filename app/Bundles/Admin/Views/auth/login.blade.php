@extends('admin::layouts.auth')

@section('content')
<form class="layui-form" action="javascript:void(0);">
    <div class="layui-form-item">
        <img class="logo" src="{:asset('assets/admin/images/logo.png')}"/>
        <div class="title">CMS Admin</div>
        <div class="desc">
            企业级门户网站管理系统
        </div>
    </div>
    <div class="layui-form-item">
        <input type="text" name="username" placeholder="请输入登录用户名" lay-verify="required" class="layui-input"/>
    </div>
    <div class="layui-form-item">
        <input type="password" name="password" placeholder="请输入登录密码" lay-verify="required" class="layui-input"/>
    </div>
    <div class="layui-form-item">
        <input type="text" name="captcha" placeholder="请输入图片验证码" lay-verify="required"
               class="code layui-input layui-input-inline"/>
        <img src="{:route('captcha')}" class="codeImage" width="124"/>
    </div>
    <div class="layui-form-item">
        <a href="{:route('console/auth/forgot')}" style="float: right; margin-top: 10px;">忘记密码</a>
        <input type="checkbox" name="remember" title="记住密码" lay-skin="primary" checked>
    </div>
    <div class="layui-form-item">
        <button type="button" class="pear-btn pear-btn-success login" lay-submit lay-filter="login">
            登 录
        </button>
    </div>
</form>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/focite/layui-admin@master/component/layui/layui.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/focite/layui-admin@master/component/pear/pear.js"></script>
<script type="text/javascript">
    layui.use(['encrypt', 'form', 'popup'], function () {
        var $ = layui.jquery,
            encrypt = layui.encrypt,
            form = layui.form,
            popup = layui.popup;

        $.ajaxSetup({
            contentType: 'application/json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        var loadCaptcha = function () {
            var codeImg = $('.codeImage');
            var captcha = codeImg.attr('src').split('?');
            codeImg.attr('src', captcha[0] + '?r=' + Math.random());
        }

        $('.codeImage').on('click', loadCaptcha)

        form.on('submit(login)', function (data) {
            data.field.password = encrypt.md5(data.field.password)
            $.post("{:route('console/auth/login')}", JSON.stringify(data.field), function (res) {
                if (res.code !== 0) {
                    popup.failure(res.message);
                    loadCaptcha();
                    return false;
                }
                popup.success("登录成功", function () {
                    location.href = "{$callback}"
                });
            }, 'json')
        });
    })
</script>
@endsection
