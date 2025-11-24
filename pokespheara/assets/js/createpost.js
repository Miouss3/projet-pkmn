const editor = document.getElementById("editor");
const hiddenInput = document.getElementById("hiddenContent");

// Appliquer le formatage texte
function format(command) {
    document.execCommand(command, false, null);
    editor.focus();
}

// Ajouter une image locale
function insertLocalImage() {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";

    input.onchange = () => {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.execCommand("insertImage", false, e.target.result);
                const imgs = editor.getElementsByTagName("img");
                const img = imgs[imgs.length - 1];
                img.style.maxWidth = "45%";
                img.style.height = "auto";
                img.style.display = "block";
                img.style.margin = "10px auto";
                img.style.borderRadius = "6px";
                editor.focus();
            };
            reader.readAsDataURL(file);
        }
    };
    input.click();
}

// Prévisualisation
function previewContent() {
    const preview = document.getElementById("preview");
    preview.innerHTML = `<h3>${document.getElementById("title").value}</h3>` + editor.innerHTML;
}

// Lors de l'envoi du formulaire
document.getElementById("postForm").addEventListener("submit", function(e) {
    const hiddenInput = document.getElementById("hiddenContent");
    const editor = document.getElementById("editor");

    // Copie le contenu HTML dans le champ caché
    hiddenInput.value = editor.innerHTML;

    // Supprime les balises vides pour vérifier du vrai texte
    const textOnly = editor.textContent.replace(/\s+/g, '').trim();
    if (!textOnly) {
        e.preventDefault();
        alert("Le contenu ne peut pas être vide !");
        return false;
    }
});
// Gestion du bouton de sélection de catégorie
const chooseBtn = document.getElementById('chooseCategoryBtn');
const categoryList = document.getElementById('categoryList');
const selectedSpan = document.getElementById('selectedCategory');
const categoryInput = document.getElementById('category_id');

// Ouvrir/fermer la liste
chooseBtn.addEventListener('click', () => {
    categoryList.style.display = categoryList.style.display === 'none' ? 'block' : 'none';
});

// Sélectionner une catégorie
categoryList.querySelectorAll('li').forEach(li => {
    li.addEventListener('click', () => {
        const name = li.textContent;
        const id = li.getAttribute('data-id');

        selectedSpan.textContent = name;   // Affiche le nom choisi
        categoryInput.value = id;          // Remplit l'input caché pour le formulaire
        categoryList.style.display = 'none';
    });
});

// Fermer la liste si clic en dehors
document.addEventListener('click', (e) => {
    if (!chooseBtn.contains(e.target) && !categoryList.contains(e.target)) {
        categoryList.style.display = 'none';
    }
});
