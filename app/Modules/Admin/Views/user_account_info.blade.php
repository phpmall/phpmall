@include('admin::pageheader')
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<div class="main-div">
    <form action="user_account.php" method="post" name="theForm" onsubmit="return validate()">
        <table width="100%">
            <tr>
                <td class="label">{{ $lang['user_id'] }}：</td>
                <td>
                    <input type="text" name="user_id" value="{{ $user_name }}" size="20"
                           @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 || $action === "edit") readonly="true" @endif/>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['surplus_amount'] }}：</td>
                <td>
                    <input type="text" name="amount" value="{{ $user_surplus['amount'] }}" size="20"
                           @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 || $action === "edit") readonly="true" @endif/>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['pay_mothed'] }}：</td>
                <td>
                    <select name="payment"
                            @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3)disabled="true" @endif>
                        <option value="">{{ $lang['please_select'] }}</option>
                        {html_options options=$payment_list selected=$user_surplus.payment}
                    </select>
                </td>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['process_type'] }}：</td>
                <td>
                    <input type="radio" name="process_type" value="0"
                           @if($user_surplus['process_type'] === 0) checked="true"
                           @endif @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 || $action === "edit")disabled="true" @endif />{{ $lang['surplus_type_0'] }}
                    <input type="radio" name="process_type" value="1"
                           @if($user_surplus['process_type'] === 1) checked="true"
                           @endif @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3|| $action === "edit")disabled="true" @endif />{{ $lang['surplus_type_1'] }}
                    @if($action === "edit" && ($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3))
                        <input type="radio" name="process_type" value="2"
                               @if($user_surplus['process_type'] === 2|| $action === "edit") checked="true"
                               @endif@if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3) disabled="true"@endif />{{ $lang['surplus_type_2'] }}
                        <input type="radio" name="process_type" value="3"
                               @if($user_surplus['process_type'] === 3|| $action === "edit") checked="true"
                               @endif@if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3) disabled="true"@endif />{{ $lang['surplus_type_3'] }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['surplus_notic'] }}：</td>
                <td>
                    <textarea name="admin_note" cols="55" rows="3"
                              @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3) readonly="true" @endif>{{ $user_surplus['admin_note'] }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['surplus_desc'] }}：</td>
                <td>
                    <textarea name="user_note" cols="55" rows="3"
                              @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3) readonly="true" @endif>{{ $user_surplus['user_note'] }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['status'] }}：</td>
                <td>
                    <input type="radio" name="is_paid" value="0"
                           @if($user_surplus['is_paid'] === 0) checked="true"
                           @endif @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 ||$action === "edit") disabled="true"@endif/>{{ $lang['unconfirm'] }}
                    <input type="radio" name="is_paid" value="1"
                           @if($user_surplus['is_paid'] === 1) checked="true"
                           @endif @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 ||$action === "edit") disabled="true"@endif/>{{ $lang['confirm'] }}
                    <input type="radio" name="is_paid" value="2"
                           @if($user_surplus['is_paid'] === 2) checked="true"
                           @endif @if($user_surplus['process_type'] === 2 || $user_surplus['process_type'] === 3 ||$action === "edit") disabled="true"@endif/>{{ $lang['cancel'] }}
                </td>
            </tr>
            <tr>
                <td class="label">&nbsp;</td>
                <td>
                    <input type="hidden" name="id" value="{{ $user_surplus['id'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_act }}"/>
                    @if($user_surplus['process_type'] === 0 || $user_surplus['process_type'] === 1)
                        <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                        <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                    @endif
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");

        validator.required("user_id", user_id_empty);
        validator.required("amount", deposit_amount_empty);
        validator.isNumber("amount", deposit_amount_error, true);

        var deposit_amount = document['theForm'].elements['amount'].value;
        if (deposit_amount.length > 0) {
            if (deposit_amount === 0 || deposit_amount < 0) {
                alert(deposit_amount_error);
                return false;
            }
        }

        return validator.passed();
    }
</script>
@include('admin::pagefooter')
