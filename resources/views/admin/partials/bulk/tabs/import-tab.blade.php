<div class="row">

@include('admin.partials.bulk.cards.module-card',[
'type'=>'import',
'title'=>'Ingredients',
'description'=>'Import ingredients from Excel',
'icon'=>'bx bx-box',
'color'=>'light',
'note'=>'Supported: XLSX',
'target'=>'Ingredient',
'template' => url('/admin/bulkOperations/template/ingredient'),
'format'=>'.xlsx',
'lastImport'=>'Never'
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'import',
'title'=>'Products',
'description'=>'Import product catalogue',
'icon'=>'bx bx-food-menu',
'color'=>'light',
'note'=>'Supported: XLSX',
'target'=>'Product',
'template'=>url('/admin/bulkOperations/template/product'),
'format'=>'.xlsx',
'lastImport'=>'Never'
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'import',
'title'=>'Recipes',
'description'=>'Import recipes and recipe items',
'icon'=>'bx bx-receipt',
'color'=>'light',
'note'=>'Supported: XLSX',
'target'=>'Recipe',
'template'=>url('/admin/bulkOperations/template/recipe'),
'format'=>'.xlsx',
'lastImport'=>'Never'
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'import',
'title'=>'Stock In',
'description'=>'Import purchase records',
'icon'=>'bx bx-package',
'color'=>'light',
'note'=>'Supported: XLSX',
'target'=>'Stock',
'template'=>url('/admin/bulkOperations/template/stock'),
'format'=>'.xlsx',
'lastImport'=>'Never'
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'import',
'title'=>'Units',
'description'=>'Import measurement units',
'icon'=>'bx bx-ruler',
'color'=>'light',
'note'=>'Normally imported once',
'target'=>'Unit',
'template'=>url('/admin/bulkOperations/template/unit'),
'format'=>'.xlsx',
'lastImport'=>'Never'
])

</div>