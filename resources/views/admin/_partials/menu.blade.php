@foreach($menu_items as $key=>$item)
    @php
        $class = "";
        if($item->id == $parent || $cur_url == $item->route)
            $class .= 'mm-active ';
        if($depth>0 && !isset($item->children))
            $class .= 'nav-item ';

    @endphp
    @if(auth('admin')->user()->can($item->permission_name))
    <li class="{{$class}}">
        <a @if(isset($item->children)) href="#" @else href="{{route($item->route)}}" @endif @if($depth>0) class="nav-link" @endif> {!! stripslashes($item->icon) !!}<span>{{$item->name}}</span>
            @if(isset($item->children))
                <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
            @endif
        </a>
        @if(isset($item->children))
            <ul class="nav-second-level" aria-expanded="false">
                @include('admin._partials.menu', ['menu_items'=>$item->children, 'parent'=>$parent, 'cur_url'=>$cur_url, 'depth'=>$depth++])
            </ul>
        @endif
    </li>
    @endif
@endforeach  