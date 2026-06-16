@extends('dashboard.admin.layout')

@section('content')

<style>
:root{
    --bg:#09090b;
    --card:#18181b;
    --input:#27272a;
    --border:#3f3f46;
    --red:#dc2626;
    --red-dark:#991b1b;
    --red-light:#ef4444;
    --text:#fafafa;
    --muted:#a1a1aa;
}

.forecast-wrapper{
    padding:30px;
}

.forecast-header{
    background:linear-gradient(135deg,#991b1b,#dc2626);
    border-radius:20px;
    padding:30px;
    margin-bottom:25px;
    color:white;
}

.forecast-header h2{
    margin:0;
    font-size:32px;
    font-weight:800;
}

.forecast-header p{
    margin-top:8px;
    color:rgba(255,255,255,.8);
}

.forecast-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:20px;
    padding:25px;
    margin-bottom:25px;
}

.card-title-custom{
    color:var(--text);
    font-size:20px;
    font-weight:700;
    margin-bottom:20px;
}

.form-label-custom{
    color:var(--muted);
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
    display:block;
}

.form-control-custom{
    width:100%;
    height:48px;
    background:var(--input);
    border:1px solid var(--border);
    border-radius:12px;
    color:var(--text);
    padding:0 15px;
}

.form-control-custom:focus{
    outline:none;
    border-color:var(--red);
    box-shadow:0 0 0 4px rgba(220,38,38,.15);
}

.status-toggle{
    height:48px;
    background:var(--input);
    border:1px solid var(--border);
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:10px;
    padding:0 14px;
    color:var(--text);
    font-weight:600;
}

.status-toggle input{
    width:18px;
    height:18px;
    accent-color:var(--red);
}

.add-btn{
    height:48px;
    min-width:110px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,var(--red),var(--red-dark));
    color:white;
    font-weight:800;
}

.add-btn:hover{
    background:linear-gradient(135deg,var(--red-light),var(--red));
}

.table-responsive-custom{
    overflow-x:auto;
}

.table-custom{
    width:100%;
    border-collapse:separate;
    border-spacing:0 12px;
}

.table-custom thead th{
    background:#27272a;
    color:white;
    padding:16px;
    font-size:13px;
    text-transform:uppercase;
}

.table-custom tbody tr{
    background:#1f1f23;
}

.table-custom tbody td{
    padding:14px;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    color:white;
    vertical-align:middle;
}

.table-custom tbody td:first-child{
    border-left:1px solid var(--border);
    border-radius:14px 0 0 14px;
}

.table-custom tbody td:last-child{
    border-right:1px solid var(--border);
    border-radius:0 14px 14px 0;
}

.status-active{
    display:inline-block;
    padding:7px 13px;
    background:rgba(34,197,94,.15);
    color:#22c55e;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
}

.status-inactive{
    display:inline-block;
    padding:7px 13px;
    background:rgba(220,38,38,.15);
    color:#ef4444;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
}

.btn-update{
    background:#dc2626;
    border:none;
    color:white;
    padding:9px 16px;
    border-radius:10px;
    font-weight:700;
}

.btn-delete{
    background:#7f1d1d;
    border:none;
    color:white;
    padding:9px 16px;
    border-radius:10px;
    font-weight:700;
}

.alert-success{
    background:rgba(34,197,94,.15);
    border:1px solid rgba(34,197,94,.3);
    color:#22c55e;
    border-radius:12px;
    padding:14px 18px;
}

.empty-row{
    text-align:center;
    color:var(--muted);
    padding:30px;
}

@media(max-width:768px){
    .forecast-wrapper{
        padding:15px;
    }

    .add-btn{
        width:100%;
    }

    .table-custom{
        min-width:900px;
    }
}
</style>

<div class="forecast-wrapper">

    <div class="forecast-header">
        <h2>Forecast Groups</h2>
        <p>Manage Dinner & Breakfast Forecast group names used by Reception.</p>
    </div>

    @if(session('success'))
        <div class="alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="forecast-card">
        <div class="card-title-custom">Add New Group</div>

        <form action="{{ route('forecast-groups.store') }}" method="POST">
            @csrf

            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label-custom">Group Name</label>
                    <input type="text" name="name" class="form-control-custom" placeholder="Example: Abbey" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label-custom">Description</label>
                    <input type="text" name="description" class="form-control-custom" placeholder="Optional note">
                </div>

                <div class="col-md-2">
                    <label class="form-label-custom">Status</label>
                    <div class="status-toggle">
                        <input type="checkbox" id="is_active" name="is_active" checked>
                        <label for="is_active">Active</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label-custom">&nbsp;</label>
                    <button class="add-btn">Add</button>
                </div>

            </div>
        </form>
    </div>

    <div class="forecast-card">
        <div class="card-title-custom">Forecast Groups</div>

        <div class="table-responsive-custom">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th width="250">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($groups as $group)
                        <tr>
                            <form action="{{ route('forecast-groups.update', $group->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <td>
                                    <input type="text" name="name" value="{{ $group->name }}" class="form-control-custom">
                                </td>

                                <td>
                                    <input type="text" name="description" value="{{ $group->description }}" class="form-control-custom">
                                </td>

                                <td>
                                    <div style="display:flex; flex-direction:column; gap:10px;">
                                        @if($group->is_active)
                                            <span class="status-active">Active</span>
                                        @else
                                            <span class="status-inactive">Inactive</span>
                                        @endif

                                        <label class="status-toggle" style="height:40px; width:130px;">
                                            <input type="checkbox" name="is_active" {{ $group->is_active ? 'checked' : '' }}>
                                            Active
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    <button class="btn-update">Update</button>
                            </form>

                            <form action="{{ route('forecast-groups.destroy', $group->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Delete this group?')" class="btn-delete">
                                    Delete
                                </button>
                            </form>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-row">
                                No forecast groups added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection