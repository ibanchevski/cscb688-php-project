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
    return header('location:dashboard.php');
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
            <div class="row mb-lg-5">
                <div class="col-md-4">
                    <h1 class="page-title">Dashboard</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="button" class="btn btn-primary-purple" id="newCategoryBtn">
                            Add category
                        </button>
                        <button type="button" class="btn btn-primary-purple" id="addEntryBtn">
                            Add entry
                        </button>
                    </div>
                    <div class="d-none custom-category-holder mt-3">
                        <p class="text-danger add-category-error d-none">Category already exists!</p>
                        <form name="newCategoryForm" id="newCategoryForm" method="post">
                            <div class="input-group">
                                <input type="text" placeholder="Category name" name="newCategory" class="form-control" id="custom-category">
                                <button type="submit" class="btn btn-primary-purple">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <div class="input-group-text" ><i class="fas fa-search"></i></div>
                        <input type="text" class="form-control" name="search" value="" placeholder="Search...">
                        <button type="submit" class="btn btn-primary-purple">Search</button>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h4 class="text-muted float-end">
                        Total monthly expense: <span class="monthly-expense"></span>лв.
                    </h4>
                </div>
            </div>
            <hr>
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
        <div class="container category-holder">
            <?php
            echo '<div class="row">';
            $currColInd = 0;
            foreach($categories as $key => $value) {
                /* echo "name: ".$key."<br>";
                 * echo "catid: ".$value['catid']."<br>";
                 * echo "entries:<br>";
                 * var_dump($value['expenses']); */
                if ($currColInd % 4 == 0) {
                    echo '</div><div class="row">';
                }
                echo '<div class="col-sm category mb-3 shadow-sm" id="category-'.$value["catid"].'">';
                echo '  <div class="mb-2 d-flex justify-content-end">
                          <button type="button" class="btn btn-sm btn-primary-purple border-top-0 rounded-0 rounded-bottom me-2 shadow-sm" onclick="toggleCategoryRename('.$category['id'].')">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <form method="post" style="display: inline;">
                            <input type="hidden" name="deleteCategory" value="'.$value["catid"].'" style="width:0;">
                            <button type="submit" class="btn btn-sm btn-danger border-top-0 rounded-0 rounded-bottom shadow-sm"><i class="far fa-trash-alt"></i></button>
                          </form>
                        </div>
                        <h4 class="fw-bold pw-3" style="color: #4c85f2">'.$key.'</h4>
                        <form method="post" name="rename" class="d-none">
                           <input type="hidden" name="categoryId" value="'.$value["catid"].'">
                           <div class="input-group">
                             <input type="text" class="form-control" name="newCategoryName" value="'.$category['name'].'">
                             <button class="btn btn-primary-purple" type="submit">Save</button>
                           </div>
                         </form>
                         <hr>';
                
                foreach($value["expenses"] as $expense) {
                    echo '<div class="log-wrapper" id="'.$expense["id"].'">';
                    echo '<button type="button" class="btn btn-sm btn-danger"><i class="far fa-trash-alt"></i></button>';
                    echo '<div class="log-date">'.$expense["date"].'</div>';
                    echo '<div class="log-description">'.$expense["description"].'</div>';
                    echo '<div class="log-amount">'.$expense["amount"].'</div>';
                    echo '</div>';
                }
                
                echo '</div>';
                $currColInd++;
            }
            echo '</div>';
            ?>
        </div>
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
            <form class="row g-3" action="utils/api.php" method="POST">
                <input type="hidden" name="action" value="newEntry">
                <div class="col-sm-12">
                    <label for="logAmount">Amount spent:</label>
                    <input type="number" name="amount" class="form-control" min="0" value="0.0" step="0.01" id="logAmount">
                </div>
                <div class="col-sm-12">
                    <label for="logDescription">Description (optional)</label>
                    <textarea name="description" class="form-control" id="logDescription" placeholder="Description"></textarea>
                </div>
                <div class="col-sm-12">
                    <label for="category">Select category</label>
                    <select id="modal-categories" name="category" class="form-select">
                        <?php
                        foreach ($categories as $category) {
                            echo '<option value="'.$category["id"].'">'.$category["name"].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="d-grid gap-2 d-md-block">
                    <button type="submit" class="btn btn-primary-purple modal-add-btn">Save</button>
                    <button type="button" id="modalCancelBtn" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/scripts.js"></script>
</body>
</html>
