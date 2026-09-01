<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    //
    // public function home(){
    //     return view('admin.store.home');
    // }

    public function home(){
        $products = Product::with([
            'storeProduct.primaryImage',
        ])
            ->latest()
            ->get();

        $totalErpProducts = Product::count();

        $totalStoreProducts = StoreProduct::count();

        $publishedProducts = StoreProduct::where('is_published', true)
            ->count();

        $draftProducts = StoreProduct::where('is_published', false)
            ->count();

        $notListedProducts = Product::whereDoesntHave('storeProduct')
            ->count();

        $featuredProducts = StoreProduct::where('is_featured', true)
            ->count();

        return view('admin.store.home', [
            'products' => $products,
            'totalErpProducts' => $totalErpProducts,
            'totalStoreProducts' => $totalStoreProducts,
            'publishedProducts' => $publishedProducts,
            'draftProducts' => $draftProducts,
            'notListedProducts' => $notListedProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }


    /**
     * Add an ERP product to the storefront.
     */
    public function addProduct(Product $product)
    {
        StoreProduct::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'is_published' => false,
                'is_featured' => false,
            ]
        );

        alert()
            ->success('Product Added', "{$product->name} has been added to the storefront.")
            ->persistent('Close');

        return redirect()->back();
    }


    /**
     * Show the storefront product editor.
     */
    public function editProduct(Product $product)
    {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {
            alert()
                ->error('Not Listed', 'This product has not been added to the storefront.')
                ->persistent('Close');

            return redirect()->back();
        }

        $storeProduct->load('images');

        return view('admin.store.product.edit', compact(
            'product',
            'storeProduct'
        ));
    }

    /**
     * Update storefront-specific product information.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {

            alert()
                ->error(
                    'Not Listed',
                    'This product is not listed on the storefront.'
                )
                ->persistent('Close');

            return redirect()->back();
        }

        $validated = $request->validate([
            'store_title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $storeProduct->update($validated);

        alert()
            ->success(
                'Updated',
                'Store product information updated successfully.'
            )
            ->persistent('Close');

        return redirect()->back();
    }


    /**
     * Update storefront publication and featured status.
     */
    public function updateStatus(Request $request, Product $product)
    {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {

            alert()
                ->error(
                    'Not Listed',
                    'This product is not listed on the storefront.'
                )
                ->persistent('Close');

            return redirect()->back();
        }

        $storeProduct->update([
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        alert()
            ->success(
                'Status Updated',
                'Store product status updated successfully.'
            )
            ->persistent('Close');

        return redirect()->back();
    }


    // /**
    //  * Add an image to a storefront product.
    //  */
    // public function addImage(Request $request, Product $product)
    // {
    //     $storeProduct = $product->storeProduct;

    //     if (!$storeProduct) {

    //         alert()
    //             ->error(
    //                 'Not Listed',
    //                 'This product is not listed on the storefront.'
    //             )
    //             ->persistent('Close');

    //         return redirect()->back();
    //     }

    //     $validated = $request->validate([
    //         'image' => [
    //             'required',
    //             'image',
    //             'mimes:jpg,jpeg,png,webp',
    //             'max:5120',
    //         ],

    //         'alt_text' => [
    //             'nullable',
    //             'string',
    //             'max:255',
    //         ],
    //     ]);

    //     $imagePath = $request->file('image')->store(
    //         'store/products',
    //         'public'
    //     );

    //     $hasImages = $storeProduct->images()->exists();

    //     /*
    //      * If this is the first image, automatically make it primary.
    //      * Otherwise respect the normal primary-image workflow.
    //      */
    //     $isPrimary = !$hasImages || $request->boolean('is_primary');

    //     DB::transaction(function () use (
    //         $storeProduct,
    //         $imagePath,
    //         $validated,
    //         $isPrimary
    //     ) {

    //         if ($isPrimary) {
    //             $storeProduct->images()->update([
    //                 'is_primary' => false,
    //             ]);
    //         }

    //         $storeProduct->images()->create([
    //             'image_path' => $imagePath,
    //             'alt_text' => $validated['alt_text'] ?? null,
    //             'is_primary' => $isPrimary,
    //         ]);
    //     });

    //     alert()
    //         ->success(
    //             'Image Added',
    //             'Product image uploaded successfully.'
    //         )
    //         ->persistent('Close');

    //     return redirect()->back();
    // }


    // /**
    //  * Set a storefront product image as the primary image.
    //  */
    // public function setPrimaryImage(
    //     Product $product,
    //     StoreProductImage $image
    // ) {
    //     $storeProduct = $product->storeProduct;

    //     if (!$storeProduct) {

    //         alert()
    //             ->error(
    //                 'Not Listed',
    //                 'This product is not listed on the storefront.'
    //             )
    //             ->persistent('Close');

    //         return redirect()->back();
    //     }

    //     /*
    //      * Security check:
    //      * Make sure the image actually belongs to this product.
    //      */
    //     if ($image->store_product_id !== $storeProduct->id) {

    //         abort(404);
    //     }

    //     DB::transaction(function () use ($storeProduct, $image) {

    //         $storeProduct->images()->update([
    //             'is_primary' => false,
    //         ]);

    //         $image->update([
    //             'is_primary' => true,
    //         ]);
    //     });

    //     alert()
    //         ->success(
    //             'Primary Image Updated',
    //             'The primary product image has been updated.'
    //         )
    //         ->persistent('Close');

    //     return redirect()->back();
    // }


    // /**
    //  * Delete a storefront product image.
    //  */
    // public function deleteImage(
    //     Product $product,
    //     StoreProductImage $image
    // ) {
    //     $storeProduct = $product->storeProduct;

    //     if (!$storeProduct) {

    //         alert()
    //             ->error(
    //                 'Not Listed',
    //                 'This product is not listed on the storefront.'
    //             )
    //             ->persistent('Close');

    //         return redirect()->back();
    //     }

    //     /*
    //      * Security check:
    //      * Never allow an image belonging to another product
    //      * to be deleted through this URL.
    //      */
    //     if ($image->store_product_id !== $storeProduct->id) {

    //         abort(404);
    //     }

    //     $wasPrimary = $image->is_primary;

    //     /*
    //      * Delete the physical file.
    //      */
    //     if ($image->image_path) {
    //         Storage::disk('public')->delete($image->image_path);
    //     }

    //     $image->delete();

    //     /*
    //      * If the deleted image was primary, automatically
    //      * promote another image.
    //      */
    //     if ($wasPrimary) {

    //         $newPrimary = $storeProduct->images()
    //             ->oldest()
    //             ->first();

    //         if ($newPrimary) {

    //             $newPrimary->update([
    //                 'is_primary' => true,
    //             ]);
    //         }
    //     }

    //     alert()
    //         ->success(
    //             'Image Deleted',
    //             'Product image deleted successfully.'
    //         )
    //         ->persistent('Close');

    //     return redirect()->back();
    // }

    /**
     * Add an image to a storefront product.
     */
    public function addImage(Request $request, Product $product)
    {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {

            alert()
                ->error(
                    'Not Listed',
                    'This product is not listed on the storefront.'
                )
                ->persistent('Close');

            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        if ($validator->fails()) {

            alert()
                ->error(
                    'Error',
                    $validator->messages()->all()[0]
                )
                ->persistent('Close');

            return redirect()->back();
        }

        /*
        * Generate a unique filename.
        */
        $filename = time() . '_' . uniqid() . '.' .
            $request->file('image')->getClientOriginalExtension();

        /*
        * Product-specific image directory.
        *
        * Example:
        * uploads/store/products/bread/
        */
        $directory = 'uploads/store/products/' . $product->slug;

        /*
        * Path stored in the database.
        *
        * Example:
        * uploads/store/products/bread/1756712345_abc123.jpg
        */
        $imagePath = $directory . '/' . $filename;

        /*
        * Move the uploaded image into the product's directory.
        */
        $request->file('image')->move(
            public_path($directory),
            $filename
        );

        /*
        * Check whether this is the first image.
        */
        $hasImages = $storeProduct->images()->exists();

        /*
        * The first image automatically becomes primary.
        * Otherwise respect the admin's selection.
        */
        $isPrimary = !$hasImages || $request->boolean('is_primary');

        DB::transaction(function () use (
            $storeProduct,
            $imagePath,
            $request,
            $isPrimary
        ) {

            /*
            * If this image is primary,
            * remove primary status from existing images.
            */
            if ($isPrimary) {

                $storeProduct->images()->update([
                    'is_primary' => false,
                ]);
            }

            /*
            * Create the image record.
            */
            $storeProduct->images()->create([
                'image_path' => $imagePath,
                'alt_text' => $request->alt_text,
                'is_primary' => $isPrimary,
            ]);
        });

        alert()
            ->success(
                'Image Added',
                'Product image uploaded successfully.'
            )
            ->persistent('Close');

        return redirect()->back();
    }
    

    /**
     * Set a storefront product image as the primary image.
     */
    public function setPrimaryImage(
        Product $product,
        StoreProductImage $image
    ) {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {

            alert()
                ->error(
                    'Not Listed',
                    'This product is not listed on the storefront.'
                )
                ->persistent('Close');

            return redirect()->back();
        }

        /*
        * Make sure the image belongs to this storefront product.
        */
        if ($image->store_product_id !== $storeProduct->id) {
            abort(404);
        }

        DB::transaction(function () use ($storeProduct, $image) {

            $storeProduct->images()->update([
                'is_primary' => false,
            ]);

            $image->update([
                'is_primary' => true,
            ]);
        });

        alert()
            ->success(
                'Primary Image Updated',
                'The primary product image has been updated.'
            )
            ->persistent('Close');

        return redirect()->back();
    }

    /**
     * Delete a storefront product image.
     */
    public function deleteImage(
        Product $product,
        StoreProductImage $image
    ) {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {

            alert()
                ->error(
                    'Not Listed',
                    'This product is not listed on the storefront.'
                )
                ->persistent('Close');

            return redirect()->back();
        }

        /*
        * Make sure the image belongs to this storefront product.
        */
        if ($image->store_product_id !== $storeProduct->id) {
            abort(404);
        }

        $wasPrimary = $image->is_primary;

        /*
        * Delete the physical image file.
        */
        if ($image->image_path) {

            $imageFullPath = public_path($image->image_path);

            if (file_exists($imageFullPath)) {
                unlink($imageFullPath);
            }
        }

        $image->delete();

        /*
        * If the deleted image was primary,
        * promote the oldest remaining image.
        */
        if ($wasPrimary) {

            $newPrimary = $storeProduct->images()
                ->oldest()
                ->first();

            if ($newPrimary) {

                $newPrimary->update([
                    'is_primary' => true,
                ]);
            }
        }

        alert()
            ->success(
                'Image Deleted',
                'Product image deleted successfully.'
            )
            ->persistent('Close');

        return redirect()->back();
    }
}
