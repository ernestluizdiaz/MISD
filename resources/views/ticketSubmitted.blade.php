<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Support Ticket</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

<body class="bg-gray-50">
  @include('nav')

  <section class="mt-8">
    <div class="flex flex-col max-w-md w-full p-8 shadow-xl rounded-xl border-2 mx-auto bg-white">
      <div class="w-full md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="space-y-4 md:space-y-6 sm:px-8">
          <h1
            class="text-center text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Your Support Ticket Has Been Received
          </h1>

          <p class="text-lg font-semibold mt-4">Dear {{ $data['first_name'] }} {{ $data['last_name'] }},</p>
          <p>Thank you for submitting a support ticket. We have received your request and assigned it ticket number
            <b>"{{ $ticketNumber }}"</b>.
          </p>


          <p class="text-lg font-semibold">Ticket Concern:</p>
          <blockquote class="pl-5 text-sm italic">{{ $data['description'] }}</blockquote>

          <h3 class="text-lg font-semibold mt-6">Solution:</h3>
          <p class="pl-5">{{ $aiSolution }}</p>

          <p class="mt-4">We will investigate your request and contact you shortly with an update. Please expect a
            response within 24-48 hours.</p>

          <div class="text-center mt-6">
            <a href="/" class="inline-block bg-blue-600 text-white p-2.5 rounded-md text-sm hover:bg-blue-700">
              Back Home
            </a>
          </div>

        </div>
      </div>
    </div>
  </section>
</body>

</html>