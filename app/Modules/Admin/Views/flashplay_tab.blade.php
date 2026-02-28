<div id="custabbar-div">
    <p>
        @foreach($group_list as $group_key => $group)
            @if($group_key === $current)
                <span class="custab-front" id="{{ $group_key }}-tab">{{ $group['text'] }}</span>
            @else
                <span class="custab-back" id="{{ $group_key }}-tab"
                      onclick="javascript:location.href='{{ $group['url'] }}';">{{ $group['text'] }}</span>
            @endif
        @endforeach
    </p>
</div>

<script type="text/javascript">
    /**
     * 标签上鼠标移动事件的处理函数
     * @return
     */
    document.getElementById("custabbar-div").onmouseover = function (e) {
        var obj = Utils.srcElement(e);

        if (obj.className === "custab-back") {
            obj.className = "custab-hover";
        }
    }

    document.getElementById("custabbar-div").onmouseout = function (e) {
        var obj = Utils.srcElement(e);

        if (obj.className === "custab-hover") {
            obj.className = "custab-back";
        }
    }
</script>
