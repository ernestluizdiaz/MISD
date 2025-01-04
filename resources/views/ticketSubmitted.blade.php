<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Submitted</title>
</head>

<body>
  <h1>Ticket Submitted Successfully</h1>
  <p><strong>Solution:</strong> {{ $ai_solution }}</p>
  <p>Ticket Details:</p>
  <ul>
    <li><strong>Name:</strong> {{ $ticket['first_name'] }} {{ $ticket['last_name'] }}</li>
    <li><strong>Email:</strong> {{ $ticket['email'] }}</li>
    <li><strong>Category:</strong> {{ $ticket['issue_category'] }}</li>
    <li><strong>Priority:</strong> {{ $ticket['priority_level'] }}</li>
    <li><strong>Description:</strong> {{ $ticket['description'] }}</li>
  </ul>
</body>

</html>