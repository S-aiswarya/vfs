@foreach($children as $child)
	@php
		$indicator = "";
		for($i=1; $i<=$depth+3; $i++)
			$indicator .= "-";
	@endphp
	<option value="{{$child->id}}" @if($selected == $child->id) selected="selected" @endif>{{$indicator}} {{$child->name}}</option>
	
	@if(count($child->children))
        @include('admin.admin_links.options',['children' => $child->children, 'depth'=>$depth+1, 'selected'=>$selected])
    @endif
@endforeach