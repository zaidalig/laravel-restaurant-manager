<div class="card-footer bg-white d-flex flex-wrap align-items-center justify-content-between gap-3 table-toolbar">
    <div class="pagination-summary">Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} records</div>
    <div class="d-flex flex-wrap align-items-center gap-2"><form method="GET" class="d-flex align-items-center gap-2">
        @foreach(request()->except(['page','per_page','sort','direction']) as $key=>$value) @if(is_scalar($value))<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif @endforeach
        <label class="small text-muted">Sort</label><select name="sort" class="form-select form-select-sm form-select-compact" onchange="this.form.submit()">@foreach(($sorts ?? ['created_at'=>'Created']) as $value=>$label)<option value="{{ $value }}" @selected(request('sort', array_key_first($sorts ?? ['created_at'=>'Created']))===$value)>{{ $label }}</option>@endforeach</select>
        <select name="direction" class="form-select form-select-sm form-select-compact" onchange="this.form.submit()"><option value="desc" @selected(request('direction','desc')==='desc')>Descending</option><option value="asc" @selected(request('direction')==='asc')>Ascending</option></select>
        <label class="small text-muted">Rows</label><select name="per_page" class="form-select form-select-sm form-select-compact" onchange="this.form.submit()">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected((int)request('per_page',10)===$size)>{{ $size }}</option>@endforeach</select>
    </form>{{ $paginator->onEachSide(1)->links() }}</div>
</div>
