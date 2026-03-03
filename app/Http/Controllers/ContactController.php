<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the contact form
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:hire_writer,buy_credit,inquiry_partnership,data_analysis',
            'service_type' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:100',
            'deadline' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'subject.required' => 'Please select an inquiry type.',
            'subject.in' => 'Please select a valid inquiry type.',
            'message.required' => 'Please describe your project requirements.',
            'message.min' => 'Please provide more details about your project (at least 10 characters).',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare contact data for email
            $contactData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'subject' => $request->input('subject'),
                'service_type' => $request->input('service_type'),
                'budget' => $request->input('budget'),
                'deadline' => $request->input('deadline'),
                'message' => $request->input('message'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()->format('Y-m-d H:i:s'),
            ];

            // Send email notification to admin
            $adminEmail = 'emrig4@gmail.com';
            
            Mail::to($adminEmail)->send(new ContactFormNotification($contactData));

            // Log successful submission
            Log::info('Contact form submitted successfully', [
                'email' => $contactData['email'],
                'subject' => $contactData['subject'],
                'service_type' => $contactData['service_type'] ?? 'N/A',
                'budget' => $contactData['budget'] ?? 'N/A',
                'ip_address' => $contactData['ip_address'],
                'timestamp' => now()
            ]);

            // Return success response
            return back()->with('success', 'Thank you for your inquiry! We have received your project details and will get back to you within 24 hours.');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'ip_address' => $request->ip(),
                'timestamp' => now()
            ]);

            // Return error response
            return back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.')
                ->withInput();
        }
    }

    /**
     * API endpoint for AJAX form submissions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitFormAjax(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:hire_writer,buy_credit,inquiry_partnership,data_analysis',
            'service_type' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:100',
            'deadline' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'subject.required' => 'Please select an inquiry type.',
            'subject.in' => 'Please select a valid inquiry type.',
            'message.required' => 'Please describe your project requirements.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fix the errors below.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare contact data for email
            $contactData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'subject' => $request->input('subject'),
                'service_type' => $request->input('service_type'),
                'budget' => $request->input('budget'),
                'deadline' => $request->input('deadline'),
                'message' => $request->input('message'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()->format('Y-m-d H:i:s'),
            ];

            // Send email notification to admin
            $adminEmail = 'emrig4@gmail.com';
            
            Mail::to($adminEmail)->send(new ContactFormNotification($contactData));

            // Log successful submission
            Log::info('Contact form submitted successfully via AJAX', [
                'email' => $contactData['email'],
                'subject' => $contactData['subject'],
                'service_type' => $contactData['service_type'] ?? 'N/A',
                'ip_address' => $contactData['ip_address'],
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your inquiry! We have received your project details and will get back to you within 24 hours.'
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form submission failed via AJAX', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'ip_address' => $request->ip(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again later.'
            ], 500);
        }
    }
}
