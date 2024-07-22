<div class="settings-item w-100 confirm-wrap">
    <hr/>
    <div class="row m-0">
        <div class="col-12">
            <div data-simplebar>
                <div class="tab-content chat-list" id="pills-tabContent" >
                    <div class="tab-pane fade show active" id="tab1">
                        <div class="row m-0">
                            @php
                                $attributes = $obj->toArray();
                                unset($attributes['id']);
                            @endphp
                            @foreach($attributes as $key=>$value)
                                    <div class="form-group col-md-12">
                                        <label for="name">{{ucwords(str_replace('_', ' ', $key))}}: </label>
                                        <b>{!! BladeHelper::formatView($key, $value, $obj) !!}</b>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</div>
