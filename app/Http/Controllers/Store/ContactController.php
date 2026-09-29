<?php

namespace App\Http\Controllers\Store;

use App\Models\Product;
use App\Mail\Store\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;


class ContactController extends Controller
{
    // public function contact()
    // {
    //     $activeProducts = Product::where('is_active', true)
    //         ->whereHas('recipe')
    //         ->get();

    //     return view('contact', compact('activeProducts')); 
    // }

    public function contact(){
        $activeProducts = Product::where('is_active', true)
             ->whereHas('recipe')
             ->get();
        
        return view('store.contact', [
            'activeProducts' => $activeProducts,
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|max:150',
            'phone'          => 'required|string|max:30',
            'delivery_date'  => 'required|date|after_or_equal:today',
            'items'          => 'nullable|array',
            'specifications' => 'required|string|min:10',
        ]);

        $selectedItems = $request->input('items', []);
        $itemStringList = !empty($selectedItems) ? implode(', ', $selectedItems) : 'Custom Formulation Request / New Product Inquiry';

        $mailPayload = [
            'clientName'     => $data['name'],
            'clientEmail'    => $data['email'],
            'clientPhone'    => $data['phone'],
            'deliveryDate'   => $data['delivery_date'],
            'items'          => $itemStringList,
            'specifications' => $data['specifications']
        ];

        Mail::to('hello@moshedibles.com')->queue(new ContactInquiry($mailPayload));

        return redirect()->back()->with('success', 'Your inquiry has been logged successfully! Our team will review your specs or new product request and reach out shortly.');
    }
}