<?php
session_start();
include 'config.php';
include 'partner_tiers.php';

$activePage = 'partners';

// Must be logged in to join or switch partnership tiers
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Generate a CSRF token for this session if one doesn't already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$userId = $_SESSION['user_id'];
$error = "";

// Fetch partnership
$existingStmt = $conn->prepare("SELECT tier, price_paid, created_at FROM partners WHERE user_id = ?");
$existingStmt->bind_param("i", $userId);
$existingStmt->execute();
$existingResult = $existingStmt->get_result();
$existingPartner = $existingResult->fetch_assoc();
$existingStmt->close();

// POST -- join for the first time, or replace the existing tier (upgrade/downgrade)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Reject if the CSRF token is missing or doesn't match
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid request. Please go back and try again.");
    }

    $tierName = $_POST['tier_name'] ?? '';
    $tier = findPartnerTierByName($tierName);

    if (!$tier) {
        $error = "That partnership tier could not be found. Please select a tier again.";
    } else {
        // ON DUPLICATE KEY UPDATE replaces the existing row for this user
        $upsertStmt = $conn->prepare(
            "INSERT INTO partners (user_id, tier, price_paid) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE tier = VALUES(tier), price_paid = VALUES(price_paid), created_at = CURRENT_TIMESTAMP"
        );
        $upsertStmt->bind_param("iss", $userId, $tier['name'], $tier['price']);

        if ($upsertStmt->execute()) {
            $upsertStmt->close();
            $conn->close();

            $action = $existingPartner ? 'switched' : 'joined';
            header("Location: account.php?partner=" . $action);
            exit();
        } else {
            $error = "Something went wrong processing your partnership. Please try again.";
        }
        $upsertStmt->close();
    }
}

$tierName = $_GET['tier'] ?? '';
$tier = findPartnerTierByName($tierName);

$isSameTier = $existingPartner && $tier && $existingPartner['tier'] === $tier['name'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - Partner Registration</title>

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

    <?php if (!$tier): ?>

      <!-- Tier picker -->
      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[550px] px-12 py-14">

        <h1 class="font-eras text-[32px] text-brand-mid font-black text-center mb-2">
          Choose Your Tier
        </h1>

        <?php if ($existingPartner): ?>
          <p class="font-consolas text-[14px] text-brand-dark/70 text-center mb-8">
            You're currently an <strong><?php echo htmlspecialchars($existingPartner['tier']); ?></strong> partner. Pick a tier below to switch.
          </p>
        <?php else: ?>
          <p class="font-consolas text-[14px] text-brand-dark/70 text-center mb-8">
            Select the partnership tier you'd like to join.
          </p>
        <?php endif; ?>

        <?php if ($error): ?>
          <p class="font-consolas text-[14px] text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3 text-center mb-6">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <div class="flex flex-col gap-4">
          <?php foreach ($partnerTiers as $t): ?>
            <?php $isCurrent = $existingPartner && $existingPartner['tier'] === $t['name']; ?>
            <a href="partner_reg.php?tier=<?php echo urlencode($t['name']); ?>"
              class="flex justify-between items-center border rounded-xl px-6 py-4 transition-colors duration-300 <?php echo $isCurrent ? 'border-brand-mid bg-brand-mid/10' : 'border-brand-dark/20 hover:bg-brand-mid/10'; ?>">
              <span class="font-eras text-[20px] text-brand-mid font-black">
                <?php echo htmlspecialchars($t['name']); ?>
                <?php if ($isCurrent): ?>
                  <span class="font-consolas text-[12px] text-brand-dark/60 font-normal ml-2">(Current)</span>
                <?php endif; ?>
              </span>
              <span class="font-consolas text-[16px] text-brand-dark font-bold">
                <?php echo $t['price'] === '0' ? 'Free' : '₱' . htmlspecialchars($t['price']); ?>
              </span>
            </a>
          <?php endforeach; ?>
        </div>

      </div>

    <?php elseif ($isSameTier): ?>

      <!-- Already on this exact tier -->
      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14 text-center">
        <h1 class="font-eras text-[28px] text-brand-mid font-black mb-4">
          You're Already <?php echo htmlspecialchars($tier['name']); ?>
        </h1>
        <p class="font-consolas text-[14px] text-brand-dark mb-8">
          No need to purchase again. Pick a different tier if you'd like to switch.
        </p>
        <a href="partner_reg.php" class="font-consolas text-[16px] text-brand-light bg-brand-dark rounded-full py-3 px-8 inline-block transition-colors duration-300 hover:bg-brand-mid mb-3">
          Choose a Different Tier
        </a>
        <br>
        <a href="account.php" class="font-consolas text-[14px] text-brand-dark underline">
          Go to My Account
        </a>
      </div>

    <?php else: ?>

      <!-- Confirm purchase or switch -->
      <div class="bg-brand-light rounded-[25px] shadow-2xl w-[500px] px-12 py-14">

        <h1 class="font-eras text-[32px] text-brand-mid font-black text-center mb-2">
          <?php echo $existingPartner ? 'Confirm Your Switch' : 'Confirm Your Partnership'; ?>
        </h1>
        <p class="font-consolas text-[14px] text-brand-dark/70 text-center mb-8">
          <?php if ($existingPartner): ?>
            Switching from <strong><?php echo htmlspecialchars($existingPartner['tier']); ?></strong> to the tier below.
          <?php else: ?>
            Review your tier before confirming.
          <?php endif; ?>
        </p>

        <?php if ($error): ?>
          <p class="font-consolas text-[14px] text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3 text-center mb-6">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <div class="border border-brand-dark/20 rounded-xl p-6 mb-8">
          <p class="font-consolas text-[12px] text-brand-dark/60 mb-1">
            Partner Tier
          </p>
          <h2 class="font-eras text-[24px] text-brand-mid font-black mb-2">
            <?php echo htmlspecialchars($tier['name']); ?>
          </h2>
          <p class="font-consolas text-[13px] text-brand-dark/70 mb-4">
            <?php echo htmlspecialchars($tier['description']); ?>
          </p>
          <p class="font-eras text-[30px] text-brand-dark font-black">
            <?php echo $tier['price'] === '0' ? 'Free' : '₱' . htmlspecialchars($tier['price']); ?>
          </p>
          <p class="font-consolas text-[11px] text-brand-dark/60">
            One-time payment. <?php echo $existingPartner ? 'This replaces your current tier.' : 'No recurring charges.'; ?>
          </p>
        </div>

        <form action="partner_reg.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="tier_name" value="<?php echo htmlspecialchars($tier['name']); ?>">

          <button type="submit"
            class="font-consolas text-[18px] text-brand-light bg-brand-dark rounded-full py-3 w-full transition-colors duration-300 hover:bg-brand-mid">
            <?php echo $existingPartner ? 'Confirm Switch' : 'Confirm Partnership'; ?>
          </button>
        </form>

        <a href="partner_reg.php"
          class="font-consolas text-[16px] text-brand-dark border-2 border-brand-dark rounded-full py-3 w-full mt-3 text-center block transition-colors duration-300 hover:bg-brand-dark hover:text-brand-light">
          Back
        </a>

      </div>

    <?php endif; ?>

  </section>

</body>
</html>