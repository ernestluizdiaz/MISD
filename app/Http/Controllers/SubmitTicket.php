<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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

        // Prepare the ticket data
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
            $database = app('firebase')->createDatabase();
            $ticketReference = $database->getReference('tickets')->push($data);

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
                                ['text' => $request->description],  // Use description from form
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
                $ai_solution = $solution['candidates'][0]['content']['parts'][0]['text'];
            } else {
                // If no content found, fallback message
                $ai_solution = 'AI did not generate a solution.';
            }


            // Add AI solution to the ticket
            $ticketReference->update([
                'ai_solution' => $ai_solution,
            ]);

            // Return the success view with ticket details and AI solution
            return view('ticketSubmitted', [
                'ticket' => $data,
                'ai_solution' => $ai_solution,
            ]);

        } catch (\Exception $e) {
            // Log the exception error
            Log::error('Error submitting ticket: ' . $e->getMessage());

            // Return error message to the user
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
