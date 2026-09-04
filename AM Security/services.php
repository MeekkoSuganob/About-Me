<?php
$activePage = 'services';
?>
<!DOCTYPE html>
<html lang="en">
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

  <!-- Placeholder Content -->
  <section class="bg-brand-dark flex items-center justify-center py-48">
    <p class="font-eras text-[35px] text-brand-light text-center">
      This section is currently being worked on.
    </p>
  </section>

  <!-- Footer -->
<footer class="bg-brand-light pt-10 pb-6">
  <div class="mx-[130px] flex justify-center gap-20 mb-8">

    <!-- Services Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Services</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="services.php" class="hover:text-brand-dark transition-colors duration-300">Consumer</a></li>
        <li><a href="services.php" class="hover:text-brand-dark transition-colors duration-300">Business</a></li>
        <li><a href="services.php" class="hover:text-brand-dark transition-colors duration-300">Enterprise</a></li>
      </ul>
    </div>

    <!-- Partners Column -->
    <div class="flex flex-col items-center text-center">
      <h3 class="font-eras text-[20px] text-brand-mid font-black mb-3">Partners</h3>
      <ul class="font-consolas text-[14px] text-brand-mid space-y-1">
        <li><a href="partners.php" class="hover:text-brand-dark transition-colors duration-300">Partner Programs</a></li>
        <li><a href="partners.php" class="hover:text-brand-dark transition-colors duration-300">Service Providers</a></li>
        <li><a href="partners.php" class="hover:text-brand-dark transition-colors duration-300">Strategic Technology</a></li>
        <li><a href="partners.php" class="hover:text-brand-dark transition-colors duration-300">Become a Partner</a></li>
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