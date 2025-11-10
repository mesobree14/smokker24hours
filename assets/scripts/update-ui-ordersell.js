"use strict";

class UpdateImage extends HTMLElement {
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
customElements.define("mian-edit-image", UpdateImage);

let dataUpdate = [];
const originalPushX = dataUpdate.push;

function updateGrandTotal() {
  const results = document.querySelectorAll("span[id^='price_result-']");
  const resutlProduct = document.querySelectorAll("span[id^='is_totals-']");
  let totalOrder = results.length;
  document.getElementById("etotalOrder").textContent = `${totalOrder} รายการ`;

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
  document.getElementById("etotalProducts").textContent = `${totalCount} ชิ้น`;
  document.getElementById("etotalPrice").textContent = totalPrice;
  document.getElementById("eis_totalprice").value = totalPrice;
  let counts = document.getElementById("s_count_totalpays");

  let count_stuck = document.getElementById("s_count_stuck");
  counts.value = Number(totalPrice) - Number(count_stuck.value);
  let result = document.getElementById("e-results");
  counts.addEventListener("input", () => {
    result.classList.add("text-danger");

    let re_price = Number(totalPrice) - Number(counts.value);
    result.textContent = `  ${Number(totalPrice) - Number(counts.value)} บาท`;
    count_stuck.value = re_price;
  });
}

class formOrDerSellUpdate extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ["numbers"];
  }
  get numbers() {
    return this.getAttribute("numbers");
  }
  input_idproductname = "";
  input_prodcutname = "";
  input_cutommer = "";
  stockdata = [];
  ratePrices = [];
  level_rate = 1;
  type_price = 0;
  async connectedCallback() {
    await this.loadDataStock();
    await this.loadRatePrice(this.data?.productname);
    this.renderCreateOrderSell();

    this.loadOption();
    this.updateData();
    this.scriptjs();
    this.isSelectPrice();
    this.removeform();
    this.valueDataProduct();
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
  async loadRatePrice(productname = "") {
    try {
      if (productname !== "") {
        const get_rateprice = await fetch(
          `http://localhost/smokker24hours/system/backend/api/api_rate_price.php?id_productname=${productname}`,
          {
            method: "GET",
            credentials: "include",
          }
        );
        const response_rate = await get_rateprice.json();

        this.ratePrices = response_rate.data;
        return response_rate.data;
      }
    } catch (e) {
      throw new Error(`Fetch Stock Is Error : ${e}`);
    }
  }

  isSelectPrice(idproductname, productname, customer) {
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

    console.log("rate:", this.level_rate);
    if (!this.level_rate) {
      return;
    }
    const fileterlevels = this.ratePrices.filter((item) =>
      item?.level_sell.includes(this.level_rate)
    );

    console.log({ fileterlevels });

    if (fileterlevels[0]?.price_customer_frontstore) {
      frontstore_price.innerHTML = `ชิ้นละ ${fileterlevels[0]?.price_customer_frontstore} บาท`;
      price_customer_frontstores.classList.remove("disabledLi");
    } else {
      frontstore_price.innerHTML = "";
      price_customer_frontstores.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (fileterlevels[0]?.price_levels_one) {
      vip_price.innerHTML = `ชิ้นละ ${fileterlevels[0]?.price_levels_one} บาท`;
      price_levels_ones.classList.remove("disabledLi");
    } else {
      vip_price.innerHTML = "";
      price_levels_ones.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (fileterlevels[0]?.price_customer_dealer) {
      dealer_price.innerHTML = `ชิ้นละ ${fileterlevels[0]?.price_customer_dealer} บาท`;
      price_customer_dealers.classList.remove("disabledLi");
    } else {
      price_customer_dealers.classList.add("disabledLi");
      dealer_price.innerHTML = "";
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (fileterlevels[0]?.price_customer_deliver) {
      deliver_price.innerHTML = `ชิ้นละ ${fileterlevels[0]?.price_customer_deliver} บาท`;
      price_customer_delivers.classList.remove("disabledLi");
    } else {
      deliver_price.innerHTML = "";
      price_customer_delivers.classList.add("disabledLi");
      selectSpan.innerText = "เลือก เรทราคา";
      res_price.innerHTML = "";
      price_result.innerHTML = "";
    }
    if (productname && customer) {
      this.type_price = fileterlevels[0][customer.replace(/-\d+$/, "")];
      res_price.innerHTML = fileterlevels[0][customer.replace(/-\d+$/, "")];
      distotal.disabled = false;

      let response =
        Number(is_totals.innerHTML) *
        Number(fileterlevels[0][customer.replace(/-\d+$/, "")]);
      price_result.innerHTML = response;
      price_result.textContent = response;
      input_result.value = response;
      updateGrandTotal();
      tatolproduct.addEventListener("input", function () {
        is_totals.textContent = this.value;
        let result =
          Number(this.value) *
          Number(fileterlevels[0][customer.replace(/-\d+$/, "")]);
        price_result.innerHTML = result;
        price_result.textContent = result;
        input_result.value = result;
        this.dispatchEvent(new CustomEvent("update", { bubbles: true }));
        updateGrandTotal();
      });
    }
  }

  updateData(productname, idproductname) {
    this.loadRatePrice(idproductname);
    let selectedIdData = document.querySelector(
      `.selectedIdData-${this.numbers}`
    );
    let selectedData = document.querySelector(`.selectedData-${this.numbers}`);
    let customInputContainer = document.querySelector(
      `.customInputContainer-${this.numbers}`
    );
    const ul = document.querySelector("ul");
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    const dropdownLevel = this.querySelector(`.dropdown-level-${this.numbers}`);
    if (productname) {
      dropdownLevel.classList.remove("disableds");
      dropdown.classList.remove("disableds");
      this.isSelectPrice(idproductname, productname, this.type_price);
    } else {
      dropdownLevel.classList.add("disableds");
      dropdown.classList.add("disableds");
    }

    selectedIdData.value = idproductname ?? "";
    selectedData.value = productname ?? "";

    for (const li of document.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find((li) => li.innerText === data);
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  loadOption() {
    const tatolproducts = this.querySelector(`#tatolproduct-${this.numbers}`);
    let customInput = document.querySelector(`.customInput-${this.numbers}`);
    let selectedData = document.querySelector(`.selectedData-${this.numbers}`);
    let selectedIdData = document.querySelector(
      `.selectedIdData-${this.numbers}`
    );
    let searchInput = document.querySelector(
      `.searchInput-${this.numbers} input`
    );
    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    const dropdownLevel = this.querySelector(`.dropdown-level-${this.numbers}`);
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
      let pre = document.createElement("p");
      let span = document.createElement("span");
      let small = document.createElement("small");
      pre.style.display = "none";
      if (products.remaining_product <= 0) {
        li.classList.add("disabled");
        li.style.pointerEvents = "none";
        li.style.color = "gray";
        li.style.opacity = "0.6";
      }

      pre.textContent = products.product_name;
      span.textContent = products.is_productname;
      if (products.remaining_product == 0) {
        small.textContent = "สินค้าหมด";
      } else if (products.remaining_product < 0) {
        small.textContent = `สินค้าติดลบ ${products.remaining_product}`;
      } else {
        small.textContent = `เหลืออีก ${products.remaining_product} ชิ้น`;
      }
      small.classList.add("ml-auto");
      row.appendChild(span);
      row.appendChild(pre);
      row.appendChild(small);
      li.appendChild(row);
      ul.appendChild(li);
    }

    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", (e) => {
        let idSpan = li.querySelector("p").innerText;
        let spanTxt = li.querySelector("span").innerText;
        selectedIdData.value = idSpan;
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
        dropdownLevel.classList.add("disableds");
        ul.innerHTML = `<p style='margin-top: 1rem;'>
                          ไม่มีข้อมูล
                        </p>`;
        return;
      }
      searched_product.forEach((product) => {
        const li = document.createElement("li");
        li.classList.add("row");
        let spanName = document.createElement("span");
        let preId = document.createElement("p");
        let smallCount = document.createElement("small");
        preId.className = "hidden-id";
        preId.style.display = "none";
        preId.textContent = product.product_name;
        spanName.textContent = product.is_productname;
        smallCount.textContent = `เหลืออีก ${product.remaining_product} ลัง`;
        smallCount.classList.add("ml-auto");
        li.appendChild(spanName);
        li.appendChild(preId);
        li.appendChild(smallCount);
        //li.textContent = product.product_name;
        this.input_idproductname = product.product_name;
        this.input_prodcutname = product.is_productname;

        li.addEventListener("click", (e) => {
          let preIDS = li.querySelector("p").innerText;
          let spanName = li.querySelector("span").innerText;
          this.input_idproductname = preIDS;
          this.input_prodcutname = spanName;
          this.updateData(spanName, preIDS);
        });
        ul.appendChild(li);
      });
    });
  }
  scriptjs() {
    const dropdownLevel = this.querySelector(`.dropdown-level-${this.numbers}`);
    const dropdownMenuLevel = dropdownLevel.querySelector(
      `.dropdown-menu-level-${this.numbers}`
    );

    const dropdown = this.querySelector(`.dropdown-${this.numbers}`);
    const dropdownMenu = dropdown.querySelector(
      `.dropdown-menu-${this.numbers}`
    );

    let ul = document.querySelector(`.options-${this.numbers} ul`);
    dropdown.classList.add("disableds");
    dropdownLevel.classList.add("disableds");

    ul.querySelectorAll("li").forEach((li) => {
      li.addEventListener("click", (e) => {
        let spanIds = li.querySelector("p").innerText;
        let spanTxt = li.querySelector("span").innerText;
        this.input_idproductname = spanIds;
        this.input_prodcutname = spanTxt;
        if (spanTxt === "") {
          dropdown.classList.add("disableds");
          dropdownLevel.classList.add("disableds");
        } else {
          dropdown.classList.remove("disableds");
          dropdownLevel.classList.remove("disableds");
        }
        this.isSelectPrice(spanIds, spanTxt, this.input_cutommer);
        this.loadRatePrice(spanIds);
      });
    });
    dropdownMenuLevel.querySelectorAll("li").forEach((lis) => {
      lis.addEventListener("click", () => {
        console.log("clcik");
        let spans = lis.querySelector("p")?.textContent.trim();
        let typeLevel = dropdownLevel.querySelector(
          `#type_level-${this.numbers}`
        );
        console.log({ spans, name: this.input_prodcutname });
        this.level_rate = spans;
        typeLevel.value = spans;
        this.isSelectPrice(
          this.input_prodcutname,
          this.input_idproductname,
          this.input_cutommer
        );
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
          //smallValue เรทราคา
          hiddenInput.value = smallValue.replace(/[^0-9.]/g, "");
        }
        this.input_cutommer = li.id;
        typeCustom.value = li.id.replace(/-\d+$/, "");
        dataUpdate.push({ [`custom-${this.numbers}`]: li.id });

        dropdownMenu.classList.remove("show");
        this.isSelectPrice(
          this.input_idproductname,
          this.input_prodcutname,
          this.input_cutommer
        );
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
        console.log("remove");
        this.remove();
        updateGrandTotal();
        document.dispatchEvent(new Event("recalculate"));
      });
      div.appendChild(btn);
    }
  }
  valueDataProduct() {
    const product_data = this.data ?? [];
    const customtypes = (data_custom) => {
      switch (data_custom) {
        case "price_levels_one":
          return "เรท 1 vip";
        case "price_customer_frontstore":
          return "เรท 2 หน้าร้าน";
        case "price_customer_deliver":
          return "เรท 4 จัดส่ง";
        case "price_customer_dealer":
          return "เรท 3 ตัวแทน";
        default:
          return data_custom;
      }
    };
    if (product_data) {
      if (product_data.productname) {
        let ProductId = document.getElementById(`product_id-${this.numbers}`);
        const dropdownLevel = this.querySelector(
          `.dropdown-level-${this.numbers}`
        );
        dropdownLevel.classList.remove("disableds");
        const dropdown = this.querySelector(`.dropdown-${this.numbers}`);

        dropdown.classList.remove("disableds");
        let selectedIdData = this.querySelector(
          `.selectedIdData-${this.numbers}`
        );
        let selectedData = this.querySelector(`.selectedData-${this.numbers}`);
        let customInputContainer = document.querySelector(
          `.customInputContainer-${this.numbers}`
        );
        customInputContainer.classList.remove("show");
        const levelSpan = dropdownLevel.querySelector(
          `.select-level-${this.numbers} span`
        );
        const selectSpan = dropdown.querySelector(
          `.select-${this.numbers} span`
        );
        const levelSell = dropdownLevel.querySelector(
          `#type_level-${this.numbers}`
        );
        const typeCustoms = dropdown.querySelector(
          `#type_custom-${this.numbers}`
        );
        const tatolproduct = this.querySelector(
          `#tatolproduct-${this.numbers}`
        );
        const is_totals = this.querySelector(`#is_totals-${this.numbers}`);
        let costommerdVal = dropdown.querySelector(
          `#costommerd-${this.numbers}`
        );
        this.level_rate = product_data.level_selltype;
        this.input_idproductname = product_data.productname;
        this.input_prodcutname = product_data?.product_name;
        this.input_cutommer = product_data?.type_custom;

        ProductId.value = product_data?.list_sellid;
        selectedIdData.value = `${product_data?.productname}`;
        selectedData.value = `${product_data?.product_name ?? ""}`;
        levelSell.value = product_data?.level_selltype;
        typeCustoms.value = `${product_data?.type_custom ?? ""}`;
        costommerdVal.value = product_data?.rate_customertype;
        selectSpan.innerHTML = customtypes(product_data?.type_custom);
        levelSpan.innerHTML = `ระดับที่ ${product_data?.level_selltype}`;
        console.log("HHKK=", this.level_rate);

        tatolproduct.value = product_data?.tatol_product;
        is_totals.textContent = product_data?.tatol_product;
        this.isSelectPrice(
          product_data?.productname,
          product_data?.product_name,
          product_data?.type_custom
        );
        //this.loadRatePrice(product_data?.productname);
      }
    }
  }
  renderCreateOrderSell() {
    this.innerHTML = `
            <div class="col-md-12 row mb-3 formGroups" id="formGroup-${
              this.numbers
            }">
              <div class="btn-remove col-md-12 row"></div>
              <input type="hidden" name="product_id[]" id="product_id-${
                this.numbers
              }"/>
              <div class="col-xl-3 col-lg-7">
                <div class="form-group mb-2">
                  <label class="mt-0 mb-0 font-weight-bold text-dark col-12">รายการสินค้าที่ 
                    <span data-role="index">${Number(this.numbers) + 1}</span>
                  </label>
                    <input class="selectedIdData selectedIdData-${
                      this.numbers
                    }" type="hidden" name="id_productnames[]" id="id_productnames-${
      this.numbers
    }"/>
                    <div class="customInputContainer customInputContainer-${
                      this.numbers
                    }">
                      <div class="customInput-${this.numbers} searchInput-${
      this.numbers
    } customInput searchInput">
                          <input class="selectedData form-control selectedData-${
                            this.numbers
                          }"  type="text" id="name_procts-${
      this.numbers
    }" name="product_name[]" required/>
                      </div>
                      <div class="options-${this.numbers} options">
                          <ul></ul>
                      </div>
                  </div> 
                   
                </div>
              </div>
              <div class="col-xl-2 col-lg-5">
                <div class="form-group mb-2">
                  <label class="m-0 font-weight-bold text-dark col-12" id="xlevel-${
                    this.numbers
                  }">ระดับ</label>
                  <div class="dropdown dropdown-level-${this.numbers}">
                      <div class="select select-level-${this.numbers}">
                        <span>เลือก level</span>
                        <i class="fa fa-chevron-left"></i>
                      </div>
                      <input type="text" name="type_level[]" id="type_level-${
                        this.numbers
                      }" style="display:none;"/>
                        <ul class="dropdown-menu-level-${
                          this.numbers
                        } dropdown-menu">
                          <li id="levels_one-${this.numbers}" class="row mx-0">
                             ระดับที่ 
                             <p id="one1" class="mx-2 font-bold" style="display:block;">1</p>
                          </li>

                          <li id="levels_two-${this.numbers}" class="row mx-0">
                              ระดับที่ 
                              <p id="two2" class="mx-2 font-bold" style="display:block;">2</p>
                          </li>
                          
                          <li id="level_tree-${this.numbers}" class="row mx-0">
                              ระดับที่ 
                              <p id="tree3" class="mx-2 font-bold" style="display:block;">3</p>
                          </li>
                          <li id="level_four-${this.numbers}" class="row mx-0">
                             ระดับที่ 
                             <p id="four4" class="mx-2 font-bold" style="display:block;">4</p>
                          </li>
                        </ul>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-lg-5">
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
                             เรท 1 vip
                           <small id="vip_price-${
                             this.numbers
                           }" class="ml-auto"></small>
                          </li>
                          <li id="price_customer_frontstore-${
                            this.numbers
                          }" class="row mx-0">
                              เรท 2 หน้าร้าน
                              <small id="frontstore_price-${
                                this.numbers
                              }" class="ml-auto"></small>
                          </li>
                          
                          <li id="price_customer_dealer-${
                            this.numbers
                          }" class="row mx-0">
                              เรท 3 ตัวแทน
                              <small id="dealer_price-${
                                this.numbers
                              }" class="ml-auto"></small>
                          </li>
                          <li id="price_customer_deliver-${
                            this.numbers
                          }" class="row mx-0">
                             เรท 4 จัดส่ง
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
                  <label class="m-0 font-weight-bold text-dark col-12">จำนวนขายกี่ชิ้น <span class="totalc-${
                    this.numbers
                  }"></span></label>
                  <div class="d-flex">
                    <input type="number" class="form-control mr-2 distotal" name="tatol_product[]" id="tatolproduct-${
                      this.numbers
                    }" placeholder="กรอกจำนวนขาย" required>ชิ้น
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-8">
                  <div class="form-group mb-2">
                    <label class="m-0 font-weight-bold text-dark col-12">จำนวนชิ้น x ราคาต่อชิ้น = ผลลัพธ์ </label>
                    <div class="form-control">
                      <span id="is_totals-${
                        this.numbers
                      }" class="px-1">&nbsp;</span>
                        <i class="fas fa-times mt-1"></i>
                      <span id="is_count-${
                        this.numbers
                      }" class="px-1" >&nbsp;</span>
                      <i class="fa fa-equals mt-1"></i> 
                      <span id="price_result-${
                        this.numbers
                      }" class="px-1" >&nbsp;</span> บาทถ้วน
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

customElements.define("mian-input-update", formOrDerSellUpdate);

class modelUpdateOrderSell extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.selectedLiId = [];
    this.addEventListener("setId", async (e) => {
      let divSelectCustom = this.querySelector("main-select-custom");
      divSelectCustom.setAttribute("customer", e.detail.customer);
      this.OrderSellId = e.detail.ordersell_id;
      await this.loadProduct(this.OrderSellId);
    });
    this.addEventListener("setIdPepleGroup", async (e) => {
      let divSelectGroup = this.querySelector("main-peple-group");
      divSelectGroup.setAttribute("peplegroup", e.detail.peplegroup);
      // this.OrderSellIds = e.detail.ordersell_id;
      // await this.loadProduct(this.OrderSellIds);
    });
    this.renderEditOrderSell();
    this.addProductForm();
    this.setIdCostomer();
    this.statusPayment();
  }

  async loadProduct(productId) {
    try {
      const responseapi = await fetch(
        `http://localhost/smokker24hours/system/backend/api/stockordersell.php?ordersell_id=${productId}`,
        {
          method: "GET",
          credentials: "include",
        }
      );
      const responsedata = await responseapi.json();
      const container = document.querySelector("#edit-form-order");
      container.innerHTML = "";
      if (responsedata.data.length > 0) {
        responsedata.data.forEach((product, index) => {
          const divIn = document.createElement("mian-input-update");
          divIn.setAttribute("numbers", index + 1);
          divIn.data = product;
          container.appendChild(divIn);
        });
      } else {
        const divIns = document.createElement("mian-input-update");
        container.appendChild(divIns);
      }
    } catch (e) {
      throw new Error(`Is Error Fetch ${e}`);
    }
  }

  addProductForm() {
    const container = this.querySelector("#edit-form-order");
    const addForm = this.querySelector("#add-form");

    addForm.addEventListener("click", () => {
      const divIn = this.querySelector("mian-input-update");
      const index = container.querySelectorAll("mian-input-update").length + 1;
      if (divIn) {
        const clone = divIn.cloneNode(true);
        clone.setAttribute("numbers", index);
        container.appendChild(clone);
      } else {
        const newInput = document.createElement("mian-input-update");
        newInput.setAttribute("numbers", index);
        container.appendChild(newInput);
      }
    });
  }

  setIdCostomer() {
    this.addEventListener("priceSelected", (e) => {
      const { numbers, selectId } = e.detail;
      this.selectedLiId[numbers] = selectId.replace(/-\d+$/, "");
    });
  }

  statusPayment() {
    var $select = $("#e-payment_options");
    let divPn = document.querySelector("#e-status_payment");
    divPn.style.display = "none";
    let txt_ui = document.querySelector("#e-txt-status");
    let ui_count = document.getElementById("s_count_totalpays");
    let res = document.getElementById("etotalPrice");
    let results = document.getElementById("e-results");
    let count_stuck = document.getElementById("s_count_stuck");

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
            //results.textContent = ` ${res.innerHTML} บาท`;
            results.classList.add("text-danger");
            //count_stuck.value = Number(res.innerHTML);
          } else {
            txt_ui.textContent = "จ่ายครบถ้วน";
            txt_ui.classList.remove("text-danger");
            txt_ui.classList.add("text-success");
            results.textContent = "";
            //ui_count.value = res.innerHTML;
            //count_stuck.value = 0;
          }
        } else {
          divPn.style.display = "none";
        }
      });
    });
  }

  renderEditOrderSell() {
    this.innerHTML = `
        <div class="modal fade" id="modalFormUpdateOrderSell" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content" id="">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">แก้ไขรายการขาย <span id="orders_name"></span></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="myForm" method="POST" action="backend/update_ordersell.php" enctype="multipart/form-data">
                    <input type="hidden" name="ordersell_id" id="ordersell_id"/>
                    <input type="hidden" name="default_img" id="img_default"/>

                    <div class="modal-body">
                      
                      <div class="" id="edit-form-order">
                      </div>
                      <div class="row col-12 mt-2 mb-4">
                        <button type="button" class="btn btn-sm btn-success ml-auto mr-4" id="add-form">เพิ่ม สินค้า</button>
                      </div>
                      <div class="col-12 row mb-3 border mt-4 py-3">
                        <div class="col-xl-9 col-lg-7 col-md-12">
                            
                          <div class="row">

                                <div class="col-xl-5 col-lg-8 col-sm-12">
                                  <div class="form-group mb-2">
                                    <label class="mt-0 mb-0 font-weight-bold text-dark">รายการขาย</label>
                                    <input type="text" class="form-control" name="ordersell_name" id="eordersell_name" placeholder="รายการขาย" required>
                                  </div> 
                                </div>
                                <div class="col-xl-7 col-lg-4 col-sm-12">
                                    <div class="form-group mb-2">
                                      <label class="m-0 font-weight-bold text-dark col-12">ราคาที่ต้องจ่าย </label>
                                      <div class="row">
                                        <div class="form-control col-5 mr-3">
                                          <span id="etotalPrice">0</span>
                                          <input type="hidden" name="is_totalprice" id="eis_totalprice"/>
                                        </div>
                                        <div class="col-6 row">
                                          <div class="col-6 align-self-center">
                                            <span class="font-weight-bold" id="etotalOrder">0 รายการ</span>
                                          </div>
                                          <div class="col-6 align-self-center row ml-auto">
                                              <span class="font-weight-bold" id="etotalProducts">0 ชิ้น</span>
                                          </div>
                                        </div>
                                        
                                      </div>
                                    </div>
                                </div>
                          </div>
                          <div class="row">
                            <div class="col-xl-8 col-md-7 col-sm-12">
                              <main-peple-group></main-peple-group>
                            </div>
                            <div class="col-xl-4 col-md-5 col-sm-12">
                              <div class="form-group mb-2">
                                <label class="mt-0 mb-0 font-weight-bold text-dark">วันที่และเวลา <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="date_time_sell" id="edate_time_sell" placeholder="วันที่และเวลา" required>
                              </div> 
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xl-8 col-md-7 col-sm-12">
                              <main-select-custom></main-select-custom>
                            </div>
                              <div class="col-xl-4 col-md-5 col-sm-12">
                                <label class="mt-0 mb-0 col-12 font-weight-bold text-dark">ตัวเลือกการจ่าย</label>
                                <select class="form-control multiple-select" id="e-payment_options" name="payment_option[]" placeholder="ตัวเลือกการจ่าย" multiple="multiple" required>
                                    <option value="โอน">โอน</option>
                                    <option value="จ่ายสด">จ่ายสด</option>
                                    <option value="ติดค้าง">ติดค้าง</option>
                                </select>
                              </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-5 col-md-12">
                          <mian-edit-image id="eslip_orderseller" count="eSell_slip" wrapper="eux-wrap" filenames="eimgname" cancles="ex-cancle"
                            names="รูปโปรไฟล์" custom="ebtn_custom" setdefault="setDefaultImgSellE"></mian-edit-image>
                            <div class="col-12" id="e-status_payment">
                              <label for="" class="small">จำนวนเงินที่ลูกค้าจ่าย</label>
                              <input type="text" class="form-control" name="count_totalpays" id="s_count_totalpays" placeholder="จำนวนเงินที่ลูกค้าจ่าย" required>
                              <input type="hidden" class="form-control" name="count_stuck" id="s_count_stuck"  placeholder="หนี้ค้าง">
                              <div class="align-self-center row mt-2 ml-2">
                                <b id="e-txt-status"></b> <span class="ml-2 font-weight-bold" id="e-results"></span>
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

customElements.define("main-update-ordersell", modelUpdateOrderSell);

class ESelectPepleGroups extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ["peplegroup"];
  }
  get Ispeplegroup() {
    return this.getAttribute("peplegroup");
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
    document
      .getElementById("edit_order_sell")
      .addEventListener("click", (e) => {
        e.preventDefault();
      });
    const index = 1;
    let isForm = document.querySelector(".is-e-PepleGroup");
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

      <div class="col-md-12 is-e-PepleGroup">
        <div class="getSelectPepleGroup form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อผู้ขาย <span class="text-danger">*</span></label>
          <input class="selectedIdData" type="hidden" name="peplegroup_id" id="IsPepleGroutId"/>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control peplegroup_name"  type="text" name="peplegroups_name" id="PepleGroupName" required/>
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

customElements.define("main-peple-group", ESelectPepleGroups);

class ESelectCustomers extends HTMLElement {
  constructor() {
    super();
  }
  static get observedAttributes() {
    return ["customer"];
  }
  get customer() {
    return this.getAttribute("customer");
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
    let isForm = document.querySelector(".e-is-form");
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
         
        <div class="col-md-12 e-is-form">
          <div class="getSelectCustomer form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อลูกค้า <span class="text-danger">*</span></label>
            <div class="customInputContainer">
                <div class="customInput searchInput">
                    <input class="selectedData form-control customers_name"  type="text" name="custome_name" id="ecustome_name" required/>
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

customElements.define("main-select-custom", ESelectCustomers);

$(document).on("click", "#edit_order_sell", function (e) {
  let ordersell_id = $(this).data("ordersellid");
  let component = document.querySelector("main-update-ordersell");

  fetch(
    `http://localhost/smokker24hours/system/backend/api/ordersell.php?ordersell_id=${ordersell_id}`,
    {
      method: "GET",
      credentials: "include",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      const ordersell = data.data.orersell;
      $("#ordersell_id").val(ordersell.id_ordersell);
      $("#eordersell_name").val(ordersell.ordersell_name);
      $("#orders_name").html(ordersell.ordersell_name);
      $("#ecustome_name").val(ordersell.custome_name);
      //$("#etell_custome").val(ordersell.tell_custome);
      $("#edate_time_sell").val(ordersell.date_time_sell);
      $("#IsPepleGroutId").val(ordersell.sell_idpeplegroup);
      $("#PepleGroupName").val(ordersell.name_peplegroup);
      // $("#eshipping_note").val(ordersell.shipping_note);
      // $("#esender").val(ordersell.sender);
      // $("#etell_sender").val(ordersell.tell_sender);
      // $("#elocation_send").val(ordersell.location_send);
      // $("#ewages").val(ordersell.wages);
      // $("#ereason").val(ordersell.reason);
      $("#s_count_stuck").val(ordersell.count_stuck);
      $("#s_count_totalpays").val(ordersell.count_totalpays);

      $("#e-results").html(ordersell.count_stuck);

      $("#img_default").val(ordersell.slip_ordersell);

      //$("#esell_slip").val(ordersell.slip_ordersell);
      $(".eslip_orderseller").attr(
        "src",
        `../db/slip-sellorder/${ordersell.slip_ordersell}`
      );
      $(".eux-wrap").last().addClass("active");
      $(".eimgname").html(ordersell.slip_ordersell);

      const selectTypePrice = data.data.sell_type.map(
        (item) => item.list_typepay
      );
      var $select = $("#e-payment_options");
      $select.multipleSelect("setSelects", selectTypePrice);

      component.dispatchEvent(
        new CustomEvent("setId", {
          detail: {
            ordersell_id: ordersell_id,
            customer: ordersell.custome_name,
          },
        })
      );
      component.dispatchEvent(
        new CustomEvent("setIdPepleGroup", {
          detail: {
            ordersell_id: ordersell_id,
            peplegroup: ordersell.sell_idpeplegroup,
          },
        })
      );
    })
    .catch((e) => {
      console.error(`Fetch Catch ${e}`);
    });
});

class EditSelectCustome extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ["customer"];
  }
  get customer() {
    return this.getAttribute("customer");
  }
  customdata = [];
  async connectedCallback() {
    await this.loadData();
    this.render();
    this.scriptCodeCustome();
  }

  async loadData() {
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
  updateData(data_custome, index, group) {
    this.scriptTell(data_custome);
    this.scriptLocation(data_custome);
    let selectedData = group.querySelector(`.IsselectedData-${index}`);
    let customInputContainer = document.querySelector(
      `.IscustomInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectedData.value = data_custome ?? "";
    for (const li of group.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find(
      (li) => li.innerText === data_custome
    );
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }
  scriptCodeCustome() {
    const index = 1;
    let isForm = document.querySelector(".e-is-form");
    let getSelect = isForm.querySelector(".getSelect");
    let customInputContainer = getSelect.querySelector(".customInputContainer");
    let customInput = getSelect.querySelector(`.customInput`);

    let selectedData = getSelect.querySelector(".selectedData");
    let serchInput = getSelect.querySelector(".searchInput input");
    let ul = getSelect.querySelector(`.options ul`);

    customInput.classList.add(`IscustomInput-${index}`);
    getSelect.classList.add(`IscustomInputContainer-${index}`);
    selectedData.classList.add(`IsselectedData-${index}`);
    serchInput.classList.add(`IsserchInput-${index}`);
    ul.classList.add(`Isoptions-${index}`);

    window.addEventListener("click", (e) => {
      const searchInputEl = getSelect.querySelector(`.IssearchInput-${index}`);
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
        this.scriptTell(spanTxt);
        this.scriptLocation(spanTxt);
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
        ul.innerHTML = "";
        return;
      }
      searched_product.forEach((data) => {
        const li = document.createElement("li");
        li.textContent = data.custome_name;
        li.addEventListener("click", (e) => {
          this.updateData(e.target.textContent, index, getSelect);
        });
        ul.appendChild(li);
      });
    });
  }

  updateDataTell(data_tell, index, group) {
    let selectedData = group.querySelector(`.IsselectedTellData-${index}`);
    let customInputContainer = document.querySelector(
      `.IsTellInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectedData.value = data_tell ?? "";
    for (const li of group.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find((li) => li.innerText === data_tell);
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  scriptTell(customename) {
    if (customename) {
      const index = 2;
      const customes = this.customdata[0].filter((data) =>
        data.custome_name.toLowerCase().includes(customename.toLowerCase())
      );
      const isTell = document.querySelector(".e-is-tell");
      let getSelectTell = isTell.querySelector(".getSelectTell");
      let customInputContainer = getSelectTell.querySelector(
        ".customInputContainer"
      );
      let customInput = getSelectTell.querySelector(`.customInput`);

      let selectedData = getSelectTell.querySelector(".selectedData");
      let serchInput = getSelectTell.querySelector(".searchInput input");
      let ul = getSelectTell.querySelector(`.options ul`);
      customInput.classList.add(`IsTellInput-${index}`);
      getSelectTell.classList.add(`IsTellInputContainer-${index}`);
      selectedData.classList.add(`IsselectedTellData-${index}`);
      serchInput.classList.add(`IsTellserchInput-${index}`);
      ul.classList.add(`IsTelloptions-${index}`);

      selectedData.value = "";

      window.addEventListener("click", (e) => {
        const searchInputEl = getSelectTell.querySelector(
          `.IsTellserchInput-${index}`
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
        customInputContainer.classList.add("show");
      });
      ul.innerHTML = "";
      if (customes.length > 0 && customes[0].customtell) {
        for (let i = 0; i < customes[0].customtell.length; i++) {
          let custom = customes[0].customtell;
          let li = document.createElement("li");
          li.classList.add("block");
          const row = document.createElement("div");
          row.classList.add("row");
          let span = document.createElement("span");
          span.textContent = custom[i];
          row.appendChild(span);
          li.appendChild(row);
          ul.appendChild(li);
        }
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
        if (customes.length > 0 && Array.isArray(customes[0].customtell)) {
          let searched_product = customes[0].customtell.filter((data) =>
            data.toLowerCase().includes(searchedVal)
          );
          ul.innerHTML = "";
          if (searched_product.length === 0) {
            ul.innerHTML = "";
            return;
          }
          searched_product.forEach((data) => {
            const li = document.createElement("li");
            li.textContent = data;
            li.addEventListener("click", (e) => {
              this.updateDataTell(e.target.textContent, index, getSelectTell);
            });
            ul.appendChild(li);
          });
        }
      });
    }
  }

  updateDataLocation(data_location, index, group) {
    let selectedLocationData = group.querySelector(
      `.IsselectedLocationData-${index}`
    );
    let customInputContainer = document.querySelector(
      `.IsLocationInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectedLocationData.value = data_location ?? "";
    for (const li of group.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find(
      (li) => li.innerText === data_location
    );
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  scriptLocation(customername) {
    if (customername) {
      const index = 3;
      const customes = this.customdata[0].filter((data) =>
        data.custome_name.toLowerCase().includes(customername.toLowerCase())
      );
      const isLocation = document.querySelector(".e-is-loaction");
      let getSelectLocation = isLocation.querySelector(".getSelectLocation");
      let customInputContainer = getSelectLocation.querySelector(
        ".customInputContainer"
      );
      let customInput = getSelectLocation.querySelector(`.customInput`);

      let selectedData = getSelectLocation.querySelector(".selectedData");
      let serchInput = getSelectLocation.querySelector(".searchInput input");
      let ul = getSelectLocation.querySelector(`.options ul`);
      customInput.classList.add(`IsLocationInput-${index}`);
      getSelectLocation.classList.add(`IsLocationInputContainer-${index}`);
      selectedData.classList.add(`IsselectedLocationData-${index}`);
      serchInput.classList.add(`IsLocationserchInput-${index}`);
      ul.classList.add(`IsLocationoptions-${index}`);

      selectedData.value = "";

      window.addEventListener("click", (e) => {
        const searchInputEl = getSelectLocation.querySelector(
          `.IsLocationserchInput-${index}`
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
        customInputContainer.classList.add("show");
      });
      ul.innerHTML = "";
      if (customes.length > 0 && customes[0].customlocation) {
        for (let i = 0; i < customes[0].customlocation.length; i++) {
          let custom = customes[0].customlocation;

          let li = document.createElement("li");
          li.classList.add("block");
          const row = document.createElement("div");
          row.classList.add("row");
          let span = document.createElement("span");
          span.textContent = custom[i];
          row.appendChild(span);
          li.appendChild(row);
          ul.appendChild(li);
        }
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

        if (customes.length > 0 && Array.isArray(customes[0].customlocation)) {
          let searched_product = customes[0].customlocation.filter((data) =>
            data.toLowerCase().includes(searchedVal)
          );
          ul.innerHTML = "";
          if (searched_product.length === 0) {
            ul.innerHTML = "";
            return;
          }
          searched_product.forEach((data) => {
            const li = document.createElement("li");
            li.textContent = data;
            li.addEventListener("click", (e) => {
              this.updateDataLocation(
                e.target.textContent,
                index,
                getSelectLocation
              );
            });
            ul.appendChild(li);
          });
        }
      });
    }
  }
  render() {
    this.innerHTML = `
    <div class="row col-12 p-0">
      <div class="col-xl-5 col-md-7 e-is-form">
        <div class="getSelect form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อลูกค้า <span class="text-danger">*</span></label>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control cusotmer_name"  type="text" id="ecustome_name" name="custome_name" required/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div> 
      </div>

      <div class="col-xl-4 col-md-5 e-is-tell">
        <div class="getSelectTell form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark">เบอร์โทร <span class="text-danger">*</span></label>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control cusotmer_tell"  type="text" id="etell_custome" name="tell_custome" required/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div> 
      </div>

      <div class="col-xl-3 col-md-5">
        <div class="form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark">วันที่และเวลา <span class="text-danger">*</span></label>
          <input type="datetime-local" class="form-control" name="date_time_sell" id="edate_time_sell" placeholder="วันที่และเวลา" required>
        </div> 
      </div>
      <div class="col-12 e-is-loaction">
        <div class="getSelectLocation form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">ที่อยู่ในการจัดส่ง</label>
            <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control location_send"  type="text" name="location_send" id="elocation_send" placeholder="ที่อยู่ในการจัดส่ง"/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div>
      </div>
    </div>
    `;
  }
}

//customElements.define("main-edit-select", EditSelectCustome);
