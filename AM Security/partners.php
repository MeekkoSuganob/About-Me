<?php
session_start();
include 'config.php';

$activePage = 'partners';
include 'partner_tiers.php';

$currentPartnerTier = null;
if (isset($_SESSION['username'])) {
    $userId = $_SESSION['user_id'];
    $partnerStmt = $conn->prepare("SELECT tier FROM partners WHERE user_id = ?");
    $partnerStmt->bind_param("i", $userId);
    $partnerStmt->execute();
    $partnerResult = $partnerStmt->get_result();
    $currentPartnerRow = $partnerResult->fetch_assoc();
    $partnerStmt->close();
    $currentPartnerTier = $currentPartnerRow['tier'] ?? null;
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

  <?php include 'header.php'; ?>

  <!-- Intro Section -->
  <section class="bg-brand-dark py-24">
    <div class="mx-[130px]">
      <h1 class="font-eras text-[60px] text-brand-light font-black leading-tight mb-6">
        Partner With AM Security
      </h1>
      <p class="font-consolas text-[22px] text-brand-light/80 leading-relaxed max-w-3xl">
        Whether you're reselling, integrating, or managing security for your own clients, we have a partnership path built for you.
      </p>
    </div>
  </section>

  <!-- Partner Programs -->
  <section id="partner-programs" class="bg-brand-light py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-mid font-black mb-6">
        Partner Programs
      </h2>
      <p class="font-consolas text-[20px] text-brand-mid leading-relaxed mb-12 max-w-3xl">
        Our tiered program rewards partners as they grow with us — the more you sell, the more support and margin you unlock. Joining is a one-time payment; Authorized tier is free. You can switch tiers at any time.
      </p>

      <div class="grid grid-cols-4 gap-6">
        <?php foreach ($partnerTiers as $tier): ?>
          <?php $isCurrent = $currentPartnerTier === $tier['name']; ?>
          <div class="bg-brand-mid/10 rounded-xl p-6 flex flex-col <?php echo $isCurrent ? 'ring-2 ring-brand-mid' : ''; ?>">
            <h3 class="font-eras text-[22px] text-brand-mid font-black mb-2">
              <?php echo htmlspecialchars($tier['name']); ?>
              <?php if ($isCurrent): ?>
                <span class="font-consolas text-[11px] text-brand-dark/60 font-normal align-middle">(Current)</span>
              <?php endif; ?>
            </h3>
            <p class="font-consolas text-[14px] text-brand-dark leading-relaxed mb-4 flex-grow">
              <?php echo htmlspecialchars($tier['description']); ?>
            </p>
            <p class="font-eras text-[22px] text-brand-dark font-black mb-4">
              <?php echo $tier['price'] === '0' ? 'Free' : '₱' . htmlspecialchars($tier['price']); ?>
            </p>

            <?php if ($isCurrent): ?>
              <span class="font-consolas text-[14px] text-brand-mid font-bold border-2 border-brand-mid rounded-lg py-2 text-center">
                Current Plan
              </span>
            <?php else: ?>
              <a href="partner_reg.php?tier=<?php echo urlencode($tier['name']); ?>"
                class="font-consolas text-[14px] text-brand-light bg-brand-mid rounded-lg py-2 text-center transition-colors duration-300 hover:bg-brand-dark">
                <?php echo $currentPartnerTier ? 'Switch to ' . htmlspecialchars($tier['name']) : 'Select ' . htmlspecialchars($tier['name']); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Service Providers -->
  <section id="service-providers" class="bg-brand-mid py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-dark font-black mb-6">
        Service Providers
      </h2>
      <p class="font-consolas text-[20px] text-brand-dark leading-relaxed mb-10 max-w-3xl">
        Built for MSPs and MSSPs who need to manage security across many client accounts without the overhead.
      </p>

      <ul class="font-consolas text-[18px] text-brand-dark space-y-6">
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-light mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">Multi-tenant dashboard</span> — manage every client account from a single console.</span>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-light mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">Usage-based billing</span> — pay monthly per active device instead of upfront licensing.</span>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-light mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">White-label options</span> — offer protection under your own brand.</span>
        </li>
      </ul>
    </div>
  </section>

  <!-- Strategic Technology -->
  <section id="strategic-technology" class="bg-brand-light py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-mid font-black mb-6">
        Strategic Technology
      </h2>
      <p class="font-consolas text-[20px] text-brand-mid leading-relaxed mb-10 max-w-3xl">
        For software and hardware vendors looking to integrate real-time threat detection into their own products.
      </p>

      <ul class="font-consolas text-[18px] text-brand-dark space-y-6">
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-mid mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">API & SDK access</span> — build our detection engine directly into your platform.</span>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-mid mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">Pre-built integrations</span> — connect with popular SIEM tools, firewalls, and cloud platforms.</span>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-4 h-4 rounded-full bg-brand-mid mt-1.5 flex-shrink-0"></div>
          <span><span class="font-bold">Joint solution briefs</span> — co-sell to shared customers with a unified story.</span>
        </li>
      </ul>
    </div>
  </section>

  <!-- Become a Partner -->
  <section id="become-a-partner" class="bg-brand-dark py-24">
    <div class="mx-[130px] text-center">
      <h2 class="font-eras text-[50px] text-brand-light font-black mb-6">
        Become a Partner
      </h2>
      <p class="font-consolas text-[20px] text-brand-light/80 leading-relaxed mb-10 max-w-2xl mx-auto">
        Interested in any of the paths above? Choose your tier and get started today.
      </p>
      <a href="partner_reg.php"
        class="font-consolas text-[20px] text-brand-dark bg-brand-light rounded-xl py-4 px-12 inline-block transition-colors duration-300 hover:bg-brand-mid hover:text-brand-light">
        Get in Touch
      </a>
    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>