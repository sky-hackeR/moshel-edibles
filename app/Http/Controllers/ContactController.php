<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact()
    {
        $activeProducts = Product::where('is_active', true)
            ->whereHas('recipe')
            ->get();

        return view('contact', compact('activeProducts')); 
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

        Mail::send([], [], function ($message) use ($mailPayload) {
            $message->to('hello@moshedibles.com')
                    ->replyTo($mailPayload['clientEmail'], $mailPayload['clientName'])
                    ->subject('Order Specification: ' . strtok($mailPayload['items'], ','))
                    ->html("
                        <div style='font-family: sans-serif; padding: 20px; color: #333;'>
                            <h2 style='color: #B38B46;'>Mosh Edibles Custom Order Pipeline</h2>
                            <p><strong>Customer Name:</strong> {$mailPayload['clientName']}</p>
                            <p><strong>Email Address:</strong> {$mailPayload['clientEmail']}</p>
                            <p><strong>Phone Number:</strong> {$mailPayload['clientPhone']}</p>
                            <p><strong>Expected Delivery Target:</strong> {$mailPayload['deliveryDate']}</p>
                            <p><strong>Products Checked:</strong> {$mailPayload['items']}</p>
                            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                            <p><strong>Order Specifications & Custom Inquiries:</strong></p>
                            <p style='background: #f9f9f9; padding: 15px; border-left: 4px solid #B38B46; white-space: pre-wrap;'>{$mailPayload['specifications']}</p>
                        </div>
                    ");
        });

        return redirect()->back()->with('success', 'Your inquiry has been logged successfully! Our team will review your specs or new product request and reach out shortly.');
    }
}