<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>

<body>
@include('web::library/page_header')
<!--当前位置 start-->
<div class="block box">
    <div id="ur_here">
        @include('web::library/ur_here')
    </div>
</div>
<!--当前位置 end-->
<div class="blank"></div>
<!--#登录界面 start-->
@if($action == 'login')
    <div class="usBox clearfix">
        <div class="usBox_1 f_l">
            <div class="logtitle"></div>
            <form name="formLogin" action="user.php" method="post" onSubmit="return userLogin()">
                <table width="100%" border="0" align="left" cellpadding="3" cellspacing="5">
                    <tr>
                        <td width="15%" align="right">{{ $lang['label_username'] }}</td>
                        <td width="85%"><input name="username" type="text" size="25" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td align="right">{{ $lang['label_password'] }}</td>
                        <td>
                            <input name="password" type="password" size="15" class="inputBg"/>
                        </td>
                    </tr>
                    <!-- 判断是否启用验证码@if($enabled_captcha)
                        -->
                        <tr>
                            <td align="right">{{ $lang['comment_captcha'] }}</td>
                            <td><input type="text" size="8" name="captcha" class="inputBg"/>
                                <img src="captcha.php?is_login=1&{{ $rand }}" alt="captcha"
                                     style="vertical-align: middle;cursor: pointer;"
                                     onClick="this.src='captcha.php?is_login=1&'+Math.random()"/></td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2"><input type="checkbox" value="1" name="remember" id="remember"/><label
                                for="remember">{{ $lang['remember'] }}</label></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td align="left">
                            <input type="hidden" name="act" value="act_login"/>
                            <input type="hidden" name="back_act" value="{{ $back_act }}"/>
                            <input type="submit" name="submit" value="" class="us_Submit"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><a href="user.php?act=qpassword_name" class="f3">{{ $lang['get_password_by_question'] }}</a>&nbsp;&nbsp;&nbsp;<a
                                href="user.php?act=get_password" class="f3">{{ $lang['get_password_by_mail'] }}</a></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="usTxt">
            <strong>{$lang.user_reg_info[0]}</strong> <br/>
            <strong class="f4">{$lang.user_reg_info[1]}：</strong><br/>
            @if($car_off == 1)
                {$lang.user_reg_info[2]}<br/>
            @endif
            @if($car_off == 0)
                {$lang.user_reg_info[8]}<br/>
            @endif
            {$lang.user_reg_info[3]}：<br/>
            1. {$lang.user_reg_info[4]}<br/>
            2. {$lang.user_reg_info[5]}<br/>
            3. {$lang.user_reg_info[6]}<br/>
            4. {$lang.user_reg_info[7]} <br/>
            <a href="user.php?act=register"><img src="images/bnt_ur_reg.gif"/></a>
        </div>
    </div>
@endif
<!--#登录界面 end-->

<!--*会员注册界面 start-->
@if($action == 'register')
    @if($shop_reg_closed == 1)
        <div class="usBox">
            <div class="usBox_2 clearfix">
                <div class="f1 f5" align="center">{{ $lang['shop_register_closed'] }}</div>
            </div>
        </div>
    @else
        <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
        <div class="usBox">
            <div class="usBox_2 clearfix">
                <div class="regtitle"></div>
                <form action="user.php" method="post" name="formUser" onsubmit="return register();">
                    <table width="100%" border="0" align="left" cellpadding="5" cellspacing="3">
                        <tr>
                            <td width="13%" align="right">{{ $lang['label_username'] }}</td>
                            <td width="87%">
                                <input name="username" type="text" size="25" id="username"
                                       onblur="is_registered(this.value);" class="inputBg"/>
                                <span id="username_notice" style="color:#FF0000"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{{ $lang['label_email'] }}</td>
                            <td>
                                <input name="email" type="text" size="25" id="email" onblur="checkEmail(this.value);"
                                       class="inputBg"/>
                                <span id="email_notice" style="color:#FF0000"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{{ $lang['label_password'] }}</td>
                            <td>
                                <input name="password" type="password" id="password1"
                                       onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)"
                                       class="inputBg" style="width:179px;"/>
                                <span style="color:#FF0000" id="password_notice"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{{ $lang['label_password_intensity'] }}</td>
                            <td>
                                <table width="145" border="0" cellspacing="0" cellpadding="1">
                                    <tr align="center">
                                        <td width="33%" id="pwd_lower">{{ $lang['pwd_lower'] }}</td>
                                        <td width="33%" id="pwd_middle">{{ $lang['pwd_middle'] }}</td>
                                        <td width="33%" id="pwd_high">{{ $lang['pwd_high'] }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{{ $lang['label_confirm_password'] }}</td>
                            <td>
                                <input name="confirm_password" type="password" id="conform_password"
                                       onblur="check_conform_password(this.value);" class="inputBg"
                                       style="width:179px;"/>
                                <span style="color:#FF0000" id="conform_password_notice"> *</span>
                            </td>
                        </tr>
                        @foreach($extend_info_list as $field)
                            @if($field['id'] == 6)
                                <tr>
                                    <td align="right">{{ $lang['passwd_question'] }}</td>
                                    <td>
                                        <select name='sel_question'>
                                            <option value='0'>{{ $lang['sel_question'] }}</option>
                                            {html_options options=$passwd_questions}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"
                                        @if($field['is_need'])id="passwd_quesetion"@endif>{{ $lang['passwd_answer'] }}</td>
                                    <td>
                                        <input name="passwd_answer" type="text" size="25" class="inputBg"
                                               maxlengt='20'/>@if($field['is_need'])
                                            <span style="color:#FF0000"> *</span>
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td align="right"
                                        @if($field['is_need'])id="extend_field{{ $field['id'] }}i"@endif>{{ $field['reg_field_name'] }}
                                    <td>
                                        <input name="extend_field{{ $field['id'] }}" type="text" size="25"
                                               class="inputBg"/>@if($field['is_need'])
                                            <span style="color:#FF0000"> *</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <!-- 判断是否启用验证码@if($enabled_captcha)
                            -->
                            <tr>
                                <td align="right">{{ $lang['comment_captcha'] }}</td>
                                <td><input type="text" size="8" name="captcha" class="inputBg"/>
                                    <img src="captcha.php?{{ $rand }}" alt="captcha"
                                         style="vertical-align: middle;cursor: pointer;"
                                         onClick="this.src='captcha.php?'+Math.random()"/></td>
                            </tr>
                        @endif
                        <tr>
                            <td>&nbsp;</td>
                            <td><label>
                                    <input name="agreement" type="checkbox" value="1" checked="checked"/>
                                    {{ $lang['agreement'] }}</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="left">
                                <input name="act" type="hidden" value="act_register">
                                <input type="hidden" name="back_act" value="{{ $back_act }}"/>
                                <input name="Submit" type="submit" value="" class="us_Submit_reg">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="actionSub">
                                <a href="user.php?act=login">{{ $lang['want_login'] }}</a><br/>
                                <a href="user.php?act=get_password">{{ $lang['forgot_password'] }}</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    @endif
@endif
<!--#会员注册界面 end-->

<!--*找回密码界面 -->
@if($action == 'get_password')
    <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript">
        @foreach($lang['password_js'] as $item => $key)
        var {{ $key }} = "{{ $item }}";
        @endforeach
    </script>
    <div class="usBox">
        <div class="usBox_2 clearfix">
            <form action="user.php" method="post" name="getPassword" onsubmit="return submitPwdInfo();">
                <br/>
                <table width="70%" border="0" align="center">
                    <tr>
                        <td colspan="2" align="center"><strong>{{ $lang['username_and_email'] }}</strong></td>
                    </tr>
                    <tr>
                        <td width="29%" align="right">{{ $lang['username'] }}</td>
                        <td width="61%"><input name="user_name" type="text" size="30" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td align="right">{{ $lang['email'] }}</td>
                        <td><input name="email" type="text" size="30" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="act" value="send_pwd_email"/>
                            <input type="submit" name="submit" value="{{ $lang['submit'] }}" class="bnt_blue"
                                   style="border:none;"/>
                            <input name="button" type="button" onclick="history.back()"
                                   value="{{ $lang['back_page_up'] }}" style="border:none;" class="bnt_blue_1"/>
                        </td>
                    </tr>
                </table>
                <br/>
            </form>
        </div>
    </div>
@endif

<!--*通过问题找回密码的确认找回账号界面 -->
@if($action == 'qpassword_name')
    <div class="usBox">
        <div class="usBox_2 clearfix">
            <form action="user.php" method="post">
                <br/>
                <table width="70%" border="0" align="center">
                    <tr>
                        <td colspan="2" align="center"><strong>{{ $lang['get_question_username'] }}</strong></td>
                    </tr>
                    <tr>
                        <td width="29%" align="right">{{ $lang['username'] }}</td>
                        <td width="61%"><input name="user_name" type="text" size="30" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="act" value="get_passwd_question"/>
                            <input type="submit" name="submit" value="{{ $lang['submit'] }}" class="bnt_blue"
                                   style="border:none;"/>
                            <input name="button" type="button" onclick="history.back()"
                                   value="{{ $lang['back_page_up'] }}" style="border:none;" class="bnt_blue_1"/>
                        </td>
                    </tr>
                </table>
                <br/>
            </form>
        </div>
    </div>
@endif

<!--*根据输入账号显示密码问题界面 -->
@if($action == 'get_passwd_question')
    <div class="usBox">
        <div class="usBox_2 clearfix">
            <form action="user.php" method="post">
                <br/>
                <table width="70%" border="0" align="center">
                    <tr>
                        <td colspan="2" align="center"><strong>{{ $lang['input_answer'] }}</strong></td>
                    </tr>
                    <tr>
                        <td width="29%" align="right">{{ $lang['passwd_question'] }}：</td>
                        <td width="61%">{{ $passwd_question }}</td>
                    </tr>
                    <tr>
                        <td align="right">{{ $lang['passwd_answer'] }}：</td>
                        <td><input name="passwd_answer" type="text" size="20" class="inputBg"/></td>
                    </tr>
                    <!-- 判断是否启用验证码@if($enabled_captcha)
                        -->
                        <tr>
                            <td align="right">{{ $lang['comment_captcha'] }}</td>
                            <td><input type="text" size="8" name="captcha" class="inputBg"/>
                                <img src="captcha.php?is_login=1&{{ $rand }}" alt="captcha"
                                     style="vertical-align: middle;cursor: pointer;"
                                     onClick="this.src='captcha.php?is_login=1&'+Math.random()"/></td>
                        </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="act" value="check_answer"/>
                            <input type="submit" name="submit" value="{{ $lang['submit'] }}" class="bnt_blue"
                                   style="border:none;"/>
                            <input name="button" type="button" onclick="history.back()"
                                   value="{{ $lang['back_page_up'] }}" style="border:none;" class="bnt_blue_1"/>
                        </td>
                    </tr>
                </table>
                <br/>
            </form>
        </div>
    </div>
@endif

@if($action == 'reset_password')
    <script type="text/javascript">
        @foreach($lang['password_js'] as $item => $key)
        var {{ $key }} = "{{ $item }}";
        @endforeach
    </script>
    <div class="usBox">
        <div class="usBox_2 clearfix">
            <form action="user.php" method="post" name="getPassword2" onSubmit="return submitPwd()">
                <br/>
                <table width="80%" border="0" align="center">
                    <tr>
                        <td>{{ $lang['new_password'] }}</td>
                        <td><input name="new_password" type="password" size="25" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td>{{ $lang['confirm_password'] }}:</td>
                        <td><input name="confirm_password" type="password" size="25" class="inputBg"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="hidden" name="act" value="act_edit_password"/>
                            <input type="hidden" name="uid" value="{{ $uid }}"/>
                            <input type="hidden" name="code" value="{{ $code }}"/>
                            <input type="submit" name="submit" value="{{ $lang['confirm_submit'] }}"/>
                        </td>
                    </tr>
                </table>
                <br/>
            </form>
        </div>
    </div>
@endif
<!--#找回密码界面 end-->
<div class="blank"></div>
@include('web::library/page_footer')
</body>
<script type="text/javascript">
    var process_request = "{{ $lang['process_request'] }}";
    @foreach($lang['passport_js'] as $item => $key)
    var {{ $key }} = "{{ $item }}";
    @endforeach
    var username_exist = "{{ $lang['username_exist'] }}";
</script>
</html>
