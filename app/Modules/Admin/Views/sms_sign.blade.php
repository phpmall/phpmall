@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<!-- 品牌搜索 -->
<div class="form-div">
    <form name="searchForm" method="post">
        {{ $lang['add_sign'] }} <input type="text" name="sms_sign" size="15"/>
        <input type="hidden" name="act" size="15" value="sms_sign_add"/>
        <input type="submit" value="{{ $lang['add'] }}" class="button"/>
        {{ $lang['new_default_sign'] }}：{{ $default_sign }}
    </form>
</div>

@foreach($sms_sign as $sms_sign)
    <div class="form-div">
        <form name="searchForm" method="post">
            {{ $lang['default_sign'] }}: {{ $sms_sign['value'] }}
            {{ $lang['edited'] }}:<input type="text" name="new_sms_sign" size="15"/>

            <input type="hidden" name="extend_no" size="15" value="{{ $sms_sign['key'] }}"/>
            <input type="submit" value="{{ $lang['edit'] }}" class="button" name="sms_sign_update"/>
            <input type="submit" value="{{ $lang['set_default_sign'] }}" class="button" name="sms_sign_default"/>
        </form>
    </div>

@endforeach

@include('admin::pagefooter')
<form method="post" action="" name="listForm">
    <!-- start brand list -->
    <div class="list-div" id="listDiv">
        <script type="text/javascript">
            listTable.recordCount = {{ $record_count }};
            listTable.pageCount = {{ $page_count }};

            @foreach($filter as $item => $key)
                listTable.filter.{{ $key }} = '{{ $item }}';
            @endforeach
        </script>
