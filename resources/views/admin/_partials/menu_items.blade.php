@foreach($items as $key=>$item)
	<li class="dd-item" data-id="{{$item->menu_nextable_id}}">
		<div class="dd-handle accord-header"><span class="menu-title">{{$item->title}}</span><span class="float-right fa fa-angle-down toggle-arraow dd-nodrag"></span></div>
		<div class="accord-content">
			<div class="form-group mb-2">
				<label class="control-label" for="inputCode">Navigation Label</label>
				<input type="text" name="menu[{{$item->menu_nextable_id}}][text]" class="form-control menu-title-input" value="{{$item->title}}"/>
			</div>
			@if($item->menu_type == 'custom_link')
				<div class="form-group mb-2">
					<label class="control-label" for="inputCode">Url</label>
					<input type="text" name="menu[{{$item->menu_nextable_id}}][url]" class="form-control" value="{{$item->url}}"/>
				</div>
				<div class="form-group mb-2">
					<div class="checkbox">
						<input type="checkbox" name="menu[{{$item->menu_nextable_id}}][target_blank]" id="checkbox-{{$item->menu_nextable_id}}" @if($item->target_blank==1) checked="checked" @endif /> <label for="checkbox-{{$item->menu_nextable_id}}"> New Window</label>
					</div>
				</div>
				<input type="hidden" name="menu[{{$item->menu_nextable_id}}][original_title]" value="{{$item->original_title}}"/>
			@else
				<input type="hidden" name="menu[{{$item->menu_nextable_id}}][id]" value="{{$item->linkable_id}}"/>
			@endif
			<input type="hidden" name="menu[{{$item->menu_nextable_id}}][menu_nextable_id]" value="{{$item->menu_nextable_id}}"/>
			<p class="menu-original-text"> Original: @if($item->menu_type == 'custom_link') {{$item->original_title}} @else @if($item->linkable) {{$item->linkable->name}} @endif @endif</p>
			<div class="form-group mb-2">
                <label for="exampleInputPassword1">Image Link</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{asset('/')}}</span>
                    </div>
                    <input type="text" class="form-control" name="menu[{{$item->menu_nextable_id}}][image_url]" id="inputCustomLinkImageUrl{{$item->menu_nextable_id}}">
                    <a href="{{route('admin.media.popup', ['popup_type'=>'set_url', 'type'=>'Image', 'holder_attr'=>'inputCustomLinkImageUrl'.$item->menu_nextable_id, 'title'=>'Image Link', 'related_id'=>''])}}" class="webadmin-open-ajax-popup w-100 text-right" title="Media Images" data-popup-size="xlarge">Get from media</a>
                </div>
            </div>
            <div class="form-group mb-2">
                <label>Icon Class</label>
                <input type="text" name="menu[{{$item->menu_nextable_id}}][icon]" class="form-control">
            </div>
			<p><a href="javascript:void(0)" class="remove-menu">Remove</a></p>
		</div>
		@if(isset($item->children))
			<ol class="dd-list">
				@include('admin._partials.menu_items', ['items'=>$item->children])
	        </ol>
		@endif
	</li>
@endforeach