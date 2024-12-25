<?php
session_start();
require '../config/config.php';
// print_r($_SESSION['role']);
if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || $_SESSION['role'] != 1) {
  header('Location: /admin/login.php');
  exit();
}
if (isset($_POST["search"])) {
  setcookie("search", $_POST["search"], time() + (86400 * 30), "/");
} else {
  if (empty($_GET["pagenu"])) {
      unset($_COOKIE["search"]);
      setcookie("search", "", time() - 3600, "/");
  }
}



?>
<?php include ('header.php') ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Detail Table</h3>
              </div>
              <?php
              if(!empty($_GET['pagenu'])) {
                $pagenu = $_GET['pagenu'];
              } else {
                $pagenu = 1;
              }
              $numOfrecs = 5;
              $offset = ($pagenu-1)*$numOfrecs;

              
                

                $stmt = $conn->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']);
                $stmt->execute();
                $raw_result = $stmt->fetchAll(PDO::FETCH_DEFAULT);

                $stmt = $conn->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']." LIMIT $offset,$numOfrecs");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_DEFAULT);
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="order.php" type="button" class="btn btn-success">Back</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                   <?php
                   $i=0;
                   if($result) {
                    foreach($result as $data) {?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM products WHERE id=".$data['product_id']);
                    $stmt->execute();
                    $catresult = $stmt->fetchAll();
                    ?>
                       <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $catresult[0]['name']; ?></td>
                      <td><?php echo $data['quantity']; ?></td>
                      <td><?php echo date('Y-m-d',strtotime($data['order_date']))?></td>
                    </tr>
                    <?php
                    $i++;
                    }
                   }
                   
                   ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float: right;">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pagenu=1">Frist</a></li>
                  <li class="page-item <?php if($pagenu <= 1) { echo 'disabled'; }?>"><a class="page-link" href="<?php if($pagenu <= 1) {echo "";} else { echo '?pagenu='.($pagenu-1);}?>">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pagenu; ?></a></li>
                  <li class="page-item <?php if($pagenu >= $total_pagenu) { echo 'disabled'; }?>"><a class="page-link" href="<?php if($pagenu >= $total_pagenu) {echo "";} else { echo '?pagenu='.($pagenu+1);}?>">Next</a></li>
                  <li class="page-item"><a class="page-link" href="?pagenu=<?php echo $total_pagenu?>">Last</a></li>
                </ul>
              </nav>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <?php include ('footer.html') ?>
  
  