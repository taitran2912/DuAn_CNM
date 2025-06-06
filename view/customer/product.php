<?php
include_once '../../controller/cProduct.php';
$p = new cProduct();
$idCate = isset($_GET['idCate']) ? $_GET['idCate'] : 0; 

// Pagination settings
$items_per_page = 15;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page); // Ensure page is at least 1

// First, get all products to count them
if($idCate != 0){
    $all_products = $p->getProductByCategory($idCate);
} else {
    $all_products = $p->getAllProduct();
}

// Get total count from the result
$total_products = $all_products ? $all_products->num_rows : 0;

// Calculate total pages
$total_pages = ceil($total_products / $items_per_page);

// Ensure current page is within valid range
$current_page = min($current_page, max(1, $total_pages));

// Calculate offset for pagination
$offset = ($current_page - 1) * $items_per_page;
?>
<!-- Products Section -->
<section class="products-page py-5">
    <div class="container">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Categories -->
                <div class="sidebar-widget mb-4">
                    <h4 class="widget-title">Danh mục sản phẩm</h4>
                    <div class="widget-content">
                        <ul class="category-list">
                            <li>
                                <a href="?action=product" class="<?php echo ($idCate == 0) ? 'active' : ''; ?>">
                                    <span>Tất cả sản phẩm</span>
                                </a>
                            </li>
                            <?php
                                $cat = $p->getAllCategorie();
                                if($cat)
                                    if($cat->num_rows > 0){
                                        while($row = $cat->fetch_assoc()){
                                            $activeClass = ($idCate == $row['id']) ? 'active' : '';
                                            echo'
                                                <li>
                                                    <a href="?action=product&idCate='.$row['id'].'" class="category-link '.$activeClass.'">
                                                        <span>'.ucwords($row['name']).'</span>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="products-toolbar mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="text-muted">
                                Hiển thị <?php echo min(($current_page-1)*$items_per_page+1, $total_products); ?> - 
                                <?php echo min($current_page*$items_per_page, $total_products); ?> 
                                trên <?php echo $total_products; ?> sản phẩm
                            </p>
                        </div>
                       
                    </div>
                </div>

                <!-- Products Grid -->
                <divc class="products-grid">
                    <div class="row g-4">
                    <?php
                        // We need to modify the existing methods to support pagination
                        // Since we can't modify cProduct.php, we'll use a workaround with PHP's array_slice
                        
                        if($idCate != 0){
                            $pd = $p->getProductByCategory($idCate);
                        } else {
                            $pd = $p->getAllProduct();
                        }
                        
                        if($pd && $pd->num_rows > 0){
                            // Convert mysqli result to array for pagination
                            $products = [];
                            while($row = $pd->fetch_assoc()){
                                $products[] = $row;
                            }
                            
                            // Apply pagination to the array
                            $paginated_products = array_slice($products, $offset, $items_per_page);
                            
                            // Display paginated products
                            foreach($paginated_products as $row){
                                echo'
                                    <div class="col-md-4">
                                        <div class="product-card">
                                                <div class="product-thumb">
                                                    <img src="../../image/'.$row['img'].'" class="img-fluid" alt="'.$row['name'].'">
                                                    <div class="product-action">
                                                    <form action="" method="POST">
                                                        <input type="hidden" name="txtID" value="'.$row['id'].'">
                                                        <button type="submit" name="btnADD" class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="product-info p-3">
                                                <span class="product-category">Rau củ</span>
                                                <h5><a href="?action=detail&id='.$row['id'].'" class="product-title">'.$row['name'].'</a></h5>
                                                <div class="product-price">
                                                    <span class="new-price">'.$row['price'].'đ/kg</span>
                                                </div>
                                                <div class="product-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <span>(4.5)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                        } else {
                            echo '<div class="col-12"><p class="text-center">Không tìm thấy sản phẩm nào.</p></div>';
                        }
                    ?>

                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                        <div class="pagination-container mt-5">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php if($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?action=product<?php echo $idCate != 0 ? '&idCate='.$idCate : ''; ?>&page=<?php echo $current_page-1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php
                                    // Display page numbers
                                    $start_page = max(1, $current_page - 2);
                                    $end_page = min($total_pages, $current_page + 2);
                                    
                                    // Always show first page
                                    if($start_page > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?action=product'.($idCate != 0 ? '&idCate='.$idCate : '').'&page=1">1</a></li>';
                                        if($start_page > 2) {
                                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                        }
                                    }
                                    
                                    // Display page links
                                    for($i = $start_page; $i <= $end_page; $i++) {
                                        $active = $i == $current_page ? 'active' : '';
                                        echo '<li class="page-item '.$active.'"><a class="page-link" href="?action=product'.($idCate != 0 ? '&idCate='.$idCate : '').'&page='.$i.'">'.$i.'</a></li>';
                                    }
                                    
                                    // Always show last page
                                    if($end_page < $total_pages) {
                                        if($end_page < $total_pages - 1) {
                                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                        }
                                        echo '<li class="page-item"><a class="page-link" href="?action=product'.($idCate != 0 ? '&idCate='.$idCate : '').'&page='.$total_pages.'">'.$total_pages.'</a></li>';
                                    }
                                    ?>
                                    
                                    <?php if($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?action=product<?php echo $idCate != 0 ? '&idCate='.$idCate : ''; ?>&page=<?php echo $current_page+1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                    </div>
                </divc>
            </div>

        </div>
    </div>
</section>



<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../../asset/js/script.js"></script>

<?php
    if (isset($_POST["btnADD"])) {
        if($id != 0){  
            if($p->addCart($_POST["txtID"], $id)){
                    echo "<script>alert('Thêm vào giỏ hàng thành công!');</script>";
                    echo "<script>window.location.href='?action=product';</script>";
                } else {
                    echo "<script>alert('Thêm vào giỏ hàng thất bại!');</script>";
                }
        } else {
            echo "<script>alert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!');</script>";
            echo "<script>window.location.href='../../log.php';</script>";
        }    
    }
?>