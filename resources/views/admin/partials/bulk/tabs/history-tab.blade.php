<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="card-title mb-1">
                    Import / Export History
                </h4>

                <p class="text-muted mb-0">
                    Review every bulk import and export operation performed in the system.
                </p>

            </div>

        </div>

    </div>

    <div class="card-body">

        {{-- Filters --}}
        <div class="row mb-4">

            <div class="col-lg-3">

                <input
                    type="text"
                    class="form-control"
                    placeholder="Search filename or remarks...">

            </div>

            <div class="col-lg-3">

                <select class="form-select">

                    <option>All Modules</option>

                    <option>Ingredients</option>

                    <option>Products</option>

                    <option>Recipes</option>

                    <option>Stock In</option>

                    <option>Units</option>

                    <option>Sales</option>

                </select>

            </div>

            <div class="col-lg-2">

                <select class="form-select">

                    <option>All Operations</option>

                    <option>Import</option>

                    <option>Export</option>

                </select>

            </div>

            <div class="col-lg-2">

                <select class="form-select">

                    <option>All Status</option>

                    <option>Success</option>

                    <option>Processing</option>

                    <option>Partial</option>

                    <option>Failed</option>

                </select>

            </div>

            <div class="col-lg-2">

                <button class="btn btn-primary w-100">

                    <i class="bx bx-search"></i>

                    Filter

                </button>

            </div>

        </div>

        @include('admin.partials.bulk.tables.history-table')

    </div>

</div>