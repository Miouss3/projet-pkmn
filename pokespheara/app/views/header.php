<?php
// Assure que $currentPage est défini pour éviter les warnings
$currentPage = $currentPage ?? ($_GET['url'] ?? '/');
?>
<header>
    <section class="header">
      <section class="pokeball-wrapper">
        <div class="pokeball"></div>
        <div class="center-circle"></div>
        <div class="center-circle-g"></div>
        <div class="center-circle-w"></div>
      </section>

      <?php if ($currentPage !== '/' && $currentPage !== 'home'): ?>
      <a href="/" class="hom">
        <img src="/assets/img/home.png" alt="home">
      </a>
      <?php endif; ?>
      <a href="/" class="hom">
        <img src="/assets/img/home.png" alt="home">
      </a>

      <input type="checkbox" id="menu-toggle" />
      <label for="menu-toggle" class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
      </label>

      <nav class="mobile-menu">
        <button class="close-btn">✕</button>
        <a href="/home">Accueil</a>
        <a href="/page dacceuil/messagerie.html">Messagerie</a>
        <a href="/page dacceuil/faq.html">FAQ</a>
        <a href="/page dacceuil/contact.html">Contact</a>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="/profile">Mon compte</a>
            <a href="/logout" onclick="return confirm('Voulez-vous vous déconnecter ?');">Déconnexion</a>
        <?php else: ?>
            <a href="/users/create">Connexion</a>
        <?php endif; ?>
      </nav>

      <nav class="burger-menu">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="/profile">Mon compte</a>
            <a href="/page dacceuil/messagerie.html">Messagerie</a>
            <a href="/page dacceuil/faq.html">FAQ</a>
            <a href="/page dacceuil/contact.html">Contact</a>
            <a href="#">|</a>
            <a href="/logout" onclick="return confirm('Voulez-vous vous déconnecter ?');">Déconnexion</a>
        <?php else: ?>
            <a href="/page dacceuil/contact.html">Contact</a>
            <a href="/page dacceuil/faq.html">FAQ</a>
            <a href="#">|</a>
            <a href="/users/create">Connexion</a>
            
        <?php endif; ?>
      </nav>
    </section>
</header>
