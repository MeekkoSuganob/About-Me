<?php
session_start();

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
      You are gay LoL.
    </p>

    <a href="account.php?logout=1"
      class="font-consolas text-[20px] text-brand-light bg-brand-mid rounded-full py-3 px-10 transition-colors duration-300 hover:bg-brand-dark inline-block">
      Log Out
    </a>

  </div>

</section>

</body>
</html>