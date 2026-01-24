@extends('admin.layout.dashboard')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col">
            <h4 class="font-weight-bold">Inventory Overview</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Ingredient</th>
                        <th>Quantity</th>
                        <th>Base Unit</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($inventories as $inventory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $inventory->ingredient->name }}</td>
                            <td class="fw-bold">
                                {{ number_format($inventory->quantity, 3) }}
                            </td>
                            <td>
                                {{ $inventory->ingredient->baseUnit->symbol }}
                            </td>
                            <td>
                                @if($inventory->ingredient->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                {{ $inventory->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No inventory records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                

        </div>
    </div>

</div>
@endsection
