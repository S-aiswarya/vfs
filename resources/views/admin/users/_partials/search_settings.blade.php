<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="filter-form" id="searchForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="useremail">Role</label>
                                <select name="users.role_id" class="form-control datatable-advanced-search webadmin-select2-input" data-placeholder="All" data-select2-url="{{route('admin.select2.user-roles')}}">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="useremail">Country</label>
                                <select name="users.office_country_id" class="form-control datatable-advanced-search webadmin-select2-input" data-placeholder="All" data-select2-url="{{route('admin.select2.countries')}}">
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="useremail">Office</label>
                                <select name="user_office-offices" class="webadmin-select2-input datatable-advanced-search form-control" data-placeholder="All" data-select2-url="{{route('admin.select2.branches')}}">
                                </select>
                            </div>
                        </div> -->
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