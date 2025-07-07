<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$currentUserId = $_SESSION['user_id'];
$result = $conn->query("SELECT id, username, fullname, email FROM users WHERE id != $currentUserId");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Management</title>
<link rel="stylesheet" href="css/style.css" />
<style>
  .manage-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 30px;
  }
  .manage-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 12px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.3s ease;
    position: relative;
  }
  .manage-btn:hover {
    background: rgba(255, 255, 255, 0.25);
  }
  .dropdown-content {
    background: rgba(255, 255, 255, 0.07);
    border-radius: 12px;
    padding: 20px;
    max-width: 500px;
    margin: 0 auto;
    color: white;
    box-shadow: 0 8px 32px rgba(0,0,0,0.6);
    display: none;
  }
  .dropdown-active {
    display: block;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    color: white;
  }
  th, td {
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
  }
  form input, form button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border-radius: 8px;
    border: none;
    font-size: 1rem;
  }
  form button {
    background-color: #00d2ff;
    color: black;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  form button:hover {
    background-color: #00a8cc;
  }
  .delete-btn {
    background: #ff4d4d;
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  .delete-btn:hover {
    background: #ff1a1a;
  }
  .back-link {
    display: block;
    text-align: center;
    margin-top: 30px;
    color: #00d2ff;
    font-weight: 600;
  }
  #deletePopup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  .popup-box {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.6);
    color: white;
    text-align: center;
    max-width: 350px;
    width: 90%;
  }
  .popup-buttons {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    gap: 20px;
  }
  .popup-btn {
    flex: 1;
    padding: 10px 0;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
  }
  .confirm-btn {
    background: #ff4d4d;
    color: white;
  }
  .cancel-btn {
    background: rgba(255, 255, 255, 0.15);
    color: white;
  }
  .popup-btn:hover {
    opacity: 0.9;
  }
  .message {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
  }
</style>
</head>
<body>
<div class="container">
  <h2>User Management</h2>
  <div class="manage-buttons">
    <button id="btnAddUser" class="manage-btn">➕ Add User ▼</button>
    <button id="btnDeleteUser" class="manage-btn">🗑️ Delete User ▼</button>
  </div>

  <!-- Add User Dropdown -->
  <div id="addUserDropdown" class="dropdown-content">
    <form id="addUserForm" autocomplete="off">
      <input type="text" name="fullname" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="text" name="phone" placeholder="Phone Number" required />
      <input type="text" name="passport_number" placeholder="Passport Number" required />
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Add User</button>
      <p id="addUserMsg" class="message"></p>
    </form>
  </div>

  <!-- Delete User Dropdown -->
  <div id="deleteUserDropdown" class="dropdown-content">
    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr><th>Username</th><th>Full Name</th><th>Email</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php while($user = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($user['username']); ?></td>
            <td><?= htmlspecialchars($user['fullname']); ?></td>
            <td><?= htmlspecialchars($user['email']); ?></td>
            <td>
              <form method="POST" action="delete_user.php" class="delete-user-form">
                <input type="hidden" name="user_id" value="<?= $user['id']; ?>" />
                <button type="submit" class="delete-btn">Delete</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No other users found.</p>
    <?php endif; ?>
  </div>
  <a href="dashboard.php" class="back-link">⬅️ Back to Dashboard</a>
</div>

<!-- Custom Delete Confirmation Popup -->
<div id="deletePopup">
  <div class="popup-box">
    <p>Are you sure you want to delete this user?</p>
    <div class="popup-buttons">
      <button id="confirmDelete" class="popup-btn confirm-btn">Delete</button>
      <button id="cancelDelete" class="popup-btn cancel-btn">Cancel</button>
    </div>
  </div>
</div>


<script>
  const btnAddUser = document.getElementById('btnAddUser');
  const btnDeleteUser = document.getElementById('btnDeleteUser');
  const addUserDropdown = document.getElementById('addUserDropdown');
  const deleteUserDropdown = document.getElementById('deleteUserDropdown');

  btnAddUser.addEventListener('click', () => {
    addUserDropdown.classList.toggle('dropdown-active');
    deleteUserDropdown.classList.remove('dropdown-active');
  });
  btnDeleteUser.addEventListener('click', () => {
    deleteUserDropdown.classList.toggle('dropdown-active');
    addUserDropdown.classList.remove('dropdown-active');
  });

  // Delete popup logic
  const deletePopup = document.getElementById('deletePopup');
  const confirmDeleteBtn = document.getElementById('confirmDelete');
  const cancelDeleteBtn = document.getElementById('cancelDelete');
  let formToDelete = null;

  document.querySelectorAll('.delete-user-form .delete-btn').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      formToDelete = button.closest('form');
      deletePopup.style.display = 'flex';
    });
  });

  confirmDeleteBtn.addEventListener('click', () => {
    if (formToDelete) formToDelete.submit();
  });
  cancelDeleteBtn.addEventListener('click', () => {
    deletePopup.style.display = 'none';
    formToDelete = null;
  });
  deletePopup.addEventListener('click', e => {
    if (e.target === deletePopup) {
      deletePopup.style.display = 'none';
      formToDelete = null;
    }
  });

  // AJAX Add User
  document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('register_process.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      document.getElementById('addUserMsg').innerText = data;
      this.reset();
    })
    .catch(() => {
      document.getElementById('addUserMsg').innerText = '❌ Error submitting form';
    });
  });
</script>

</body>
</html>
