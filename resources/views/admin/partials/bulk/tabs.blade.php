<div class="card">

    <div class="card-body">

        <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">

            <li class="nav-item">
                <a class="nav-link active"
                   data-bs-toggle="tab"
                   href="#bulk-import"
                   role="tab">

                    <i class="bx bx-upload me-1"></i>

                    Import

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#bulk-export"
                   role="tab">

                    <i class="bx bx-export me-1"></i>

                    Export

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#bulk-history"
                   role="tab">

                    <i class="bx bx-history me-1"></i>

                    History

                </a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane active"
                 id="bulk-import"
                 role="tabpanel">

                @include('admin.partials.bulk.tabs.import-tab')

            </div>

            <div class="tab-pane"
                 id="bulk-export"
                 role="tabpanel">

                @include('admin.partials.bulk.tabs.export-tab')

            </div>

            <div class="tab-pane"
                 id="bulk-history"
                 role="tabpanel">

                @include('admin.partials.bulk.tabs.history-tab')

            </div>

        </div>

    </div>

</div>