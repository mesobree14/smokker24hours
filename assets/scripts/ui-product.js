class modalCreateProduct extends HTMLElement {
  constructor() {
    super();
  }
  static get observedAttributes() {
    return ["idProductname"];
  }
  get IsIdProductname() {
    return this.getAttribute("idProductname");
  }
  connectedCallback() {
    this.addEventListener("getProduct", async (e) => {
      const { id_productname, productname } = e.detail;
      await this.getRateLevel(id_productname);
      console.log("Get Product in Modal Create Product : ", productname);
      // You can add additional logic here to handle the received data
    });
    this.renders();
    this.setScript();
  }
  async getRateLevel(product_id) {
    try {
      const response = await fetch(
        `backend/api/api_rate_price.php?id_productname=${product_id}`,
        {
          method: "GET",
          credentials: "include",
        }
      );
      const data = await response.json();
      document
        .querySelectorAll(".form-control-rate-id")
        .forEach((el) => el.remove());
      if (data.data && data.data.length > 0) {
        data.data.forEach((rate, i) => {
          const level = rate.level_sell;

          const rateId = document.createElement("input");
          rateId.classList.add("form-control-rate-id");
          rateId.id = `rate_id-${i}`;
          rateId.name = "rate_id[]";
          rateId.value = rate.rate_id;
          rateId.type = "text";
          rateId.style.display = "none";

          document.getElementById(`rate_price_vip-${level}`).value =
            rate.price_levels_one;
          document.getElementById(`rate_price_storefront-${level}`).value =
            rate.price_customer_frontstore;
          document.getElementById(`rate_price_dealers-${level}`).value =
            rate.price_customer_deliver;
          document.getElementById(`rate_price_delivery-${level}`).value =
            rate.price_customer_deliver;
          let lavel = document.querySelectorAll(`.is_level_${level}`);
          lavel.forEach((element) => {
            element.appendChild(rateId);
          });
        });
      }
      return data;
    } catch (error) {
      console.error("Error fetching rate level:", error);
      return null;
    }
  }
  setScript() {
    const container = document.querySelector(".form-is-level");
    for (let i = 1; i <= 4; i++) {
      const groupLevel = document.createElement("div");
      const groupOne = document.createElement("div");
      const groupTwo = document.createElement("div");
      const groupTree = document.createElement("div");
      const groupFour = document.createElement("div");
      groupLevel.classList.add("input-goup", "col-md-2");
      groupOne.classList.add("input-goup", "col-md-2");
      groupTwo.classList.add("input-goup", "col-md-3");
      groupTree.classList.add("input-goup", "col-md-3");
      groupFour.classList.add("input-goup", "col-md-2");

      const groupIn = document.createElement("div");
      groupIn.classList.add("form-group", "mb-2");
      const groupInOne = document.createElement("div");
      groupInOne.classList.add("form-group", "mb-2");
      const groupInTwo = document.createElement("div");
      groupInTwo.classList.add("form-group", "mb-2");
      const groupInTree = document.createElement("div");
      groupInTree.classList.add("form-group", "mb-2");
      const groupInFour = document.createElement("div");
      groupInFour.classList.add("form-group", "mb-2");

      const labelLevel = document.createElement("label");
      labelLevel.textContent = `ระดับที่ ${i}`;
      labelLevel.classList.add("mt-0", "mb-0", "font-weight-bold", "text-dark");
      const levelInput = document.createElement("input");
      levelInput.id = `level-rate-${i}`;
      levelInput.classList.add("form-control");
      levelInput.type = "number";
      levelInput.name = "level_sell[]";
      levelInput.value = i;
      levelInput.readOnly = true;
      levelInput.required = true;

      const labelOne = document.createElement("label");
      labelOne.textContent = `ราคา เรท 1 vip`;
      labelOne.classList.add(
        "mt-0",
        "mb-0",
        "font-weight-bold",
        "text-dark",
        `is_level_${i}`
      );
      const rateOneInput = document.createElement("input");
      rateOneInput.id = `rate_price_vip-${i}`;
      rateOneInput.classList.add("form-control");
      rateOneInput.name = "rate_vip1[]";
      rateOneInput.type = "text";
      rateOneInput.required = true;

      const labelTwo = document.createElement("label");
      labelTwo.textContent = `ราคา เรท 2 หน้าร้าน`;
      labelTwo.classList.add("mt-0", "mb-0", "font-weight-bold", "text-dark");
      const rateTwoInput = document.createElement("input");
      rateTwoInput.id = `rate_price_storefront-${i}`;
      rateTwoInput.classList.add("form-control");
      rateTwoInput.type = "text";
      rateTwoInput.name = "rate_storefront2[]";
      rateTwoInput.required = true;

      const labelTree = document.createElement("label");
      labelTree.textContent = `ราคา เรท 3 ตัวแทน`;
      labelTree.classList.add("mt-0", "mb-0", "font-weight-bold", "text-dark");
      const rateTreeInput = document.createElement("input");
      rateTreeInput.id = `rate_price_dealers-${i}`;
      rateTreeInput.classList.add("form-control");
      rateTreeInput.type = "text";
      rateTreeInput.name = "rate_dealers3[]";
      rateTreeInput.required = true;

      const labelFour = document.createElement("label");
      labelFour.textContent = `ราคา เรท 4 จัดส่ง`;
      labelFour.classList.add("mt-0", "mb-0", "font-weight-bold", "text-dark");
      const rateFourInput = document.createElement("input");
      rateFourInput.id = `rate_price_delivery-${i}`;
      rateFourInput.classList.add("form-control");
      rateFourInput.type = "text";
      rateFourInput.name = "rate_delivery4[]";
      rateFourInput.required = true;

      groupIn.appendChild(labelLevel);
      groupIn.appendChild(levelInput);
      groupLevel.appendChild(groupIn);

      groupInOne.appendChild(labelOne);
      groupInOne.appendChild(rateOneInput);
      groupOne.appendChild(groupInOne);

      groupInTwo.appendChild(labelTwo);
      groupInTwo.appendChild(rateTwoInput);
      groupTwo.appendChild(groupInTwo);

      groupInTree.appendChild(labelTree);
      groupInTree.appendChild(rateTreeInput);
      groupTree.appendChild(groupInTree);

      groupInFour.appendChild(labelFour);
      groupInFour.appendChild(rateFourInput);
      groupFour.appendChild(groupInFour);

      container.appendChild(groupLevel);
      container.appendChild(groupOne);
      container.appendChild(groupTwo);
      container.appendChild(groupTree);
      container.appendChild(groupFour);
    }
  }
  renders() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalcreateformproduct" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl2 modal-dialog-centered" role="document">
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
                <div class="mt-2 row">
                  <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อสินค้า</label>
                      <input type="text" class="form-control" name="product_name" id="productname" placeholder="ชื่อสินค้า" required>
                    </div> 
                  </div>
                  <div class="col-md-6 col-lg-4 col-xl-2">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ราคาต้นทุนต่อลัง</label>
                      <input type="text" class="form-control" name="price_default" id="pricedefault" placeholder="ราคาต้นทุน" required>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 col-xl-2">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลางต่อลัง</label>
                      <input type="text" class="form-control" name="price_center" id="pricecenter" placeholder="ราคากลาง" required>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 col-xl-2">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอตต่อลัง</label>
                      <input type="number" class="form-control" name="count_cord" id="countcord" placeholder="จำนวนคอต" required>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="form-group mb-2">
                      <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่งต่อลัง</label>
                      <input type="text" class="form-control" name="shipping_cost" id="shippingcost" placeholder="ค่าส่ง" required>
                    </div>
                  </div>
                </div>
                <div class="w-full text-center my-4"><h3>เรทราคาขาย</h3></div>
                <div class="col-md-12 row mb-3 form-is-level"></div>
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
  for (let i = 1; i <= 4; i++) {
    $(`#rate_id-${i}`).val("");
    $(`#rate_price_vip-${i}`).val("");
    $(`#rate_price_storefront-${i}`).val("");
    $(`#rate_price_dealers-${i}`).val("");
    $(`#rate_price_delivery-${i}`).val("");
  }
  // $(this)
  //   .find("input, textarea, select")
  //   .each(function () {
  //     if (this.type === "checkbox" || this.type === "radio") {
  //       this.checked = false;
  //     } else {
  //       this.value = "";
  //     }
  //   });
  e.preventDefault();
});
$(document).on("click", "#update_nameproduct", function (e) {
  let id_name = $(this).data("ids");
  let name_product = $(this).data("names");
  let countcost = $(this).data("countcost");
  let price = $(this).data("price");
  let pricecenter = $(this).data("pricecenter");
  let shippingcost = $(this).data("shippingcost");
  let component = document.querySelector("main-create-product");

  $("#idnames").val(id_name);
  $("#productname").val(name_product);
  $("#pricedefault").val(price);
  $("#pricecenter").val(pricecenter);
  $("#countcord").val(countcost);
  $("#shippingcost").val(shippingcost);

  for (let i = 1; i <= 4; i++) {
    $(`#rate_price_vip-${i}`).val("");
    $(`#rate_price_storefront-${i}`).val("");
    $(`#rate_price_dealers-${i}`).val("");
    $(`#rate_price_delivery-${i}`).val("");
  }
  e.preventDefault();
  component.dispatchEvent(
    new CustomEvent("getProduct", {
      detail: {
        id_productname: id_name,
        productname: name_product,
      },
    })
  );
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
