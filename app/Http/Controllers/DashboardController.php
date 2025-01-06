<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // Show the dashboard
    public function index()
    {
        try {
            // Get the Firebase database instance
            $database = app('firebase')->createDatabase();
            $ticketsReference = $database->getReference('tickets');
            $tickets = $ticketsReference->getValue();

            // If no tickets found, set an empty array
            if ($tickets === null) {
                $tickets = [];
            } else {
                // Include the ticket_id in the tickets data
                foreach ($tickets as $key => $ticket) {
                    $tickets[$key]['ticket_id'] = $key; // The Firebase key will be used as the ticket_id
                }
                // Define a priority order (Low, Medium, High)
                $priorityOrder = ['Low' => 1, 'Medium' => 2, 'High' => 3];

                // Sort tickets by priority level
                usort($tickets, function ($a, $b) use ($priorityOrder) {
                    return $priorityOrder[$b['priority_level']] <=> $priorityOrder[$a['priority_level']];
                });
            }

            // Return the dashboard view with the tickets
            return view('dashboard', ['tickets' => $tickets]);
        } catch (\Exception $e) {
            // Log the exception error
            Log::error('Error fetching tickets: ' . $e->getMessage());

            // Return error message to the user
            return redirect()->back()->with('error', 'Error fetching tickets: ' . $e->getMessage());
        }
    }
    public function updateTicketStatus(Request $request, $ticketId)
    {
        // Validate form input
        $request->validate([
            'status' => 'required|string',
        ]);

        try {
            // Get the Firebase database instance
            $database = app('firebase')->createDatabase();
            $ticketsReference = $database->getReference('tickets');

            // Update the ticket status in Firebase
            $ticketsReference->getChild($ticketId)->update([
                'status' => $request->status,
            ]);

            // Return success message to the user
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the exception error
            Log::error('Error updating ticket status: ' . $e->getMessage());

            // Return error message to the user
            return response()->json(['success' => false, 'message' => 'Error updating ticket status: ' . $e->getMessage()]);
        }
    }

}
