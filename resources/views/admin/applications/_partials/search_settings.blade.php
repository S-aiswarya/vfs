<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="filter-form" id="searchForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="username">Date</label>
                                <input type="text" class="form-control datatable-advanced-search date-range-picker" name="date_between-applications.created_at">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="username">Keyword</label>
                                <input type="text" class="form-control datatable-advanced-search" name="like-keyword">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="username">Lead Id</label>
                                <input type="text" class="form-control datatable-advanced-search" name="leads.id">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="useremail">Visa Stage</label>
                                @if(isset($search_settings['stages']))
                                    <select name="applications.stage_id" class="form-control datatable-advanced-search webadmin-select2-input">
                                        <option value="">All</option>
                                        @foreach($search_settings['stages'] as $stage)
                                            <option value="{{$stage->id}}">{{$stage->name}}</option>
                                            @if($stage->sub_stages && count($stage->sub_stages))
                                                @foreach($stage->sub_stages as $sub_stage)
                                                    <option value="{{$sub_stage->id}}">---{{$sub_stage->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <select name="applications.stage_id" class="form-control datatable-advanced-search webadmin-select2-input" data-placeholder="All" data-select2-url="{{route('admin.select2.stages')}}">
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-4">
                                <button type="button" class="btn btn-primary px-4" onclick="dt();">Filter</button>
                                <button type="button" class="btn btn-secondary px-4" id="search-table-clear-btn">Clear</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>