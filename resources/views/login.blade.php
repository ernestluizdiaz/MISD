<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="bg-gray-50">

    @include('nav')
    <section class="mt-8">
        <div class="flex flex-col max-w-md w-full p-8 shadow-xl rounded-xl border-2 mx-auto">
            <div class="w-full md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <form id="loginForm" action="{{ url('login') }}" method="POST" class="space-y-4 md:space-y-6">
                        @csrf
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                            <p id="emailError" class="text-sm text-red-600 hidden">Email does not exist.</p>
                        </div>

                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                            <p id="confirmPasswordError" class="text-sm text-red-600 hidden">Password is incorrect.</p>
                        </div>

                        <!-- Error message for password -->

                        <div class="text-right">
                            <a href="#"
                                class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Forgot
                                password?</a>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sign
                            in</button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">Don’t have an account yet? <a
                                href="{{ route('register') }}"
                                class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign up</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Handle form submission with AJAX
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this); // Get form data

            // Hide any previous error messages
            document.getElementById('confirmPasswordError').classList.add('hidden');
            document.getElementById('emailError').classList.add('hidden');

            fetch("{{ url('login') }}", {
                method: 'POST',
                body: formData, // Send form data
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Check the error type and display the appropriate message
                        if (data.error === 'Email does not exist.') {
                            document.getElementById('emailError').classList.remove('hidden');
                            document.getElementById('emailError').innerText = data.error;
                        } else if (data.error === 'Password is incorrect.') {
                            document.getElementById('confirmPasswordError').classList.remove('hidden');
                            document.getElementById('confirmPasswordError').innerText = data.error;
                        } else {
                            // General error handling
                            document.getElementById('confirmPasswordError').classList.remove('hidden');
                            document.getElementById('confirmPasswordError').innerText = data.error;
                        }
                    } else if (data.success) {
                        // Redirect to dashboard on successful login
                        window.location.href = '/dashboard';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

    </script>

</body>

</html>