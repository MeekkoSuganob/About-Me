<?php
session_start();
include 'db_connect.php';

$error = "";
$success = "";

// Show a success message if redirected here after registering
if (isset($_GET['registered'])) {
    $success = "Account created successfully! Please log in.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {

        // Look up the user by username
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Correct password - start the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                $stmt->close();
                $conn->close();

                header("Location: home.php");
                exit();
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            // Same message as a wrong password, so we don't reveal which part was wrong
            $error = "Incorrect username or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security</title>
  
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

  <?php $activePage = 'login'; include 'header.php'; ?>

<!-- Log In Section -->
<section class="bg-brand-dark flex items-center justify-center py-32">

  <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14">

    <!-- Title -->
    <h1 class="font-eras text-[40px] text-brand-mid font-black text-center mb-2">
      AM Security
    </h1>

    <!-- Subtitle -->
    <p class="font-consolas text-[20px] text-brand-dark font-bold text-center mb-6">
      Enter your login credentials
    </p>

    <?php if ($success): ?>
      <p class="font-consolas text-[14px] text-green-700 bg-green-100 border border-green-300 rounded-lg px-4 py-3 text-center mb-6">
        <?php echo htmlspecialchars($success); ?>
      </p>
    <?php endif; ?>

    <?php if ($error): ?>
      <p class="font-consolas text-[14px] text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3 text-center mb-6">
        <?php echo htmlspecialchars($error); ?>
      </p>
    <?php endif; ?>

    <form action="login.php" method="POST" class="flex flex-col gap-6">

      <!-- Username -->
      <div class="flex flex-col gap-2">
        <label for="username" class="font-consolas text-[16px] text-brand-dark font-bold">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your Username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <!-- Password -->
      <div class="flex flex-col gap-2">
        <label for="password" class="font-consolas text-[16px] text-brand-dark font-bold">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your Password"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <!-- Submit Button -->
      <button type="submit"
        class="font-consolas text-[20px] text-brand-light bg-brand-mid rounded-full py-3 mt-4 transition-colors duration-300 hover:bg-brand-dark">
        Login
      </button>

    </form>

    <!-- Create Account Link -->
    <p class="font-consolas text-[16px] text-brand-dark text-center mt-8">
      Don't have an account yet?
      <a href="register.php" class="text-brand-mid font-bold hover:text-brand-dark transition-colors duration-300">Register</a>
    </p>

  </div>

</section>

      <!-- Footer -->
<footer class="bg-brand-light pt-10 pb-6">
  <div class="mx-[130px] flex justify-center gap-20 mb-8">

    <!-- Services Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Services</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Consumer</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Business</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Enterprise</a></li>
      </ul>
    </div>

    <!-- Partners Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Partners</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Partner Programs</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Service Providers</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Strategic Technology</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">Become a Partner</a></li>
      </ul>
    </div>

    <!-- Support Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Support</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="tel:+639051535155" class="hover:text-brand-dark transition-colors duration-300">+63 0905-153-5155</a></li>
        <li><a href="mailto:AMSecurity@gmail.com" class="hover:text-brand-dark transition-colors duration-300">AMSecurity@gmail.com</a></li>
        <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">FAQs</a></li>
      </ul>
    </div>

  </div>

  <!-- Copyright -->
  <div class="mx-[130px]">
    <p class="font-consolas text-[16px] text-brand-mid">@ 2026 AM Security</p>
  </div>
</footer>

</body>
</html>