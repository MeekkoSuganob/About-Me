<?php
session_start();
include 'config.php';

// If nobody is logged in, send them to the login page instead
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_account'])) {
    $userId = $_SESSION['user_id'];

    $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $userId);
    $deleteStmt->execute();
    $deleteStmt->close();
    $conn->close();

    session_destroy();
    header("Location: home.php?deleted=1");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - My Account</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              light: '#fdfbd4',
              mid: '#6d8196',
              dark: '#1d2545',
            }
          },
          fontFamily: {
            eras: ['"Jost"', 'sans-serif'],
            consolas: ['Consolas', 'monospace'],
          }
        }
      }
    }
  </script>
</head>
<body class="bg-brand-dark text-brand-light font-consolas m-0 p-0">

  <?php $activePage = 'account'; include 'header.php'; ?>

<!-- Account Section -->
<section class="bg-brand-dark flex items-center justify-center py-32">

  <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14 text-center">

    <h1 class="font-eras text-[40px] text-brand-mid font-black mb-4">
      Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
    </h1>

    <p class="font-consolas text-[18px] text-brand-dark mb-10">
      This is your account page. Add subscription details, device management, or account settings here.
    </p>

    <div class="flex justify-center gap-6">
      <a href="account.php?logout=1"
        class="font-consolas text-[20px] text-brand-light bg-brand-mid rounded-full py-3 px-10 transition-colors duration-300 hover:bg-brand-dark inline-block">
        Log Out
      </a>

      <form action="account.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
        <button type="submit" name="delete_account"
          class="font-consolas text-[20px] text-brand-light bg-red-600 rounded-full py-3 px-10 transition-colors duration-300 hover:bg-red-800">
          Delete Account
        </button>
      </form>
    </div>

  </div>

</section>

</body>
</html>