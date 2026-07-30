<div class="table-responsive">

    <table class="table table-hover align-middle">

        <thead class="table-light">

            <tr>

                <th>Date</th>

                <th>Module</th>

                <th>Operation</th>

                <th>File</th>

                <th>Status</th>

                <th>Total</th>

                <th>Success</th>

                <th>Failed</th>

                <th>Admin</th>

                <th class="text-end">
                    Action
                </th>

            </tr>

        </thead>

        <tbody>

            {{-- Temporary Dummy Data --}}

            <tr>

                <td>
                    29 Jul 2026
                </td>

                <td>
                    Ingredients
                </td>

                <td>

                    <span class="badge bg-primary">

                        Import

                    </span>

                </td>

                <td>

                    ingredients.xlsx

                </td>

                <td>

                    @include('admin.partials.badges',[
                        'status'=>'success'
                    ])

                </td>

                <td>

                    150

                </td>

                <td class="text-success">

                    149

                </td>

                <td class="text-danger">

                    1

                </td>

                <td>

                    Super Admin

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-soft-primary btn-sm">

                        <i class="bx bx-show"></i>

                    </button>

                </td>

            </tr>

            <tr>

                <td>
                    28 Jul 2026
                </td>

                <td>
                    Products
                </td>

                <td>

                    <span class="badge bg-success">

                        Export

                    </span>

                </td>

                <td>

                    products.xlsx

                </td>

                <td>

                    @include('admin.partials.badges',[
                        'status'=>'success'
                    ])

                </td>

                <td>

                    45

                </td>

                <td class="text-success">

                    45

                </td>

                <td>

                    -

                </td>

                <td>

                    Super Admin

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-soft-primary btn-sm">

                        <i class="bx bx-show"></i>

                    </button>

                </td>

            </tr>

            <tr>

                <td>
                    27 Jul 2026
                </td>

                <td>
                    Recipes
                </td>

                <td>

                    <span class="badge bg-primary">

                        Import

                    </span>

                </td>

                <td>

                    recipes.xlsx

                </td>

                <td>

                    @include('admin.partials.badges',[
                        'status'=>'partial'
                    ])

                </td>

                <td>

                    98

                </td>

                <td class="text-success">

                    94

                </td>

                <td class="text-danger">

                    4

                </td>

                <td>

                    Super Admin

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-soft-primary btn-sm">

                        <i class="bx bx-show"></i>

                    </button>

                </td>

            </tr>

        </tbody>

    </table>

</div>

<div class="d-flex justify-content-between align-items-center mt-3">

    <small class="text-muted">

        Showing 3 of 3 records

    </small>

    <nav>

        <ul class="pagination pagination-sm mb-0">

            <li class="page-item disabled">

                <a class="page-link">

                    Previous

                </a>

            </li>

            <li class="page-item active">

                <a class="page-link">

                    1

                </a>

            </li>

            <li class="page-item disabled">

                <a class="page-link">

                    Next

                </a>

            </li>

        </ul>

    </nav>

</div>