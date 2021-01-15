<?php
session_start();
require_once("controllers/User.php");
require_once("controllers/Category.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['deleteCategory'])) {
        Controllers\Category::delete($_POST['deleteCategory']);
    }

    if (isset($_POST['newCategory'])) {
        Controllers\Category::create($_POST['newCategory'], $_SESSION['userid']);
    }

    if (isset($_POST['newCategoryName'])) {
        Controllers\Category::rename($_POST['newCategoryName'], $_POST['categoryId']);
    }
    //return header('location:dashboard.php');
}

$categories = Controllers\User::getCategories($_SESSION['userid']);
?>

<!DOCTYPE html>
<html lang="en">
<?php
$CURR_TITLE = 'Dashboard';
require_once('head.php');
?>
    <body>
        <?php require_once("navbar.php") ?>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <button type="button" class="category-btn" id="newCategoryBtn">
                    <i class="fas fa-plus"></i>&nbsp;New category
                </button>
                <div class="d-none custom-category-holder mt-3">
                    <p class="text-danger add-category-error d-none">Category already exists!</p>
                    <form name="newCategoryForm" id="newCategoryForm" method="post">
                        <div class="input-group">
                            <input type="text" placeholder="Category name" name="newCategory" class="form-control" id="custom-category">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm">
                <h4 class="text-muted mt-3 float-right">
                    Total monthly expense: <span class="monthly-expense"></span>лв.
                </h4>
            </div>
        </div>
    </div>

    <?php
    if (count($categories) === 0) {
        echo '
    <div class="container py-5">
        <div class="row no-categories">
            <div class="col-sm-6 offset-lg-3">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <h3><i class="far fa-comment-alt"></i> &nbsp;There are no categories listed.</h3>
                    <div>
                        <h6 class="text-muted">You could:</h6>
                        <ol class="text-muted">
                            <li>Add new category to the category list.</li>
                            <li>Add entries to already created categories.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
        ';
    }
    ?>
    <div class="container">
        <div class="row category-holder">
            <?php
            foreach ($categories as $category) {
                echo '
                <div class="col-sm category mb-3" id="category-'.$category['id'].'">
                  <h5>' .$category["name"]. '</h5>
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleCategoryRename('.$category['id'].')"><i class="fas fa-pencil-alt"></i></button>
                  <form method="post" name="rename" class="d-none">
                    <div class="input-group">
                      <input type="hidden" name="categoryId" value="'.$category['id'].'">
                      <input type="text" class="form-control" name="newCategoryName" value="'.$category['name'].'">
                      <button class="btn btn-outline-secondary" type="submit">Save</button>
                    </div>
                  </form>
                  <form method="post" style="display: inline;">
                    <input type="hidden" name="deleteCategory" value="'.$category["id"].'" style="width:0;">
                    <button type="submit" class="btn btn-sm btn-danger float-end mt-3">Delete</button>
                  </form>
                </div>
                ';
            }
            ?>
        </div>
    </div>
    <a href="#" class="new-log-btn" id="addRecordBtn" title="Add record">
        <i class="fas fa-plus"></i>
    </a>
    <div class="add-record-modal d-none">
        <div class="inside">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="">New expense</h3>
                </div>
            </div>
            <div class="row d-none modal-error">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        Please, fill out all the fileds properly!
                        <small class="text-muted">(e.g. Amount spent must be >= 0 and category has to selected)</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="logAmount">Amount spent:</label>
                        <input type="number" class="form-control" min="0" value="0.0" step="0.01" id="logAmount">
                    </div>
                    <div class="form-group">
                        <label for="logDescription">Description (optional)</label>
                        <textarea placeholder="Description" class="form-control" id="logDescription"></textarea>
                    </div>
                    <div class="form-group">
                        <select id="modal-categories" class="form-control"></select>
                    </div>
                    <button type="button" class="modal-add-btn category-btn">Save</button>
                    <button type="button" class="modal-cancel-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/scripts.js"></script>
</body>
</html>
