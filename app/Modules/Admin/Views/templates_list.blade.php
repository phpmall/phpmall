@include('admin::pageheader')
<!-- start templates list -->
<div class="list-div">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['current_template'] }}</th>
        </tr>
        <tr>
            <td>
                <table>
                    <tr>
                        <td width="250" align="center"><img id="screenshot" src="{{ $curr_template['screenshot'] }}"/>
                        </td>
                        <td valign="top"><strong><span id="templateName">{{ $curr_template['name'] }}</span></strong>
                            v<span id="templateVersion">{{ $curr_template['version'] }}</span><br/>
                            <span id="templateAuthor"><a href="{{ $curr_template['uri'] }}"
                                                         target="_blank">{{ $curr_template['author'] }}</a></span><br/>
                            <span id="templateDesc">{{ $curr_template['desc'] }}</span><br/>
                            <span><input class="button" onclick="backupTemplate('{{ $curr_template['code'] }}')"
                                         value="{{ $lang['backup'] }}" type="button" id="backup"/></span>
                            <div id="CurrTplStyleList">
                                @foreach($template_style[$curr_template]['code'] as $curr_style)
                                    @if($loop->foo . total > 1)
                                        <span style="cursor:pointer;"
                                              onMouseOver="javascript:onSOver('screenshot', '{{ $curr_style }}', this);"
                                              onMouseOut="onSOut('screenshot', this, '{{ $curr_template['screenshot'] }}');"
                                              onclick="javascript:setupTemplateFG('{{ $curr_template['code'] }}', '{{ $curr_style }}', '');"
                                              id="templateType_{{ $curr_style }}"><img
                                                src="../themes/{{ $curr_template['code'] }}/images/type{{ $curr_style }}_'.($curr_style === $curr_tpl_style  ? '1' : '0').'.gif"
                                                border="0"></span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>{{ $lang['available_templates'] }}</th>
        </tr>
        <tr>
            <td>
                @foreach($available_templates as $template)
                    <div
                        style="display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1;*display:inline;">
                        <table style="width: 220px;">
                            <tr>
                                <td><strong><a href="{{ $template['uri'] }}"
                                               target="_blank">{{ $template['name'] }}</a></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>@if($template['screenshot'])
                                        <img src="{{ $template['screenshot'] }}" border="0"
                                             style="cursor:pointer; float:left; margin:0 2px;display:block;"
                                             id="{{ $template['code'] }}"
                                             onclick="javascript:setupTemplate('{{ $template['code'] }}')"/>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    @foreach($template_style[$template]['code'] as $style)
                                        @if($loop->foo1 . total > 1)
                                            <img src="../themes/{{ $template['code'] }}/images/type{{ $style }}_0.gif"
                                                 border="0"
                                                 style="cursor:pointer; float:left; margin:0 2px;"
                                                 onMouseOver="javascript:onSOver('{{ $template['code'] }}', '{{ $style }}', this);"
                                                 onMouseOut="onSOut('{{ $template['code'] }}', this, '');"
                                                 onclick="javascript:setupTemplateFG('{{ $template['code'] }}', '{{ $style }}', this);">
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">{{ $template['desc'] }}</td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </td>
        </tr>
    </table>
</div>
<!-- end templates list -->

<script type="text/javascript">
/**
 * 模板风格 全局变量
 */
var T = 0;
    var StyleSelected = '{{ $curr_tpl_style }}';
    var StyleCode = '';
    var StyleTem = '';

    /**
     * 载入页面 初始化
     */
    /**
     * 安装模版
     */
    function setupTemplate(tpl) {
        if (tpl != StyleTem) {
            StyleCode = '';
        }
        if (confirm(setupConfirm)) {
            Ajax.call('template.php?is_ajax=1&act=install', 'tpl_name=' + tpl + '&tpl_fg=' + StyleCode, setupTemplateResponse, 'GET', 'JSON');
        }
    }

    /**
     * 处理安装模版的反馈信息
     */
    function setupTemplateResponse(result) {
        StyleCode = '';
        if (result.message.length > 0) {
            alert(result.message);
        }
        if (result.error === 0) {
            showTemplateInfo(result.content);
        }
    }

    /**
     * 备份当前模板
     */
    function backupTemplate(tpl) {
        Ajax.call('template.php?is_ajax=1&act=backup', 'tpl_name=' + tpl, backupTemplateResponse, "GET", "JSON");
    }

    function backupTemplateResponse(result) {
        if (result.message.length > 0) {
            alert(result.message);
        }

        if (result.error === 0) {
            location.href = result.content;
        }
    }

    /**
     * 显示模板信息
     */
    function showTemplateInfo(res) {
        document.getElementById("CurrTplStyleList").innerHTML = res.tpl_style;

        StyleSelected = res.stylename;

        document.getElementById("screenshot").src = res.screenshot;
        document.getElementById("templateName").innerHTML = res.name;
        document.getElementById("templateDesc").innerHTML = res.desc;
        document.getElementById("templateVersion").innerHTML = res.version;
        document.getElementById("templateAuthor").innerHTML = '<a href="' + res.uri + '" target="_blank">' + res.author + '</a>';
        document.getElementById("backup").onclick = function () {
            backupTemplate(res.code);
        };
    }

    /**
     * 模板风格 切换
     */
    function onSOver(tplid, fgid, _self) {
        var re = /(\/|\\)([^\/\\])+\.png$/;
        var img_url = document.getElementById(tplid).src;
        StyleCode = fgid;
        StyleTem = tplid;

        T = 0;

        // 模板切换
        if (tplid != '' && fgid != '') {
            document.getElementById(tplid).src = img_url.replace(re, '/screenshot_' + fgid + '.png');
        } else {
            document.getElementById(tplid).src = img_url.replace(re, '/screenshot.png');
        }

        return true;
    }

    //
    function onSOut(tplid, _self, def) {
        if (T === 1) {
            return true;
        }

        var re = /(\/|\\)([^\/\\])+\.png$/;
        var img_url = document.getElementById(tplid).src;

        // 模板切换为默认风格
        if (def != '') {
            document.getElementById(tplid).src = def;
        } else {
            //  document.getElementById(tplid).src = img_url.replace(re, '/screenshot.png');
        }

        return true;
    }

    //
    function onTempSelectClear(tplid, _self) {
        var re = /(\/|\\)([^\/\\])+\.png$/;
        var img_url = document.getElementById(tplid).src;

        // 模板切换为默认风格
        document.getElementById(tplid).src = img_url.replace(re, '/screenshot.png');

        T = 0;

        return true;
    }

    /**
     * 模板风格 AJAX安装
     */
    function setupTemplateFG(tplNO, TplFG, _self) {
        T = 1;

        if (confirm(setupConfirm)) {
            Ajax.call('template.php?is_ajax=1&act=install', 'tpl_name=' + tplNO + '&tpl_fg=' + TplFG, setupTemplateResponse, 'GET', 'JSON');
        }

        if (_self) {
            onTempSelectClear(tplNO, _self);
        }

        return true;
    }
</script>
@include('admin::pagefooter')
