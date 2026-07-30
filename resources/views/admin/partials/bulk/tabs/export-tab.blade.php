<div class="row">

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Ingredients',
'description'=>'Download ingredient list',
'icon'=>'bx bx-box',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/ingredients')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Products',
'description'=>'Download products',
'icon'=>'bx bx-food-menu',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/products')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Recipes',
'description'=>'Download recipes',
'icon'=>'bx bx-receipt',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/recipes')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Stock',
'description'=>'Download inventory',
'icon'=>'bx bx-package',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/stock')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Stock In',
'description'=>'Download purchase history',
'icon'=>'bx bx-download',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/stockin')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Production',
'description'=>'Download production history',
'icon'=>'bx bx-cog',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/production')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Sales',
'description'=>'Download sales records',
'icon'=>'bx bx-cart',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/sales')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Units',
'description'=>'Download unit configuration',
'icon'=>'bx bx-ruler',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/units')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Staff',
'description'=>'Download staff records',
'icon'=>'bx bx-user',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/staff')
])

@include('admin.partials.bulk.cards.module-card',[
'type'=>'export',
'title'=>'Import Logs',
'description'=>'Download operation history',
'icon'=>'bx bx-history',
'color'=>'light',
'note'=>'Excel',
'route'=>url('/admin/export/logs')
])

</div>