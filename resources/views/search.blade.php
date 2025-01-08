<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Your Ticket</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

<body class="bg-gray-50">
  @include('nav')
  <h1 class="text-xl font-bold text-center text-gray-900 md:text-2xl dark:text-white mt-8">Track Your Ticket</h1>

  <form class="flex items-center max-w-lg mx-auto mt-4" method="POST" action="{{ route('trackTicket') }}">
    @csrf
    <div class="relative w-full">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4 text-gray-500 dark:text-gray-400"
          aria-hidden="true">
          <path
            d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
        </svg>
      </div>
      <input type="email" id="voice-search" name="email"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Your email address..." required />
    </div>
    <button type="submit"
      class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
      </svg>Search
    </button>
  </form>

  <section class="mt-8">
    <div class="flex flex-col max-w-md w-full p-8 shadow-xl rounded-xl border-2 mx-auto bg-white">
      <div class="w-full md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="space-y-4 md:space-y-6 sm:px-8">
          <h1
            class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Ticket Details:
          </h1>

          <div id="tickets-container" class="space-y-6 pt-4">
          </div>
        </div>

      </div>
    </div>
    </div>
  </section>


  <script>
    document.querySelector('form').addEventListener('submit', function (event) {
      event.preventDefault();

      const email = document.getElementById('voice-search').value;
      const token = document.querySelector('input[name="_token"]').value;

      fetch("{{ route('trackTicket') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify({ email: email }),
      })
        .then(response => response.json())
        .then(data => {
          const ticketsContainer = document.getElementById('tickets-container');
          ticketsContainer.innerHTML = ''; // Clear existing tickets

          if (data.error) {
            alert(data.error);
          } else if (data.tickets && data.tickets.length > 0) {
            data.tickets.forEach(ticket => {
              const ticketDiv = document.createElement('div');
              ticketDiv.className = "p-4 mb-5 bg-gray-100 rounded-lg border border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600";

              ticketDiv.innerHTML = `
            <p class="text-lg font-bold"><strong>Ticket ID:</strong> ${ticket.ticket_id}</p>
            <p class="text-lg"><strong>Name:</strong> ${ticket.first_name} ${ticket.last_name}</p>
            <p class="text-lg"><strong>Email:</strong> ${ticket.email}</p>
            <p class="text-lg"><strong>Issue Category:</strong> ${ticket.issue_category}</p>
            <p class="text-lg"><strong>Priority Level:</strong> <span style="font-weight: bold; color: ${ticket.priority_level === 'High'
                  ? 'red'
                  : ticket.priority_level === 'Medium'
                    ? '#eab308'
                    : 'green'
                };">${ticket.priority_level}</span></p>
            <p class="text-lg"><strong>Description:</strong> ${ticket.description}</p>
            <p class="text-lg"><strong>Status:</strong> <span style="font-weight: bold; color: ${ticket.status === 'Pending' ? '#eab308' : 'green'
                };">${ticket.status}</span></p>
            <p class="text-lg"><strong>Submitted At:</strong> ${ticket.submitted_at}</p>
          `;

              ticketsContainer.appendChild(ticketDiv);
            });
          } else {
            ticketsContainer.innerHTML = `<p class="text-center text-gray-600 dark:text-gray-400">No tickets found.</p>`;
          }
        })
        .catch(error => console.error('Error:', error));
    });

  </script>
</body>

</html>