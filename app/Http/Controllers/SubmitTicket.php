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

            // Call Hugging Face API using Guzzle
            $client = new Client();
            $response = $client->post('https://api-inference.huggingface.co/models/Qwen/Qwen2.5-Coder-32B-Instruct', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
                ],
                'json' => [
                    'inputs' => $request->description,
                    'parameters' => [
                        'max_tokens' => 500,
                    ],
                ]
            ]);

            // Get the API response
            $body = $response->getBody();
            $solution = json_decode($body, true);


            // Check if the response contains generated text
            if (isset($solution[0]['generated_text'])) {
                $ai_solution = $solution[0]['generated_text'];
                $ai_solution = preg_replace('/^.*\?/', '', $ai_solution);
            } else {
                $ai_solution = 'No solution found.';
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
