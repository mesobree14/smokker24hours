<?php
session_name("session_smokker");
  session_start();
include_once("../../backend/config.php");
include_once("../../link/link-2.php");
include_once("../../components/component.php");
$lot_number = $_GET['lot_number'];
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
if(!isset($_SESSION['users_data'])){
  echo "
          <script>
              alert('pless your login');
              window.location = '../index.php';
          </script>
      ";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.20/css/uikit.css">
    <link rel="stylesheet" href="../../assets/scripts/module/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <link rel="stylesheet" href="../../assets/scss/navigationTrue-a-j.scss">
    <link rel="stylesheet" href="../../assets/scss/revenue.scss">
    <link rel="stylesheet" href="../../assets/scss/index.u.scss">
    <script src="../../assets/scripts/script-bash.js"></script>
    <link rel="stylesheet" href="../../assets/scripts/module/select-picker/select.scss">
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
      <?php  navigationOfiicer("../"); ?>
       <main class="page-content mt-0">
      <?php navbar("รายละเอียด / ".$lot_number."", "../"); ?>
      <div class="container-fluid row">
          <div class="col-md-12">
           
            <div class="col-12 shadow-lg row">
              <div class="col-12 row">
                <a class="ml-auto px-4 mx-4 py-1 w-22 btn-print" href="../details/PDF/PDF_detaillot.php?lot_number=<?php echo $lot_number ?>" target="_blank">
            <i class="fas fa-file-code px-2"></i> PDF
          </a>
               
              </div>
            </div>
          </div>
          <div class="col-md-12">
              <div id="tabC01" class="tab-contents">
                <?php
                  $get_product = "SELECT COUNT(*) AS total_lot, NP.product_name AS in_productname, SP.product_id, SP.product_name,SP.create_at, 
                    SP.product_price,SP.price_center,SP.shipping_cost,SP.expenses, SUM(SP.product_count) AS sum_product, SP.lot_number FROM stock_product SP 
                    LEFT JOIN name_product NP ON SP.product_name = NP.id_name WHERE SP.lot_number='$lot_number' GROUP BY SP.product_id, SP.lot_number";
                    $query = $conn->query($get_product);
                    $data = [];
                    while($row = $query->fetch_assoc()){
                      $data[] = $row;
                    }
                  
                    $get_productsell = "SELECT LP.list_sellid, LP.productname, NP.product_name,
                      LP.level_selltype,LP.rate_customertype,LP.tatol_product,LP.price_to_pay
                      FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name ORDER BY LP.list_sellid ASC";
                      $query_sell = $conn->query($get_productsell);
                      $data_sell = [];
                      while($is_row = $query_sell->fetch_assoc()){
                        $data_sell[] = $is_row;
                      }
                    
                      $lot_resutl = [];
                    
                  foreach($data as $stock){
                    $lotQty = $stock['sum_product'];
                    $remainQty = $lotQty;
                    $soldQtry = 0;
                    $rate_costometype = 0;
                    $totalSellValue = 0;
                    $lot_code = $stock['lot_number'];
                    $p_idname = $stock['product_name'];
                    $p_name = $stock['in_productname'];
                    $p_id = $stock['product_id'];
                  
                    foreach($data_sell as &$sales){
                      if($sales['productname'] !== $p_idname) continue;
                      if($remainQty <= 0) break;
                    
                      $saleQty = (int)$sales['tatol_product'];
                      
                      if($saleQty > 0){
                        
                        if($remainQty >= $saleQty){
                          $soldFromLot = $saleQty;
                          $remainQty -= $saleQty;
                          $sales['tatol_product'] = 0;
                          
                        }else{
                          $soldFromLot = $remainQty;
                          $sales['tatol_product'] -= $remainQty;
                          $remainQty = 0;
                          
                          
                        }
                        $rate_costometype = (int)$sales['rate_customertype'];
                        $soldQtry += $soldFromLot;
                        $totalSellValue += $soldFromLot * $rate_costometype;
                      }else{
                        echo "sell zero";
                        $rate_costometype = 0;
                      }
                    
                    }
                    $shipping_one = $stock['shipping_cost'] / $lotQty;
                    $lot_resutl[] = [
                      'id' => $p_id,
                      'p_name' =>$p_name,
                      'id_pname'=> $p_idname,
                      'lot_no'=> $lot_code,
                      'count_inlot' => $lotQty, //จำนวนฝน lot
                      'total_sell' => $soldQtry, // จำนวนขาย
                      'remain_qty' => $remainQty, //คงเหลือ
                      'product_price' => $stock['product_price'] + $shipping_one, //ราคาเริ่มต้นต่อลัง
                      'product_priceAll' => ($stock['product_price'] + $shipping_one) * $soldQtry,
                      'capital_using' => ($stock['product_price'] + $shipping_one) * $remainQty,
                      'capital_received' => ($stock['product_price'] + $shipping_one) * $soldQtry,
                      'price_center' => $stock['price_center'], // ราคากลาง
                      'price_centerAll' => $stock['price_center'] * $soldQtry,
                      'pricecenter_delcaptialshipping' => ($stock['price_center'] * $soldQtry) - (($stock['product_price'] + $shipping_one) * $soldQtry),
                      'shipping_one' => $shipping_one,
                      'shipping_cost' => $stock['shipping_cost'],
                      'expenses' => $rate_costometype * $soldQtry,
                      'profit' => ($rate_costometype * $soldQtry) - ($stock['price_center'] * $soldQtry),
                      'price_seller' => $rate_costometype, // ราคาขายต่อลัง
                      'total_sell_value' => $totalSellValue, //cancel
                      'create_at' => $stock['create_at']
                    ];
                  }
                ?>
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width:15%;">สินค้า</th>
                            <th>จำนวนซื้อ</th>
                            <th>จำนวนขาย</th>
                            <th>จำนวนคงเหลือ</th>
                            <th>(ซื่อ+ค่าส่ง)ต่อลัง</th>
                            <th>ราคาซื้อ + ค่าส่ง</th>
                            <th>ราคากลาง</th>
                            <th>ส่วนต่าง</th>
                            <th>ขายต่อลัง</th>
                            
                            
                            <th>ราคาขาย</th>
                            <th>กำไร</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach($lot_resutl as $key => $res){
                          detailLotFinance(($key+1),$res['id'],$res['p_name'],$res['id_pname'],$res['lot_no'],$res['count_inlot'],$res['total_sell'],$res['remain_qty'],
                          $res['product_price'],$res['product_priceAll'],$res['price_center'],$res['price_centerAll'],$res['pricecenter_delcaptialshipping'],$res['shipping_one'],$res['shipping_cost'],
                          $res['price_seller'],$res['expenses'],$res['total_sell_value'],$res['profit'],$res['create_at']);
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
             
          </div>
        </div>
      </div>
    </main>
  </div>
  
</body>
</html>