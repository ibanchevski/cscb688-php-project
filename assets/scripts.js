(function() {
    document
        .querySelector('#newCategoryBtn')
        .addEventListener('click', toggleCustomCategoryInput);
    
    document
        .querySelector('#newCategoryForm')
        .addEventListener('submit', addCategory);
})();

function toggleCustomCategoryInput() {
    const customCategoryHolder = document.querySelector('.custom-category-holder');
    customCategoryHolder.classList.toggle('d-none');
    if (!customCategoryHolder.classList.contains('d-none')) {
            document.querySelector('#custom-category').value = '';
    }
}

function addCategory() {
    let categoryName = document.querySelector('#custom-category').value;
    let categories = window.localStorage.getItem('categories');

    // Escape whitespace in category name
    while (categoryName.indexOf(' ') > 0) {
            categoryName = categoryName.replace(' ', '-');
    }

    if (categories === null || categories === undefined) {
            categories = [];
    } else {
            categories = JSON.parse(categories);
    }

    if (categories.includes(categoryName)) {
            document.querySelector('.add-category-error').classList.remove('d-none');
            return;
    }

    //toggleCustomCategoryInput();
    document.querySelector('.new-log-btn').classList.remove('disabled');
    document.querySelector('.add-category-error').classList.add('d-none');
}

