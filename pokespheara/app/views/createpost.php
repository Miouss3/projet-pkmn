

<?php require __DIR__ . '/../../app/views/header.php'; ?>
<?php require __DIR__ . '/../../assets/templates/head.php'; ?>

<body>
<video id="background-video" autoplay loop muted playsinline>
    <source src="/assets/img/background.mp4" type="video/mp4">
</video>

<h1 class="titre_creat">Créer un nouveau post</h1>

<section class="form_content">
    <form id="postForm" action="/posts/store" method="POST">
        
        <input type="text" name="title" id="title" placeholder="Titre" />

        <!-- SELECT DES CATÉGORIES -->
<div class="category-picker">
    <button type="button" id="chooseCategoryBtn">Choisir une catégorie</button>
    <span id="selectedCategory">Aucune catégorie sélectionnée</span>
    <input type="hidden" name="category_id" id="category_id" value="">
    <ul id="categoryList">
        <?php foreach ($categories as $cat): ?>
            <li data-id="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

        <div class="toolbar">
            <button type="button" onclick="format('bold')" title="Gras"><b>A</b></button>
            <button type="button" onclick="format('italic')" title="Italique"><i>I</i></button>
            <button type="button" onclick="format('underline')" title="Souligné"><u>U</u></button>
            <button type="button" onclick="format('strikeThrough')" title="Barré"><s>S</s></button>
            <button type="button" onclick="insertLocalImage()" title="Image locale">📁</button>
        </div>

        <div id="editor" contenteditable="true"></div>
        <input type="hidden" name="content" id="hiddenContent" />

        <button type="button" class="preview-btn" onclick="previewContent()">Prévisualiser</button>
        <div id="preview">prévisualisation</div>

        <button type="submit" class="submit-btn">Publier</button>
    </form>
</section>

<footer>
    <div class="center-circle-fin"></div>
    <a href="">Mention legal</a>
    <a href="">FAQ</a>
    <a href="">Confidentialité</a>
    <a href="">Droit auteur</a>
    <a href="">Règle</a>
    <a href="">CGU (Conditions générales d’utilisation)</a>
</footer>

<script src="/assets/js/createpost.js"></script>
</body>
