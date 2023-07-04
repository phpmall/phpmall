@extends('auth::layouts.app')

@section('content')
    <div class="switch">
        <a href="javascript:void(0);">
            <img src="{{ asset('static/passport/img/switch-qrcode.png') }}"
                 class="switch-qrcode" alt="{{ config('app.name') }}">
            <img src="{{ asset('static/passport/img/switch-mobile.png') }}"
                 class="switch-mobile layui-hide" alt="{{ config('app.name') }}">
        </a>
    </div>
    <form class="layui-form">
        <div class="login-container">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li class="layui-this">帐号登录</li>
                    <li>短信登录</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form-item">
                            <div class="layui-input-wrap">
                                <div class="layui-input-prefix">
                                    <i class="layui-icon layui-icon-username"></i>
                                </div>
                                <input type="text" name="mobile" value="" lay-verify="required" placeholder="请输入手机号码"
                                       lay-reqtext="请填写手机号码" autocomplete="off" class="layui-input"
                                       lay-affix="clear">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-wrap">
                                <div class="layui-input-prefix">
                                    <i class="layui-icon layui-icon-password"></i>
                                </div>
                                <input type="password" name="password" value="" lay-verify="required"
                                       placeholder="请输入登录密码"
                                       lay-reqtext="请填写密码" autocomplete="off" class="layui-input" lay-affix="eye">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-row">
                                <div class="layui-col-xs7">
                                    <div class="layui-input-wrap">
                                        <div class="layui-input-prefix">
                                            <i class="layui-icon layui-icon-vercode"></i>
                                        </div>
                                        <input type="text" name="captcha" value="" lay-verify="required"
                                               placeholder="图片验证码"
                                               lay-reqtext="请填写验证码" autocomplete="off" class="layui-input"
                                               lay-affix="clear">
                                    </div>
                                </div>
                                <div class="layui-col-xs5">
                                    <div style="margin-left: 10px;">
                                        <img id="captcha" src="{{ route('captcha') }}" alt="验证码"
                                             style="width: 110px; cursor:pointer;"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <div class="layui-row">
                                <div class="layui-col-xs7">
                                    <div class="layui-input-wrap">
                                        <div class="layui-input-prefix">
                                            <i class="layui-icon layui-icon-cellphone"></i>
                                        </div>
                                        <input type="text" name="cellphone" value="" lay-verify="phone"
                                               placeholder="手机号"
                                               lay-reqtext="请填写手机号" autocomplete="off" class="layui-input"
                                               id="reg-cellphone">
                                    </div>
                                </div>
                                <div class="layui-col-xs5">
                                    <div style="margin-left: 11px;">
                                        <button type="button" class="layui-btn layui-btn-fluid layui-btn-primary"
                                                lay-on="reg-get-vercode">获取验证码
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-wrap">
                                <div class="layui-input-prefix">
                                    <i class="layui-icon layui-icon-vercode"></i>
                                </div>
                                <input type="text" name="vercode" value="" lay-verify="required" placeholder="验证码"
                                       lay-reqtext="请填写验证码" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        qrcode
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="J-login">登录</button>
            </div>

            <div class="layui-form-item login-other">
                <a href="{{ route('signup') }}" class="layui-font-red">免费注册</a>
                <span class="layui-font-gray"> | </span>
                <a href="{{ route('password.forget') }}" class="layui-font-red">忘记密码？</a>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        layui.use(function () {
            var form = layui.form;
            var layer = layui.layer;
            var $ = layui.jquery;

            $('.switch-qrcode').bind('click', function () {
                $(this).addClass('layui-hide');
                $('.switch-mobile').removeClass('layui-hide');
                $('.login-container').hide();
            })
            $('.switch-mobile').bind('click', function () {
                $(this).addClass('layui-hide');
                $('.switch-qrcode').removeClass('layui-hide');
                $('.login-container').show();
            })

            // 图片验证码
            $('#captcha').bind('click', function () {
                var captcha = '{{ route('captcha') }}?r=' + Math.random()
                $(this).attr('src', captcha);
            })

            // 提交事件
            form.on('submit(J-login)', function (data) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('login') }}',
                    contentType: 'application/json; charset=utf-8',
                    data: JSON.stringify(data.field),
                    dataType: 'json',
                    success: function (res) {
                        if (res.code !== 0) {
                            layer.msg(res.message);
                            return false;
                        }

                        window.location.href = '{{ url('/') }}'; // TODO redirect callback url
                    }
                });

                return false; // 阻止默认 form 跳转
            });
        });
    </script>
@endsection
