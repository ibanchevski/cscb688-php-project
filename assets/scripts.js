
(function() {
    document
        .querySelector('#newCategoryBtn')
        .addEventListener('click', toggleCustomCategoryInput);
    
    document
        .querySelector('#newCategoryForm')
        .addEventListener('submit', addCategory);

    document
        .querySelector('#logoutBtn')
        .addEventListener('click', logout);

    document
        .querySelector('#addEntryBtn')
        .addEventListener('click', openAddEntryModal)
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

// eslint-disable-next-line
function toggleCategoryRename(categoryId) {
    // Hide category name
    document.querySelector(`#category-${categoryId} h5`)
        .classList
        .toggle('d-none');

    // Show edit input
    document.querySelector(`#category-${categoryId} form[name="rename"]`)
        .classList
        .toggle('d-none');
}

function logout() {
    // Remove session cookie
    document.cookie = document.cookie.replace(/PHPSESSID=\w+;?/gi, 'PHPSESSID= ;expires=0');
    // Redirect to login
    window.location.href = '/';
}

function openAddEntryModal() {
    const newRecordModal = document.querySelector('.add-record-modal');
    newRecordModal.classList.remove('d-none');

    document
        .querySelector('#modalCancelBtn')
        .addEventListener('click', () => {
            newRecordModal.classList.add('d-none');
        });
}
