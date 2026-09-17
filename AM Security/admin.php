<?php
session_start();
include 'config.php';

// Must be logged in at all
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//only appears if admin
if (empty($_SESSION['is_admin'])) {
    header("Location: home.php");
    exit();
}

$activePage = 'admin';

// Fetch user
$usersResult = $conn->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY created_at DESC");
$users = $usersResult->fetch_all(MYSQLI_ASSOC);

// Fetch order and user in order by date
$ordersResult = $conn->query("
    SELECT orders.id, orders.plan_name, orders.plan_section, orders.price_paid, orders.created_at, users.username
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
$orders = $ordersResult->fetch_all(MYSQLI_ASSOC);

// ── Fetch every subscription, joined with the username that holds it ────
$subsResult = $conn->query("
    SELECT subscriptions.plan_name, subscriptions.plan_section, subscriptions.purchased_at, subscriptions.expires_at, users.username
    FROM subscriptions
    JOIN users ON subscriptions.user_id = users.id
    ORDER BY subscriptions.expires_at ASC
");
$subscriptions = $subsResult->fetch_all(MYSQLI_ASSOC);

// Fetch partners and user in order by date
$partnersResult = $conn->query("
    SELECT partners.tier, partners.price_paid, partners.created_at, users.username
    FROM partners
    JOIN users ON partners.user_id = users.id
    ORDER BY partners.created_at DESC
");
$partners = $partnersResult->fetch_all(MYSQLI_ASSOC);

$conn->close();

// ── Compute summary stats ────────────────────────────────────────────────
$totalUsers = count($users);
$totalOrders = count($orders);
$totalPartners = count($partners);

$totalRevenue = 0;
foreach ($orders as $order) {
    $totalRevenue += (float) str_replace(',', '', $order['price_paid']);
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - Admin</title>

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
              red: '#ff0000',
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

  <!-- Overview Section -->
  <section class="bg-brand-dark py-20">
    <div class="mx-[130px]">
      <h1 class="font-eras text-[50px] text-brand-light font-black mb-10">
        Admin Dashboard
      </h1>

      <div class="grid grid-cols-4 gap-6">
        <div class="bg-brand-light rounded-xl p-8">
          <p class="font-consolas text-[13px] text-brand-dark/60 mb-2">Total Users</p>
          <p class="font-eras text-[36px] text-brand-mid font-black"><?php echo $totalUsers; ?></p>
        </div>
        <div class="bg-brand-light rounded-xl p-8">
          <p class="font-consolas text-[13px] text-brand-dark/60 mb-2">Total Orders</p>
          <p class="font-eras text-[36px] text-brand-mid font-black"><?php echo $totalOrders; ?></p>
        </div>
        <div class="bg-brand-light rounded-xl p-8">
          <p class="font-consolas text-[13px] text-brand-dark/60 mb-2">Total Partners</p>
          <p class="font-eras text-[36px] text-brand-mid font-black"><?php echo $totalPartners; ?></p>
        </div>
        <div class="bg-brand-light rounded-xl p-8">
          <p class="font-consolas text-[13px] text-brand-dark/60 mb-2">Total Revenue</p>
          <p class="font-eras text-[36px] text-brand-mid font-black">₱<?php echo number_format($totalRevenue); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Users Table -->
  <section class="bg-brand-light py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[36px] text-brand-mid font-black mb-8">
        Users
      </h2>

      <?php if (empty($users)): ?>
        <p class="font-consolas text-[16px] text-brand-dark/60">No users yet.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full font-consolas text-brand-dark text-[15px]">
            <thead>
              <tr class="border-b-2 border-brand-dark/30 text-left">
                <th class="pb-3 font-bold">Username</th>
                <th class="pb-3 font-bold">Email</th>
                <th class="pb-3 font-bold">Role</th>
                <th class="pb-3 font-bold">Joined</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <tr class="border-b border-brand-dark/10">
                  <td class="py-3"><?php echo htmlspecialchars($user['username']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($user['email']); ?></td>
                  <td class="py-3">
                    <?php if ($user['is_admin']): ?>
                      <span class="font-bold text-brand-red">Admin</span>
                    <?php else: ?>
                      User
                    <?php endif; ?>
                  </td>
                  <td class="py-3"><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Orders Table -->
  <section class="bg-brand-mid py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[36px] text-brand-dark font-black mb-8">
        Orders
      </h2>

      <?php if (empty($orders)): ?>
        <p class="font-consolas text-[16px] text-brand-dark/70">No orders yet.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full font-consolas text-brand-dark text-[15px]">
            <thead>
              <tr class="border-b-2 border-brand-dark/30 text-left">
                <th class="pb-3 font-bold">Username</th>
                <th class="pb-3 font-bold">Plan</th>
                <th class="pb-3 font-bold">Section</th>
                <th class="pb-3 font-bold">Price Paid</th>
                <th class="pb-3 font-bold">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <tr class="border-b border-brand-dark/10">
                  <td class="py-3"><?php echo htmlspecialchars($order['username']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($order['plan_name']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($order['plan_section']); ?></td>
                  <td class="py-3">₱<?php echo htmlspecialchars($order['price_paid']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Subscriptions Table -->
  <section class="bg-brand-light py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[36px] text-brand-mid font-black mb-8">
        Subscriptions
      </h2>
 
      <?php if (empty($subscriptions)): ?>
        <p class="font-consolas text-[16px] text-brand-dark/60">No subscriptions yet.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full font-consolas text-brand-dark text-[15px]">
            <thead>
              <tr class="border-b-2 border-brand-dark/30 text-left">
                <th class="pb-3 font-bold">Username</th>
                <th class="pb-3 font-bold">Plan</th>
                <th class="pb-3 font-bold">Purchased</th>
                <th class="pb-3 font-bold">Expires</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($subscriptions as $sub): ?>
                <?php $isExpired = strtotime($sub['expires_at']) < time(); ?>
                <tr class="border-b border-brand-dark/10">
                  <td class="py-3"><?php echo htmlspecialchars($sub['username']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($sub['plan_name']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($sub['purchased_at']); ?></td>
                  <td class="py-3">
                    <?php echo htmlspecialchars($sub['expires_at']); ?>
                    <?php if ($isExpired): ?>
                      <span class="font-bold text-red-600 ml-2">Expired</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Partners Table -->
  <section class="bg-brand-dark py-20">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[36px] text-brand-light font-black mb-8">
        Partners
      </h2>

      <?php if (empty($partners)): ?>
        <p class="font-consolas text-[16px] text-brand-light/60">No partners yet.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full font-consolas text-brand-light text-[15px]">
            <thead>
              <tr class="border-b-2 border-brand-light/30 text-left">
                <th class="pb-3 font-bold">Username</th>
                <th class="pb-3 font-bold">Tier</th>
                <th class="pb-3 font-bold">Partner Since</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($partners as $partner): ?>
                <tr class="border-b border-brand-light/10">
                  <td class="py-3"><?php echo htmlspecialchars($partner['username']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($partner['tier']); ?></td>
                  <td class="py-3"><?php echo htmlspecialchars($partner['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>