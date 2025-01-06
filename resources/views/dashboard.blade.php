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
  <title>Dashboard</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

<body>
  <div class="flex justify-between items-center p-4 ">
    <h1 class="text-xl font-bold  text-gray-900 md:text-2xl dark:text-white">Welcome to the
      Dashboard</h1>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="text-white bg-red-600 rounded-md text-sm p-2">Logout</button>
    </form>
  </div>


  @include('table', ['tickets' => $tickets])
</body>

</html>