<?php
session_start();
include 'config.php';
include 'plans.php';

$activePage = '';

// Must be logged in to buy anything
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Generate a CSRF token for this session if one doesn't already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";

// ── Handle the actual purchase (POST) ───────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Reject if the CSRF token is missing or doesn't match
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid request. Please go back and try again.");
    }

    // Look up the plan server-side by name -- the price is NEVER trusted
    // from the form itself, only the plan name, which we then re-verify here.
    $planName = $_POST['plan_name'] ?? '';
    $plan = findPlanByName($planName);

    if (!$plan) {
        $error = "That plan could not be found. Please go back and select a plan again.";
    } else {
        $userId = $_SESSION['user_id'];

        $insertStmt = $conn->prepare("INSERT INTO orders (user_id, plan_name, plan_section, price_paid) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("isss", $userId, $plan['name'], $plan['section'], $plan['price']);

        if ($insertStmt->execute()) {
            $orderId = $insertStmt->insert_id;
            $insertStmt->close();

            // Create the matching subscription record. Every plan is
            // 1 year for now (matches the "*For the first year" copy on
            // every pricing card) -- swap this to a per-plan billing
            // cycle later once monthly vs. yearly plans are decided.
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));

            $subStmt = $conn->prepare("INSERT INTO subscriptions (user_id, plan_name, plan_section, expires_at) VALUES (?, ?, ?, ?)");
            $subStmt->bind_param("isss", $userId, $plan['name'], $plan['section'], $expiresAt);
            $subStmt->execute();
            $subStmt->close();

            $conn->close();

            header("Location: receipt.php?order_id=" . $orderId);
            exit();
        } else {
            $error = "Something went wrong processing your order. Please try again.";
        }
        $insertStmt->close();
    }
}

// ── Look up the plan for display (GET) ──────────────────────────────────
$planName = $_GET['plan'] ?? '';
$plan = findPlanByName($planName);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - Checkout</title>

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

  <?php include 'header.php'; ?>

  <section class="bg-brand-dark flex items-center justify-center py-32">

    <?php if (!$plan): ?>

      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14 text-center">
        <h1 class="font-eras text-[32px] text-brand-mid font-black mb-4">Plan Not Found</h1>
        <p class="font-consolas text-[16px] text-brand-dark mb-8">
          We couldn't find the plan you selected. Please go back to Services and pick a plan again.
        </p>
        <a href="services.php" class="font-consolas text-[18px] text-brand-light bg-brand-dark rounded-full py-3 px-10 inline-block transition-colors duration-300 hover:bg-brand-mid">
          Back to Services
        </a>
      </div>

    <?php else: ?>

      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14">

        <h1 class="font-eras text-[32px] text-brand-mid font-black text-center mb-2">
          Confirm Your Order
        </h1>
        <p class="font-consolas text-[14px] text-brand-dark/70 text-center mb-8">
          Review your plan before confirming.
        </p>

        <?php if ($error): ?>
          <p class="font-consolas text-[14px] text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3 text-center mb-6">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <div class="border border-brand-dark/20 rounded-xl p-6 mb-8">
          <p class="font-consolas text-[12px] text-brand-dark/60 mb-1">
            <?php echo htmlspecialchars($plan['section']); ?> Plan
          </p>
          <h2 class="font-eras text-[24px] text-brand-mid font-black mb-2">
            <?php echo htmlspecialchars($plan['name']); ?>
          </h2>
          <p class="font-consolas text-[13px] text-brand-dark/70 mb-4">
            <?php echo htmlspecialchars($plan['devices']); ?>
          </p>
          <p class="font-eras text-[30px] text-brand-dark font-black">
            ₱<?php echo htmlspecialchars($plan['price']); ?>
          </p>
          <p class="font-consolas text-[11px] text-brand-dark/60">
            *For the first year. Plus applicable sales tax.
          </p>
        </div>

        <form action="checkout.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="plan_name" value="<?php echo htmlspecialchars($plan['name']); ?>">

          <button type="submit"
            class="font-consolas text-[18px] text-brand-light bg-brand-dark rounded-full py-3 w-full transition-colors duration-300 hover:bg-brand-mid">
            Confirm Purchase
          </button>
        </form>

        <a href="services.php"
          class="font-consolas text-[16px] text-brand-dark border-2 border-brand-dark rounded-full py-3 w-full mt-3 text-center block transition-colors duration-300 hover:bg-brand-dark hover:text-brand-light">
          Back
        </a>

        <p class="font-consolas text-[12px] text-brand-dark/50 text-center mt-6">
          This is a simulated checkout for demonstration purposes -- no real payment is processed.
        </p>

      </div>

    <?php endif; ?>

  </section>

</body>
</html>