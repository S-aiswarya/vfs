@foreach($links as $link)
                                              <li class="ui-state-default pr-2 pl-2" data-order-id="{{$link->id}}">
                                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{$link->name}}
                                                <div class="float-right">
                                                  <a href="{{route('admin.admin-links.index', [encrypt($link->id)])}}" class="mr-1"><i class="fa fa-pencil-alt"></i></a>
                                                  @if(count($link->children) == 0)
                                                    <a href="{{route('admin.admin-links.destroy', [encrypt($link->id)])}}" class="webadmin-btn-warning-popup" data-message="Are you sure to delete?  Associated data will be removed if it is deleted." data-redirect-url="{{route($route.'.index')}}"><i class="fa fa-trash"></i></a>
                                                  @endif
                                                </div>
                                                @if(count($link->children))
                                                  <ul class="sortable">
                                                    @include('admin.admin_links.children',['links' => $link->children, 'depth'=>0])
                                                  </ul>
                                                @endif
                                              </li>
                                              @endforeach