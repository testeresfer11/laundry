<form id="filter">
    <div class="row align-items-center mb-3">
        <div class="col-4 d-flex gap-2">
            <input type="text" class="form-control"  placeholder="Search keyword" name="search_keyword" value="{{request()->filled('search_keyword') ? request()->search_keyword : ''}}">            
        </div>
        @if(request()->routeIs('user.locker.list'))
        <div class="col-4">
            <select class="form-control" name="category_id" style="width:100%">
                <option value="">Select Category</option>
                @foreach (getCommonList('allcategory') as $key =>  $value)
                    <option value="{{$key}}" {{(request()->filled('category_id') && request()->category_id == $key)? 'selected' : ''}}> {{$value}} </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-4">
            <div class="filter_btns">
            <button type="submit" class="bg-main py-1 px-3">Filter</button>
            @if(request()->filled('search_keyword') || request()->filled('status') || request()->filled('category_id'))
                <button class="btn red" id="clear_filter">Clear Filter</button>
            @endif
            </div>
        </div>
    </div>
</form>
