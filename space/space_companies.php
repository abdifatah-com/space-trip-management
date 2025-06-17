<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>🚀 Space Companies</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .table-wrapper {
      margin-top: 30px;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 1rem;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    th {
      background-color: rgba(0, 210, 255, 0.3);
    }
    a {
      color: #82e9ff;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🚀 Space Companies Directory</h2>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Company</th>
            <th>Country</th>
            <th>Founded</th>
            <th>Website</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>SpaceX</td>
            <td>USA</td>
            <td>2002</td>
            <td><a href="https://www.spacex.com" target="_blank">spacex.com</a></td>
          </tr>
          <tr>
            <td>Blue Origin</td>
            <td>USA</td>
            <td>2000</td>
            <td><a href="https://www.blueorigin.com" target="_blank">blueorigin.com</a></td>
          </tr>
          <tr>
            <td>Virgin Galactic</td>
            <td>UK/USA</td>
            <td>2004</td>
            <td><a href="https://www.virgingalactic.com" target="_blank">virgingalactic.com</a></td>
          </tr>
          <tr>
            <td>Roscosmos</td>
            <td>Russia</td>
            <td>1992</td>
            <td>—</td>
          </tr>
          <tr>
            <td>NASA</td>
            <td>USA</td>
            <td>1958</td>
            <td><a href="https://www.nasa.gov" target="_blank">nasa.gov</a></td>
          </tr>
        </tbody>
      </table>
    </div>

    <a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
