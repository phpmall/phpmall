@include('admin::pageheader')
<div class="main-div">
    <form name="theForm" method="post" enctype="multipart/form-data" onsubmit="return validate();">
        <table width="100%">
            <tr>
                <td class="label">{{ $lang['user_name'] }}</td>
                <td>
                    <input type="text" name="user_name" maxlength="20" value="{{ $user['user_name'] }}"
                           size="34"/>{{ $lang['require_field'] }}
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['email'] }}</td>
                <td>
                    <input type="text" name="email" value="{{ $user['email'] }}"
                           size="34"/>{{ $lang['require_field'] }}
                </td>
            </tr>
            @if($action === "add")
                <tr>
                    <td class="label">{{ $lang['password'] }}</td>
                    <td>
                        <input type="password" name="password" maxlength="32" size="34"/>{{ $lang['require_field'] }}
                    </td>
                </tr>
                <tr>
                    <td class="label">{{ $lang['pwd_confirm'] }}</td>
                    <td>
                        <input type="password" name="pwd_confirm" maxlength="32" size="34"/>{{ $lang['require_field'] }}
                    </td>
                </tr>
            @endif
            @if($action != "add")
                <tr>
                    <td class="label">
                        <a href="javascript:showNotice('passwordNotic');" title="{{ $lang['form_notice'] }}">
                            <img src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                                 alt="{{ $lang['form_notice'] }}"></a>{{ $lang['old_password'] }}
                    </td>
                    <td>
                        <input type="password" name="old_password" size="34"/>{{ $lang['require_field'] }}
                        <br/><span class="notice-span"
                                   {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="passwordNotic">{{ $lang['password_notic'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">{{ $lang['new_password'] }}</td>
                    <td>
                        <input type="password" name="new_password" maxlength="32"
                               size="34"/>{{ $lang['require_field'] }}
                    </td>
                </tr>
                <tr>
                    <td class="label">{{ $lang['pwd_confirm'] }}</td>
                    <td>
                        <input type="password" name="pwd_confirm" value="" size="34"/>{{ $lang['require_field'] }}
                    </td>
                </tr>
                @if($user['agency_name'])
                    <tr>
                        <td class="label">{{ $lang['agency'] }}</td>
                        <td>{{ $user['agency_name'] }}</td>
                    </tr>
                @endif
            @endif
            @if($select_role)
                <tr>
                    <td class="label">{{ $lang['select_role'] }}</td>
                    <td>
                        <select name="select_role">
                            <option value="">{{ $lang['select_please'] }}</option>
                            @foreach($select_role as $list)
                                <option value="{{ $list['role_id'] }}"
                                        @if($list['role_id'] === $user['role_id']) selected="selected" @endif >{{ $list['role_name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @endif
            @if($action === "modif")
                <tr>
                    <td align="left" class="label">{{ $lang['edit_navi'] }}</td>
                    <td>
                        <table style="width:300px" cellspacing="0">
                            <tr>
                                <td valign="top">
                                    <input type="hidden" name="nav_list[]" id="nav_list[]"/>
                                    <select name="menus_navlist" id="menus_navlist" multiple="true"
                                            style="width: 120px; height: 180px"
                                            onclick="setTimeout('toggleButtonSatus()', 1);">
                                        {html_options options=$nav_arr}
                                    </select>
                                </td>
                                <td align="center">
                                    <input type="button" class="button" value="{{ $lang['move_up'] }}" id="btnMoveUp"
                                           onclick="moveOptions('up')" disabled="true"/>
                                    <input type="button" class="button" value="{{ $lang['move_down'] }}"
                                           id="btnMoveDown"
                                           onclick="moveOptions('down')" disabled="true"/>
                                    <input type="button" value="{{ $lang['add_nav'] }}" id="btnAdd"
                                           onclick="JavaScript:addItem(theForm.all_menu_list,theForm.menus_navlist); this.disabled=true; "
                                           class="button" disabled="true"/><br/>
                                    <input type="button" value="{{ $lang['remove_nav'] }}"
                                           onclick="JavaScript:delItem(theForm.menus_navlist); toggleButtonSatus()"
                                           class="button" disabled="true" id="btnRemove"/>
                                </td>
                                <td>
                                    <select id="all_menu_list" name="all_menu_list" size="15" multiple="true"
                                            style="width:150px; height: 180px" onchange="toggleAddButton()">
                                        @foreach($menus as $menu => $key)
                                            @if($key != "admin_home" && $menus[$key])
                                                <option value="" style="font-weight:bold;">{$lang.$key}</option>
                                                @foreach($menus[$key] as $k => $item)
                                                    <option value="{{ $item }}">&nbsp;&nbsp;&nbsp;&nbsp;{$lang.$k}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>&nbsp;&nbsp;&nbsp;
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                    <input type="hidden" name="act" value="{{ $form_act }}"/>
                    <input type="hidden" name="token" value="{{ $token }}"/>
                    <input type="hidden" name="id" value="{{ $user['user_id'] }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<script type="text/javascript">
    var action = "{{ $action }}";

    document.forms['theForm'].elements['user_name'].focus();
    /**
     * 切换增加按钮的状态
     */
    function toggleAddButton() {
        var sel = document.getElementById("all_menu_list");
        document.getElementById("btnAdd").disabled = (sel.selectedIndex > -1) ? false : true;
    }

    /**
     * 切换移出，上移，下移按钮状态
     */
    function toggleButtonSatus() {
        var sel = document.getElementById("menus_navlist");
        document.getElementById("btnRemove").disabled = (sel.selectedIndex > -1) ? false : true;
        document.getElementById("btnMoveUp").disabled = (sel.selectedIndex > -1) ? false : true;
        document.getElementById("btnMoveDown").disabled = (sel.selectedIndex > -1) ? false : true;
    }

    /**
     * 移动选定的列表项
     */
    function moveOptions(direction) {
        var sel = document.getElementById('menus_navlist');
        if (sel.selectedIndex === -1) {
            return;
        }

        len = sel.length
        for (i = 0; i < len; i++) {
            if (sel.options[i].selected) {
                if (i === 0 && direction === 'up') {
                    return;
                }

                newOpt = sel.options[i].cloneNode(true);

                sel.removeChild(sel.options[i]);
                tarOpt = (direction === "up") ? sel.options[i - 1] : sel.options[i + 1]
                sel.insertBefore(newOpt, tarOpt);
                newOpt.selected = true;
                break;
            }
        }
    }

    /**
     * 检查表单输入的数据
     */
    function validate() {
        get_navlist();

        validator = new Validator("theForm");
        validator.password = function (controlId, msg) {
            var obj = document.forms[this.formName].elements[controlId];
            obj.value = Utils.trim(obj.value);
            if (!(obj.value.length >= 6 && /\d+/.test(obj.value) && /[a-zA-Z]+/.test(obj.value))) {
                this.addErrorMsg(msg);
            }

        }

        validator.required("user_name", user_name_empty);
        validator.required("email", email_empty, 1);
        validator.isEmail("email", email_error);

        if (action === "add") {
            if (document.forms['theForm'].elements['password']) {
                validator.password("password", password_invaild);
                validator.eqaul("password", "pwd_confirm", password_error);
            }
        }
        if (action === "edit" || action === "modif") {
            if (document.forms['theForm'].elements['old_password'].value.length > 0) {
                validator.password("new_password", password_invaild);
                validator.eqaul("new_password", "pwd_confirm", password_error);
            }
        }

        return validator.passed();
    }

    function get_navlist() {
        if (!document.getElementById('nav_list[]')) {
            return;
        }

        document.getElementById('nav_list[]').value = joinItem(document.getElementById('menus_navlist'));
        //alert(document.getElementById('nav_list[]').value);
    }
</script>
@include('admin::pagefooter')
