@if($obj->id)
    <form method="POST" action="{{ route($route.'.update') }}" class="p-t-15" id="InputFrm" data-validate=true>
@else
    <form method="POST" action="{{ route($route.'.store') }}" class="p-t-15" id="InputFrm" data-validate=true>
@endif
    @csrf
                                    <input type="hidden" name="id" @if($obj->id) value="{{encrypt($obj->id)}}" @endif id="inputId">
                                        <div class="settings-item row">
                                            <div class="form-group col-md-12">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="name" value="{{$obj->name}}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="name">Route</label>
                                                <select id="permissions_id" class="form-control webadmin-select2-input" name="permissions_id">
                                                    <option value="">-- Select --</option>
                                                    @foreach($permissions as $permission)
                                                        <option value="{{$permission->id}}" @if($obj->permissions_id == $permission->id) selected="selected" @endif>{{$permission->route}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="name">Parent</label>
                                                <select name="parent_id" id="parent_id" class="form-control webadmin-select2-input">
                                                    <option value="0">-- No Parent --</option>
                                                    @foreach($links as $link)
                                                        <option value="{{$link->id}}" @if($obj->    parent_id == $link->id) selected="selected" @endif >{{$link->name}}</option>
                                                        @if(count($link->children))
                                                            @include('admin.admin_links.options',['children' => $link->children, 'depth'=>0, 'selected'=>$obj->parent_id])
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="name">Icon</label>
                                                <input type="text" class="form-control" name="icon" id="icon" value="{{$obj->icon}}" >
                                            </div>
                                          </div>
                                          <div class="row bottom-btn">
                                            <div class="col-md-12" align="right">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>  