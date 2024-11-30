<div class="search-container text-center">
    <form action="" method="GET" class="form-inline">
        <input type="hidden" name="page" value="home.php">
        
        <!-- Input tìm kiếm -->
        <input type="text" name="search" placeholder="Search for products..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
               class="form-control">
        
        <!-- Bộ lọc loại món ăn -->
        <select name="filter_type" class="form-control">
            <option value="">All Types</option>
            <option value="Dish" <?php echo (isset($_GET['filter_type']) && $_GET['filter_type'] === 'Dish') ? 'selected' : ''; ?>>Dishes</option>
            <option value="Drink" <?php echo (isset($_GET['filter_type']) && $_GET['filter_type'] === 'Drink') ? 'selected' : ''; ?>>Drinks</option>
        </select>
        
        <!-- Bộ lọc sắp xếp giá -->
        <select name="sort_price" class="form-control">
            <option value="">Sort by Price</option>
            <option value="asc" <?php echo (isset($_GET['sort_price']) && $_GET['sort_price'] === 'asc') ? 'selected' : ''; ?>>Low to High</option>
            <option value="desc" <?php echo (isset($_GET['sort_price']) && $_GET['sort_price'] === 'desc') ? 'selected' : ''; ?>>High to Low</option>
        </select>
        
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<hr />

<div class="row">
    <?php
    // Số lượng sản phẩm mỗi trang
    $items_per_page = 9;

    // Xác định trang hiện tại
    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $current_page = max($current_page, 1);

    // Tính toán offset
    $offset = ($current_page - 1) * $items_per_page;

    // Lấy từ khóa tìm kiếm, loại món và sắp xếp giá trị nếu có
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $filter_type = isset($_GET['filter_type']) ? mysqli_real_escape_string($conn, $_GET['filter_type']) : '';
    $sort_price = isset($_GET['sort_price']) ? mysqli_real_escape_string($conn, $_GET['sort_price']) : '';

    // Câu truy vấn SQL cơ bản
    $total_sql = "SELECT COUNT(*) FROM dishanddrink";
    $sql = "SELECT * FROM dishanddrink";

    // Thêm điều kiện tìm kiếm nếu có từ khóa
    $conditions = [];
    if (!empty($search_query)) {
        $conditions[] = "DishanddrinkName LIKE '%$search_query%'";
    }
    if (!empty($filter_type)) {
        $conditions[] = "DishanddrinkType = '$filter_type'";
    }

    // Ghép các điều kiện lại trong câu truy vấn
    if (count($conditions) > 0) {
        $total_sql .= " WHERE " . implode(" AND ", $conditions);
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Thêm điều kiện sắp xếp giá nếu có
    if (!empty($sort_price)) {
        $sql .= " ORDER BY DishanddrinkPrice " . ($sort_price === 'asc' ? 'ASC' : 'DESC');
    }

    // Thêm phân trang
    $sql .= " LIMIT $offset, $items_per_page";

    // Thực thi truy vấn SQL
    $total_result = mysqli_query($conn, $total_sql);
    $total_items = mysqli_fetch_array($total_result)[0];
    $total_pages = ceil($total_items / $items_per_page);

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
    ?>
            <div class="col-sm-4">
                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="card-title"><?php echo htmlspecialchars($row['DishanddrinkName']); ?></h4>
                    </div>
                    <div class="panel-body-inner">
                        <div>
                            <?php
                            $imagePath = 'admin/pimgs/' . htmlspecialchars($row['DishanddrinkImage']);
                            // Check if the image exists
                            if (file_exists($imagePath)) {
                                echo '<img src="' . $imagePath . '" class="img-product" alt="' . htmlspecialchars($row['DishanddrinkName']) . '" />';
                            } else {
                                echo '<img src="path/to/placeholder.jpg" class="img-product" alt="Image not available" />';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span>Price: <?php echo htmlspecialchars($row['DishanddrinkPrice']); ?></span>
                        <a class="btn btn-primary" href="?page=detail.php&id=<?php echo $row['DishanddrinkID']; ?>">Details</a>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo "<p>No products found.</p>"; // Message when no products are found
    }
    ?>
</div>

<!-- Pagination navigation -->
<div class="pagination">
    <?php if ($total_pages > 1): ?>
        <?php if ($current_page > 1): ?>
            <a href="?page=home.php&page_num=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search_query); ?>&filter_type=<?php echo urlencode($filter_type); ?>&sort_price=<?php echo urlencode($sort_price); ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                <strong><?php echo $i; ?></strong>
            <?php else: ?>
                <a href="?page=home.php&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>&filter_type=<?php echo urlencode($filter_type); ?>&sort_price=<?php echo urlencode($sort_price); ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=home.php&page_num=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search_query); ?>&filter_type=<?php echo urlencode($filter_type); ?>&sort_price=<?php echo urlencode($sort_price); ?>">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
