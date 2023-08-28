const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const categorie = document.createElement('li');

    categorie.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(categorie);

    collectionHolder.dataset.index++;
};

document
    .querySelectorAll('.add_categorie_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });