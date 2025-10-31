<?php
// index.php
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>License API</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h1>License API</h1>
    <p>Endpoints:</p>
    <ul>
      <li><code>/check.php</code> - POST (JSON) register / heartbeat with X-Signature header</li>
      <li><code>/redirect.php?token=...</code> - one-time link</li>
      <li><code>/admin.php</code> - admin UI</li>
    </ul>
  </div>
</body>
</html>
