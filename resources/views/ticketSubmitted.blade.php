<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Submitted</title>
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
            Ticket Submitted Successfully
          </h1>

          <p class="text-lg font-semibold mt-4">Ticket Details:</p>
          <ul class="list-disc list-inside">
            <li><strong>Name:</strong> {{ $ticket['first_name'] }} {{ $ticket['last_name'] }}</li>
            <li><strong>Email:</strong> {{ $ticket['email'] }}</li>
            <li><strong>Category:</strong> {{ $ticket['issue_category'] }}</li>
            <li><strong>Priority:</strong> {{ $ticket['priority_level'] }}</li>
            <li><strong>Problem:</strong> {{ $ticket['description'] }}</li>
          </ul>

          <p class="text-lg font-semibold">Solution:</p>
          <p class="pl-5">{{ $ai_solution }}</p>

          <p class="text-red-500 text-center mt-4">
            Your ticket has been successfully submitted. Our IT support team will be notified and will address your
            issue as soon as possible. In the meantime, please try the suggested solution that may assist you.
          </p>

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