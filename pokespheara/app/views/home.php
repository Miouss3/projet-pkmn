
<!-- Vidéo de fond -->
<video id="background-video" autoplay loop muted playsinline>
    <source src="/assets/img/background.mp4" type="video/mp4">
</video>

<!-- Header -->
<header>
    <div class="header">
        <div class="pokeball-wrapper"></div>
        <div class="pokeball"></div>
        <div class="center-circle"></div>
        <div class="center-circle-g"></div>
        <div class="center-circle-w"></div>

        <nav class="burger-menu">
            <a href="/page dacceuil/messagerie.html">Messagerie</a>
            <a href="/app/views/Faq.php">FAQ</a>
            <a href="/page dacceuil/contact.html">Contact</a>
            <a href="/app/views/test_categories.php">|</a>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="/profile" class="btn-profil">Mon Profil</a>
                <a href="/logout" 
                onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion
</a>
            <?php else: ?>
                <a href="/users/create" class="btn-login">se connecter</a>
            <?php endif; ?>
        </nav>

        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </label>

        <nav class="mobile-menu">
            <button class="close-btn">✕</button>
            <a href="/page dacceuil/messagerie.html">Messagerie</a>
            <a href="/page dacceuil/faq.html">FAQ</a>
            <a href="/page dacceuil/contact.html">Contact</a>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="/profile" class="btn-profil">Mon Profil</a>
                <a href="/logout" class="btn-logout">Déconnexion</a>
            <?php else: ?>
                <a href="/users/create" class="btn-login">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
    <h1>Pokespheara</h1>

    <!-- Section de recherche -->
    <section class="container">
        <section class="search">
            <input type="search" id="search" value="">
            <button><img src="/assets/img/loupe.png" alt="loupe"></button>
        </section>
    </section>

    <!-- Navigation boutons -->
    <section class="navbtn">
        <a class="btn" href="/page dacceuil/page_forum.html">Forum</a>
        <a class="btn" href="#">TCG</a>
        <a class="btn" href="#">Pokedex</a>
    </section>

    <!-- Top actu -->
    <section class="top_actu">
        <section class="top">
            <section class="content">
                <h2>Top 4 meilleurs dresseur(euse)</h2>
                <div class="item">
                    <img class="utivu" src="/assets/img/avdragon.png" alt="">
                    <div class="topvu">Lorem, ipsum dolor sit amet consectetur</div>
                </div>
                <div class="item">
                    <img class="utivu" src="/assets/img/avdragon.png" alt="">
                    <div class="topvu">Lorem, ipsum dolor sit amet consectetur</div>
                </div>
                <div class="item">
                    <img class="utivu" src="/assets/img/avdragon.png" alt="">
                    <div class="topvu">Lorem, ipsum dolor sit amet consectetur</div>
                </div>
                <div class="item">
                    <img class="utivu" src="/assets/img/avdragon.png" alt="">
                    <div class="topvu">Lorem, ipsum dolor sit amet consectetur</div>
                </div>
                <section id="time"></section>
            </section>
        </section>

        <section class="actu">
            <h2>Actu</h2>
            <section class="actuc">
                <!-- Exemple de posts d'actu -->
                <?php for ($i=0; $i<5; $i++): ?>
                <div class="actuvu">
                    <img class="pepingle" loading="lazy" width="600" height="400" src="/assets/img/neb.webp" alt="">
                    <div class="epingle-text">
                        <a href="#">Lorem ipsum dolor sit amet.</a>
                        <p class="p2">Lorem ipsum 11 sit 12h50.</p>
                    </div>
                </div>
                <?php endfor; ?>
            </section>
        </section>
    </section>

    <!-- Section des posts -->
    <section class="post_content">
        <section class="post">
            <section class="titre_post">
                <h2>Post</h2>
                <button><a href="/app/views/CreatePost.php">+</a></button>
            </section>
            <section class="publication">
                <!-- Exemple de posts utilisateurs -->
                <?php for ($i=0; $i<10; $i++): ?>
                <div class="postuser">
                    <img class="pepingle" loading="lazy" src="/assets/img/avdragon.png" alt="">
                    <div class="epingle-text">
                        <p>@Pseudo</p>
                        <a href="#">Lorem ipsum dolor sit amet.</a>
                        <p class="p2">Lorem ipsum 11 sit 12h50.</p>
                    </div>
                </div>
                <?php endfor; ?>
                <a href="/app/views/Post.php">Voir plus</a>
            </section>
        </section>
    </section>

    <!-- Liste utilisateurs si nécessaire -->
    
</main>

<script src="/assets/js/timer.js"></script>
<script src="/assets/js/burger.js"></script>
</body>
</html>
