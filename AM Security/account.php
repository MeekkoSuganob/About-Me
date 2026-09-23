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

// Generate a CSRF token for this session if one doesn't already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_account'])) {

    // Reject the request if the CSRF token is missing or doesn't match
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid request. Please go back and try again.");
    }

    // orders, subscriptions, and partners all have ON DELETE CASCADE now,
    // so deleting the user alone cleans up every related row automatically.
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

// fetch email
$userId = $_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userRow = $userResult->fetch_assoc();
$userEmail = $userRow['email'] ?? '';
$userStmt->close();

// Fetch transaction history
$ordersStmt = $conn->prepare("SELECT plan_name, plan_section, price_paid, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$ordersStmt->bind_param("i", $userId);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
$orders = $ordersResult->fetch_all(MYSQLI_ASSOC);
$ordersStmt->close();

// Fetch subscriptions and license keys
$subsStmt = $conn->prepare("SELECT plan_name, plan_section, purchased_at, expires_at, license_key FROM subscriptions WHERE user_id = ? ORDER BY expires_at DESC");
$subsStmt->bind_param("i", $userId);
$subsStmt->execute();
$subsResult = $subsStmt->get_result();
$subscriptions = $subsResult->fetch_all(MYSQLI_ASSOC);
$subsStmt->close();

// Fetch partnership tier, if any (one-time purchase, so at most one row)
$partnerStmt = $conn->prepare("SELECT tier, price_paid, created_at FROM partners WHERE user_id = ?");
$partnerStmt->bind_param("i", $userId);
$partnerStmt->execute();
$partnerResult = $partnerStmt->get_result();
$partner = $partnerResult->fetch_assoc();
$partnerStmt->close();

$conn->close();

$activePage = 'account';
$partnerAction = $_GET['partner'] ?? null; // 'joined' or 'switched'
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

  <?php include 'header.php'; ?>

  <!-- Profile Section -->
  <section class="bg-brand-dark py-20">
    <div class="mx-[130px]">

      <?php if ($partnerAction && $partner): ?>
        <p class="font-consolas text-[14px] text-green-400 bg-green-900/30 border border-green-500/40 rounded-lg px-4 py-3 mb-8 inline-block">
          <?php if ($partnerAction === 'switched'): ?>
            You've switched to the <?php echo htmlspecialchars($partner['tier']); ?> tier!
          <?php else: ?>
            You're now an <?php echo htmlspecialchars($partner['tier']); ?> partner!
          <?php endif; ?>
        </p>
      <?php endif; ?>

      <div class="flex justify-between items-center">

        <!-- Username & Email -->
        <div>
          <h1 class="font-eras text-[50px] text-brand-light font-black mb-2">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </h1>
          <p class="font-consolas text-[20px] text-brand-light/70">
            <?php echo htmlspecialchars($userEmail); ?>
          </p>
        </div>

        <!-- Partner Tier Badge -->
        <?php if ($partner): ?>
          <div class="bg-brand-light/10 border border-brand-light/30 rounded-xl px-8 py-6 text-right">
            <p class="font-consolas text-[13px] text-brand-light/60 mb-1">Partner Tier</p>
            <p class="font-eras text-[26px] text-brand-light font-black">
              <?php echo htmlspecialchars($partner['tier']); ?>
            </p>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- My Subscriptions Section -->
  <section class="bg-brand-mid py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[40px] text-brand-dark font-black mb-10">
        My Subscriptions
      </h2>

      <?php if (empty($subscriptions)): ?>

        <p class="font-consolas text-[16px] text-brand-dark/70">
          No active subscriptions. Purchase a plan from Services to get your license key.
        </p>

      <?php else: ?>

        <div class="grid grid-cols-2 gap-6">
          <?php foreach ($subscriptions as $sub): ?>
            <?php $isExpired = strtotime($sub['expires_at']) < time(); ?>
            <div class="bg-brand-light rounded-xl p-8">
              <p class="font-consolas text-[12px] text-brand-dark/60 mb-1">
                <?php echo htmlspecialchars($sub['plan_section']); ?> Plan
              </p>
              <h3 class="font-eras text-[24px] text-brand-mid font-black mb-4">
                <?php echo htmlspecialchars($sub['plan_name']); ?>
              </h3>

              <p class="font-consolas text-[12px] text-brand-dark/60 mb-1">License Key</p>
              <p class="font-consolas text-[16px] text-brand-dark font-bold tracking-wide mb-4">
                <?php echo htmlspecialchars($sub['license_key'] ?? 'N/A'); ?>
              </p>

              <p class="font-consolas text-[13px] text-brand-dark/70">
                Expires <?php echo htmlspecialchars($sub['expires_at']); ?>
                <?php if ($isExpired): ?>
                  <span class="font-bold text-red-600 ml-2">Expired</span>
                <?php endif; ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>
    </div>
  </section>

  <!-- Transaction History Section -->
  <section class="bg-brand-light py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[40px] text-brand-mid font-black mb-10">
        Transaction History
      </h2>

      <?php if (empty($orders)): ?>

        <p class="font-consolas text-[16px] text-brand-dark/60">
          No purchases yet.
        </p>

      <?php else: ?>

        <table class="w-full font-consolas text-brand-dark text-[16px]">
          <thead>
            <tr class="border-b-2 border-brand-dark/30 text-left">
              <th class="pb-4 font-bold">Plan</th>
              <th class="pb-4 font-bold">Section</th>
              <th class="pb-4 font-bold">Price</th>
              <th class="pb-4 font-bold">Date Purchased</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
              <tr class="border-b border-brand-dark/10">
                <td class="py-4"><?php echo htmlspecialchars($order['plan_name']); ?></td>
                <td class="py-4"><?php echo htmlspecialchars($order['plan_section']); ?></td>
                <td class="py-4">₱<?php echo htmlspecialchars($order['price_paid']); ?></td>
                <td class="py-4"><?php echo htmlspecialchars($order['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      <?php endif; ?>
    </div>
  </section>

  <!-- Account Actions Section -->
  <section class="bg-brand-dark py-16">
    <div class="mx-[130px] flex gap-6">

      <a href="account.php?logout=1"
        class="flex-1 text-center font-consolas text-[18px] text-brand-light bg-brand-mid rounded-xl py-4 transition-colors duration-300 hover:bg-brand-dark border-2 border-brand-mid">
        Log Out
      </a>

      <form action="account.php" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" name="delete_account"
          class="w-full font-consolas text-[18px] text-brand-light bg-red-600 rounded-xl py-4 transition-colors duration-300 hover:bg-red-800">
          Delete Account
        </button>
      </form>

    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>