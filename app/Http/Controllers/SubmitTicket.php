<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;

class SubmitTicket extends Controller
{
    public function submitTicket(Request $request)
    {
        // Validate form input
        $request->validate([
            'floating_first_name' => 'required|string|max:255',
            'floating_last_name' => 'required|string|max:255',
            'floating_email' => 'required|email',
            'who_is_submitting' => 'required|string',
            'issue_category' => 'required|string',
            'priority_level' => 'required|string',
            'description' => 'required|string|max:500',
        ]);

        // Prepare the data
        $data = [
            'first_name' => $request->floating_first_name,
            'last_name' => $request->floating_last_name,
            'email' => $request->floating_email,
            'submitter' => $request->who_is_submitting,
            'issue_category' => $request->issue_category,
            'priority_level' => $request->priority_level,
            'description' => $request->description,
            'submitted_at' => now()->toDateTimeString(),
        ];

        try {
            // Get the Firebase database instance
            $database = app('firebase')->createDatabase(); // Use createDatabase() instead of getDatabase()
            $database->getReference('tickets')->push($data);

            return redirect()->back()->with('success', 'Ticket submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
