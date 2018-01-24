<?php
/*$default_checked = 'checked';
if(count($details) > 0){
    $default_checked = '';
}*/
?>
@foreach($machineData as $category)
    <div class="panel-heading panel_collapse1_{{$category->id}}">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1_{{$category->id}}"><span class="glyphicon glyphicon-collapse-down"></span>{{$category->category_name}}</a>
            <a href="{{url('admin/machine/add/1/'.$category->id)}}"  class="btn btn-primary pull-right add_product">Add Product</a>
        </h4>
    </div>
    <div id="collapse1_{{$category->id}}" class="panel-collapse collapse">
        <div class="panel-body">

            <div class="col-md-12">
            @foreach($category->machine as $machine)
                    <div class="col-md-4">
                        <?php $checked1 = ""; ?>
                        @if(in_array($machine->id,$machineArray))
                            <?php $checked1 = "checked"; ?>
                        @endif
                        <div>
                            <img src="{{url('img/machine/'.$machine->id.'/thumb/'.$machine->machineImages)}}" style="width:50px;height:50px;" class="machine_image" />
                        </div>
                        <input type="checkbox" class="product_name required" name="product_name[{{$category->id}}][machine_ids][]" {{$checked1}} id="product_name_{{$machine->id}}" value="{{$machine->id}}" />
                        <input type="hidden" name="unchecked_machine[]" class="unchecked_machine" id="unchecked_machine" value="">
                        <label>{{$machine->machine_name}}</label>

                    </div>
            @endforeach
            </div>
        </div>
    </div>
@endforeach