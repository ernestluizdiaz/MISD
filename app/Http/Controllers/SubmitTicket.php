<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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

        try {
            // Prepare the ticket data
            $data = [
                'first_name' => $request->floating_first_name,
                'last_name' => $request->floating_last_name,
                'email' => $request->floating_email,
                'submitter' => $request->who_is_submitting,
                'issue_category' => $request->issue_category,
                'priority_level' => $request->priority_level,
                'description' => $request->description,
                'status' => 'Pending',
                'submitted_at' => now()->toDateTimeString(),
            ];

            // Get the Firebase database instance
            $database = app('firebase')->createDatabase();

            // Push the ticket data to Firebase and get a reference
            $ticketReference = $database->getReference('tickets')->push($data);

            // Get the ticket ID (using Firebase key as ticket number)
            $ticketId = $ticketReference->getKey();

            // Add ticket_id to the data array after storing it in Firebase
            $data['ticket_id'] = $ticketId;

            // Log the ticket description for debugging
            Log::info('Ticket Description:', ['description' => $request->description]);

            // Call Gemini API using Guzzle
            $client = new Client();
            $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'If you are unable to offer a solution to the user\'s concern, inform them that no solution is available, but assure them that their ticket has been successfully submitted. If you can provide a solution, please do so. ' . $request->description],
                            ],
                        ],
                    ],
                ],
            ]);

            // Get the raw response from the stream
            $body = $response->getBody();
            $rawResponse = $body->getContents();  // Extract the content from the stream

            // Log the raw response for debugging
            Log::info('Raw Gemini API Response:', ['response' => $rawResponse]);

            // Decode the response body to get the structured data
            $solution = json_decode($rawResponse, true);

            // Log the decoded response for debugging
            Log::info('Gemini API Response:', ['response' => $solution]);

            // Check if the response contains generated content
            if (isset($solution['candidates'][0]['content']['parts'][0]['text'])) {
                $aiSolution = $solution['candidates'][0]['content']['parts'][0]['text'];
            } else {
                // If no content found, fallback message
                $aiSolution = 'AI did not generate a solution.';
            }

            // Return the view with ticket ID, ticket data, and AI solution
            return view('ticketSubmitted', [
                'ticketId' => $ticketId,
                'data' => $data,
                'aiSolution' => $aiSolution,
            ]);

        } catch (\Exception $e) {
            // Log the exception error
            Log::error('Error submitting ticket: ' . $e->getMessage());

            // Return error message to the user
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function showTickets()
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

            // Return the view with the tickets data
            return view('table', ['tickets' => $tickets]);
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
    public function trackTicket(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Get the Firebase database instance
            $database = app('firebase')->createDatabase();
            $ticketsReference = $database->getReference('tickets');

            // Retrieve all tickets
            $tickets = $ticketsReference->getValue();

            // Filter tickets by the provided email
            $matchedTickets = [];
            foreach ($tickets as $key => $ticket) {
                if (isset($ticket['email']) && $ticket['email'] === $request->email) {
                    $ticket['ticket_id'] = $key; // Add ticket ID to the ticket data
                    $matchedTickets[] = $ticket;
                }
            }

            if (!empty($matchedTickets)) {
                return response()->json([
                    'tickets' => $matchedTickets,
                ]);
            } else {
                return response()->json(['error' => 'No tickets found for this email.']);
            }

        } catch (\Exception $e) {
            // Log the exception error
            Log::error('Error tracking tickets: ' . $e->getMessage());

            // Return error message
            return response()->json(['error' => 'Error fetching tickets: ' . $e->getMessage()]);
        }
    }

}
