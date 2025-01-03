<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

<body class="bg-gray-50">
  @include('nav')
  <section class="mt-8">
    <div class="flex flex-col max-w-md w-full p-8 shadow-xl rounded-xl border-2 mx-auto">
      <div class="w-full md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Create an account
          </h1>
          <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('register') }}"
            onsubmit="return validateForm()">
            @csrf
            <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
              <input type="email" name="email" id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
            </div>
            <div>
              <label for="password"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
              <input type="password" name="password" id="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                minlength="6" required>
              <p id="passwordError" class="text-sm text-red-600 mt-1 hidden">Password must be at least 6 characters
                long.</p>
            </div>
            <div>
              <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                password</label>
              <input type="password" name="confirm-password" id="confirm-password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
              <p id="confirmPasswordError" class="text-sm text-red-600 mt-1 hidden">Password do not match.</p>
            </div>


            <button type="submit"
              class="w-full text-white bg-blue-700 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create
              an account</button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Already have an account? <a href="{{ route('login') }}"
                class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
            </p>
          </form>
        </div>
        <script>
          function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            let isValid = true;

            // Check password length
            if (password.length < 6) {
              document.getElementById('passwordError').classList.remove('hidden');
              isValid = false;
            } else {
              document.getElementById('passwordError').classList.add('hidden');
            }

            // Check if passwords match
            if (password !== confirmPassword) {
              document.getElementById('confirmPasswordError').classList.remove('hidden');
              isValid = false;
            } else {
              document.getElementById('confirmPasswordError').classList.add('hidden');
            }

            return isValid;
          }
        </script>

      </div>
    </div>

  </section>

</body>

</html>