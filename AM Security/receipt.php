<?php
session_start();
include 'config.php';

$activePage = '';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$orderId = $_GET['order_id'] ?? '';
$order = null;

if ($orderId) {
    // IMPORTANT: also check user_id matches the logged-in session.
    // Without this, changing the order_id in the URL would let anyone
    // view anyone else's order/receipt.
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - Receipt</title>

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

    <?php if (!$order): ?>

      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14 text-center">
        <h1 class="font-eras text-[28px] text-brand-mid font-black mb-4">Order Not Found</h1>
        <p class="font-consolas text-[14px] text-brand-dark mb-8">
          We couldn't find that order under your account.
        </p>
        <a href="account.php" class="font-consolas text-[16px] text-brand-light bg-brand-dark rounded-full py-3 px-8 inline-block transition-colors duration-300 hover:bg-brand-mid">
          Go to My Account
        </a>
      </div>

    <?php else: ?>

      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14 text-center">

        <div class="text-brand-mid text-[50px] mb-4">&#10003;</div>

        <h1 class="font-eras text-[28px] text-brand-mid font-black mb-2">
          Purchase Confirmed
        </h1>

        <div class="border border-brand-dark/20 rounded-xl p-6 mb-8 text-left">
          <p class="font-consolas text-[12px] text-brand-dark/60 mb-1">
            <?php echo htmlspecialchars($order['plan_section']); ?> Plan
          </p>
          <h2 class="font-eras text-[22px] text-brand-mid font-black mb-2">
            <?php echo htmlspecialchars($order['plan_name']); ?>
          </h2>
          <p class="font-eras text-[26px] text-brand-dark font-black">
            ₱<?php echo htmlspecialchars($order['price_paid']); ?>
          </p>
          <p class="font-consolas text-[12px] text-brand-dark/60 mt-2">
            Purchased on <?php echo htmlspecialchars($order['created_at']); ?>
          </p>
        </div>

        <a href="home.php" class="font-consolas text-[16px] text-brand-light bg-brand-dark rounded-full py-3 px-8 inline-block transition-colors duration-300 hover:bg-brand-mid">
          Back to Home
        </a>

      </div>

    <?php endif; ?>

  </section>

</body>
</html>