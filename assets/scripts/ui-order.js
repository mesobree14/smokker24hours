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
    this.isSetImagePriviews();
  }
  isSetImagePriviews() {
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

const uiForm = `    
      <div class="col-md-12">
        <div class=row col-12">
        <button type="button" class="remove-btn ml-auto my-2">❌ ลบ</button>
        </div>
        <div class="form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark labelCount"> </label>
          <input class="selectedId form-control c_product_id"  type="hidden" name="product_id[]" required/>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control c_product_name"  type="text" name="product_name[]" required/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div> 
        <div class="col-12 row">
          <span class="res_expenses text-danger ml-auto"></span>
        </div>
      </div>
      <div class="col-md-12 row">
        <div class="col-md-2">
          <div class="form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนลัง</label>
            <input type="text" class="c_count_product form-control" name="count_product[]" id="" placeholder="จำนวนลัง" required>
          </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ราคาต้นทุน/ลัง</label>
              <input type="text" class="c_price_product form-control" name="price_product[]" id="" placeholder="ราคาต้นทุน" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลาง/ลัง</label>
              <input type="text" class="c_price_center form-control" name="price_center[]" id="" placeholder="ราคาต้นทุน" required>
            </div>
        </div>
        <div class="col-md-2 align-self-center">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอต <span class="is_countcord"></span> / 1 ลัง</label>
              <input type="text" class="c_cord form-control" name="count_cord[]" placeholder="จำนวนคอต" required>
            </div>
        </div>
        <div class="col-md-2 align-self-center">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่ง <span class="is_shipping"></span> /1 ลัง </label>
              <input type="text" class="c_shippingcost form-control" name="shipping_cost[]" placeholder="ค่าใช้จ่าย" required>
            </div>
        </div>
        <div class="col-md-2 align-self-center">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าใช้จ่าย </label>
              <input type="text" class="c_expenses form-control" name="expenses[]" placeholder="ค่าใช้จ่าย" required>
            </div>
        </div>
      </div>
    `;

const createGrandTotal = (capital = []) => {
  let valueInput = document.getElementById("totalcost_order");
  const results = document.querySelectorAll("input[id^='expenses-']");
  let totalPrice = 0;
  results.forEach((span) => {
    const value = Number(span.value.trim());
    if (!isNaN(value)) {
      totalPrice += value;
    }
  });

  if (
    totalPrice >
    Number(capital[0]?.funds_that_can_be_used.replace(/[^\d.-]/g, ""))
  ) {
    valueInput.style.color = "red";
    valueInput.style.border = "2px solid red";
  } else {
    valueInput.style.color = "green";
    valueInput.style.border = "2px solid green";
  }
  document.getElementById("totalcost_order").value = totalPrice;
};

class modelCreateOrder extends HTMLElement {
  constructor() {
    super();
  }

  stockproducts = [];
  financedata = [];
  async connectedCallback() {
    await this.loadProduct();

    this.renderCreateOrder();
    this.scripts();
    this.generateID();
    await this.loadBeUseCapital();
    await this.loadLotCode();
  }

  async loadBeUseCapital() {
    try {
      const api_finance = await fetch(
        "http://localhost/smokker24hours/system/backend/api/api_capital_withdraw.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const response = await api_finance.json();
      this.financedata.push(response.data);
      let span = document.getElementById("funds_that_can_be_used");

      span.textContent = `ทุนที่มี ${response.data.funds_that_can_be_used}`;

      if (
        Number(response.data.funds_that_can_be_used.replace(/,/g, "").trim()) >
        0
      ) {
        span.classList.add("text-success");
        span.classList.remove("text-danger");
      } else {
        span.classList.add("text-danger");
        span.classList.remove("text-success");
      }

      return response.data;
    } catch (e) {
      throw new Error(`Is Error : ${e}`);
    }
  }
  async loadProduct() {
    try {
      const api_data = await fetch(
        //"http://localhost/smokker24hours/system/backend/api/stock.php",
        "http://localhost/smokker24hours/system/backend/api/product_name.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const response = await api_data.json();
      this.stockproducts.push(...response.data);
      return response.data;
    } catch (e) {
      throw new Error(`Is Error : ${e}`);
    }
  }
  async loadLotCode() {
    try {
      const api_lotcode = await fetch(
        "http://localhost/smokker24hours/system/backend/api/lot_order.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const responsedata = await api_lotcode.json();
      console.log({ lot: responsedata });
      document.getElementById("lot_code").value = responsedata.lot_code;
    } catch (e) {
      throw new Error(`Is Error Get data lot code ${e}`);
    }
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
    document.getElementById("orderbuy_name").value = id;
  }

  updateData(data_product, id_nameproduct, index, group) {
    let selectedData = group.querySelector(`.IsselectedData-${index}`);
    let selectId = group.querySelector(`.IsSelectId-${index}`);
    let customInputContainer = group.querySelector(
      `.IscustomInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    //code
    selectedData.value = data_product ?? "";
    selectId.value = id_nameproduct ?? "";
    for (const li of group.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find(
      (li) => li.innerText === data_product
    );
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  scripts() {
    const container = document.getElementById("formcreateorder");

    const CountForm = () => {
      const group = container.querySelectorAll(".formGroup");
      group.forEach((group, index) => {
        let label = group.querySelector("label");
        label.textContent = `ชื่อสินค้าตัวที่ ${index + 1}`;

        let customInput = group.querySelector(`.customInput`);
        let customInputContainer = group.querySelector(".customInputContainer");
        let selectedData = group.querySelector(".selectedData");
        let selectedId = group.querySelector(".selectedId");
        let serchInput = group.querySelector(".searchInput input");
        let ul = group.querySelector(`.options ul`);

        customInput.classList.add(`IscustomInput-${index}`);
        customInputContainer.classList.add(`IscustomInputContainer-${index}`);
        selectedData.classList.add(`IsselectedData-${index}`);
        selectedId.classList.add(`IsSelectId-${index}`);
        serchInput.classList.add(`IsserchInput-${index}`);
        ul.classList.add(`Isoptions-${index}`);

        let product_name = group.querySelector(".c_product_name");
        let count_product = group.querySelector(".c_count_product");
        let price_product = group.querySelector(".c_price_product");
        let expenses = group.querySelector(".c_expenses");
        let price_center = group.querySelector(".c_price_center");
        let count_cord = group.querySelector(".c_cord");
        let shippingcost = group.querySelector(".c_shippingcost");
        let is_countcord = group.querySelector(".is_countcord");
        let is_shipping = group.querySelector(".is_shipping");
        let res_expenses = group.querySelector(".res_expenses");
        product_name.id = `product_name-${index}`;
        count_product.id = `count_product-${index}`;
        price_product.id = `price_product-${index}`;
        expenses.id = `expenses-${index}`;
        count_cord.id = `count_cord-${index}`;
        shippingcost.id = `i_shippingcost-${index}`;
        price_center.id = `price_center-${index}`;
        is_countcord.id = `is_countcord-${index}`;
        is_shipping.id = `is_shipping-${index}`;
        res_expenses.id = `res_expenses-${index}`;

        window.addEventListener("click", (e) => {
          const searchInputEl = group.querySelector(`.IssearchInput-${index}`);
          if (searchInputEl && searchInputEl.contains(e.target)) {
            searchInputEl.classList.add("focus");
          } else if (searchInputEl) {
            searchInputEl.classList.remove("focus");
          }
          if (
            customInputContainer &&
            !customInputContainer.contains(e.target)
          ) {
            customInputContainer.classList.remove("show");
          }
        });
        customInput.addEventListener("click", () => {
          customInputContainer.classList.add("show");
        });
        for (let i = 0; i < this.stockproducts.length; i++) {
          let product = this.stockproducts[i];
          let li = document.createElement("li");
          li.classList.add("block");
          const row = document.createElement("div");
          row.classList.add("row");
          let span = document.createElement("span");
          let pre = document.createElement("p");
          pre.style.display = "none";
          let small = document.createElement("small");
          span.textContent = product.product_name;
          pre.textContent = product.id_name;
          small.textContent = `เหลืออีก ${product.countcord_product} คอต`;
          small.classList.add("ml-auto");
          row.appendChild(span);
          row.appendChild(pre);
          row.appendChild(small);
          li.appendChild(row);
          ul.appendChild(li);
        }
        ul.querySelectorAll("li").forEach((li) => {
          li.addEventListener("click", () => {
            let spanTxt = li.querySelector("span").innerText;
            let spanId = li.querySelector("p").innerText;
            selectedId.value = spanId;
            selectedData.value = spanTxt;

            for (const li of document.querySelectorAll("li.selected")) {
              li.classList.remove("selected");
            }
            li.classList.add("selected");
            customInputContainer.classList.toggle("show");
            this.getDataList(spanId, index, group);
          });
        });
        serchInput.addEventListener("keyup", () => {
          let searchedVal = serchInput.value.toLowerCase();
          let searched_product = this.stockproducts.filter((data) =>
            data.product_name.toLowerCase().includes(searchedVal)
          );
          ul.innerHTML = "";
          if (searched_product.length === 0) {
            //dropdown.classList.add("disableds");
            ul.innerHTML = `<p style='margin-top: 1rem;'>
                          ไม่มีข้อมูล
                        </p>`;
            return;
          }
          searched_product.forEach((data) => {
            const li = document.createElement("li");
            li.classList.add("row");
            const spanName = document.createElement("span");
            const idInLi = document.createElement("p");
            let small2 = document.createElement("small");
            idInLi.textContent = data.id_name;
            spanName.classList = "names-product";
            idInLi.className = "hidden-id";
            idInLi.style.display = "none";
            //li.textContent = data.product_name;
            spanName.textContent = data.product_name;
            small2.textContent = `เหลืออีก ${data.countcord_product} คอต`;
            small2.classList.add("ml-auto");
            li.appendChild(spanName);
            li.appendChild(idInLi);
            li.appendChild(small2);
            li.addEventListener("click", (e) => {
              let idName = li.querySelector(".hidden-id").textContent;
              let productName = li.querySelector(".names-product").textContent;
              this.updateData(productName, idName, index, group);
              this.getDataList(idName, index, group);
            });
            ul.appendChild(li);
          });
        });

        count_product.addEventListener("input", (e) => {
          let value = e.target.value;

          //price_product.value = Number((expenses.value / value).toFixed(2));
          count_cord.value = Number(value * Number(is_countcord.textContent));
          shippingcost.value = Number(
            value * Number(is_shipping.textContent)
          ).toFixed(2);
          expenses.value =
            Number((price_product.value * value).toFixed(2)) +
            Number(shippingcost.value);
          res_expenses.textContent = `สินค้า(${Number(
            (price_product.value * value).toFixed(2)
          )}) + ค่าส่ง(${Number(shippingcost.value).toFixed(2)})`;
          createGrandTotal(this.financedata);
        });

        price_product.addEventListener("input", (e) => {
          let value = e.target.value;
          expenses.value =
            Number((count_product.value * value).toFixed(2)) +
            Number(shippingcost.value);
          res_expenses.textContent = `สินค้า(${Number(
            (count_product.value * value).toFixed(2)
          )}) + ค่าส่ง(${Number(shippingcost.value).toFixed(2)})`;
          createGrandTotal(this.financedata);
        });
        shippingcost.addEventListener("input", (e) => {
          let value = e.target.value;
          expenses.value =
            Number((count_product.value * price_product.value).toFixed(2)) +
            Number(value);
          res_expenses.textContent = `สินค้า(${Number(
            (count_product.value * price_product.value).toFixed(2)
          )}) + ค่าส่ง(${Number(shippingcost.value).toFixed(2)})`;
        });

        expenses.addEventListener("input", (e) => {
          let value =
            Number(e.target.value) - Number(shippingcost.value).toFixed(2);
          price_product.value = Number(
            (value / count_product.value).toFixed(2)
          );
          createGrandTotal(this.financedata);
        });
      });
    };
    document.getElementById("add-form").addEventListener("click", function () {
      const div = document.createElement("div");
      div.className = "formGroup col-md-12 border mb-3";
      div.innerHTML = uiForm;
      container.appendChild(div);
      div.querySelector(".remove-btn").addEventListener("click", function () {
        div.remove();
        CountForm();
        createGrandTotal();
        document.dispatchEvent(new Event("recalculate"));
      });
      CountForm();
    });
    CountForm();
  }
  getDataList(idProductName, indexs, groups) {
    let price_center = groups.querySelector(".c_price_center");
    let price_product = groups.querySelector(".c_price_product");
    let is_countcord = groups.querySelector(".is_countcord");
    //let is_expenses = groups.querySelector(".is_expenses");
    let is_shipping = groups.querySelector(".is_shipping");
    price_product.id = `price_product-${indexs}`;
    price_center.id = `price_center-${indexs}`;
    is_countcord.id = `is_countcord-${indexs}`;
    //is_expenses.id = `is_expenses-${indexs}`;
    is_shipping.id = `is_shipping-${indexs}`;
    let dataproduct = this.stockproducts.filter((data) =>
      data.id_name.toLowerCase().includes(idProductName.toLowerCase())
    );
    price_product.value = dataproduct[0].price;
    price_center.value = dataproduct[0].price_center;
    is_countcord.textContent = dataproduct[0].count_cord;
    is_shipping.textContent = `${dataproduct[0].shipping_cost}`;
  }
  renderCreateOrder() {
    this.innerHTML = `
    <div class="modal fade bd-example-modal-xl" id="modalFormCreateOrder" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl2 modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">สินค้าที่สั่งซื้อ</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="myForm" method="post" action="backend/create_order.php" enctype="multipart/form-data">
              <input type="hidden" name="status_form" value="create" />
              <div class="modal-body">
                <div class="mt-2 row border">
                  <div class="col-md-8">
                      <div class="col-md-12">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">รายการล็อตที่</label>
                          <input type="hidden" class="form-control" name="order_name" id="orderbuy_name" placeholder="รายการคำสั่งซื้อ" required>
                          <input type="text" class="form-control" name="lot_number" id="lot_code" placeholder="รายการล็อตที่" required>
                        </div> 
                      </div>

                      <div class="col-md-12 row">
                        <div class="col-md-7">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าใช้จ่ายทั้งหมด</label>
                            <input type="text" class="form-control" name="totalcost_order" id="totalcost_order" placeholder="ค่าใช้จ่าย" required>
                          </div> 
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <label class="mt-0 mb-0 font-weight-bold text-dark align-self-center mt-4">บาท <span id="funds_that_can_be_used"></span></label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">วันที่และเวลา</label>
                          <input type="datetime-local" class="form-control" name="date_time_order" id="" placeholder="วันที่และเวลา" required>
                        </div> 
                      </div>
                  </div>
                  <div class="col-md-4">
                    <mian-add-image id="orphanImage" count="slipt_order" wrapper="x-wrapX" filenames="ximgnameX" cancles="x-cancleX"
                      names="รูปโปรไฟล์" custom="setbtnCustomX" setdefault="setDefaultImgOrphan"></mian-add-image>
                  </div>
                </div>
                
                <div id="formcreateorder">
                <div class="col-md-12 border mb-3 formGroup">
                    <div class="col-md-12">
                      <div class="form-group mb-2">
                        <label class="mt-0 mb-0 font-weight-bold text-dark">ชื่อสินค้าตัวที่1 </label>
                        <input class="selectedId form-control c_product_id"  type="hidden" name="product_id[]" required/>
                        <div class="customInputContainer">
                            <div class="customInput searchInput">
                                <input class="selectedData form-control c_product_name"  type="text" name="product_name[]" required/>
                            </div>
                            <div class="options">
                                <ul></ul>
                            </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <span class="res_expenses text-danger ml-auto"></span>
                      </div>  
                    </div>
                    <div class="col-md-12 row">
                      <div class="col-md-2">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนลัง</label>
                          <input type="text" class="c_count_product form-control" name="count_product[]"placeholder="จำนวนลัง" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">ราคาต้นทุน/ลัง</label>
                            <input type="text" class="c_price_product form-control" name="price_product[]" placeholder="ราคาต้นทุน" required>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลาง/ลัง</label>
                            <input type="text" class="c_price_center form-control" name="price_center[]" id="" placeholder="ราคาต้นทุน" required>
                          </div>
                      </div>
                      <div class="col-md-2 align-self-center">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอต <span class="is_countcord text-danger"></span> / 1 ลัง</label>
                          <input type="text" class="c_cord form-control" name="count_cord[]" placeholder="จำนวนคอต" required>
                        </div>
                      </div>
                      <div class="col-md-2 align-self-center">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่ง <span class="is_shipping text-danger"></span> /1 ลัง</label>
                            <input type="text" class="c_shippingcost form-control" name="shipping_cost[]" placeholder="ค่าใช้จ่าย" required>
                          </div>
                      </div>
                      <div class="col-md-2 align-self-center">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">
                              ค่าใช้จ่าย
                            </label>
                            <input type="text" class="c_expenses form-control" name="expenses[]" placeholder="ค่าใช้จ่าย" required>
                          </div>
                      </div>
                    </div>
                </div>
                </div>
                <div class="col-md-12 row mt-4">
                  <button type="button" class="btn btn-sm btn-success ml-auto mr-4" id="add-form">เพิ่ม สินค้า</button>
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
customElements.define("main-create-order", modelCreateOrder);

const uiFormUpdate = `    
      <div class="col-md-12">
        <div class=row col-12">
        <button type="button" class="remove-btn ml-auto my-2">❌ ลบ</button>
        </div>
        <input type="hidden" name="product_id[]" value="" />
        <div class="form-group mb-2">
          <label class="mt-0 mb-0 font-weight-bold text-dark labelCount"></label>
          <input class="selectId form-control c_product_id"  type="hidden" name="is_idproduct[]" required/>
          <div class="customInputContainer">
              <div class="customInput searchInput">
                  <input class="selectedData form-control u_product_name"  type="text" name="product_name[]" required/>
              </div>
              <div class="options">
                  <ul></ul>
              </div>
          </div>
        </div>  
        <div class="col-12 row">
          <span class="ures_expenses text-danger ml-auto"></span>
        </div> 
      </div>
      <div class="col-md-12 row">
        <div class="col-md-2">
          <div class="form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนลัง</label>
            <input type="text" class="u_count_product form-control" name="count_product[]" id="" placeholder="จำนวนลัง" required>
          </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ราคาต้นทุน/ลัง</label>
              <input type="text" class="u_price_product form-control" name="price_product[]" id="" placeholder="ราคาต้นทุน" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลาง/ลัง</label>
              <input type="text" class="u_price_center form-control" name="price_center[]" id="" placeholder="ราคาต้นทุน" required>
            </div>
        </div>
        <div class="col-md-2 align-self-center">
          <div class="form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอต <span class="uis_countcord text-danger"></span> / 1 ลัง</label>
            <input type="text" class="u_count_cord form-control" name="count_cord[]" placeholder="จำนวนคอต" required>
          </div>
        </div>
        <div class="col-md-2 align-self-center">
          <div class="form-group mb-2">
            <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่ง <span class="uis_shipping text-danger"></span> /1 ลัง</label>
            <input type="text" class="u_shippingcost form-control" name="shipping_cost[]" placeholder="ค่าส่ง" required>
          </div>
        </div>
        <div class="col-md-2 align-self-center">
            <div class="form-group mb-2">
              <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าใช้จ่าย</label>
              <input type="text" class="u_expenses form-control" name="expenses[]" placeholder="ค่าใช้จ่าย" required>
            </div>
        </div>
      </div>
    `;

const updateGrandTotal = (capital = []) => {
  let valueInput = document.getElementById("add-price");
  let def_value = document.getElementById("defult-price");

  const results = document.querySelectorAll("input[id^='e-expenses-']");
  let totalPrice = 0;
  results.forEach((span) => {
    const value = Number(span.value.trim());
    if (!isNaN(value)) {
      totalPrice += value;
    }
  });
  let del = totalPrice - Number(def_value.textContent.match(/[0-9.]+/)[0]);

  if (del > Number(capital[0].funds_that_can_be_used.replace(/[^\d.-]/g, ""))) {
    valueInput.style.color = "red";
    valueInput.textContent = `เพิ่มมา ${del} บาท เกินงบ`;
  } else {
    valueInput.style.color = "green";
    valueInput.textContent = `เพิ่มมา ${del} บาท`;
  }

  document.getElementById("totalcost_orders").value = totalPrice;
};
class modelUpdateOrder extends HTMLElement {
  stockproductAll = [];
  stockproducts_id = [];
  financedata = [];
  connectedCallback() {
    this.addEventListener("setId", (e) => {
      this.productId = e.detail;
      this.loadOrder(this.productId);
      this.scripts();
    });
    this.loadProductAll();
    //this.loadLotCode();

    this.renderUpdateOrder();
    this.countterForm();
    this.loadBeUseCapital();
  }

  async loadBeUseCapital() {
    try {
      const api_finance = await fetch(
        "http://localhost/smokker24hours/system/backend/api/api_capital_withdraw.php",
        {
          method: "GET",
          credentials: "include",
        }
      );
      const response = await api_finance.json();
      this.financedata.push(response.data);
      let span = document.getElementById("u_funds_that_can_be_used");
      span.textContent = `ทุนที่มี ${response.data.funds_that_can_be_used}`;

      if (
        Number(response.data?.funds_that_can_be_used.replace(/,/g, "").trim()) >
        0
      ) {
        span.classList.add("text-success");
        span.classList.remove("text-danger");
      } else {
        span.classList.add("text-danger");
        span.classList.remove("text-success");
      }

      return response.data;
    } catch (e) {
      throw new Error(`Is Error : ${e}`);
    }
  }

  async loadProductAll() {
    try {
      const api_data = await fetch(
        //"http://localhost/smokker24hours/system/backend/api/stock.php",
        "http://localhost/smokker24hours/system/backend/api/product_name.php",
        {
          method: "GET",
          credentials: "include",
        }
      );

      const responsedata = await api_data.json();
      this.stockproductAll.push(...responsedata.data);
      return responsedata.data;
    } catch (e) {
      throw new Error(`Is Error Get data stock ${e}`);
    }
  }

  // async loadLotCode() {
  //   try {
  //     const api_lotcode = await fetch(
  //       "http://localhost/smokker24hours/system/backend/api/lot_order.php",
  //       {
  //         method: "GET",
  //         credentials: "include",
  //       }
  //     );
  //     const responsedata = await api_lotcode.json();
  //     console.log({ lot: responsedata });
  //     document.getElementById("lot_code").value = responsedata.lot_code;
  //   } catch (e) {
  //     throw new Error(`Is Error Get data lot code ${e}`);
  //   }
  // }

  updateData(data_product, id_products, index, group) {
    let selectedData = group.querySelector(`.IsselectedData-${index}`);
    let IsSelectId = group.querySelector(`.IsSelectId-${index}`);
    let customInputContainer = group.querySelector(
      `.IscustomInputContainer-${index}`
    );
    const ul = group.querySelector("ul");
    selectedData.value = data_product ?? "";
    IsSelectId.value = id_products ?? "";
    for (const li of group.querySelectorAll("li.selected")) {
      li.classList.remove("selected");
    }
    const clickedLi = [...ul.children].find(
      (li) => li.innerText === data_product
    );
    if (clickedLi) clickedLi.classList.add("selected");
    customInputContainer.classList.toggle("show");
  }

  countterForm() {
    const container = document.getElementById("formupdateorder");
    const group = container.querySelectorAll(".formGroup");
    group.forEach((group, index) => {
      let label = group.querySelector("label");
      label.textContent = `ชื่อสินค้าตัวที่ ${index + 1}`;

      let customInput = group.querySelector(`.customInput`);
      let customInputContainer = group.querySelector(".customInputContainer");
      let selectedData = group.querySelector(".selectedData");
      let selectId = group.querySelector(".selectId");
      let serchInput = group.querySelector(".searchInput input");
      let ul = group.querySelector(`.options ul`);

      customInput.classList.add(`IscustomInput-${index}`);
      customInputContainer.classList.add(`IscustomInputContainer-${index}`);
      selectedData.classList.add(`IsselectedData-${index}`);
      selectId.classList.add(`IsSelectId-${index}`);
      serchInput.classList.add(`IsserchInput-${index}`);
      ul.classList.add(`Isoptions-${index}`);

      let product_name = group.querySelector(".u_product_name");
      let count_product = group.querySelector(".u_count_product");
      let price_product = group.querySelector(".u_price_product");
      let price_center = group.querySelector(".u_price_center");
      let count_cords = group.querySelector(".u_count_cord");
      let shippings_cost = group.querySelector(".u_shippingcost");
      let ures_expenses = group.querySelector(".ures_expenses");

      let uis_countcord = group.querySelector(".uis_countcord");
      let uis_shipping = group.querySelector(".uis_shipping");
      let expenses = group.querySelector(".u_expenses");
      product_name.id = `e-product_name-${index}`;
      count_product.id = `e-count_product-${index}`;
      price_product.id = `e-price_product-${index}`;
      price_center.id = `e-price_center-${index}`;
      count_cords.id = `e-count_cords-${index}`;
      shippings_cost.id = `e-shippings_cost-${index}`;
      expenses.id = `e-expenses-${index}`;

      uis_countcord.id = `e-uis_countcord-${index}`;
      uis_shipping.id = `e-uis_shipping-${index}`;
      ures_expenses.id = `e-ures_expenses-${index}`;

      window.addEventListener("click", (e) => {
        const searchInputEl = group.querySelector(`.IssearchInput-${index}`);
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
      for (let i = 0; i < this.stockproductAll.length; i++) {
        let product = this.stockproductAll[i];
        let li = document.createElement("li");
        li.classList.add("block");
        const row = document.createElement("div");
        row.classList.add("row");
        let span = document.createElement("span");
        let pre = document.createElement("p");
        pre.style.display = "none";
        let small = document.createElement("small");
        span.textContent = product.product_name;
        pre.textContent = product.id_name;
        small.textContent = `เหลืออีก ${product.countcord_product} คอต`;
        small.classList.add("ml-auto");
        row.appendChild(span);
        row.appendChild(pre);
        row.appendChild(small);
        li.appendChild(row);
        ul.appendChild(li);
      }

      ul.querySelectorAll("li").forEach((li) => {
        li.addEventListener("click", () => {
          let spanTxt = li.querySelector("span").innerText;
          let spanId = li.querySelector("p").innerText;
          selectId.value = spanId;
          selectedData.value = spanTxt;

          for (const li of document.querySelectorAll("li.selected")) {
            li.classList.remove("selected");
          }
          li.classList.add("selected");
          customInputContainer.classList.toggle("show");
          this.getDataLists(spanId, index, group);
        });
      });
      serchInput.addEventListener("keyup", () => {
        let searchedVal = serchInput.value.toLowerCase();
        let searched_product = this.stockproductAll.filter((data) =>
          data.product_name.toLowerCase().includes(searchedVal)
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
          let spannames = document.createElement("span");
          let IdInLi = document.createElement("p");
          let smallNo2 = document.createElement("small");
          IdInLi.textContent = data.id_name;
          spannames.classList = "productnames";
          IdInLi.className = "id-hedden";
          IdInLi.style.display = "none";
          //li.textContent = data.product_name;
          spannames.textContent = data.product_name;
          smallNo2.textContent = `เหลืออีก ${data.countcord_product} คอต`;
          smallNo2.classList.add("ml-auto");
          li.appendChild(spannames);
          li.appendChild(IdInLi);
          li.appendChild(smallNo2);

          li.addEventListener("click", (e) => {
            let IsIdName = li.querySelector(".id-hedden").textContent;
            let IsProductName = li.querySelector(".productnames").textContent;
            console.log({ IsIdName });
            this.updateData(IsProductName, IsIdName, index, group);
            this.getDataLists(IsIdName, index, group);
          });
          ul.appendChild(li);
        });
      });

      count_product.addEventListener("input", (e) => {
        let value = e.target.value;
        //price_product.value = Number((expenses.value / value).toFixed(2));
        count_cords.value = Number(value * Number(uis_countcord.textContent));
        shippings_cost.value = Number(
          value * Number(uis_shipping.textContent)
        ).toFixed(2);
        expenses.value =
          Number((price_product.value * value).toFixed(2)) +
          Number(shippings_cost.value);
        ures_expenses.textContent = `สินค้า(${Number(
          (price_product.value * value).toFixed(2)
        )}) + ค่าส่ง(${Number(shippings_cost.value).toFixed(2)})`;
        updateGrandTotal(this.financedata);
      });
      console.log("gH=", shippings_cost.value);

      price_product.addEventListener("input", (e) => {
        let value = e.target.value;
        expenses.value =
          Number((count_product.value * value).toFixed(2)) +
          Number(shippings_cost.value);
        ures_expenses.textContent = `สินค้า(${Number(
          (count_product.value * value).toFixed(2)
        )}) + ค่าส่ง(${Number(shippings_cost.value).toFixed(2)})`;
        updateGrandTotal(this.financedata);
      });
      shippings_cost.addEventListener("input", (e) => {
        let value = e.target.value;
        expenses.value =
          Number((count_product.value * price_product.value).toFixed(2)) +
          Number(value);
        ures_expenses.textContent = `สินค้า(${Number(
          (count_product.value * price_product.value).toFixed(2)
        )}) + ค่าส่ง(${Number(value).toFixed(2)})`;
      });

      expenses.addEventListener("input", (e) => {
        let value =
          Number(e.target.value) - Number(shippings_cost.value).toFixed(2);
        price_product.value = Number((value / count_product.value).toFixed(2));
        updateGrandTotal(this.financedata);
      });
    });
  }

  async loadOrder(productId) {
    try {
      const response = await fetch(
        `http://localhost/smokker24hours/system/backend/api/order.php?order_id=${productId}`,
        {
          method: "GET",
          credentials: "include",
        }
      );

      const data = await response.json();
      this.stockproducts_id.push(...data.data);
      const container = document.getElementById("formupdateorder");
      data.data &&
        data.data.forEach((stock, index) => {
          const div = document.createElement("div");
          div.className = "formGroup col-md-12 border mb-3";
          div.dataset.index = stock?.product_id;
          this.countterForm();
          div.innerHTML = `
            <div class="col-md-12" >
              <div class=row col-12">
               <button type="button" class="remove-btn-2 ml-auto my-2" data-index="${
                 stock?.product_id
               }">❌ ลบ</button>
              </div>
              <input type="hidden" name="product_id[]" value="${
                stock?.product_id
              }" />
              <div class="form-group mb-2">
                <label class="mt-0 mb-0 font-weight-bold text-dark"></label>
                <input class="selectId form-control c_product_id" name="is_idproduct[]"  type="hidden" value="${
                  stock?.id_name
                }" name="product_id[]" required/>
                <div class="customInputContainer">
                    <div class="customInput searchInput">
                        <input class="selectedData form-control u_product_name" value="${
                          stock?.product_name
                        }" type="text" name="product_name[]" required/>
                    </div>
                    <div class="options">
                        <ul></ul>
                    </div>
                </div
              </div>  
              <div class="col-12 row">
                <span class="ures_expenses text-danger ml-auto"></span>
              </div> 
            </div>
            <div class="col-md-12 row">
              <div class="col-md-2">
                <div class="form-group mb-2">
                  <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนลัง</label>
                  <input type="text" class="u_count_product form-control" name="count_product[]" value="${
                    stock?.product_count
                  }" placeholder="ชื่อสินค้า" required>
                </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group mb-2">
                    <label class="mt-0 mb-0 font-weight-bold text-dark">ต้นทุน/ลัง</label>
                    <input type="text" class="u_price_product form-control" name="price_product[]" value="${
                      stock?.product_price
                    }"  placeholder="ต้นทุนต่อชิ้น" required>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group mb-2">
                    <label class="mt-0 mb-0 font-weight-bold text-dark">ราคากลาง/ลัง</label>
                    <input type="text" class="u_price_center form-control" name="price_center[]" value="${
                      stock?.price_center
                    }" id="" placeholder="ราคากลาง" required>
                  </div>
              </div>
              <div class="col-md-2 align-self-center">
               <div class="form-group mb-2">
                 <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนคอต <span class="uis_countcord text-danger">${
                   stock?.res_count_cord / stock?.product_count
                 }</span> / 1 ลัง</label>
                 <input type="text" class="u_count_cord form-control" name="count_cord[]" value="${
                   stock?.res_count_cord
                 }" placeholder="จำนวนคอต" required>
               </div>
             </div>
             <div class="col-md-2 align-self-center">
               <div class="form-group mb-2">
                 <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าส่ง <span class="uis_shipping text-danger">${
                   stock?.res_shipping_cost / stock?.product_count
                 }</span> /1 ลัง</label>
                 <input type="text" class="u_shippingcost form-control" name="shipping_cost[]" value="${
                   stock?.res_shipping_cost
                 }" placeholder="ค่าส่ง" required>
               </div>
             </div>
             <div class="col-md-2 align-self-center">
               <div class="form-group mb-2">
                 <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าใช้จ่าย</label>
                 <input type="text" class="u_expenses form-control" name="expenses[]" value="${
                   stock?.expenses
                 }" placeholder="ค่าใช้จ่าย" required>
               </div>
             </div>
            </div>`;
          container.appendChild(div);
          let product_name = div.querySelector(".u_product_name");
          let count_product = div.querySelector(".u_count_product");
          let count_cords = div.querySelector(".u_count_cord");
          let price_product = div.querySelector(".u_price_product");
          let price_center = div.querySelector(".u_price_center");
          let u_shippingcost = div.querySelector(".u_shippingcost");
          let expenses = div.querySelector(".u_expenses");

          let is_countcords = div.querySelector(".uis_countcord");
          let is_shipping = div.querySelector(".uis_shipping");

          product_name.id = `e-product_name-${index}`;
          count_product.id = `e-count_product-${index}`;
          count_cords.id = `e-count_cords-${index}`;
          price_product.id = `e-price_product-${index}`;
          price_center.id = `e-price_center-${index}`;
          u_shippingcost.id = `e-u_shippingcost-${index}`;
          expenses.id = `e-expenses-${index}`;

          is_countcords.id = `e-is_countcords-${index}`;
          is_shipping.id = `e-is_shipping-${index}`;

          count_product.addEventListener("input", (e) => {
            let value = e.target.value;
            //price_product.value = Number((expenses.value / value).toFixed(2));
            count_cords.value = Number(
              value * Number(is_countcords.textContent)
            );
            u_shippingcost.value = Number(
              value * Number(is_shipping.textContent)
            ).toFixed(2);

            expenses.value =
              Number((price_product.value * value).toFixed(2)) +
              Number(u_shippingcost.value);

            updateGrandTotal(this.financedata);
          });

          price_product.addEventListener("input", (e) => {
            let value = e.target.value;
            expenses.value = Number((count_product.value * value).toFixed(2));
            //count_cords.value = Number(value * Number())
            updateGrandTotal(this.financedata);
          });

          expenses.addEventListener("input", (e) => {
            let value = e.target.value;
            price_product.value = Number(
              (value / count_product.value).toFixed(2)
            );
            updateGrandTotal(this.financedata);
          });
          container.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-btn-2")) {
              const index = e.target.dataset.index;
              const targetDiv = document.querySelector(
                `[data-index="${index}"]`
              );
              if (targetDiv) targetDiv.remove();
              updateGrandTotal(this.financedata);
            }
          });
        });
    } catch (e) {
      console.error(`"Error loading orders: ${e}`);
    }
  }

  scripts() {
    const containers = document.getElementById("formupdateorder");

    document.getElementById("add-form-update").addEventListener("click", () => {
      const divs = document.createElement("div");
      divs.className = "formGroup col-md-12 border mb-3";

      divs.innerHTML = uiFormUpdate;
      containers.appendChild(divs);

      divs.querySelector(".remove-btn").addEventListener("click", () => {
        divs.remove();
        this.countterForm();
        //updateGrandTotal();
      });
      this.countterForm();
    });
  }
  getDataLists(ProductName_Id, indexs, Groups) {
    let price_center = Groups.querySelector(".u_price_center");
    let price_product = Groups.querySelector(".u_price_product");
    let is_countcord = Groups.querySelector(".uis_countcord");
    let is_shipping = Groups.querySelector(".uis_shipping");

    price_product.id = `e-price_product-${indexs}`;
    price_center.id = `e-price_center-${indexs}`;
    is_countcord.id = `ui-count_cords-${indexs}`;
    is_shipping.id = `ui-is_shipping-${indexs}`;
    let isDataProduct = this.stockproductAll.filter((data) =>
      data.id_name.toLowerCase().includes(ProductName_Id.toLowerCase())
    );
    price_product.value = isDataProduct[0].price;
    price_center.value = isDataProduct[0].price_center;
    is_countcord.textContent = isDataProduct[0].count_cord;
    is_shipping.textContent = `${isDataProduct[0].shipping_cost}`;
    console.log({ is_countcord, is_shipping });
  }
  renderUpdateOrder() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl " id="modalFormUpdateOrder" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl2 modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header px-4">
              <h5 class="modal-title row mx-4" id="exampleModalLongTitle">สินค้าที่สั่งซื้อ</p></h5>
              <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="form_update" method="post" action="backend/create_order.php" enctype="multipart/form-data">
              <input type="hidden" name="status_form" value="update" />
              <input type="hidden" name="order_id" id="order_id" />
              <input type="hidden" name="default_img" id="img_default"/>
              <div class="modal-body">
                <div class="mt-2 row">
                  <div class="col-md-8">
                      <div class="col-md-12">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">รายการคำสั่งซื้อ</label>
                          <input type="hidden" class="form-control" name="order_name" id="order_name" placeholder="รายการคำสั่งซื้อ" required>
                          <input type="text" class="form-control" name="lot_number" id="lot_codes" placeholder="รายการล็อตที่" required>
                        </div> 
                      </div>

                      <div class="col-md-12 row">
                        <div class="col-md-7">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">ค่าใช้จ่าย</label>
                            <input type="text" class="form-control" name="totalcost_order" id="totalcost_orders" placeholder="ค่าใช้จ่าย" required>
                          </div> 
                        </div>
                        <div class="col-md-5 row"> 
                          <div class="form-group">
                            <label class="mt-0 mb-0 font-weight-bold text-dark align-self-center mt-4">บาท</label>
                          </div>
                        </div>
                        
                      </div>
                      <div class="col-md-12">
                      <div class="form-group">
                        <label class="mt-0 mb-0 font-weight-bold text-dark align-self-center">
                          <span class="text-primary px-2" id="defult-price"></span>
                          <span class="px-2" id="u_funds_that_can_be_used"></span> 
                          <span class="px-2" id="add-price"></span>
                        </label>
                      </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">วันที่และเวลา</label>
                          <input type="datetime-local" class="form-control" name="date_time_order" id="date_time_order" placeholder="วันที่และเวลา" required>
                        </div> 
                      </div>
                  </div>
                  <div class="col-md-4">
                    <mian-add-image id="slipt_order" count="order_Slip" wrapper="ux-wrap" filenames="uimgname" cancles="ux-cancle"
                      names="รูปโปรไฟล์" custom="btn_custom" setdefault="setDefaultImgOrder"></mian-add-image>
                  </div>
                </div>
                
                <div id="formupdateorder"></div>
                
                <div class="col-md-12 row mt-4">
                  <button type="button" class="btn btn-sm btn-success ml-auto mr-4" id="add-form-update">เพิ่ม สินค้า</button>
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
customElements.define("main-update-order", modelUpdateOrder);

$(document).on("click", "#update_order", function (e) {
  let product_id = $(this).data("id");
  let comp = document.querySelector("main-update-order");
  comp.dispatchEvent(new CustomEvent("setId", { detail: product_id }));
  const container = document.querySelector("#formupdateorder");
  if (container) container.innerHTML = "";

  let order_name = $(this).data("ordername");
  let lot_codes = $(this).data("lot");
  let total_cost = $(this).data("totalcost");

  let priceorder = $(this).data("priceorder");
  let slipimage = $(this).data("slipimage");
  let dateorder = $(this).data("dateorder");
  $("#order_id").val(product_id);
  $("#order_name").val(order_name);
  $("#lot_codes").val(lot_codes);
  $("#totalcost_orders").val(total_cost);
  $("#defult-price").html(`เดิม ${total_cost} บาท`);
  $("#priceorder").html(priceorder);
  $("#date_time_order").val(dateorder);
  $("#img_default").val(slipimage);

  e.preventDefault();
  $("#slip_order").val(slipimage);
  $(".slipt_order").attr("src", `../db/slip-orders/${slipimage}`);
  $(".ux-wrap").last().addClass("active");
  $(".uimgname").html(slipimage);
});

$(document).on("click", "#confirmTrashOrder", function (e) {
  let ID = $(this).data("id");
  let ordername = $(this).data("ordername");
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `รายการ ${ordername} นี้ พร้อมสินค้า จะถูกลบทั้งหมด จะไม่สามารถย้อนกลับได้`,
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
          `http://localhost/smokker24hours/system/backend/api/order.php?order_id=${ID}`,
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
        throw new Error(`Is Delete Error : ${e}`);
      }
    }
  });
});
