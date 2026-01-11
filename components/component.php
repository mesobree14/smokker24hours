<?php

function navigationOfiicer($path = ""){
    if ($path !== "" && substr($path, -1) !== "/") {
        $path .= "/";
    }
    $list = "
        <nav id=\"sidebar\" class=\"sidebar-wrapper\">
            <div class=\"sidebar-content\">
                <div class=\"sidebar-brand\">
                    <a href=\"#\" class=\"text-success\">สถานะ admin</a>
                    <div id=\"close-sidebar\">
                        <i class=\"fas fa-times\"></i>
                    </div>
                </div>
                <div class=\"sidebar-menu mt-4\">
                    <ul>
                        <li class=\"header-menu\">
                            <span>Menu</span>
                        </li>
                        <li>
                          <a href=\"{$path}index.php\" class=\"text-white\">
                              <i class=\"fa fa-tachometer-alt \"></i>
                              <span>หน้าแรก</span>
                          </a>
                        </li>
                        <li class=\"\">
                            <a href=\"#PaymentSubmenu\" data-toggle=\"collapse\" aria-expanded=\"false\" class=\"text-white dropdown-toggle\">
                             <i class=\"fa-solid fa-wallet \"></i>  การเงิน
                            </a>
                            <ul class=\"collapse list-unstyled pl-4\" id=\"PaymentSubmenu\">
                              <li>
                                <a href=\"{$path}finance.php\" class=\"text-white pr-0\">
                                    <i class=\"fab fa-bitcoin text-warning\"></i>
                                    <span>กำไร / ต้นทุน</span>
                                </a>
                              </li>
                                      <li>
                                <a href=\"{$path}resutl_finance.php\" class=\"text-white pr-0\">
                                    <i class=\"fa-solid fa-comments-dollar text-warning\"></i>
                                    <span>สรุปการเงิน</span>
                                </a>
                              </li>
                              <li>
                                <a href=\"{$path}is_profit.php\" class=\"text-white pr-0\">
                                    <i class=\"fa-solid fa-money-bill-trend-up text-warning\"></i>
                                    <span>รายรับรายจ่ายราคากลาง</span>
                                </a>
                              </li>
                            </ul>
                        </li>
                        <li class=\"\">
                            <a href=\"#ProductSubmenu\" data-toggle=\"collapse\" aria-expanded=\"false\" class=\"text-white dropdown-toggle\">
                             <i class=\"fa-solid fa-warehouse\"></i>  สินค้า รายการ
                            </a>
                            <ul class=\"collapse list-unstyled pl-4\" id=\"ProductSubmenu\">
                              <li>
                                <a href=\"{$path}product.php\" class=\"text-white\">
                                    <i class=\"fa-solid fa-cube text-success\"></i>
                                    <span>รายการสินค้า</span>
                                </a>
                              </li>
                              <li>
                                <a href=\"{$path}stock.php\" class=\"text-white\">
                                    <i class=\"fas fa-store text-success\"></i>
                                    <span>คลังสินค้า</span>
                                </a>
                              </li> 
                              <li>
                                <a href=\"{$path}lot.php\" class=\"text-white\">
                                    <i class=\"fa-solid fa-cubes text-success\"></i>
                                    <span>สต็อกสินค้า</span>
                                </a>
                              </li>

                            </ul>
                        </li>
                        <li class=\"\">
                          <a href=\"#PepleSubmenu\" data-toggle=\"collapse\" aria-expanded=\"false\" class=\"text-white dropdown-toggle\">
                           <i class=\"fa-solid fa-user-group\"></i>  ผู้คน
                          </a>
                          <ul class=\"collapse list-unstyled pl-4\" id=\"PepleSubmenu\">
                            <li>
                              <a href=\"{$path}peplegroup.php\" class=\"text-white\">
                                <i class=\"fa-solid fa-users-rectangle text-info\"></i>
                                  <span>ข้อมูลสมาชิก</span>
                              </a>
                            </li>
                            <li>
                              <a href=\"{$path}customer.php\" class=\"text-white\">
                                <i class=\"fa-solid fa-people-group text-info\"></i>
                                  <span>ข้อมูลลูกค้า</span>
                              </a>
                            </li>
                          </ul>
                        </li>
                        
                        <li>
                          <a href=\"{$path}orders.php\" class=\"text-white\">
                              <i class=\"fa-solid fa-truck\"></i>
                              <span>คำสั่งซื้อ</span>
                          </a>
                        </li>
                       
                        <li>
                          <a href=\"{$path}ordersell.php\" class=\"text-white\">
                            <i class=\"fa-solid fa-truck-fast\"></i>
                              <span>สินค้าที่ขาย</span>
                          </a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    ";
    echo $list;
}

function navbar($logo, $path=""){
    $navList = "
        <nav class=\"navbar navbar-tp navbar-expand-md navbar-dark bg-set row\">
          <div class=\"container-fluid ml-4\">
              <a id=\"show-sidebar\" class=\"btn btn-primary mt-1\" href=\"#\">
                  <i class=\"fas fa-bars\"></i>
              </a>
              <p class=\"h3 mb-0 text-white text-uppercase d-none d-lg-inline-block font-thi\">$logo</p>
              <div class=\"ml-auto\">
                  <a class=\"nav-link btn btn-outline-success\" href=\"{$path}logout.php\">
                    <i class=\"now-ui-icons ui-1_settings-gear-63\"></i>
                    ออกจากระบบ
                  </a>
              </div>
          </div>
        </nav>
    ";
    echo $navList;

}

function tableCapital($number, $capital_id,$balance_capital, $count_capital,$slip, $date_time_ad){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$balance_capital</td>
      <td class=\"font-weight-bold\">$count_capital</td>
      <td class=\"font-weight-bold\">$date_time_ad</td>

      <td>
        <div class=\"table-data-feature row\" >
            <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"pdf\" target=\"_blank\" href=\"details/PDF/PDF_capital.php?capital_id=$capital_id\">
              <i class=\"fa-solid fa-file-pdf\"></i>
            </a>
            <button type=\"button\" id=\"update_capital\" data-target=\"#modalFormCapital\" data-toggle=\"modal\"  
                   class=\"item\" data-id=\"$capital_id\" data-count=\"$count_capital\" data-date=\"$date_time_ad\" 
                   data-img=\"$slip\" data-balance=\"$balance_capital\"
            >
                <i class=\"fas fa-pencil-alt text-warning\"></i>
            </button>
            <button type=\"button\" class=\"item\" id=\"confirmTrashCapital\" data-img=\"$slip\" data-name=\"$count_capital\" data-id=\"$capital_id\">
              <i class=\"fas fa-trash-alt text-danger\"></i> 
            </button>
          </div>
      </td>
    </tr>
  ";
  echo $list;
}
function tableWithDraw($number, $withdraw_id, $withdraw_balance, $count_withdraw,$reason,$slip_withdrow, $date_time_ad){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$withdraw_balance</td>
      <td class=\"font-weight-bold\">$count_withdraw</td>
      
      <td class=\"font-weight-bold\">$date_time_ad</td>
      <td>
        <div class=\"table-data-feature row \" >
            <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"pdf\" target=\"_blank\" href=\"details/PDF/PDF_withdraw.php?withdraw_id=$withdraw_id\">
              <i class=\"fa-solid fa-file-pdf\"></i>
            </a>
            <button type=\"button\" id=\"update_withraw\" data-target=\"#modalFormWithdraw\" data-toggle=\"modal\"  
                   class=\"item\" data-id=\"$withdraw_id\" data-count=\"$count_withdraw\" data-date=\"$date_time_ad\"
                   data-img=\"$slip_withdrow\" data-reason=\"$reason\" data-balance=\"$withdraw_balance\"
            >
                <i class=\"fas fa-pencil-alt text-warning\"></i>
            </button>
            <button type=\"button\" class=\"item\" id=\"confirmTrashWithroaw\" data-img=\"$slip_withdrow\" data-name=\"$count_withdraw\" data-id=\"$withdraw_id\">
              <i class=\"fas fa-trash-alt text-danger\"></i> 
            </button>
          </div>
      </td>
    </tr>
  ";
  echo $list;
}

function setData($titleText, $number){
    $listData = "
      <div class=\"col-xl-3 col-md-6 mb-4\">
        <div class=\"card border-left-primary shadow  py-2 border\">
            <div class=\"card-body  pr-0\">
                <div class=\"row no-gutters align-items-center \">
                    <div class=\"col mr-2\">
                        <div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1 px-0 mx-0\">
                            $titleText
                        </div>
                        <div class=\"h5 mb-0 font-weight-bold text-gray-800\">$number บาท</div>
                    </div>
                </div>
            </div>
        </div>
      </div>";
      echo $listData;
  }

  function uiWorking($titleText,$average_cost, $average_sell){
    $list = "
    <div class=\"col-xl-3 col-md-6 mb-4\">
        <div class=\"card border-left-primary shadow  py-2 border\">
            <div class=\"px-3 py-2\">
                <div class=\"row no-gutters align-items-center\">
                    <div class=\"col mr-2\">
                        <div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">
                            $titleText
                        </div>
                        <div class=\"small mb-2 font-weight-bold text-danger\">ทุนตกลังละ: $average_cost บาท</div>
                        <div class=\"small mb-0 font-weight-bold text-success\">ขายตกลังละ: $average_sell บาท</div>
                    </div>
                </div>
            </div>
        </div>
      </div>";
    echo $list;
  }

function tableNameProduct($number,$id_product,$product_name,$price_default,$price_center,$count_cord,$shipping_cost){
  $list = "
    <tr>
      <td></td>
      <td>$number</td>
      <td class=\"font-weight-bold\">$product_name</td>
      <td class=\"font-weight-bold\">$price_default บาท</td>
      <td class=\"font-weight-bold\">$price_center บาท</td>
      <td class=\"font-weight-bold\">$count_cord คอต</td>
      <td class=\"font-weight-bold\">$shipping_cost บาท</td>
      <td>
          <div class=\"table-data-feature\" >
            <button type=\"button\" id=\"update_nameproduct\" data-target=\"#modalcreateformproduct\" data-toggle=\"modal\"  
                   class=\"item\" data-ids=\"$id_product\" data-names=\"$product_name\" data-countcost=\"$count_cord\"
                   data-price=\"$price_default\" data-pricecenter=\"$price_center\" data-shippingcost=\"$shipping_cost\"
            >
                <i class=\"fas fa-pencil-alt text-warning\"></i>
            </button>
            <button type=\"button\" class=\"item\" id=\"confirmTrashProductName\" data-id=\"$id_product\" data-name=\"$product_name\">
              <i class=\"fas fa-trash-alt text-danger\"></i> 
            </button>
          </div>
      </td>
    </tr>
  ";
  echo $list;
}

function tablelistsetOrder ($number, $orderid, $ordername,$lot_numbers, $totalcost_order, $price_order, $sliptImg, $date_time_order,$pay_times,$paid_total,$balance){
  $listOrder = "
  <form>
    <tr>
      <td>$number</td>
      <td class=\"font-weight-bold\">$ordername</td>
      <td class=\"font-weight-bold\">$lot_numbers</td>
      <td class=\"font-weight-bold\">".number_format($totalcost_order ?? 0,2,'.',',')." บาท</td>
      <td class=\"font-weight-bold\">".number_format($paid_total ?? 0,2,'.',',')." บาท</td>
      <td class=\"font-weight-bold\">".number_format($balance ?? 0,2,'.',',')." บาท</td>
      <td class=\"font-weight-bold\">$pay_times งวด</td>
      <td>
          <div class=\"account-item account-item--style2 clearfix js-item-menu\">
              <div class=\"image\">
                  <img src=\"../db/slip-orders/$sliptImg \" alt=\"John Doe\" />
              </div>
          </div>
      </td>
      <td>
          <div class=\"table-data-feature\" >
            <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"จัดสรรทุน\" href=\"details/detail_orderbuy.php?orderbuy_id=$orderid \">
              <i class=\"fas fa-list-alt\"></i>
            </a>
            
            <button type=\"button\" id=\"update_order\" data-target=\"#modalFormUpdateOrder\" data-toggle=\"modal\"  
                   class=\"item\" data-id=\"$orderid\" data-ordername=\"$ordername\" data-totalcost=\"$totalcost_order\" 
                   data-priceorder=\"$price_order\" data-slipimage=\"$sliptImg\" data-dateorder=\"$date_time_order\" data-lot=\"$lot_numbers\"
            >
                <i class=\"fas fa-pencil-alt text-warning\"></i>
            </button>
            <button type=\"button\" class=\"item\" id=\"confirmTrashOrder\" data-id=\"$orderid\" data-ordername=\"$ordername\">
              <i class=\"fas fa-trash-alt text-danger\"></i> 
            </button>
          </div>
      </td>
    </tr>
    </form>
  ";
  echo $listOrder;
}

function tablelistStock ($number, $id_product,$is_productname, $total_order, $total_count, $total_price, $total_sell){
  $remaining_amount = $total_count - $total_sell;
  $listStock = "
  <form>
    <tr>
      <td class=\"font-weight-bold\">$number</td>
      <td class=\"font-weight-bold\">$is_productname</td>
      <td class=\"font-weight-bold\">$total_count ลัง</td> 
      <td class=\"font-weight-bold\">$total_sell ลัง</td> 
      <td class=\"font-weight-bold\">$remaining_amount ลัง</td> 
      <td class='text-center'>
          <div class=\"table-data-feature\" >
            <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"จัดสรรทุน\" href=\"details/detail_stock.php?id_productname=$id_product \">
              <i class=\"fas fa-list-alt\"></i>
            </a>
          </div>
      </td>
    </tr>
    </form>
  ";
  echo $listStock;
}

function tableDetailStock($number, $product_id,$id_nameproduct,$is_product_name,$productcount,$productprice, $count_cord,$price_center,$ordername,$lot_numbers, $datetime_order,$shipping_cost,$expenses){
    $toal_all = $productprice * $productcount;
    $list_stock = "
        <form>
            <tr>
              <td class=\"font-weight-bold\">$number</td>
              <td class=\"font-weight-bold\">$is_product_name</td>
              <td class=\"font-weight-bold\">$lot_numbers</td>
              <td class=\"font-weight-bold\">".number_format($productprice ?? 0,2,'.',',')." บาท</td>
              <td class=\"font-weight-bold\">".number_format($price_center ?? 0,2,'.',',')." บาท</td>
              <td class=\"font-weight-bold\">".number_format($productcount ?? 0)." ลัง</td>
              <td class=\"font-weight-bold\">".number_format($toal_all ?? 0,2,'.',',')." บาท</td> 
              <td class=\"font-weight-bold\">".number_format($shipping_cost ?? 0,2,'.',',')." บาท</td> 
              <td class=\"font-weight-bold\">".number_format($expenses ?? 0,2,'.',',')." บาท</td> 
              <td class=\"font-weight-bold\">$datetime_order</td> 
            </tr>
        </form>
    ";
    echo $list_stock;
}

function typecustomse($type){
    if($type === "price_customer_dealer"){
      return "ตัวแทนจำหน่าย";
    }else if($type === "price_levels_one"){
      return "ลูกค้า vip";
    }else if($type === "price_customer_frontstore"){
      return "ลูกค้าหน้าร้าน";
    }else if($type === "price_customer_deliver"){
      return "การจัดส่ง";
    }else{
      return $type;
    }
  }

function tableDetailStockSell($number, $product_id,$productname,$ordername, $productcount,$productprice, $rate_custom, $datetime_order){
    $toal_all = $productprice * $productcount;
    $list_stock = "
        <form>
            <tr>
              <td class=\"font-weight-bold\">$number</td>
              <td class=\"font-weight-bold\">$productname</td>
              <td class=\"font-weight-bold\">$ordername</td>
              <td class=\"font-weight-bold\">". typecustomse($rate_custom) ."</td> 
              <td class=\"font-weight-bold\">".number_format($productprice ?? 0,2,'.',',')." บาท</td> 
              <td class=\"font-weight-bold\">".number_format($productcount ?? 0)." ลัง</td> 
              <td class=\"font-weight-bold\">$toal_all</td> 
              <td class=\"font-weight-bold\">$datetime_order</td> 
            </tr>
        </form>
    ";
    echo $list_stock;
}

function detailStock($productname,$total_count,$count_cord,$total_price,$product_price, $count_sell, $income_price, $number_of_timessold){
    $Average1_piece = $total_price / $total_count;
    $remaining_products = $total_count - $count_sell;
    $cost_price = $Average1_piece * $count_sell;
    $total_profit = $income_price - $cost_price;
    $detail = "
    
        <div class=\"rounded row mt-4 p-4\">
           <div class=\"col-lg-3 col-md-3 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded info-box rounded\">
            <span>ค่าเฉลี่ยสินค้า $productname ลังละ</span>
            <p class=\"font-weight-bold res_text\"> ".number_format($Average1_piece ?? 0,2,'.',',')." บาท </p>
          </div>
          <div class=\"col-lg-3 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
            <span>จำนวนสิ้นค้าทัั้งหมด</span>
                <p class=\"font-weight-bold res_text\">". number_format($total_count) ." ลัง (".number_format($count_cord ?? 0)."คอต)</p>
          </div>
          <div class=\"col-lg-2 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
            <span>ต้นทุนทั้งหมด</span>
            <p class=\"font-weight-bold res_text\"> ".number_format($total_price ?? 0,2,'.',',')."  บาท</p>
          </div>
          <div class=\"col-lg-3 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
                <span>จำนวนสินค้าที่ขายไปแล้ว</span>
                <p class=\"font-weight-bold res_text\"> ".number_format($count_sell)."  ลัง</p>
          </div>
          <div class=\"col-lg-3 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
                <span>สินค้าที่เหลือ</span>
                <p class=\"font-weight-bold res_text\"> ".number_format($remaining_products)."  ลัง</p>
          </div>
          <div class=\"col-lg-3 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
            <span>ราคาที่ขายได้</span>
            <small class=\"text-danger\">ต้นทุน ".number_format($cost_price ?? 0,2,'.',',')." บาท</small>
           <p class=\"font-weight-bold res_text\"> ".number_format($income_price ?? 0,2,'.',',')." บาท</p>
          </div>
          <div class=\"col-lg-2 col-md-3 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
                <span>ยอดกำไร</span>
                <p class=\"font-weight-bold res_text\">".number_format($total_profit ?? 0,2,'.',',')."  บาท</p>
          </div>
          <div class=\"col-lg-3 col-md-4 col-sm-6 col-12 m-2 p-3 bg-white info-box rounded\">
            <span>จำนวนครั้งที่ขายได้</span>
            <p class=\"font-weight-bold res_text\">". number_format($number_of_timessold)." ครั้ง</p>
          </div>
        </div>

    ";

    echo $detail;
}

function statusRatePrice($rate_id,$levelrate,$rate_storefront_price,$rate_onelevel_price,$rate_dealer_price,$rate_delivery_price,$product_name,$price_product,$id_name,$productpricecenter,$countcords,$shipping_cost){
    if($rate_id){
        return "
            <div class=\"table-data-feature\" >
                <button type=\"button\" id=\"setupdate_rate_price\" data-target=\"#modalFormUpdateRate\" data-toggle=\"modal\"  
                   class=\"item\" data-id=\"$rate_id\" data-product=\"$product_name\" data-idname=\"$id_name\" data-level=\"$levelrate\" data-storefront=\"$rate_storefront_price\" data-vip=\"$rate_onelevel_price\" 
                   data-dealers=\"$rate_dealer_price\" data-delivery=\"$rate_delivery_price\" data-productprice=\"$price_product\" data-productpricecenter=\"$productpricecenter\"
                   data-countcord=\"$countcords\" data-shippingcost=\"$shipping_cost\"
                >
                    <i class=\"fas fa-pencil-alt text-warning\"></i>
                </button>
            </div>
        ";
    }else{
        return "
          <div class=\"table-data-feature\" >
              <button type=\"button\" id=\"set_rate_price\" data-target=\"#modalFormCreateRate\" data-toggle=\"modal\"  
                 class=\"item\" data-id=\"$rate_id\" data-product=\"$product_name\" data-idname=\"$id_name\" data-productprice=\"$price_product\" data-productpricecenter=\"$productpricecenter\"
                 data-countcord=\"$countcords\" data-shippingcost=\"$shipping_cost\"
              >
                  <i class=\"fas fa-plus text-success\"></i>
              </button>
          </div>
        ";
    }
}

function listRatePrice($rate_id="", $levelrate="", $rate_storefront_price="",$rate_onelevel_price="",$rate_dealer_price="ยังไม่ได้กำหนด",$rate_delivery_price="",$product_name, $price_product,$id_name,$productpricecenter,$countcords,$shipping_cost){
    
    $list = "
        <form>
            <tr>
                <td></td>
                <td class=\"font-weight-bold\">$levelrate</td>
                <td class=\"font-weight-bold\">$rate_onelevel_price บาท</td>
                <td class=\"font-weight-bold\">$rate_storefront_price บาท</td>
                <td class=\"font-weight-bold\">$rate_dealer_price บาท</td>
                <td class=\"font-weight-bold\">$rate_delivery_price บาท</td>
                <td>
                    ".statusRatePrice($rate_id,$levelrate,$rate_storefront_price,$rate_onelevel_price,$rate_dealer_price,$rate_delivery_price,$product_name,$price_product,$id_name,$productpricecenter,$countcords,$shipping_cost)."
                </td>
            </tr>
        </form>
    ";

    echo $list;

}

function listOrderSell($number, $ordersell_id, $ordersell_name,$count_item, $price_total,$customer_name,$date_sell,$list_typepays,$sum_amount_paid){

  $listorder = "
      <tr>
        <td class=\"font-weight-bold \"> $number</td>
        <td class=\"font-weight-bold\">$ordersell_name</td>
        <td class=\"font-weight-bold\">$count_item รายการ</td> 
        <td class=\"font-weight-bold\">".number_format($price_total ?? 0,2,'.',',')." บาท</td> 
        <td class=\"font-weight-bold\">".number_format($sum_amount_paid ?? 0,2,'.',',')." บาท</td> 
        <td class=\"font-weight-bold\">$customer_name</td> 
        <td class=\"font-weight-bold\">$list_typepays</td> 
        
        <td class='text-center'>
            <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"จัดสรรทุน\" href=\"details/detail_ordersell.php?ordersell_id=".$ordersell_id." \">
                <i class=\"fas fa-list-alt\"></i>
              </a>
              <button type=\"button\" id=\"edit_order_sell\" data-target=\"#modalFormUpdateOrderSell\" data-toggle=\"modal\"  
                       class=\"item\" data-ordersellid=\"$ordersell_id\" data-ordersellname=\"$ordersell_name\" data-pricetotal=\"$price_total\" 
                       data-customername=\"$customer_name\" data-datesell=\"$date_sell\"
                    >
                        <i class=\"fas fa-pencil-alt text-warning\"></i>
                    </button>
                    <button type=\"button\" class=\"item\" id=\"confirmTrashOrderSell\" data-id=\"$ordersell_id\" data-ordersell=\"$ordersell_name\">
                      <i class=\"fas fa-trash-alt text-danger\"></i>
                    </button>
            </div>
        </td>
      </tr>

  ";
  echo $listorder;
}

function status_deliver($shippingnote, $sender, $wages){
  if($wages){
    $lists = "
      <div class=\"col-sm-12 col-lg-8\">
        <h3 class=\"fs-3 font-thi\">สถานะการจัดส่ง </h3>
        <div class=\"col-12 row\">
          <span class=\"font-thi font-bold mx-4\">$shippingnote</span>
          <span class=\"font-thi font-bold mx-4\">$sender</span>
          <span class=\"font-thi font-bold mx-4\">$wages</span>
        </div>
      </div>
    ";
    return $lists;
  }
}
  function set_type($sell_types){
    $list = "<tr>
        <td>ตัวเลือกการจ่าย: </td>
    ";
      
      foreach($sell_types as $type){
        $list .= '<td class=\"font-weight-bold"\">'. htmlspecialchars($type['list_typepay'], ENT_QUOTES) .'</td>';
      }
    $list.="</tr>";
    return $list;
  }
  

  function slip($img_slip){
    if($img_slip !==""){ 
      return "
        <div>
          <span class=\"col-12\">หลักฐานการโอน</span>
        </div>
        <img src=\"http://localhost/smokker24hours/db/slip-sellorder/$img_slip \" class=\"img-sell\"/> ";
    }
  }
function status_pays($totalprice,$custompay,$countstuck){
  if($totalprice == $custompay){
    return "<p class=\"text-success font-weight-bold\">[ จ่ายครบถ้วน ]</p>";
  }elseif($totalprice == $countstuck){
    return "<p class=\"text-danger font-weight-bold\">[ ติดค้าง ]</p>";
  }else{
    return "<p class=\"text-danger font-weight-bold\">[ จ่ายแล้วแต่ยังติดค้าง ]</p>";
  }
}
function detailOrderSell($id_ordersell,$ordersell_name,$is_totalprice,$custome_name,$tell_custome,$location_send,$name_peplegroup,$date_time_sell,$total,$totalproduct,$reasons,$sender,$tell_sender,$count_totalpays,$count_stuck,$slip_ordersell,$adder_id,$create_at,$sell_type,$count_pays){
  $list = "
    <div class=\"col-12 p-0 m-0\">
      <div class=\"col-12 row\">
         <a href=\"PDF/PDF_ordersell.php?ordersell_id=$id_ordersell\" target=\"_blank\" class=\"ml-auto px-4 py-1 w-22 btn-print mt-4\">
            <i class=\"fas fa-file-code\"></i>
            Print PDF
          </a>
          ";
          if($count_pays > 0){
            $list .= "<a href=\"PDF/PDF_ordersell.php?ordersell_id=$id_ordersell\" target=\"_blank\" class=\"ml-1 px-4 py-1 w-22 btn-print mt-4\">
              <i class=\"fas fa-file-code\"></i>
              New Print PDF
            </a>";
          }
        $list .="
          
      </div>
      <div class=\"col-12 row p-0 m-0\">
        <div class=\"col-lg-3 row\">
          รายการ: <p class=\"font-weight-bold\" style=\"color:#cc00ff\">[ $ordersell_name ]</p>
        </div>

        <div class=\"col-lg-3 row\">
          จำนวนรายการ: <p class=\"font-weight-bold\">[ $total ] รายการ</p>
        </div>
        <div class=\"col-lg-3 row\">
          ราคารวม: <p class=\"font-weight-bold\">[ $is_totalprice ]</p> บาท
        </div>
        <div class=\"col-lg-3 p-0 m-0 row\">
          วันที่ชื่อ: <p class=\"font-weight-bold\">[ $date_time_sell ]</p>
        </div>
      </div>
      <div class=\"row p-0 m-0\">
        <div class=\"col-sm-12 col-md-7 col-lg-9 p-0 m-0 row\">
        <div class=\"col-lg-6 col-xl-4 row\">
            ชื่อผู้ขาย: <p class=\"font-weight-bold text-primary\">[ $name_peplegroup ]</p>
          </div>
          <div class=\"col-lg-6 col-xl-4 row\">
            ชื่อผู้ซื้อ: <p class=\"font-weight-bold text-primary\">[ $custome_name ]</p>
          </div>
          <div class=\"col-lg-6 col-xl-4 row p-0 m-0\">
            สถานะ: ".status_pays($is_totalprice,$count_totalpays,$count_stuck)."
          </div>
          <div class=\"col-lg-6 col-xl-4 row\">
              จำนวนสินค้าทั้งหมด <p class=\"font-weight-bold\">[ $totalproduct ] </p> ลัง
          </div>
          <div class=\"col-sm-6 col-lg-5 col-xl-4 row p-0 m-0\">
            จำนวนเงินที่จ่าย: <p class=\"font-weight-bold text-success\">[ $count_totalpays ] </p> บาท
          </div>
          <div class=\"col-sm-6 col-lg-5 col-xl-4 row p-0 m-0\">
            จำนวนเงินที่ค้าง: <p class=\"font-weight-bold text-danger\">[ $count_stuck ] </p> บาท
          </div>
         
          <div class=\"col-md-12 col-lg-6 col-xl-5 m-0 p-0 \">
            <table class=\"table table-data-pay\">
              <tbody>
                ". set_type($sell_type) ."
              </tbody>
            </table>
          </div>
        </div>
        <div class=\"col-sm-12 col-md-5 col-lg-3\">
          <div class=\"div-img\">
            ". slip($slip_ordersell) ."
          </div>
        </div>
      </div>
    </div>
  ";
  echo $list;
}

function detailCustomer($customname){

}
function boxCustom($titleText, $data, $type){
  $listData = "
      <div class=\"col-xl-4 col-md-6 mb-2\">
        <div class=\"card shadow  border\">
            <div class=\"card-body\">
                <div class=\"row no-gutters align-items-center\">
                    <div class=\"col mr-2\">
                        <div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">
                            $titleText
                        </div>
                        <div class=\"h5 mb-0 font-weight-bold text-gray-800\">$data $type</div>
                    </div>
                </div>
            </div>
        </div>
      </div>";
    echo $listData;
}

function listProductSell($number,$product_id, $product_nameid,$product_name, $rate_customerprice, $type_custom, $lotal_product, $price_topay){


  $list = "
    <tr>
      <td class=\"font-weight-bold\">$number</td>
      <td class=\"font-weight-bold\">$product_name</td>
      <td class=\"font-weight-bold\">$rate_customerprice</td>
      <td class=\"font-weight-bold\">$lotal_product</td>
      <td class=\"font-weight-bold\">$price_topay</td>
      <td class=\"font-weight-bold\">". typecustomse($type_custom) ."</td>
      <td class='text-center'>
            <div class=\"table-data-feature\" >
              <button type=\"button\" id=\"update_order_sell\" data-target=\"#modalFormUpdateOrderSell\" data-toggle=\"modal\"  
                 class=\"item\" data-id=\"$product_id\" data-productname=\"$product_nameid\" data-customerprice=\"$rate_customerprice\" 
                 data-totalproduct=\"$lotal_product\" data-pricetopay=\"$price_topay\" data-customtype=\"$type_custom\"
              >
                  <i class=\"fas fa-pencil-alt text-warning\"></i>
              </button>
              <button type=\"button\" class=\"item\" id=\"falseTrashBtnProject\" data-id=\"$product_id\">
                <i class=\"fas fa-trash-alt text-danger\"></i>
              </button>
           </div>
        </td>
    </tr>
  ";
  echo $list;
}

function listDetailOrderBuy($order_id, $order_name, $total_cost, $data_time_buy, $order_slipt, $total_product, $count_product,$count_cord){
  $list = "
    <div class=\"col-12 p-0 m-0\">
        <div class=\"col-12 row my-4\">
          <a href=\"PDF/PDF_orderbuy.php?order_id=$order_id\" target=\"_blank\" class=\"border ml-auto  py-2 px-5\">
            <i class=\"fas fa-file-code\"></i>
            Print PDF
          </a>
        </div>
        <div class=\"col-12 row\">
          <div class=\"col-9 row\">
            <div class=\"col-12\">
              <h3 class=\"card-title font-bold row\">รายการคำสั่งซื้อ : 
                <div class=\"mx-3\" style=\"color:#cc00ff\"> [&nbsp;&nbsp; $order_name &nbsp;&nbsp;] </div>
              </h3>
            </div>
            <div class=\"col-12 row gap-x-4 my-4\">
              <div> ค่าใช้จ่าย : <span style=\"color:#ff944d\"> [ $total_cost ] </span>บาท </div>
              <div class=\"mx-5\"> ประเภทสินค้า : <span style=\"color:#9933ff\"> [ $total_product ] </span> รายการ</div>
              <div class=\"mt-3\"> จำนวน : <span style=\"color:#ff0066\"> [ $count_product ] </span> ลัง / <span style=\"color:#ff0066\"> [ $count_cord ] </span> คอต</div>
            </div>
            <div class=\"col-12 row mb-4\">
              <div class=\"font-bold\">
                สั่งซื้อเมื่อ : <span style=\"color:#0066ff\" class=\"\"> $data_time_buy </span>
              </div>
            </div>
          </div>
          <div class=\"col-3 row\">
            <div><span class=\"col-12\">หลักฐานการโอน</span></div>
            <div class=\"img-buylist\">
              <img class=\"\" width=\"120\" height=\"125\" src=\"../../db/slip-orders/$order_slipt\" />
            </div>
          </div>
        </div>
    </div>
  ";
  echo $list;
}

function listProductBuy($number,$product_id, $product_name, $cost_price,$price_center, $total_product,$count_cord, $prices,$shipping_cost){
  $list = "
    <tr>
      <td class=\"font-weight-bold \"> $number</td>
      <td class=\"font-weight-bold \"> $product_name</td>
      <td class=\"font-weight-bold \"> $cost_price บาท</td>
      <td class=\"font-weight-bold \"> $price_center บาท</td>
      <td class=\"font-weight-bold \"> $total_product ลัง</td>
      <td class=\"font-weight-bold \">". $prices - $shipping_cost." บาท</td>
      <td class=\"font-weight-bold \"> $shipping_cost บาท</td>
      <td class=\"font-weight-bold \"> $prices บาท</td>
    </tr>
  ";
  echo $list;
}

function listCustomer($number,$custom_name,$count_order,$pricessell,$pricespay,$countstuck,$count_debt,$count_paydebt){
  $price_balnce_stuck = $countstuck - $count_debt;
  $list = "
    <tr>
      <td class=\"font-weight-bold\"></td>
      <td class=\"font-weight-bold\">$number</td>
      <td class=\"font-weight-bold\">$custom_name</td>
      <td class=\"font-weight-bold\">$count_order รายการ</td>
      <td class=\"font-weight-bold\">$pricessell บาท</td>
      <td class=\"font-weight-bold text-success\">$pricespay บาท</td>
      
      <td class=\"font-weight-bold text-danger\">$price_balnce_stuck บาท</td>
      <td class=\"font-weight-bold\">$count_paydebt ครั้ง</td>
      <td class='text-center'>
            <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"จัดสรรทุน\" href=\"details/detail_customer.php?custom_name=".urlencode($custom_name)." \">
                <i class=\"fas fa-list-alt\"></i>
              </a>
              <button 
                type=\"button\" data-toggle=\"modal\" class=\"item\" data-custome=\"$custom_name\" data-debt=\"$price_balnce_stuck\"
                data-types=\"OUT\" data-target=\"#modalFormPayOffDebt\" id=\"modelpayoff_debt\"
              >
                <i class=\"fa-solid fa-circle-dollar-to-slot\"></i>
              </button>
          </div>
        </td>
    </tr>
  ";
  echo $list;
}

function listOrderForCustomer($number, $order_id,$order_name,$date_sell,$prices_all,$price_pay,$price_stuck,$count_product,){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$number</td>
      <td class=\"font-weight-bold\">$order_name</td>
      <td class=\"font-weight-bold\">$date_sell</td>
      <td class=\"font-weight-bold\">$prices_all</td>
      <td class=\"font-weight-bold\">$price_pay</td>
      <td class=\"font-weight-bold\">$price_stuck</td>
      <td class=\"font-weight-bold\">$count_product</td>
      <td class='text-center'>
          <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"จัดสรรทุน\" href=\"../details/detail_ordersell.php?ordersell_id=$order_id \">
                <i class=\"fas fa-list-alt\"></i>
              </a>
          </div>
        </td>
    </tr>
  ";
  echo $list;
}

function listhistoryPayDebt($number,$id_paydebt,$serial_number,$customer_name,$text_orther,$count_paydebt,$debt_balance,$date_pay,$img_pay){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$number</td>
      <td class=\"font-weight-bold\">$serial_number</td>
      <td class=\"font-weight-bold\">$count_paydebt บาท</td>
      
      <td class=\"font-weight-bold\">$date_pay</td>
      <td class=\"font-weight-bold\">$text_orther</td>
      <td>
        <div class=\"account-item account-item--style2 clearfix js-item-menu\">
              <div class=\"image\">
                  <img src=\"../../db/slip-paydebt/$img_pay \" alt=\"John Doe\" />
              </div>
          </div>
      </td>
      <td class='text-center'>
          <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"pdf\" target=\"_blank\" href=\"../details/PDF/PDF_historydebt.php?id_paydebt=$id_paydebt \">
                <i class=\"fa-solid fa-file-pdf\"></i>
              </a>
              <button type=\"button\" id=\"update_order_sell\" data-target=\"#modalFormUpdateOrderSell\" data-toggle=\"modal\"  
                 class=\"item\" data-id=\"$id_paydebt\"
              >
                  <i class=\"fas fa-pencil-alt text-warning\"></i>
              </button>
              <button type=\"button\" class=\"item\" id=\"confirmTrashPayOffDebt\" data-id=\"$id_paydebt\" data-count=\"$count_paydebt\" data-name=\"$customer_name\" data-img=\"$img_pay\" >
                <i class=\"fas fa-trash-alt text-danger\"></i>
              </button>
           </div>
        </td>
    </tr>
  ";
  echo $list;
}

function listPepleGroup($number,$idgroup, $namegroup,$phonegroup,$status,$countsell, $price_allsell, $profitprice){
  $list = "
    <tr>
      <td class=\"font-weight-bold\">$namegroup</td>
      <td class=\"font-weight-bold\">$countsell</td>
      <td class=\"font-weight-bold\">$price_allsell</td>
      <td class=\"font-weight-bold\">$profitprice</td>
      <td class='text-center'>
        <div class=\"table-data-feature\" >
            <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"pdf\" target=\"_blank\" href=\"../details/PDF/PDF_historydebt.php?id_paydebt=$idgroup \">
              <i class=\"fa-solid fa-file-pdf\"></i>
            </a>
            <button type=\"button\" id=\"update_peplegroup\" data-target=\"#modalFormPepleGroup\" data-toggle=\"modal\"  
               class=\"item\" data-id=\"$idgroup\" data-name=\"$namegroup\" data-phone=\"$phonegroup\" data-status=\"$status\"
            >
                <i class=\"fas fa-pencil-alt text-warning\"></i>
            </button>
            <button type=\"button\" class=\"item\" id=\"confirmTrashPepleGroup\" data-id=\"$idgroup\" >
              <i class=\"fas fa-trash-alt text-danger\"></i>
            </button>
         </div>
      </td>
    </tr>
  ";
  echo $list;
}

function listLotProduct($number, $lot_no, $listcount, $total_inlot, $totalsell, $remain, $price_all, $pricecenter_all, $shipping_cost,$expenses, $price_seller){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$lot_no</td>
      
      <td class=\"\">$total_inlot ลัง</td>
      <td class=\"\">$totalsell ลัง</td>
      <td class=\"\">$remain ลัง</td>
      <td class=\"\">$listcount รายการ</td>
    
      
      <td class=\"text-center\">
        <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"detail lot\" href=\"details/detail_lot.php?lot_number=$lot_no\">
                <i class=\"fas fa-list-alt\"></i>
              </a>
          </div>
      </td>
    </tr>
  ";
  echo $list;
}

function listResutlFinance(
  $number, $lot_no, $listcount, $total_inlot, $totalsell, $remain, $price_all, $pricecenter_all, $shipping_cost,$expenses,
  $capital_using,$capital_received,$capital_delshipping,$captial_shipping, $pricecenter_using, $pricecenter_received,
  $pricecenter_delcaptialshiipin, $profit
  ){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$lot_no</td>
      <td class=\"\">$total_inlot ลัง <br/> ขาย $totalsell ลัง <br/> เหลือ $remain ลัง</td>
      <td class=\"\">".number_format($price_all ?? 0,2,'.',',')." บาท</td>
      
      <td class=\"\">".number_format($capital_received ?? 0,2,'.',',')." บาท</td>
      <td class=\"\">".number_format($pricecenter_received ?? 0,2,'.',',')." บาท</td>
      <td class=\"\">".number_format($pricecenter_delcaptialshiipin ?? 0,2,'.',',')." บาท</td> 
      
      <td class=\"text-center\">
        <div class=\"table-data-feature\" >
              <a class=\"item\" data-toggle=\"tootip\" data-placement=\"top\" title=\"detail lot\" href=\"details/detail_resultfinance.php?lot_number=$lot_no\">
                <i class=\"fas fa-list-alt\"></i>
              </a>
          </div>
      </td>
    </tr>
  ";
  echo $list;
}

function detailLotProduct(
  $num,$id,$p_name,$id_pname,$lot_no,$countin_lot,$total_sell,$remainQty,$price_def,$priceall_def,$price_center,
  $priceall_center,$shipping_one,$shipping_all,$expenses,$totalsell,$date
){
    $profit_center = $priceall_def - $price_center;
    $profit_sell = $price_center - $totalsell;
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$p_name</td>
      <td class=\"\">$countin_lot ลัง</td>
      <td class=\"\">$total_sell ลัง</td>
      <td class=\"\">$remainQty ลัง</td>
      <td class=\"\">$date</td>
      
    </tr>
  ";
  echo $list;
  
}

function detailLotFinance(
  $num,$id,$p_name,$id_pname,$lot_no,$countin_lot,$total_sell,$remainQty,$price_def,$priceall_def,$product_price,$price_center,
  $priceall_center,$pricecenter_delcaptialshipping,$shipping_one,$shipping_all,$price_seller,$expenses,$date
){
  $list = "
    <tr>
      <td></td>
      <td class=\"font-weight-bold\">$p_name</td>
      <td class=\"\">".number_format($total_sell)." ลัง</td>
      <td class=\"\">".number_format($product_price)." บาท</td>
      <td class=\"\">".number_format($price_center)." บาท</td>
      <td class=\"\">".number_format($price_def)." บาท</td>

      <td class=\"\">".number_format($priceall_def)." บาท</td>
      <td class=\"\">".number_format($priceall_center)." บาท</td>
      <td class=\"\">".number_format($pricecenter_delcaptialshipping)." บาท</td>
    
      
    </tr>
  ";
  echo $list;
  
}

?>