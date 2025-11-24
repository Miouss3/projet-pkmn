
<?php require __DIR__ . '/../../assets/templates/head.php'; ?>

<?php
// profile.php
// Assure-toi que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo "Vous devez être connecté pour voir votre profil.";
    exit;
}
?>

<body>

    <video id="background-video" autoplay loop muted playsinline>
    <source src="/img/background.mp4" type="video/mp4">
    </video>

  <header>
    <section class="header">
      <section class="pokeball-wrapper">
        <div class="pokeball"></div>
        <div class="center-circle"></div>
        <div class="center-circle-g"></div>
        <div class="center-circle-w"></div>
      </section>

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
        <a href="#">Accueil</a>
        <a href="/page dacceuil/messagerie.html">Messagerie</a>
        <a href="/page dacceuil/faq.html">FAQ</a>
        <a href="/page dacceuil/contact.html">Contact</a>
        <a href="/conexion/index.html">Connexion</a>
      </nav>

      <nav class="burger-menu">
        <a href="#">Mon compte</a>
        <a href="#">Messagerie</a>
        <a href="/page dacceuil/faq.html">FAQ</a>
        <a href="/page dacceuil/contact.html">Contact</a>
        <a href="#">|</a>
        <a href="/conexion/index.html">Connexion</a>
      </nav>
    </section>
  </header>

  <main class="compt_uti">
    <section class="content_compte">

      <section class="utillisateur">
        <p>@<?= htmlspecialchars($username) ?></p>
        <section class="bio">
          <h4>BIO</h4>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor non minima molestias impedit! Id quidem nostrum.</p>
        </section>
         <a href="/page dacceuil/index.html" class="messagerie">Messagerie</a>

      </section>

      <section class="main_content">
        <section class="info_uti">
          <section class="stat_uti">
           <h2>Statistiques de l’utilisateur</h2>
            <p>Membre depuis le <?= date('d/m/Y', strtotime($created_at)) ?></p>
           <p>Nombre total de messages :???</p>
           <p>Catégorie la plus active : ???</p>
           </section>
           <section class="signiature">
            <h2>Signature</h2>
            <p>Lorem ipsum dolor sit amet consectetur</p> 
            <p>Lorem ipsum dolor sit amet.</p>
           </section>
          </section>
          </section>  
      </section>
    </section>
  </main>

  <script src="/javas/kebab.js"></script>
  <script src="/javas/burger.js"></script>
</body>
</html>
