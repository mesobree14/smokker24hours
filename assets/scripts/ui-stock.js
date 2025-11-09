class AddImage extends HTMLElement {
  constructor() {
    super();
  }
  get count() {
    return this.getAttribute("count");
  }
  get names() {
    return this.getAttribute("names");
  }
  get defaultbtn() {
    return this.getAttribute("setdefault");
  }
  get custom() {
    return this.getAttribute("custom");
  }
  get filenames() {
    return this.getAttribute("filenames");
  }
  get wrapper() {
    return this.getAttribute("wrapper");
  }
  get cancle() {
    return this.getAttribute("cancles");
  }
  connectedCallback() {
    this.renderImage();
  }
  renderImage() {
    this.innerHTML = `
              <div class="container">
                  <div class="wrapper ${this.wrapper}">
                      <div class="image">
                         <img src="" alt="" class="${this.id}"> 
                      </div>
                      <div class="content">
                          <div class="icon">
                              <i class="fas fa-cloud-upload-alt"></i>
                          </div>
                          <div class="text">${this.names}</div>
                      </div>
                      <div class="btnCancle ${this.cancle}">
                          <i class="fas fa-times"></i>
                      </div>
                      <div class="file-name ${this.filenames}">File name hear</div>
                  </div>
                  <input type="file" name="${this.count}" class="${this.defaultbtn}" hidden>
                  <p class="BtnCustom" id="${this.custom}">อัพโหลดไฟล์</p> 
              </div>
          `;
  }
}
customElements.define("mian-add-image", AddImage);

const setImagePriviews = (
  getImage,
  setDefaultFile,
  setCustomBtn,
  btnCancle,
  getImgNames,
  setWrapper
) => {
  let setwrapper = document.querySelector(setWrapper);
  let setImgName = document.querySelector(getImgNames);
  let setBtncancle = document.querySelector(btnCancle);
  let typeImg = document.querySelector(getImage);
  let defaultInput = document.querySelector(setDefaultFile);
  let CustomButton = document.querySelector(setCustomBtn);
  let setExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;

  CustomButton.onclick = function () {
    defaultInput.click();
  };
  defaultInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function () {
        const result = reader.result;
        typeImg.src = result;
        setwrapper.classList.add("active");
      };
      setBtncancle.addEventListener("click", function () {
        typeImg.src = "";
        setwrapper.classList.remove("active");
      });
      reader.readAsDataURL(file);
    }
    if (this.value) {
      let valueStore = this.value.match(setExp);
      setImgName.textContent = valueStore;
    }
  });
};

$(document).on("click", "#set_rate_price", function (e) {
  $id_name = $(this).data("idname");
  $rate_id = $(this).data("id");
  $product_name = $(this).data("product");
  $productprice = $(this).data("productprice");
  $productpricecenter = $(this).data("productpricecenter");
  $countcord = $(this).data("countcord");
  $shippingcost = $(this).data("shippingcost");
  console.log({ id_name: $id_name });
  $("#is_idname").val($id_name);

  $("#product_name").val($product_name);
  $("#productname").html($product_name);
  $("#productnames").html($product_name);
  $("#productprice").html($productprice);
  $("#productpricecenter").html($productpricecenter);
  $("#countcord").html($countcord);
  $("#shippingcost").html($shippingcost);
  e.preventDefault();
});

class modelCreateRatePrice extends HTMLElement {
  connectedCallback() {
    this.renderCreatePrice();
    this.setScript();
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

      const rateId = document.createElement("input");
      rateId.id = "rate_id";
      rateId.name = "rate_id[]";

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
      labelOne.classList.add("mt-0", "mb-0", "font-weight-bold", "text-dark");
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
  renderCreatePrice() {
    this.innerHTML = `
    <div class="modal fade bd-example-modal-xl" id="modalFormCreateRate" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg2 modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มเรทราคา <span id="productname"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="myForm" method="POST" action="../backend/product_stock.php">
              <input type="hidden" name="status_form" value="create_rate" />
              <input type="hidden" name="rate_id" id="rate_id" />
              <input type="hidden" name="product_name" id="product_name" />
              <input type="hidden" name="is_idname" id="is_idname" />
              <div class="modal-body">
                  <div class="col-md-12 row mb-3 form-is-level"></div>
                  <div class="col-md-12 align-self-center row mt-4">
                        <div class="col-md-12 " id="name_product">
                            ชื่อสินค้า : <span id="productnames" class="font-weight-bold text-danger"></span>
                        </div>
                        <div class="col-md-6 mt-2" >
                          ราคาต้นทุนต่อลัง : <span id="productprice" class="font-weight-bold text-danger"></span> บาท
                        </div>
                        <div class="col-md-6 mt-2" >
                          ราคากลางต่อลัง : <span id="productpricecenter" class="font-weight-bold text-danger"></span> บาท
                        </div>
                        <div class="col-md-6 mt-2" >
                          จำนวนคอตต่อลัง : <span id="countcord" class="font-weight-bold text-danger"></span> คอต
                        </div>
                        <div class="col-md-6 mt-2" >
                          ค่าส่งต่อลัง : <span id="shippingcost" class="font-weight-bold text-danger"></span> บาท
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary ml-auto mr-4">บันทึกข้อมูล</button>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    `;
  }
}
customElements.define("main-rate-price", modelCreateRatePrice);

$(document).on("click", "#setupdate_rate_price", function (e) {
  $id_name = $(this).data("idname");
  $rate_id = $(this).data("id");
  $product_name = $(this).data("product");
  $productprice = $(this).data("productprice");
  $productpricecenter = $(this).data("productpricecenter");
  $countcord = $(this).data("countcord");
  $shippingcost = $(this).data("shippingcost");
  let levelrate = $(this).data("level");
  $rate_price_storefront = $(this).data("storefront");
  $rate_price_vip = $(this).data("vip");
  $rate_price_dealers = $(this).data("dealers");
  $rate_price_delivery = $(this).data("delivery");
  console.log({ id_name: $id_name, levelrate });
  $("#is_idnames").val($id_name);
  $("#rate_id").val($rate_id);
  $("#product_names").val($product_name);
  $("#show_levels").html(`เรทราคา ระดับ : ${levelrate}`);
  $("#level_rates").val(levelrate);
  $("#rate_price_vips").val($rate_price_vip);
  $("#rate_price_storefronts").val($rate_price_storefront);
  $("#rate_price_dealerss").val($rate_price_dealers);
  $("#rate_price_deliverys").val($rate_price_delivery);
  $("#productname_edit").html(`${$product_name} - ระดับที่ ${levelrate}`);
  $("#productnames_edit").html($product_name);
  $("#productprice_edit").html($productprice);
  $("#productpricecenter_edit").html($productpricecenter);
  $("#countcord_edit").html($countcord);
  $("#shippingcost_edit").html($shippingcost);
  e.preventDefault();
});

class modalUpdateRatePrice extends HTMLElement {
  connectedCallback() {
    this.renderUpdate();
  }
  renderUpdate() {
    this.innerHTML = `
          <div class="modal fade bd-example-modal-xl" id="modalFormUpdateRate" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มเรทราคา <span id="productname_edit"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="myForm" method="POST" action="../backend/product_stock.php">
              <input type="hidden" name="status_form" value="create_rate" />
              <input type="hidden" name="rate_id" id="rate_id" />
              <input type="hidden" name="product_name" id="product_names" />
              <input type="hidden" name="is_idname" id="is_idnames" />
              <div class="modal-body">
                  
                  <div class="col-md-12 row mb-3">
                    <div class="col-md-6">
                      <div class="form-group mb-2">
                        <label class="mt-0 mb-0 font-weight-bold text-dark">ระดับ</label>
                        <input type="text" class="form-control" name="level_rates" id="level_rates" placeholder="ระดับ" readonly>
                      </div>  
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                      <div class="form-group mb-2">
                        <label class="mt-0 mb-0 font-weight-bold text-dark">ราคา เรท 1 vip</label>
                        <input type="text" class="form-control" name="rate_vip" id="rate_price_vips" placeholder="ชื่อสินค้า" required>
                      </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">ราคา เรท 2 หน้าร้าน</label>
                          <input type="text" class="form-control" name="rate_storefront" id="rate_price_storefronts" placeholder="ชื่อสินค้า" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">ราคา เรท 3 ตัวแทน</label>
                          <input type="text" class="form-control" name="rate_dealers" id="rate_price_dealerss" placeholder="ชื่อสินค้า" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">ราคา เรท 4 จัดส่ง</label>
                          <input type="text" class="form-control" name="rate_delivery" id="rate_price_deliverys" placeholder="ชื่อสินค้า" required>
                        </div>
                    </div>
                    
                  </div>
                  <div class="col-md-12 align-self-center row mt-4">
                        <div class="col-md-12 " id="name_product">
                            ชื่อสินค้า : <span id="productnames_edit" class="font-weight-bold text-danger"></span>
                        </div>
                        <div class="col-md-6 mt-2" >
                          ราคาต้นทุนต่อลัง : <span id="productprice_edit" class="font-weight-bold text-danger"></span> บาท
                        </div>
                        <div class="col-md-6 mt-2" >
                          ราคากลางต่อลัง : <span id="productpricecenter_edit" class="font-weight-bold text-danger"></span> บาท
                        </div>
                        <div class="col-md-6 mt-2" >
                          จำนวนคอตต่อลัง : <span id="countcord_edit" class="font-weight-bold text-danger"></span> คอต
                        </div>
                        <div class="col-md-6 mt-2" >
                          ค่าส่งต่อลัง : <span id="shippingcost_edit" class="font-weight-bold text-danger"></span> บาท
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary ml-auto mr-4">บันทึกข้อมูล</button>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    `;
  }
}

customElements.define("main-update-price", modalUpdateRatePrice);

$(function () {
  var $tabButtonItem = $("#tab-button li"),
    $tabSelect = $("#tab-select"),
    $tabContents = $(".tab-contents"),
    activeClass = "is-active";

  $tabButtonItem.first().addClass(activeClass);
  $tabContents.not(":first").hide();

  $tabButtonItem.find("a").on("click", function (e) {
    var target = $(this).attr("href");

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
  });

  $tabSelect.on("change", function () {
    var target = $(this).val(),
      targetSelectNum = $(this).prop("selectedIndex");

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
  });
});
