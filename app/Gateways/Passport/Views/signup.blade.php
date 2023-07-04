@extends('auth::layouts.app')

@section('content')
    <form class="layui-form">
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                <li class="layui-this">立即注册</li>
            </ul>
            
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="signup-container">

                        <div class="layui-form-item">
                            <div class="layui-row">
                                <div class="layui-col-xs7">
                                    <div class="layui-input-wrap">
                                        <div class="layui-input-prefix">
                                            <i class="layui-icon layui-icon-cellphone"></i>
                                        </div>
                                        <input type="text" name="mobile" lay-verify="required|phone" placeholder="手机号"
                                               lay-reqtext="请填写手机号" autocomplete="off" class="layui-input"
                                               id="J-mobile">
                                    </div>
                                </div>
                                <div class="layui-col-xs5">
                                    <div style="margin-left: 10px;">
                                        <button type="button" class="layui-btn layui-btn-fluid layui-btn-primary"
                                                lay-on="showCaptcha">获取验证码
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
                                <input type="text" name="code" lay-verify="required" placeholder="验证码"
                                       lay-reqtext="请填写验证码" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <input type="checkbox" name="agreement" lay-verify="required" lay-skin="primary"
                                   title="同意">
                            <a href="#terms" target="_blank" style="position: relative; top: 6px; left: -15px;">
                                <ins>用户协议</ins>
                            </a>
                            <a href="#terms" target="_blank" style="position: relative; top: 6px; left: -15px;">
                                <ins>隐私政策</ins>
                            </a>
                        </div>

                        <div class="layui-form-item">
                            <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="register">
                                同意并注册
                            </button>
                        </div>

                        <div class="layui-form-item signup-other">
                            <a href="{{ route('login') }}" class="layui-font-red">登录已有帐号</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{--captchaForm start--}}
    <div class="layui-hide" id="captchaForm">
        <form class="layui-form">
            <div class="layui-row">
                <div class="layui-col-xs9">
                    <img id="captchaImg" src="{{ route('captcha') }}" alt="验证码"/>
                </div>
                <div class="layui-col-xs3 refresh-captcha">
                    <i class="layui-icon layui-icon-refresh"> 换一个</i>
                </div>
            </div>
            <div class="layui-row">
                <input type="text" name="captcha" lay-verify="required" placeholder="请输入图片验证码"
                       lay-reqtext="请输入图片验证码" autocomplete="off" class="layui-input" lay-affix="clear">
            </div>
            <div class="layui-row">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="sendSMS">发送短信</button>
            </div>
        </form>
    </div>
    {{--captchaForm end--}}
@endsection

@section('script')
    <script>
        layui.use(function () {
            var $ = layui.$;
            var form = layui.form;
            var layer = layui.layer;
            var util = layui.util;

            // 刷新图片验证码
            $('.refresh-captcha').bind('click', function () {
                $('#captchaImg').attr('src', '{{ route('captcha') }}?r=' + Math.random());
            });

            // 普通事件
            util.on('lay-on', {
                // 显示图片验证码
                'showCaptcha': function (o) {
                    // 主动触发验证
                    var isValid = form.validate('#J-mobile');
                    // 验证通过
                    if (isValid) {
                        $('.refresh-captcha').click();
                        $('input[name="captcha"]').val('');

                        var captchaForm = $('#captchaForm');
                        captchaForm.removeClass('layui-hide');

                        // 页面层
                        layer.open({
                            type: 1,
                            area: ['280px', '180px'], // 宽高
                            title: false, // 不显示标题栏
                            content: captchaForm,
                            end: function () {
                                captchaForm.addClass('layui-hide')
                            }
                        });
                    }
                }
            });

            // 发送短信验证码
            form.on('submit(sendSMS)', function (data) {
                // var data = {
                //     mobile: $('input[name="mobile"]').val(),
                //     captcha: $('input[name="captcha"]').val()
                // }

                if (data.captcha.length !== 4) {
                    layer.msg('请填写图片验证码');
                    return false;
                }

                // 此处可继续书写「发送验证码」等后续逻辑
                console.log(data)

                return false; // 阻止默认 form 跳转
            })

            // 提交事件
            form.on('submit(register)', function (data) {
                var field = data.field; // 获取表单字段值

                // 是否勾选同意
                if (!field.agreement) {
                    layer.msg('请阅读并同意协议');
                    return false;
                }

                // 显示填写结果
                layer.alert(JSON.stringify(field), {
                    title: '当前填写的字段值'
                });

                // 此处可执行 Ajax 等操作
                // …

                return false; // 阻止默认 form 跳转
            });
        });
    </script>
@endsection
