<?php
$activePage = 'partners';
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
        Our tiered program rewards partners as they grow with us — the more you sell, the more support and margin you unlock.
      </p>

      <div class="grid grid-cols-4 gap-6">
        <div class="bg-brand-mid/10 rounded-xl p-6">
          <h3 class="font-eras text-[22px] text-brand-mid font-black mb-2">Authorized</h3>
          <p class="font-consolas text-[14px] text-brand-dark leading-relaxed">
            Entry-level access to our reseller pricing and sales resources.
          </p>
        </div>
        <div class="bg-brand-mid/10 rounded-xl p-6">
          <h3 class="font-eras text-[22px] text-brand-mid font-black mb-2">Silver</h3>
          <p class="font-consolas text-[14px] text-brand-dark leading-relaxed">
            Deal registration and priority technical support.
          </p>
        </div>
        <div class="bg-brand-mid/10 rounded-xl p-6">
          <h3 class="font-eras text-[22px] text-brand-mid font-black mb-2">Gold</h3>
          <p class="font-consolas text-[14px] text-brand-dark leading-relaxed">
            Co-marketing funds and dedicated partner manager.
          </p>
        </div>
        <div class="bg-brand-mid/10 rounded-xl p-6">
          <h3 class="font-eras text-[22px] text-brand-mid font-black mb-2">Platinum</h3>
          <p class="font-consolas text-[14px] text-brand-dark leading-relaxed">
            Top-tier margins, joint go-to-market planning, and executive support.
          </p>
        </div>
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
        Interested in any of the paths above? Reach out and our partnerships team will help you find the right fit.
      </p>
      <a href="#"
        class="font-consolas text-[20px] text-brand-dark bg-brand-light rounded-xl py-4 px-12 inline-block transition-colors duration-300 hover:bg-brand-mid hover:text-brand-light">
        Get in Touch
      </a>
    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>