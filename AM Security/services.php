<?php
$activePage = 'services';

// Plan data now lives in plans.php (shared with checkout.php) so prices
// can be looked up server-side rather than trusted from the browser.
include 'plans.php';

/** @var array $consumerPlans */
/** @var array $businessPlans */
/** @var array $enterprisePlans */

// ── Card renderer ────────────────────────────────────────────────────────
// Renders one pricing card. Called in a loop for each plan in each section.
function renderPricingCard($plan) {
    ?>
    <div class="bg-brand-light rounded-[25px] shadow-2xl p-10 flex flex-col h-full">

        <h3 class="font-eras text-[24px] text-brand-mid font-black leading-snug mb-2 min-h-[64px]">
            <?php echo htmlspecialchars($plan['name']); ?>
        </h3>

        <p class="font-consolas text-[13px] text-brand-dark/70 mb-4 min-h-[20px]">
            <?php echo htmlspecialchars($plan['devices']); ?>
        </p>

        <p class="font-consolas text-[14px] text-brand-dark leading-relaxed mb-6 min-h-[85px]">
            <?php echo htmlspecialchars($plan['description']); ?>
        </p>

        <div class="flex items-center gap-3 mb-1">
            <span class="font-consolas text-[15px] text-brand-dark/40 line-through">
                ₱<?php echo htmlspecialchars($plan['original_price']); ?>
            </span>
            <span class="font-consolas text-[12px] font-bold text-brand-light bg-brand-mid px-2 py-1 rounded">
                Save <?php echo htmlspecialchars($plan['save']); ?>
            </span>
        </div>

        <p class="font-eras text-[34px] text-brand-dark font-black mb-1">
            ₱<?php echo htmlspecialchars($plan['price']); ?>
        </p>

        <p class="font-consolas text-[11px] text-brand-dark/60 leading-snug mb-6">
            *For the first year.<br>Plus applicable sales tax.
        </p>

        <a href="checkout.php?plan=<?php echo urlencode($plan['name']); ?>" class="font-consolas text-[16px] text-brand-light bg-brand-dark rounded-xl py-3 text-center mb-3 transition-colors duration-300 hover:bg-brand-mid">
            Buy Now
        </a>

        <p class="font-consolas text-[11px] text-brand-dark/50 mb-6">
            30 days money back guarantee.
        </p>

        <?php if ($plan['previous_tier']): ?>
            <p class="font-consolas text-[13px] text-brand-dark font-bold mb-3">
                &larr; Everything from <?php echo htmlspecialchars($plan['previous_tier']); ?> and:
            </p>
        <?php else: ?>
            <p class="font-consolas text-[13px] text-brand-dark font-bold mb-3">
                Includes:
            </p>
        <?php endif; ?>

        <ul class="font-consolas text-[13px] text-brand-dark space-y-2">
            <?php foreach ($plan['features'] as $feature): ?>
                <li class="flex items-start gap-2">
                    <span class="text-brand-mid font-bold">&#10003;</span>
                    <span><?php echo htmlspecialchars($feature); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM Security - Services</title>

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

  <!-- Consumer Section -->
  <section id="consumer-section" class="bg-brand-dark py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-light font-black mb-2">Consumer</h2>
      <p class="font-consolas text-[18px] text-brand-light/80 mb-12">
        Protect what matters most — you, your family, and your devices.
      </p>
      <div class="grid grid-cols-<?php echo count($consumerPlans); ?> gap-6">
        <?php foreach ($consumerPlans as $plan): ?>
          <?php renderPricingCard($plan); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Business Section -->
  <section id="business-section" class="bg-brand-mid py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-dark font-black mb-2">Business</h2>
      <p class="font-consolas text-[18px] text-brand-dark/80 mb-12">
        Scalable protection built for teams of every size.
      </p>
      <div class="grid grid-cols-<?php echo count($businessPlans); ?> gap-6">
        <?php foreach ($businessPlans as $plan): ?>
          <?php renderPricingCard($plan); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Enterprise Section -->
  <section id="enterprise-section" class="bg-brand-dark py-24">
    <div class="mx-[130px]">
      <h2 class="font-eras text-[50px] text-brand-light font-black mb-2">Enterprise</h2>
      <p class="font-consolas text-[18px] text-brand-light/80 mb-12">
        Full-spectrum, custom-fit protection for large organizations.
      </p>
      <div class="grid grid-cols-<?php echo count($enterprisePlans); ?> gap-6">
        <?php foreach ($enterprisePlans as $plan): ?>
          <?php renderPricingCard($plan); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-brand-light pt-10 pb-6">
    <div class="mx-[130px] flex justify-center gap-20 mb-8">

      <!-- Services Column -->
      <div class="flex flex-col items-center text-center">
        <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Services</h3>
        <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
          <li><a href="services.php#consumer-section" class="hover:text-brand-dark transition-colors duration-300">Consumer</a></li>
          <li><a href="services.php#business-section" class="hover:text-brand-dark transition-colors duration-300">Business</a></li>
          <li><a href="services.php#enterprise-section" class="hover:text-brand-dark transition-colors duration-300">Enterprise</a></li>
        </ul>
      </div>

      <!-- Partners Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Partners</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="partners.php#partner-programs" class="hover:text-brand-dark transition-colors duration-300">Partner Programs</a></li>
        <li><a href="partners.php#service-providers" class="hover:text-brand-dark transition-colors duration-300">Service Providers</a></li>
        <li><a href="partners.php#strategic-technology" class="hover:text-brand-dark transition-colors duration-300">Strategic Technology</a></li>
        <li><a href="partners.php#become-a-partner" class="hover:text-brand-dark transition-colors duration-300">Become a Partner</a></li>
      </ul>
    </div>

      <!-- Support Column -->
      <div class="flex flex-col items-center text-center">
        <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Support</h3>
        <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
          <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">+63 0905-153-5155</a></li>
          <li><a href="#" class="hover:text-brand-dark transition-colors duration-300">AMSecurity@gmail.com</a></li>
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