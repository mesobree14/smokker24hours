class modalCreateProduct extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.renders();
  }
  renders() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalcreateformproduct" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มข้อมูลสินค้า</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="myForm" method="post" action="backend/create_productname.php" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" name="status_form" value="create" />
                <input type="hidden" name="id_name" id="idnames" />
                <div class="mt-2 row border">
                  <div class="col-md-12">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อสินค้า</label>
                      <input type="text" class="form-control" name="product_name" id="productname" placeholder="ชื่อสินค้า" required>
                    </div> 
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ราคาต้นทุนต่อลัง</label>
                      <input type="text" class="form-control" name="price_default" id="pricedefault" placeholder="ราคาต้นทุน" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลางต่อลัง</label>
                      <input type="text" class="form-control" name="price_center" id="pricecenter" placeholder="ราคากลาง" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอตต่อลัง</label>
                      <input type="number" class="form-control" name="count_cord" id="countcord" placeholder="จำนวนคอต" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่งต่อลัง</label>
                      <input type="text" class="form-control" name="shipping_cost" id="shippingcost" placeholder="ค่าส่ง" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary ml-auto mr-4">บันทึกข้อมูล</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }
}

customElements.define("main-create-product", modalCreateProduct);
$(document).on("click", "#openModalFormNameProduct", function (e) {
  $("#idnames").val("");
  $("#productname").val("");
  $("#pricedefault").val("");
  $("#pricecenter").val("");
  $("#countcord").val("");
  $("#shippingcost").val("");
  e.preventDefault();
});
$(document).on("click", "#update_nameproduct", function (e) {
  let id_name = $(this).data("ids");
  let name_product = $(this).data("names");
  let countcost = $(this).data("countcost");
  let price = $(this).data("price");
  let pricecenter = $(this).data("pricecenter");
  let shippingcost = $(this).data("shippingcost");

  $("#idnames").val(id_name);
  $("#productname").val(name_product);
  $("#pricedefault").val(price);
  $("#pricecenter").val(pricecenter);
  $("#countcord").val(countcost);
  $("#shippingcost").val(shippingcost);
  e.preventDefault();
});

$(document).on("click", "#confirmTrashProductName", function (e) {
  let ID = $(this).data("id");
  let name = $(this).data("name");
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `ราชื่อ ${name} นี้ มีความเกี่ยวโยงกับรายการต่าง หากลบจะมีข้อมูลบางส่วนหายไป จะไม่สามารถย้อนกลับได้`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ยกเลิก",
    confirmButtonText: "ยืนยัน",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        //const responseapi = await fetch(
        //  `http://localhost/smokker24hours/system/backend/api/order.php?order_id=${ID}`,
        //  {
        //    method: "DELETE",
        //    credentials: "include",
        //  }
        //);
        //const responsedata = await responseapi.json();
        //console.log("s=", responsedata.status);
        //if (responsedata.status === 201) {
        //console.log(responsedata);
        Swal.fire({
          title: "เรียบร้อย",
          text: `ลบ order นี้เรียบร้อยแล้ว ${ID}`,
          icon: "success",
          showConfirmButton: false,
        }).then(() => {
          window.location.reload();
        });
        //}
      } catch (e) {
        throw new Error(`Is Delete Error : ${e}`);
      }
    }
  });
});
