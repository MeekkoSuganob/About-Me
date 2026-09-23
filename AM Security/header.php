<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($activePage)) {
    $activePage = '';
}
if (!isset($downloadHref)) {
    $downloadHref = 'services.php';
}

// Returns the right classes for a nav link depending on whether it's the active page
function navLinkClass($page, $activePage, $offset = 4) {
    if ($page === $activePage) {
        return "text-brand-dark underline underline-offset-{$offset} transition-colors duration-300";
    }
    return 'hover:text-brand-dark transition-colors duration-300';
}

// name shortened to 8 letters
function truncateUsername($name, $limit = 8) {
    if (mb_strlen($name) > $limit) {
        return mb_substr($name, 0, $limit) . '...';
    }
    return $name;
}
?>
<header class="bg-brand-light">
  <div class="mx-[130px] flex justify-between items-center py-6">

    <!-- Logo Area -->
    <div class="flex items-center relative">
      <img src="pictures/logo.png" alt="AM Security Logo" class="w-[60px] h-[60px] object-contain">
      <a href="home.php" class="font-eras text-[50px] text-brand-mid tracking-wide ml-[15px]">
        AM Security
      </a>
    </div>

    <!-- Center Navigation -->
    <nav class="flex gap-8 text-[25px] text-brand-mid">
      <a href="home.php" class="<?php echo navLinkClass('home', $activePage); ?>">Home</a>
      <a href="services.php" class="<?php echo navLinkClass('services', $activePage); ?>">Services</a>
      <a href="partners.php" class="<?php echo navLinkClass('partners', $activePage); ?>">Partners</a>
      <a href="aboutus.php" class="<?php echo navLinkClass('aboutus', $activePage); ?>">About Us</a>

      <?php if (isset($_SESSION['username'])): ?>
        <a href="account.php" class="<?php echo navLinkClass('account', $activePage); ?>">
          <?php echo htmlspecialchars(truncateUsername($_SESSION['username'])); ?>
        </a>
      <?php else: ?>
        <a href="login.php" class="<?php echo navLinkClass('login', $activePage, 8); ?>">Login</a>
      <?php endif; ?>

      <?php if (!empty($_SESSION['is_admin'])): ?>
        <a href="admin.php" class="<?php echo navLinkClass('admin', $activePage); ?>">Admin</a>
      <?php endif; ?>

    </nav>

    <!-- Right Actions -->
    <div class="flex items-center gap-6 text-[30px] text-brand-mid">
      <a href="home.php#downloads" class="px-6 py-2 bg-brand-mid text-brand-dark rounded-xl transition-colors duration-300 hover:bg-brand-dark hover:text-brand-mid">Download</a>
    </div>

  </div>
</header>