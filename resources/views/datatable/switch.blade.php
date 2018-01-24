<?php
    if ($status == '1') {
        $status_check = "checked";
    } else {
        $status_check = "";
    }
?>
@if(isset($dashboard_switch) && $dashboard_switch == true)
    <div class="switch-button switch-button-xs">
        <input name="swt{{$id}}" rel="{{$id}}" formaction="{{$module}}" id="swt{{$id}}" {{$status_check}} type="checkbox" />
        <span class="change_dashboard_status">
			<label for="swt{{$id}}"></label>
    </span>
    </div>
@else
    <div class="switch-button switch-button-xs">
        <input name="swt{{$id}}" rel="{{$id}}" formaction="{{$module}}" id="swt{{$id}}" {{$status_check}} type="checkbox" />
        <span class="change_status">
			<label for="swt{{$id}}"></label>
    </span>
    </div>
@endif

