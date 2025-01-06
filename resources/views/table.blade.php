<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user'])) {
  // Redirect to login page if not logged in
  header('Location: /login');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ticket Management</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 max-w-7xl mx-auto mt-8">
  <div class="relative flex flex-col w-full h-full text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">
    <!-- Tabs -->
    <div class="relative p-4 overflow-hidden text-gray-700 bg-[#f4f5f7] rounded-none bg-clip-border">
      <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
        <div class="block w-full overflow-hidden md:w-max">
          <nav>
            <ul role="tablist" class="relative flex flex-row p-1 rounded-lg bg-blue-gray-50 bg-opacity-60">
              <li role="tab"
                class="tab-item relative flex items-center justify-center w-full px-4 py-2 font-bold text-center text-gray-700 cursor-pointer bg-white rounded-lg"
                data-value="all">
                All
              </li>
              <li role="tab"
                class="tab-item relative flex items-center justify-center w-full px-4 py-2 font-bold text-center text-gray-700 cursor-pointer bg-[#f4f5f7] rounded-lg"
                data-value="pending">
                Pending
              </li>
              <li role="tab"
                class="tab-item relative flex items-center justify-center w-full px-4 py-2 font-bold text-center text-gray-700 cursor-pointer bg-[#f4f5f7] rounded-lg"
                data-value="completed">
                Completed
              </li>
            </ul>
          </nav>
        </div>

        <!-- Refresh Button -->
        <div class="flex items-center mt-4 md:mt-0">
          <button id="refreshButton" class="p-2">
            <i id="refreshIcon" class="fa-sharp fa-solid fa-arrow-rotate-right"></i>
          </button>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('refreshButton').addEventListener('click', function () {
        const refreshIcon = document.getElementById('refreshIcon');

        // Replace the icon with the spinning version
        refreshIcon.className = 'fa-sharp fa-solid fa-arrow-rotate-right fa-spin text-gray-700';

        // Simulate loading time and then refresh the page
        setTimeout(() => {
          location.reload(); // Refresh the page
        }, 2000); // Adjust the delay as needed
      });
    </script>

    <!-- Tickets Table -->
    <div class="pb-6 pt-0 px-0 xl-round shadow-lg">
      <div class="hidden md:block">
        <table class="w-full text-left table-auto min-w-max">
          <thead>
            <tr>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Ticket Respondents</th>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Issue Category</th>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Priority</th>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Status</th>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Date Created</th>
              <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tickets as $ticket)
        <tr class="ticket-row" data-status="{{ strtolower($ticket['status']) }}"
          data-ticket-id="{{ $ticket['ticket_id'] }}">
          <td class="p-4 border-b border-blue-gray-50">
          <div class="flex flex-col">
            <p class="text-sm font-normal text-black">{{ $ticket['first_name'] }} {{ $ticket['last_name'] }}</p>
            <p class="text-sm font-normal text-gray-500">{{ $ticket['email'] }}</p>
          </div>
          </td>
          <td class="p-4 border-b border-blue-gray-50">{{ $ticket['issue_category'] }}</td>
          <td class="p-4 border-b border-blue-gray-50">
          <div class="flex items-center">
            <div
            class="h-2.5 w-2.5 rounded-full me-2 {{ $ticket['priority_level'] == 'Low' ? 'bg-green-500' : ($ticket['priority_level'] == 'Medium' ? 'bg-yellow-500' : 'bg-red-500') }}">
            </div>
            <span class="text-xs font-bold uppercase">{{ $ticket['priority_level'] }}</span>
          </div>
          </td>
          <td class="p-4 border-b border-blue-gray-50">
          <div
            class="px-2 py-1 text-xs font-bold uppercase rounded-md {{ $ticket['status'] == 'Completed' ? 'text-green-900 ' : 'text-yellow-500  ' }}">
            {{ $ticket['status'] }}
          </div>
          </td>
          <td class="p-4 border-b border-blue-gray-50">
          {{ \Carbon\Carbon::parse($ticket['submitted_at'])->format('d/m/y H:i') }}

          </td>
          <td class="p-4 border-b border-blue-gray-50">
          <button
            class="focus:outline-none py-2 px-2 bg-blue-500 text-white text-sm rounded transition-transform duration-200 hover:scale-110"
            data-ticket-id="{{ $ticket['ticket_id'] }}" @if($ticket['status'] == 'Completed') disabled
      class="opacity-50" @endif>
            {{ $ticket['status'] == 'Completed' ? 'Resolved' : 'Resolve' }}
          </button>
          </td>


        </tr>
      @endforeach
          </tbody>

        </table>
      </div>

      <!-- Mobile Cards -->
      <div class="block md:hidden">
        @foreach($tickets as $ticket)
      <div class="ticket-row border rounded-lg p-4 mb-4 shadow-sm bg-white"
        data-status="{{ strtolower($ticket['status']) }}">
        <div class="flex flex-col mb-2">
        <p class="text-sm font-bold text-black">{{ $ticket['first_name'] }} {{ $ticket['last_name'] }}</p>
        <p class="text-sm font-normal text-gray-500">{{ $ticket['email'] }}</p>
        </div>
        <p class="text-sm mb-1"><strong>Issue:</strong> {{ $ticket['issue_category'] }}</p>
        <p class="text-sm mb-1"><strong>Priority:</strong>
        <span
          class="h-2.5 w-2.5 inline-block rounded-full {{ $ticket['priority_level'] == 'Low' ? 'bg-green-500' : ($ticket['priority_level'] == 'Medium' ? 'bg-yellow-500' : 'bg-red-500') }}">
        </span>
        {{ $ticket['priority_level'] }}
        </p>
        <p class="text-sm mb-1"><strong>Status:</strong>
        <span
          class="px-2 py-1 text-xs font-bold uppercase rounded-md {{ $ticket['status'] == 'Completed' ? 'text-green-900 bg-green-500/20' : 'text-yellow-900 bg-yellow-500/20' }}">
          {{ $ticket['status'] }}
        </span>
        </p>
        <p class="text-sm"><strong>Date Created:</strong>
        {{ \Carbon\Carbon::parse($ticket['submitted_at'])->format('d/m/y H:i') }}
        </p>

        <!-- Resolve Button for Mobile -->
        <div class="mt-4">
        <button
          class="w-full py-2 px-4 bg-blue-500 text-white text-sm rounded transition-transform duration-200 hover:scale-110"
          data-ticket-id="{{ $ticket['ticket_id'] }}" @if($ticket['status'] == 'Completed') disabled
      class="opacity-50" @endif>
          {{ $ticket['status'] == 'Completed' ? 'Resolved' : 'Resolve' }}
        </button>
        </div>
      </div>
    @endforeach
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          // Handle the "Resolved" buttons for both desktop and mobile
          const resolveButtons = document.querySelectorAll('button[data-ticket-id]');
          resolveButtons.forEach(button => {
            const ticketRow = button.closest('.ticket-row');
            const statusElement = ticketRow.querySelector('.status');

            // Check if the ticket is already completed
            if (statusElement && statusElement.textContent.trim() === 'Completed') {
              button.disabled = true;  // Disable the button
              button.classList.add('opacity-50');  // Optional: Add a fade effect for the disabled button
              button.textContent = 'Resolved';  // Optionally change the button text
            }

            button.addEventListener('click', function () {
              const ticketId = button.getAttribute('data-ticket-id');
              console.log(`Ticket ID: ${ticketId}`);  // Log the ticket ID

              // Make an AJAX request to update the status of the ticket
              fetch(`/updateTicketStatus/${ticketId}`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                  status: 'Completed' // Set the new status
                })
              })
                .then(response => {
                  console.log('Response status:', response.status); // Log the response status
                  return response.json();  // Convert response to JSON
                })
                .then(data => {
                  console.log('Response data:', data);  // Log the response data
                  if (data.success) {
                    alert('Ticket resolved!');
                    // Update the UI with the new status
                    const ticketRow = button.closest('.ticket-row');  // Find the row containing the button
                    const statusElement = ticketRow.querySelector('.status');

                    if (statusElement) {
                      statusElement.textContent = 'Completed';  // Update the status text in the UI
                      statusElement.classList.remove('text-yellow-500');
                      statusElement.classList.add('text-green-900');
                    } else {
                      console.error('Status element not found for ticket:', ticketId);
                    }

                    // Disable the button and change its appearance
                    button.disabled = true;
                    button.classList.add('opacity-50');
                    button.textContent = 'Resolved';  // Optionally change the button text

                    // Reload the page to reflect changes
                    setTimeout(() => {
                      location.reload();  // Refresh the page
                    }, 1000);  // Delay to allow UI update before refreshing
                  } else {
                    alert(`Error: ${data.message || 'Unable to resolve the ticket.'}`);
                  }
                })
                .catch(error => {
                  console.error('Error:', error);  // Log the error if there is any
                  alert('There was an error resolving the ticket.');
                });
            });
          });

          // Handle the tab functionality (for mobile view as well)
          const tabs = document.querySelectorAll('.tab-item');
          const tickets = document.querySelectorAll('.ticket-row');

          tabs.forEach(tab => {
            tab.addEventListener('click', function () {
              const status = this.dataset.value;

              // Reset tab styles
              tabs.forEach(item => {
                item.classList.remove('bg-white', 'rounded-lg');
                item.classList.add('bg-[#f4f5f7]');
              });

              // Highlight active tab
              this.classList.add('bg-white', 'rounded-lg');
              this.classList.remove('bg-[#f4f5f7]');

              // Filter tickets
              tickets.forEach(ticket => {
                const ticketStatus = ticket.dataset.status;
                ticket.style.display = (status === 'all' || ticketStatus === status) ? '' : 'none';
              });
            });
          });
        });
      </script>


</body>

</html>