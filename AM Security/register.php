<?php
include 'config.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {

        // Check if email is already registered
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        // Check if username is already taken
        $checkUsernameStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkUsernameStmt->bind_param("s", $username);
        $checkUsernameStmt->execute();
        $checkUsernameStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "An account with that email already exists.";
        } elseif ($checkUsernameStmt->num_rows > 0) {
            $error = "That username is already taken.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user using a prepared statement
            $insertStmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($insertStmt->execute()) {
                // Registration successful - redirect to login page
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
            $insertStmt->close();
        }
        $checkStmt->close();
        $checkUsernameStmt->close();
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

  <?php $activePage = ''; include 'header.php'; ?>

<!-- Register Section -->
<section class="bg-brand-dark flex items-center justify-center py-32">

  <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14">

    <!-- Title -->
    <h1 class="font-eras text-[40px] text-brand-mid font-black text-center mb-2">
      AM Security
    </h1>

    <!-- Subtitle -->
    <p class="font-consolas text-[20px] text-brand-dark font-bold text-center mb-6">
      Create your account
    </p>

    <?php if ($error): ?>
      <p class="font-consolas text-[14px] text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3 text-center mb-6">
        <?php echo htmlspecialchars($error); ?>
      </p>
    <?php endif; ?>

    <form action="register.php" method="POST" class="flex flex-col gap-6">

      <!-- Username -->
      <div class="flex flex-col gap-2">
        <label for="username" class="font-consolas text-[16px] text-brand-dark font-bold">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your Username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <!-- Email -->
      <div class="flex flex-col gap-2">
        <label for="email" class="font-consolas text-[16px] text-brand-dark font-bold">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your Email"
          value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <!-- Password -->
      <div class="flex flex-col gap-2">
        <label for="password" class="font-consolas text-[16px] text-brand-dark font-bold">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your Password"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <!-- Confirm Password -->
      <div class="flex flex-col gap-2">
        <label for="confirm-password" class="font-consolas text-[16px] text-brand-dark font-bold">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter your Password"
          class="font-consolas text-[16px] text-brand-dark border border-brand-mid/40 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-mid">
      </div>

      <button type="submit"
        class="font-consolas text-[20px] text-brand-light bg-brand-mid rounded-full py-3 mt-4 transition-colors duration-300 hover:bg-brand-dark">
        Submit
      </button>

    </form>

    <!-- Login Link -->
    <p class="font-consolas text-[16px] text-brand-dark text-center mt-8">
      Already have an account?
      <a href="login.php" class="text-brand-mid font-bold hover:text-brand-dark transition-colors duration-300">Login</a>
    </p>

  </div>

</section>

<?php include 'footer.php'; ?>
</body>
</html>