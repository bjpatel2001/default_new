<div class="icon pull-left">
    <a href="{{url('/admin/'.$module.'/edit/'.$id)}}" title="Edit">
        <i class="fa fa-edit fa-lg" style="font-size: 20px;"></i>
    </a>
</div>

<div class="icon pull-left">
    <a href="javascript:void(0);" class="deleteRecord" formaction="{{$module}}" rel="{{$id}}" title="Delete">
        <i class="fa fa-trash fa-lg" style="font-size: 20px; margin-left: 5px;"></i>
    </a>
</div>
@if(isset($log) && $log == true)
    <div class="icon pull-left">
        <a href="{{url('/admin/'.$module.'/log/'.$id)}}" style="font-size: 14px; margin-left: 5px;" title="{{ucfirst($module)}} {{__('app.log')}}">
            <i class="fa fa-history fa-lg" style="font-size: 20px; margin-left: 5px;"></i>
        </a>
    </div>
@endif
