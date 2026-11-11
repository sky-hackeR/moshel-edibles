<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Audit\AuditLogger;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Record activity after response is generated
        $this->recordAuditActivity($request, $response);

        return $response;
    }

    /**
     * Record activity if appropriate.
     */
    protected function recordAuditActivity(Request $request, $response): void
    {
        try {
            if (!$request->hasSession()) {
                return;
            }

            $method = strtoupper($request->method());
            $path = trim($request->path(), '/');

            // 1. Never log static assets, livewire, debugbar, or the audit log viewer itself
            if ($request->is('livewire/*', '_debugbar/*', 'assets/*', 'css/*', 'js/*', 'images/*', 'favicon.ico')) {
                return;
            }

            [$userType, $user] = AuditLogger::resolveCurrentUser($request);
            if (!$userType) {
                return;
            }

            // Keep session alive on legitimate requests
            AuditLogger::getOrCreateSession($request, $userType, $user);

            // Do not log individual audit_log records for viewing the audit log dashboard itself to prevent self-polluting log spam
            if ($request->is('admin/audit-logs*')) {
                return;
            }

            // 2. Determine if this request represents a notable user activity
            $isMutating = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);

            // Storefront customer key GET actions
            $isStorefrontActivity = (
                $path === '' ||
                $request->is('products*') ||
                $request->is('cart*') ||
                $request->is('checkout*') ||
                $request->is('account*') ||
                $request->is('contact*')
            );

            // Admin & Staff key GET actions
            $isAdminStaffKeyGet = $method === 'GET' && (
                $request->is('admin/home', 'admin/salesHistory', 'admin/inventory', 'admin/customers*', 'admin/productionHistory', 'staff/home', 'staff/salesHistory', 'staff/productionHistory')
            );

            // We log: all mutating actions, all admin/staff key actions, and customer storefront actions
            $shouldLog = $isMutating || $isAdminStaffKeyGet || ($userType === 'customer' && $isStorefrontActivity);

            if (!$shouldLog) {
                return;
            }

            // Enrich customer contact details if submitted in request
            if ($userType === 'customer' && $isMutating) {
                $contactName = $request->input('name') ?: $request->input('clientName');
                $contactEmail = $request->input('email') ?: $request->input('clientEmail');
                if ($contactName) {
                    AuditLogger::enrichSessionContact($request, $contactName, $contactEmail);
                }
            }

            $category = $this->determineCategory($path, $userType);
            $action = $this->determineActionName($request, $path, $method);

            // Sanitize inputs
            $inputs = $isMutating ? $request->except(['password', 'password_confirmation', '_token', '_method']) : [];

            // Add contextual storefront query details for GET requests
            if ($method === 'GET' && $userType === 'customer') {
                if ($request->route('slug')) {
                    $inputs['product_slug'] = $request->route('slug');
                }
                if ($request->route('reference')) {
                    $inputs['order_reference'] = $request->route('reference');
                }
            }

            AuditLogger::log($category, $action, null, $inputs, $request);
        } catch (\Throwable $e) {
            // Never break normal request execution
            report($e);
        }
    }

    /**
     * Determine category based on route path.
     */
    protected function determineCategory(string $path, string $userType): string
    {
        if ($userType === 'customer') {
            if (str_contains($path, 'cart')) {
                return 'Cart';
            }
            if (str_contains($path, 'checkout') || str_contains($path, 'paystack')) {
                return 'Checkout';
            }
            if (str_contains($path, 'product')) {
                return 'Products';
            }
            if (str_contains($path, 'account')) {
                return 'Account';
            }
            if (str_contains($path, 'contact')) {
                return 'Inquiry';
            }
            return 'Storefront';
        }

        if (str_contains($path, 'pos') || str_contains($path, 'sale')) {
            return 'POS';
        }
        if (str_contains($path, 'product') || str_contains($path, 'recipe')) {
            return 'Products & Recipes';
        }
        if (str_contains($path, 'stock') || str_contains($path, 'inventory') || str_contains($path, 'ingredient')) {
            return 'Inventory';
        }
        if (str_contains($path, 'production') || str_contains($path, 'produce')) {
            return 'Production';
        }
        if (str_contains($path, 'customer')) {
            return 'Customers';
        }
        if (str_contains($path, 'login') || str_contains($path, 'logout') || str_contains($path, 'password')) {
            return 'Auth';
        }
        if (str_contains($path, 'unit') || str_contains($path, 'siteSettings') || str_contains($path, 'adminList') || str_contains($path, 'staffList')) {
            return 'System';
        }

        return ucfirst($userType);
    }

    /**
     * Determine a clean, descriptive action title.
     */
    protected function determineActionName(Request $request, string $path, string $method): string
    {
        $routeName = $request->route() ? $request->route()->getName() : null;

        // Named route map
        $map = [
            // Storefront / Customer
            'store.welcome' => 'Visited Storefront Home',
            'store.products' => 'Browsed Products Catalog',
            'store.productDetails' => 'Viewed Product Details',
            'store.about' => 'Viewed About Us Page',
            'store.contact' => 'Viewed Contact Page',
            'store.contact.submit' => 'Submitted Custom Order Inquiry',
            'store.cart' => 'Viewed Shopping Cart',
            'store.cart.add' => 'Added Item to Cart',
            'store.cart.update' => 'Updated Cart Quantity',
            'store.cart.remove' => 'Removed Item from Cart',
            'store.checkout' => 'Viewed Checkout Page',
            'store.checkout.initialize' => 'Initiated Storefront Checkout',
            'store.checkout.success' => 'Completed Order Payment',
            'store.checkout.failed' => 'Encountered Payment Failure',
            'customer.account' => 'Viewed Customer Account',
            'customer.account.orders.show' => 'Viewed Order Details',
            'customer.account.orders.requery' => 'Requeried Order Payment Status',
            'customer.account.profile' => 'Viewed Account Profile',
            'customer.account.profile.update' => 'Updated Account Profile',
            'customer.account.addresses' => 'Viewed Customer Addresses',
            'customer.login' => 'Viewed Customer Login Form',
            'customer.login.submit' => 'Customer Login',
            'customer.register' => 'Viewed Customer Registration Form',
            'customer.register.submit' => 'Customer Registration',
            'customer.logout' => 'Customer Logout',

            // Admin & Staff ERP
            'processSale' => 'Processed POS Sale',
            'sales.void' => 'Voided POS Sale',
            'salesHistory' => 'Viewed Sales History',
            'newProduct' => 'Created New Product',
            'updateProduct' => 'Updated Product',
            'deleteProduct' => 'Deleted Product',
            'newRecipe' => 'Created New Recipe',
            'updateRecipe' => 'Updated Recipe',
            'deleteRecipe' => 'Deleted Recipe',
            'produce' => 'Recorded Production Batch',
            'productionHistory' => 'Viewed Production History',
            'newStockIn' => 'Recorded Stock Purchase (Stock In)',
            'newIngredient' => 'Added New Ingredient',
            'updateIngredient' => 'Updated Ingredient',
            'deleteIngredient' => 'Deleted Ingredient',
            'newUnit' => 'Created Measurement Unit',
            'updateUnit' => 'Updated Measurement Unit',
            'deleteUnit' => 'Deleted Measurement Unit',
            'updateSiteInfo' => 'Updated Site Configuration',
            'updateProfile' => 'Updated User Profile',
            'updatePassword' => 'Changed Account Password',
            'newAdmin' => 'Created Administrator Account',
            'deleteAdmin' => 'Deleted Administrator Account',
            'newStaff' => 'Created Staff Account',
            'deleteStaff' => 'Deleted Staff Account',
            'deleteCustomer' => 'Deleted Customer Account',
            'customers.orders' => 'Viewed Customer Order History',
            'home' => 'Viewed Dashboard Overview',
        ];

        if ($routeName && isset($map[$routeName])) {
            return $map[$routeName];
        }

        // Special storefront URL matches
        if ($path === '' || $path === '/') {
            return 'Visited Storefront Home';
        }
        if ($request->is('products/*')) {
            $slug = $request->segment(2);
            $slugTitle = ucwords(str_replace(['-', '_'], ' ', $slug));
            return "Viewed Product: {$slugTitle}";
        }
        if ($request->is('checkout/success/*')) {
            return 'Completed Order Payment: ' . $request->segment(3);
        }
        if ($request->is('checkout/failed/*')) {
            return 'Payment Failed: ' . $request->segment(3);
        }

        // Fallback friendly formatting
        $segments = explode('/', $path);
        $last = end($segments);
        $friendlyLast = ucwords(str_replace(['-', '_'], ' ', $last));

        return match ($method) {
            'POST' => "Created / Submitted {$friendlyLast}",
            'PUT', 'PATCH' => "Updated {$friendlyLast}",
            'DELETE' => "Deleted {$friendlyLast}",
            default => "Viewed {$friendlyLast}",
        };
    }
}
