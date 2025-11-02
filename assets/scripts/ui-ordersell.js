"use strict";

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
    this.setImagePriviews();
  }
  setImagePriviews() {
    let setwrapper = document.querySelector(`.${this.wrapper}`);
    let setImgName = document.querySelector(`.${this.filenames}`);
    let setBtncancle = document.querySelector(`.${this.cancle}`);
    let typeImg = document.querySelector(`.${this.id}`);
    let defaultInput = document.querySelector(`.${this.defaultbtn}`);
    let CustomButton = document.querySelector(`#${this.custom}`);
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

let data = [];
const originalPush = data.push;

function createGrandTotal() {
  const results = document.querySelectorAll("span[id^='price_result-']");
  const resutlProduct = document.querySelectorAll("span[id^='is_totals-']");
  let totalOrder = results.length;
  document.getElementById("totalOrder").textContent = `${totalOrder} รายการ`;

  let totalPrice = 0;
  results.forEach((span) => {
    const value = parseFloat(span.textContent.trim()) || 0;
    totalPrice += value;
  });

  let totalCount = 0;
  resutlProduct.forEach((span) => {
    const value = parseFloat(span.textContent.trim()) || 0;
    totalCount += value;
  });
  document.getElementById("totalProducts").textContent = `${totalCount} ชิ้น`;
  document.getElementById("totalPrice").textContent = totalPrice;
  document.getElementById("is_totalprice").value = totalPrice;
  let counts = document.getElementById("count_totalpays");
  counts.value = totalPrice;
  let count_stuck = document.getElementById("count_stuck");

  let result = document.getElementById("results");
  counts.addEventListener("input", () => {
    result.classList.add("text-danger");
    let re_price = Number(totalPrice) - Number(counts.value);
    result.textContent = `  ${re_price} บาท`;
    count_stuck.value = re_price;
  });
}

class formOrDerSell extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ["numbers"];
  }
  get numbers() {
    return this.getAttribute("numbers");
  }
  input_prodcutname = "";
  input_cutommer = "";
  stockdata = [];
  type_price = 0;
  async connectedCallback() {
    await this.loadDataStock();
    this.renderCreateOrderSell();
    this.loadOption();
    this.updateData();
    this.scriptjs();
    this.isSelectPrice();
    this.removeform();
  }

  async loadDataStock() {
    try {
      const get_api_stock = await fetch(
        "http://localhost/smokker24hours/system/backend/api/stock.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const response = await get_api_stock.json();
      this.stockdata.push(...response.data);
      return response.data;
    } catch (e) {
      throw new Error(`Fetch Stock Is Error : ${e}`);
    }
  }

  isSelectPrice(productname, customer) {
    const price_customer_frontstores = this.querySelector(
      `#price_customer_frontstore-${this.numbers}`
    );
    const price_levels_ones = this.querySelector(
      `#price_levels_one-${this.numbers}`
    );
    const price_customer_dealers = this.querySelector(
      `#price_customer_dealer-${this.numbers}`
    );
    const price_customer_delivers = this.querySelector(
      `#price_customer_deliver-${this.numbers}`
    );

    const frontstore_price = this.querySelector(
      `#frontstore_price-${this.numbers}`
    );
    const vip_price = this.querySelector(`#vip_price-${this.numbers}`);
    const dealer_price = this.querySelector(`#dealer_price-${this.numbers}`);
    const deliver_price = this.querySelector(`#deliver_price-${this.numbers}`);

    const tatolproduct = this.querySelector(`#tatolproduct-${this.numbers}`);
    const res_price = this.querySelector(`#is_count-${this.numbers}`);
    const is_totals = this.querySelector(`#is_totals-${this.numbers}`);
    const price_result = this.querySelector(`#price_result-${this.numbers}`);
    const distotal = this.querySelector(".distotal");
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    const selectSpan = dropdown.querySelector(`.select-${this.numbers} span`);
    let input_result = this.querySelector(`#resutl_${this.numbers}`);
    distotal.disabled = true;
    if (!productname) {
      console.log("not data");
      return;
    }
    const filtered = this.stockdata.filter((item) =>
      item.is_productname.includes(productname)
    );

    if (filtered[0].price_customer_frontstore) {
      frontstore_price.innerHTML = `ชิ้นละ ${filtered[0].price_customer_frontstore} บาท`;
      price_customer_frontstores.classList.remove("disabledLi");
    } else {
      frontstore_price.innerHTML = "";
      price_customer_frontstores.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (filtered[0].price_levels_one) {
      vip_price.innerHTML = `ชิ้นละ ${filtered[0].price_levels_one} บาท`;
      price_levels_ones.classList.remove("disabledLi");
    } else {
      vip_price.innerHTML = "";
      price_levels_ones.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (filtered[0].price_customer_dealer) {
      dealer_price.innerHTML = `ชิ้นละ ${filtered[0].price_customer_dealer} บาท`;
      price_customer_dealers.classList.remove("disabledLi");
    } else {
      price_customer_dealers.classList.add("disabledLi");
      dealer_price.innerHTML = "";
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (filtered[0].price_customer_deliver) {
      deliver_price.innerHTML = `ชิ้นละ ${filtered[0].price_customer_deliver} บาท`;
      price_customer_delivers.classList.remove("disabledLi");
    } else {
      deliver_price.innerHTML = "";
      price_customer_delivers.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (productname && customer) {
      this.type_price = filtered[0][customer.replace(/-\d+$/, "")];
      res_price.innerHTML = filtered[0][customer.replace(/-\d+$/, "")];
      distotal.disabled = false;

      let response =
        Number(is_totals.innerHTML) *
        Number(filtered[0][customer.replace(/-\d+$/, "")]);
      price_result.innerHTML = response;
      price_result.textContent = response;
      input_result.value = response;
      createGrandTotal();
      tatolproduct.addEventListener("input", function () {
        is_totals.textContent = this.value;
        let result =
          Number(this.value) *
          Number(filtered[0][customer.replace(/-\d+$/, "")]);
        price_result.innerHTML = result;
        price_result.textContent = result;
        input_result.value = result;
        this.dispatchEvent(new CustomEvent("update", { bubbles: true }));
        createGrandTotal();
      });
    }
  }

  updateData(productname, idProductName) {
    let selectIdProductName = document.querySelector(
      `.selectIdProductName-${this.numbers}`
    );
    let selectedData = document.querySelector(`.selectedData-${this.numbers}`);
    let customInputContainer = document.querySelector(
      `.customInputContainer-${this.numbers}`
    );
    const ul = document.querySelector("ul");
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    if (productname) {
      dropdown.classList.remove("disableds");
      this.isSelectPrice(productname, this.type_price);
    } else {
      dropdown.classList.add("disableds");
    }

    selectedData.value = productname ?? "";
    selectIdProductName.value = idProductName ?? "";

    for (const li of document.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find(
      (li) => li.innerText === productname
    );
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  loadOption() {
    const tatolproducts = this.querySelector(`#tatolproduct-${this.numbers}`);
    let customInput = document.querySelector(`.customInput-${this.numbers}`);
    let selectIdProductName = document.querySelector(
      `.selectIdProductName-${this.numbers}`
    );
    let selectedData = document.querySelector(`.selectedData-${this.numbers}`);
    let searchInput = document.querySelector(
      `.searchInput-${this.numbers} input`
    );
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    let ul = document.querySelector(`.options-${this.numbers} ul`);
    let customInputContainer = document.querySelector(
      `.customInputContainer-${this.numbers}`
    );
    window.addEventListener("click", (e) => {
      const searchInputEl = document.querySelector(
        `.searchInput-${this.numbers}`
      );
      if (searchInputEl && searchInputEl.contains(e.target)) {
        searchInputEl.classList.add("focus");
      } else if (searchInputEl) {
        searchInputEl.classList.remove("focus");
      }
      if (customInputContainer && !customInputContainer.contains(e.target)) {
        customInputContainer.classList.remove("show");
      }
    });

    customInput.addEventListener("click", () => {
      customInputContainer.classList.toggle("show");
    });

    let productLength = this.stockdata.length;

    for (let i = 0; i < productLength; i++) {
      let products = this.stockdata[i];
      tatolproducts.min = 0;
      const li = document.createElement("li");
      li.classList.add("block");
      const row = document.createElement("div");
      row.classList.add("row");
      let span = document.createElement("span");
      let small = document.createElement("small");
      let pre = document.createElement("p");
      pre.style.display = "none";
      if (products.remaining_product <= 0) {
        li.classList.add("disabled");
        li.style.pointerEvents = "none";
        li.style.color = "gray";
        li.style.opacity = "0.6";
      }

      pre.textContent = products.product_name; //id
      span.textContent = products.is_productname;
      if (products.remaining_product == 0) {
        small.textContent = "สินค้าหมด";
      } else if (products.remaining_product < 0) {
        small.textContent = `สินค้าติดลบ ${products.remaining_product}`;
      } else {
        small.textContent = `เหลืออีก ${products.remaining_product} ลัง`;
      }
      small.classList.add("ml-auto");
      row.appendChild(span);
      row.appendChild(small);
      row.appendChild(pre);
      li.appendChild(row);
      ul.appendChild(li);
    }

    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", (e) => {
        let pre = li.querySelector("p").innerText;
        let spanTxt = li.querySelector("span").innerText;
        selectIdProductName.value = pre;
        selectedData.value = spanTxt;

        for (const li of document.querySelectorAll("li.selected")) {
          li.classList.remove("selected");
        }
        li.classList.add("selected");
        customInputContainer.classList.toggle("show");
      });
    });
    searchInput.addEventListener("keyup", (e) => {
      let searchedVal = searchInput.value.toLowerCase();
      let searched_product = this.stockdata.filter((data) =>
        data.is_productname.toLowerCase().includes(searchedVal)
      );

      ul.innerHTML = "";
      if (searched_product.length === 0) {
        dropdown.classList.add("disableds");
        ul.innerHTML = `<p style='margin-top: 1rem;'>
                          ไม่มีข้อมูล
                        </p>`;
        return;
      }
      searched_product.forEach((product) => {
        const li = document.createElement("li");
        li.classList.add("row");
        let spanName = document.createElement("span");
        const idInLi = document.createElement("p");
        let small2 = document.createElement("small");
        idInLi.textContent = product.product_name;
        spanName.classList = "id-names-product";
        idInLi.className = "hidden-id";
        idInLi.style.display = "none";
        idInLi.textContent = product.product_name;
        spanName.textContent = product.is_productname;
        //li.textContent = product.product_name;
        this.input_prodcutname = product.is_productname;
        small2.textContent = `เหลืออีก ${product.remaining_product} ลัง`;
        small2.classList.add("ml-auto");
        li.appendChild(spanName);
        li.appendChild(idInLi);
        li.appendChild(small2);

        li.addEventListener("click", (e) => {
          this.input_prodcutname = e.target.textContent;
          let idName = li.querySelector(".hidden-id").textContent;
          let productName = li.querySelector(".id-names-product").textContent;
          this.updateData(productName, idName);
        });
        ul.appendChild(li);
      });
    });
  }
  scriptjs() {
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    const dropdownMenu = dropdown.querySelector(
      `.dropdown-menu-${this.numbers}`
    );

    let ul = document.querySelector(`.options-${this.numbers} ul`);

    dropdown.classList.add("disableds");

    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", (e) => {
        let spanTxt = li.querySelector("span").innerText;
        this.input_prodcutname = spanTxt;
        if (spanTxt === "") {
          dropdown.classList.add("disableds");
        } else {
          dropdown.classList.remove("disableds");
        }
        this.isSelectPrice(this.input_prodcutname, this.input_cutommer);
      });
    });
    dropdownMenu.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", () => {
        const smallValue = li.querySelector("small")?.textContent.trim();
        const typeCustom = dropdown.querySelector(
          `#type_custom-${this.numbers}`
        );
        const hiddenInput = dropdown.querySelector(
          `#costommerd-${this.numbers}`
        );
        if (hiddenInput && smallValue) {
          hiddenInput.value = smallValue.replace(/[^0-9.]/g, "");
        }

        this.input_cutommer = li.id;
        typeCustom.value = li.id.replace(/-\d+$/, "");
        data.push({ [`custom-${this.numbers}`]: li.id });

        dropdownMenu.classList.remove("show");
        this.isSelectPrice(this.input_prodcutname, this.input_cutommer);
        this.dispatchEvent(
          new CustomEvent("priceSelected", {
            detail: { numbers: this.numbers, selectId: li.id },
            bubbles: true,
          })
        );
      });
    });
  }

  removeform() {
    if (this.numbers > 0) {
      let div = this.querySelector(".btn-remove");
      const btn = document.createElement("button");
      btn.textContent = "❌ ลบ";
      btn.classList.add("ml-auto");
      btn.addEventListener("click", () => {
        this.remove();
        createGrandTotal();
        document.dispatchEvent(new Event("recalculate"));
      });
      div.appendChild(btn);
    }
  }

  renderCreateOrderSell() {
    this.innerHTML = `
            <div class="col-md-12 row mb-3 formGroups" id="formGroup-${
              this.numbers
            }">
              <div class="btn-remove col-md-12 row"></div>
              <div class="col-xl-3 col-lg-7">
                <div class="form-group mb-2">
                  <label class="mt-0 mb-0 font-weight-bold text-dark col-12">รายการสินค้าที่ 
                    <span data-role="index">${Number(this.numbers) + 1}</span>
                  </label>
                    <input class="selectIdProductName-${
                      this.numbers
                    }" name="is_idproductname[]"  type="hidden" id="id_productname-${
      this.numbers
    }" required/>
                    <div class="customInputContainer customInputContainer-${
                      this.numbers
                    }">
                      <div class="customInput-${this.numbers} searchInput-${
      this.numbers
    } customInput searchInput">
                          <input class="selectedData form-control selectedData-${
                            this.numbers
                          }"  type="text" name="product[]" id="name_procts-${
      this.numbers
    }" name="product_name[]" required/>
                      </div>
                      <div class="options-${this.numbers} options">
                          <ul></ul>
                      </div>
                  </div> 
                   
                </div>
              </div>
              <div class="col-xl-3 col-lg-5">
                <div class="form-group mb-2">
                    <label class="m-0 font-weight-bold text-dark col-12" id="x-${
                      this.numbers
                    }">เรทราคา</label>
                      <div class="dropdown dropdown-${this.numbers}">
                        <div class="select select-${this.numbers}">
                          <span>เลือก เรทราคา</span>
                          <i class="fa fa-chevron-left"></i>
                        </div>
                        <input type="text" name="type_custom[]" id="type_custom-${
                          this.numbers
                        }" style="display:none;"/>
                        <ul class="dropdown-menu-${this.numbers} dropdown-menu">
                          <li id="price_levels_one-${
                            this.numbers
                          }" class="row mx-0">
                             ราคา ระดับ 1
                           <small id="vip_price-${
                             this.numbers
                           }" class="ml-auto"></small>
                          </li>

                          <li id="price_customer_frontstore-${
                            this.numbers
                          }" class="row mx-0">
                              ราคา ระดับ 2
                              <small id="frontstore_price-${
                                this.numbers
                              }" class="ml-auto"></small>
                          </li>
                          
                          <li id="price_customer_dealer-${
                            this.numbers
                          }" class="row mx-0">
                              ราคา ระดับ 3
                              <small id="dealer_price-${
                                this.numbers
                              }" class="ml-auto"></small>
                          </li>
                          <li id="price_customer_deliver-${
                            this.numbers
                          }" class="row mx-0">
                             ราคา ระดับ 4
                            <small id="deliver_price-${
                              this.numbers
                            }" class="ml-auto"></small>
                          </li>
                        </ul>
                        <input type="text" name="costommerds[]" id="costommerd-${
                          this.numbers
                        }" style="display:none;">
                      </div>
                </div>
              </div>
              <div class="col-xl-2 col-lg-4">
                <div class="form-group mb-2">
                  <label class="m-0 font-weight-bold text-dark col-12">จำนวนขายกี่ลัง <span class="totalc-${
                    this.numbers
                  }"></span></label>
                  <div class="d-flex">
                    <input type="number" class="form-control mr-2 distotal" name="tatol_product[]" id="tatolproduct-${
                      this.numbers
                    }" placeholder="กรอกจำนวนขาย" required>ลัง
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-8">
                  <div class="form-group mb-2">
                    <label class="m-0 font-weight-bold text-dark col-12">จำนวนลัง x ราคาต่อลัง = ผลลัพธ์ </label>
                    <div class="form-control">
                      <span id="is_totals-${
                        this.numbers
                      }" class="px-3">&nbsp;</span>
                        <i class="fas fa-times mt-1"></i>
                      <span id="is_count-${
                        this.numbers
                      }" class="px-3" >&nbsp;</span>
                      <i class="fa fa-equals mt-1"></i> 
                      <span id="price_result-${
                        this.numbers
                      }" class="px-3" >&nbsp;</span> บาทถ้วน
                    </div>
                    <input type="hidden" name="resutl_price[]" id="resutl_${
                      this.numbers
                    }"  />
                  </div>
              </div>
            </div>
    `;
  }
}

customElements.define("mian-input-ordersell", formOrDerSell);

class modelSetOrderSell extends HTMLElement {
  connectedCallback() {
    this.selectedLiId = [];
    this.renderCreateOrderSell();
    this.addProductForm();
    this.setIdCostomer();
    this.generateID();
    this.statusPayment();
  }

  generateID() {
    function generateId(length = 8) {
      const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      let result = "";
      for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      return result;
    }
    const id = generateId(10);
    document.getElementById("ordersell_name").value = id;
  }
  setIdCostomer() {
    this.addEventListener("priceSelected", (e) => {
      const { numbers, selectId } = e.detail;
      this.selectedLiId[numbers] = selectId.replace(/-\d+$/, "");
    });
  }

  addProductForm() {
    const container = this.querySelector("#create-form-order");
    const addForm = this.querySelector("#add-form");

    addForm.addEventListener("click", () => {
      const divIn = this.querySelector("mian-input-ordersell");
      const clone = divIn.cloneNode(true);
      const index =
        container.querySelectorAll("mian-input-ordersell").length + 1;
      clone.setAttribute("numbers", index);
      container.appendChild(clone);
    });
  }

  statusPayment() {
    var $select = $("#payment_options");
    let divPn = document.querySelector("#status_payment");
    divPn.style.display = "none";
    let txt_ui = document.querySelector("#txt-status");
    let ui_count = document.getElementById("count_totalpays");
    let res = document.getElementById("totalPrice");
    let results = document.getElementById("results");
    let count_stuck = document.getElementById("count_stuck");
    $(function () {
      $select.change(function () {
        var result = $select.multipleSelect("getSelects", "text");
        if (result.length > 0) {
          divPn.style.display = "block";
          if (result.includes("ติดค้าง")) {
            ui_count.value = "";
            txt_ui.textContent = "จำนวนเงินที่ยังติดค้าง";
            txt_ui.classList.remove("text-success");
            txt_ui.classList.add("text-danger");
            results.textContent = ` ${res.innerHTML} บาท`;
            results.classList.add("text-danger");
            count_stuck.value = Number(res.innerHTML);
          } else {
            txt_ui.textContent = "จ่ายครบถ้วน";
            txt_ui.classList.remove("text-danger");
            txt_ui.classList.add("text-success");
            results.textContent = "";
            ui_count.value = res.innerHTML;
            count_stuck.value = 0;
          }
        } else {
          divPn.style.display = "none";
        }
      });
    });
  }

  renderCreateOrderSell() {
    this.innerHTML = `
      <div class="modal fade" id="modalFormOrderSell" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content" id="">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มรายการขาย <span id="productname"></span></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="myForm" method="POST" action="backend/order_sell.php" enctype="multipart/form-data">
                  <input type="hidden" name="status_form" value="create" />
                    <div class="modal-body">
                      <mian-input-ordersell numbers="0"></mian-input-ordersell>
                      <div class="" id="create-form-order">
                      </div>
                      <div class="row col-12 mt-2 mb-4">
                        <button type="button" class="btn btn-sm btn-success ml-auto mr-4" id="add-form">เพิ่ม สินค้า</button>
                      </div>
                      <div class="col-12 row mb-3 border mt-4 py-3">
                        <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12">
                            <div class="row">
                              <div class="col-xl-5 col-lg-8 col-sm-12">
                                <div class="form-group mb-2">
                                  <label class="mt-0 mb-0 font-weight-bold text-dark">รายการขาย <span class="text-danger">*</span> </label>
                                  <input type="text" class="form-control" name="ordersell_name" id="ordersell_name" placeholder="รายการขาย" required>
                                </div> 
                              </div>
                              <div class="col-xl-7 col-lg-4 col-sm-12">
                                  <div class="form-group mb-2">
                                    <label class="m-0 font-weight-bold text-dark">ราคาที่ต้องจ่าย <span class="text-danger">*</span></label>
                                    <div class="row">
                                      <div class="form-control col-5 ml-2">
                                        <span id="totalPrice">0</span>
                                        <input type="hidden" name="is_totalprice" id="is_totalprice"/>
                                      </div>
                                      <div class="col-6 row">
                                        <div class="col-6 align-self-center">
                                          <span class="font-weight-bold" id="totalOrder">0 รายการ</span>
                                        </div>
                                        <div class="col-6 align-self-center row ml-auto">
                                            <span class="font-weight-bold" id="totalProducts">0 ชิ้น</span>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>
                              </div>
                            </div>
                            
                            <div class="row">
                              <div class="col-xl-8 col-md-7 col-sm-12">
                                <main-get-group></main-get-group>
                              </div>
                              <div class="col-xl-4 col-md-5 col-sm-12">
                                <div class="form-group mb-2">
                                  <label class="mt-0 mb-0 font-weight-bold text-dark">วันที่และเวลา <span class="text-danger">*</span></label>
                                  <input type="datetime-local" class="form-control" name="date_time_sell" id="date_time_sell" placeholder="วันที่และเวลา" required>
                                </div> 
                              </div>
                              <div class="col-xl-8 col-md-7 col-sm-12">
                                <main-get-select></main-get-select>
                              </div>
                              <div class="col-xl-4 col-md-5 col-sm-12">
                                <label class="mt-0 mb-0 col-12 font-weight-bold text-dark">ตัวเลือกการจ่าย <span class="text-danger">*</span></label>
                                <select class="form-control multiple-select" name="payment_option[]" id="payment_options" placeholder="ตัวเลือกการจ่าย" multiple="multiple" required>
                                    <option value="โอน">โอน</option>
                                    <option value="จ่ายสด">จ่ายสด</option>
                                    <option value="ติดค้าง">ติดค้าง</option>
                                </select>
                              </div>
                            </div>
                          
                        </div>
                        <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
                          <mian-add-image id="slip_orderseller" count="sell_slip" wrapper="ux-wrap" filenames="uimgname" cancles="ux-cancle"
                            names="รูปโปรไฟล์" custom="btn_custom" setdefault="setDefaultImgSell"></mian-add-image>
                          <div class="col-12" id="status_payment">
                              <label for="" class="small">จำนวนเงินที่ลูกค้าจ่าย <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="count_totalpays" id="count_totalpays" placeholder="จำนวนเงินที่ลูกค้าจ่าย" required>
                              <input type="hidden" class="form-control" name="count_stuck" id="count_stuck" value="0" placeholder="หนี้ค้าง">
                              <div class="align-self-center row mt-2 ml-2" id="is_stock">
                                <b id="txt-status"></b> <span class="ml-2 font-weight-bold" id="results"></span>
                              </div>
                          </div>
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

customElements.define("mian-form-ordersell", modelSetOrderSell);

class SelectPepleGroup extends HTMLElement {
  constructor() {
    super();
  }
  peplegroup = [];
  customdata = [];
  async connectedCallback() {
    await this.loadDataPepleGroup();
    this.renderHTML();
    this.scriptPeplegroup();
  }

  async loadDataPepleGroup() {
    try {
      const responses = await fetch(
        "http://localhost/smokker24hours/system/backend/api/peplegroup_api.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const responsedata = await responses.json();
      this.peplegroup.push(responsedata.data);
      return responsedata;
    } catch (e) {
      console.error(`Is Error ${e}`);
    }
  }

  scriptPeplegroup() {
    const index = 1;
    let isForm = document.querySelector(".is-formPepleGroup");
    let isSelect = isForm.querySelector(".getSelectPepleGroup");
    let pepleGroupInputContainer = isSelect.querySelector(
      ".customInputContainer"
    );
    let customInput = isSelect.querySelector(".customInput");
    let selectedData = isSelect.querySelector(".selectedData");
    let selectedIdData = isSelect.querySelector(".selectedIdData");
    let serchInput = isSelect.querySelector(".searchInput input");
    let ul = isSelect.querySelector(`.options ul`);

    customInput.classList.add(`IsPepleGroupInput-${index}`);
    isSelect.classList.add(`IsPepleGroupInputContainer-${index}`);
    selectedData.classList.add(`IsGoupselectedData-${index}`);
    selectedIdData.classList.add(`IsGroupSelectId-${index}`);
    serchInput.classList.add(`IsGroupserchInput-${index}`);
    ul.classList.add(`IsGroupoptions-${index}`);

    window.addEventListener("click", (e) => {
      const searchInputEl = isSelect.querySelector(`.IssearchInput-${index}`);
      if (searchInputEl && searchInputEl.contains(e.target)) {
        searchInputEl.classList.add("focus");
      } else if (searchInputEl) {
        searchInputEl.classList.remove("focus");
      }
      if (
        pepleGroupInputContainer &&
        !pepleGroupInputContainer.contains(e.target)
      ) {
        pepleGroupInputContainer.classList.remove("show");
      }
    });
    customInput.addEventListener("click", () => {
      pepleGroupInputContainer.classList.add("show");
    });
    for (let i = 0; i < this.peplegroup[0].length; i++) {
      let peplegroup = this.peplegroup[0][i];
      let li = document.createElement("li");
      li.classList.add("block");
      const row = document.createElement("div");
      row.classList.add("row");
      let pre = document.createElement("p");
      let span = document.createElement("span");
      pre.style.display = "none";
      pre.textContent = peplegroup.id_peplegroup;
      span.textContent = peplegroup.name_peplegroup;
      row.appendChild(span);
      row.appendChild(pre);
      li.appendChild(row);
      ul.appendChild(li);
    }
    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", () => {
        let spanTxt = li.querySelector("span").innerText;
        let spanId = li.querySelector("p").innerText;
        selectedData.value = spanTxt;
        selectedIdData.value = spanId;
        for (const li of document.querySelectorAll("li.selected")) {
          li.classList.remove("selected");
        }
        li.classList.add("selected");
        pepleGroupInputContainer.classList.toggle("show");
      });
    });
    serchInput.addEventListener("keyup", () => {
      let searchedVal = serchInput.value.toLowerCase();
      let searched_product = this.peplegroup[0].filter((data) =>
        data.name_peplegroup.toLowerCase().includes(searchedVal)
      );
      ul.innerHTML = "";
      if (searched_product.length === 0) {
        ul.innerHTML = `<p style='margin-top: 1rem;'>
                          ไม่มีข้อมูล
                        </p>`;
        return;
      }
      searched_product.forEach((data) => {
        const li = document.createElement("li");
        li.classList.add("row");
        let span_name = document.createElement("span");
        let pre_id = document.createElement("p");
        span_name.classList = "peplegroupname";
        pre_id.classList = "id_peplegroup";
        //li.textContent = data.custome_name;
        pre_id.style.display = "none";
        span_name.textContent = data.name_peplegroup;
        pre_id.textContent = data.id_peplegroup;
        li.appendChild(span_name);
        li.appendChild(pre_id);
        li.addEventListener("click", (e) => {
          let IsPepleGoup = li.querySelector(".peplegroupname").textContent;
          let IsIdPepleGroup = li.querySelector(".id_peplegroup").textContent;
          this.updateDataPepleGroup(
            IsIdPepleGroup,
            IsPepleGoup,
            index,
            isSelect
          );
        });
        ul.appendChild(li);
      });
    });
  }

  updateDataPepleGroup(id_peplegroup, data_peplegroup, index, group) {
    let selectGroupData = group.querySelector(`.IsGoupselectedData-${index}`);
    let selectGroupId = group.querySelector(`.IsGroupSelectId-${index}`);

    let groupInputContainer = document.querySelector(
      `.IsPepleGroupInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectGroupId.value = id_peplegroup ?? "";
    selectGroupData.value = data_peplegroup ?? "";
    for (const lis of group.querySelectorAll("li.selected")) {
      lis.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find((li) => {
      return li.innerText === data_peplegroup;
    });
    if (clickedLi) clickedLi.classList.add("selected");
    groupInputContainer.classList.toggle("show");
  }
  renderHTML() {
    this.innerHTML = `
      
      <div class="col-md-12 is-formPepleGroup">
        <div class="getSelectPepleGroup form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อผู้ขาย <span class="text-danger">*</span></label>
          <input class="selectedIdData" type="hidden" name="peplegroup_id" id="peplegroutid"/>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control peplegroup_name"  type="text" name="peplegroups_name" required/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div> 
      </div>     
    `;
  }
}

customElements.define("main-get-group", SelectPepleGroup);

class SelectCustomers extends HTMLElement {
  constructor() {
    super();
  }
  peplegroup = [];
  customdata = [];
  async connectedCallback() {
    await this.loadDataCustomer();
    this.renderHTML();
    this.scriptCustomer();
  }

  async loadDataCustomer() {
    try {
      const response = await fetch(
        "http://localhost/smokker24hours/system/backend/api/customer_api.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const responsedata = await response.json();
      this.customdata.push(responsedata.data);
      return responsedata;
    } catch (e) {
      console.error(`Is Error ${e}`);
    }
  }

  scriptCustomer() {
    const index = 1;
    let isForm = document.querySelector(".is-formCustom");
    let isSelect = isForm.querySelector(".getSelectCustomer");
    let customInputContainer = isSelect.querySelector(".customInputContainer");
    let customInput = isSelect.querySelector(".customInput");
    let selectedData = isSelect.querySelector(".selectedData");
    let serchInput = isSelect.querySelector(".searchInput input");
    let ul = isSelect.querySelector(`.options ul`);

    customInput.classList.add(`IscustomInput-${index}`);
    isSelect.classList.add(`IscustomInputContainer-${index}`);
    selectedData.classList.add(`IsCustomselectedData-${index}`);
    serchInput.classList.add(`IsCustomserchInput-${index}`);
    ul.classList.add(`IsCustomoptions-${index}`);

    window.addEventListener("click", (e) => {
      const searchInputEl = isSelect.querySelector(`.IssearchInput-${index}`);
      if (searchInputEl && searchInputEl.contains(e.target)) {
        searchInputEl.classList.add("focus");
      } else if (searchInputEl) {
        searchInputEl.classList.remove("focus");
      }
      if (customInputContainer && !customInputContainer.contains(e.target)) {
        customInputContainer.classList.remove("show");
      }
    });
    customInput.addEventListener("click", () => {
      customInputContainer.classList.add("show");
    });
    for (let i = 0; i < this.customdata[0].length; i++) {
      let custom = this.customdata[0][i];
      let li = document.createElement("li");
      li.classList.add("block");
      const row = document.createElement("div");
      row.classList.add("row");
      let span = document.createElement("span");
      span.textContent = custom.custome_name;
      row.appendChild(span);
      li.appendChild(row);
      ul.appendChild(li);
    }
    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", () => {
        let spanTxt = li.querySelector("span").innerText;
        selectedData.value = spanTxt;
        for (const li of document.querySelectorAll("li.selected")) {
          li.classList.remove("selected");
        }
        li.classList.add("selected");
        customInputContainer.classList.toggle("show");
      });
    });
    serchInput.addEventListener("keyup", () => {
      let searchedVal = serchInput.value.toLowerCase();
      let searched_product = this.customdata[0].filter((data) =>
        data.custome_name.toLowerCase().includes(searchedVal)
      );
      ul.innerHTML = "";
      if (searched_product.length === 0) {
        ul.innerHTML = `<p style='margin-top: 1rem;'>
                          ไม่มีข้อมูล
                        </p>`;
        return;
      }
      searched_product.forEach((data) => {
        const li = document.createElement("li");
        li.classList.add("row");
        let span_name = document.createElement("span");
        span_name.classList = "customername";
        //li.textContent = data.custome_name;
        span_name.textContent = data.custome_name;
        li.appendChild(span_name);
        li.addEventListener("click", (e) => {
          let IsCustomerName = li.querySelector(".customername").textContent;
          this.updateData(IsCustomerName, index, isSelect);
        });
        ul.appendChild(li);
      });
    });
  }
  updateData(data_custome, index, group) {
    let selectedCustomData = group.querySelector(
      `.IsCustomselectedData-${index}`
    );
    let customInputContainer = document.querySelector(
      `.IscustomInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectedCustomData.value = data_custome ?? "";
    for (const lis of group.querySelectorAll("li.selected")) {
      lis.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find((li) => {
      return li.innerText === data_custome;
    });
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  renderHTML() {
    this.innerHTML = `
         
        <div class="col-md-12 is-formCustom">
          <div class="getSelectCustomer form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อลูกค้า <span class="text-danger">*</span></label>
            <div class="customInputContainer">
                <div class="customInput searchInput">
                    <input class="selectedData form-control customers_name"  type="text" name="custome_name" required/>
                </div>
                <div class="options">
                    <ul></ul>
                </div>
            </div>
          </div> 
        </div>
    `;
  }
}
customElements.define("main-get-select", SelectCustomers);

$(document).on("click", "#confirmTrashOrderSell", function (e) {
  let idorder_sell = $(this).data("id");
  let order_sellname = $(this).data("ordersell");
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `รายการ ${order_sellname} นี้ พร้อมสินค้า จะถูกลบทั้งหมด จะไม่สามารถย้อนกลับได้`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ยกเลิก",
    confirmButtonText: "ยืนยัน",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const responseapi = await fetch(
          `http://localhost/smokker24hours/system/backend/api/stockordersell.php?ordersell_id=${idorder_sell}`,
          {
            method: "DELETE",
            credentials: "include",
          }
        );
        const responsedata = await responseapi.json();
        if (responsedata.status === 201) {
          Swal.fire({
            title: "เรียบร้อย",
            text: "ลบ order นี้เรียบร้อยแล้ว",
            icon: "success",
            showConfirmButton: false,
          }).then(() => {
            window.location.reload();
          });
        }
      } catch (e) {
        throw new Error(`Is Error Catch ${e}`);
      }
    }
  });
});
