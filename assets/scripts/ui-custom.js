"use strict";
// import {useState} from "./modules/state"
// const [] = useState(0,)

$(function () {
  var $tabButtonItem = $("#tab-button-custom li"),
    $tabSelect = $("#tab-select-custom"),
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

class ModelPayOffDebt extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback() {
    this.renderUi();
    //this.script();
    this.generateID();
    this.getordersellDebt();
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
    document.getElementById("serial_number").value = id;
  }

  //script() {
  // let count_paydebt = document.getElementById("count_paydebt");
  // let count_debt = document.getElementById("count_debt");
  // let debtpaid_balance = document.getElementById("debtpaid_balance");
  // let debtpaid_balance_html = document.getElementById(
  //   "debtpaid_balance_html"
  // );
  // count_paydebt.addEventListener("input", function () {
  //   let result = Number(count_debt.textContent) - Number(count_paydebt.value);
  //   debtpaid_balance.value = result;
  //   //debtpaid_balance_html.textContent = `เหลืออีก ${result} บาท`;
  // });
  //}
  async getordersellDebt(custom_names = "") {
    let total_order = document.getElementById("total_order");
    let SelectedItem = document.getElementById("SelectedItem");
    let buttom_issubmit = document.getElementById("buttom_issubmit");
    let message_dis = document.getElementById("message_dis");

    let count_paydebt = document.getElementById("count_paydebt");
    let count_debt = document.getElementById("count_debt");
    let debtpaid_balance = document.getElementById("debtpaid_balance");
    count_paydebt.disabled = true;
    const hiddenCountOrdersell = document.getElementById(
      "hidden-count-ordersell"
    );
    hiddenCountOrdersell.innerHTML = "";

    const $seleted = $("#is_ordersell_ids");
    try {
      const response = await fetch(
        `http://localhost/smokker24hours/system/backend/api/list_orderdebt.php?customers=${custom_names}`,
        {
          method: "GET",
          credentials: "include",
        }
      );
      const responsedata = await response.json();
      const optionData = responsedata.data.map((item) => ({
        id: item.id_ordersell,
        text: item.ordersell_name,
        amount: item.debt_remaining,
      }));
      $seleted.multipleSelect("uncheckAll");
      $seleted.empty();

      optionData.forEach((item) => {
        $seleted.append(
          `<option value="${item.id}|${item.amount}|${item.text}" data-text="${item.text}" data-amount="${item.amount}">
            ${item.text} <span class="text-danger font-weight-bold">(ค้าง:${item.amount} บาท )</span>
          </option>`
        );
      });

      $seleted.multipleSelect("refresh");

      $seleted.change(function () {
        var result = $(this).multipleSelect("getSelects");
        let datas = [];
        let responses = 0;
        result.map((id) => {
          let inputs = document.createElement("input");
          inputs.type = "hidden";
          inputs.name = "priceordersell[]";

          hiddenCountOrdersell.appendChild(inputs);
          let text = $(this).find(`option[value="${id}"]`).data("text");
          let amount = $(this).find(`option[value="${id}"]`).data("amount");
          datas.push(amount);
          responses += Number(amount);
          inputs.value = amount;
          return text;
        });
        SelectedItem.textContent = `จำนวนที่ต้องจ่าย ${responses} บ.`;

        total_order.textContent = `${result.length} รายการ`;
        if (datas.length > 0) {
          count_paydebt.disabled = false;
          buttom_issubmit.disabled = false;
        } else {
          count_paydebt.disabled = true;
          buttom_issubmit.disabled = true;
        }
        count_paydebt.addEventListener("input", function () {
          let result =
            Number(count_debt.textContent) - Number(count_paydebt.value);
          debtpaid_balance.value = result;
          if (responses === Number(count_paydebt.value)) {
            count_paydebt.classList.add("input-border-success");
            count_paydebt.classList.remove("input-border-danger");
          } else {
            count_paydebt.classList.add("input-border-danger");
            count_paydebt.classList.remove("input-border-success");
          }
          if (Number(count_paydebt.value) > responses) {
            buttom_issubmit.disabled = true;
            message_dis.textContent = `ห้ามจ่ายเกิน ${responses} บาท`;
          } else {
            buttom_issubmit.disabled = false;
            message_dis.textContent = "";
          }
          //debtpaid_balance_html.textContent = `เหลืออีก ${result} บาท`;
        });
      });
    } catch (e) {
      console.error("IS ORROR :: ", e);
    }
  }
  renderUi() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalFormPayOffDebt" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มการจ่ายหนี้ของ <span id="customname"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="myFormCustom" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="type_page" id="type_page"/>
              <input type="hidden" name="customer_name" id="customer_name" />
              <input type="hidden" name="debtpaid_balance" id="debtpaid_balance" />
              <input type="hidden" name="serial_number" id="serial_number" />
              <div class="modal-body">
                <div class="modal-body">
                    <div class="col-md-12 row mb-3">
                      <div class="col-md-7 row py-2">
                        <span class=" text-primary font-weight-bold">จำนวนหนี้ที่ <span id="custom_name"></span> เหลืออยู่ <span id="count_debt"></span> บาท</span>
                      </div>
                      <div class="col-md-5"></div>
                      <div class="col-md-9">
                        <label class="mt-0 mb-0 font-weight-bold text-dark">เลือกรายการที่ต้องการจ่าย <span class="text-danger">*</span></label>
                          <select class="form-control multiple-select" name="is_ordersell_id[]" id="is_ordersell_ids" placeholder="เลือกรายการที่ต้องการจ่าย" multiple="multiple" required></select>
                          <div id="hidden-count-ordersell"></div>
                      </div>
                      <div class="col-md-3">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">รายการที่เลือก</label>
                          <div class="form-control py-2">
                            <span id="total_order" class="py-1">0 รายการ</span>
                          </div>
                      </div>
                      <div class="col-md-7 mt-3">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนเงินที่ต้องการจ่าย / .บ  <span id="SelectedItem" class="text-success"></span></label>
                          <input type="text" class="form-control" name="count_paydebt" id="count_paydebt" placeholder="จำนวนเงิน" required>
                        </div>  
                      </div>
                      <div class="col-md-5 mt-3">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">เวลา</label>
                            <input type="datetime-local" class="form-control" name="date_add" id="date_add" placeholder="วันที่และเวลา" required>
                          </div>
                      </div>
                      <div class="col-md-7">
                        
                          <label class="mt-0 mb-0 font-weight-bold text-dark">ตัวเลือกการจ่าย <span class="text-danger">*</span></label>
                          <select class="form-control multiple-select" name="payment_option[]" id="payment_options" placeholder="ตัวเลือกการจ่าย" multiple="multiple" required>
                            <option value="โอน">โอน</option>
                            <option value="จ่ายสด">จ่ายสด</option>
                          </select>
                       
                          <label for="exampleFormControlTextarea1">เหตุผล(ถ้ามี)</label>
                          <textarea class="form-control" id="exampleFormControlTextarea1" name="orther_text" rows="4"></textarea>
                        
                      </div>
                      <div class="col-md-5">
                          <mian-add-image id="slip_payoffdebt" count="payoffdebt_slip" wrapper="ux-wrap" filenames="uimgname" cancles="ux-cancle"
                            names="หลักฐานโอนเงิน" custom="btn_payoffdebt" setdefault="setDefaultImgCapital"></mian-add-image>
                      </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                  <div class="row w-100">
                    <div class="ml-auto d-flex align-items-center px-4 text-danger font-bold" id="message_dis"></div>
                    <button type="submit" class="btn btn-primary mr-4 border" id="buttom_issubmit">บันทึกข้อมูล</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }
}

customElements.define("main-pay-debt", ModelPayOffDebt);

$(document).on("click", "#modelpayoff_debt", function (e) {
  e.preventDefault();
  let typepage = $(this).data("types");
  let customname = $(this).data("custome");
  let countdebt = $(this).data("debt");
  const compo_modalpay_debt = document.querySelector("main-pay-debt");
  compo_modalpay_debt.getordersellDebt(customname);

  $("#customer_name").val(customname);
  $("#type_page").val(typepage);
  $("#customname").html(customname);
  $("#custom_name").html(customname);
  $("#count_debt").html(countdebt);
  let myForm = document.getElementById("myFormCustom");
  if (typepage === "IN") {
    myForm.action = "../backend/customer.php";
  } else {
    myForm.action = "backend/customer.php";
  }
});

$(document).on("click", "#confirmTrashPayOffDebt", function (e) {
  let ID = $(this).data("id");
  let name = $(this).data("name");
  let img = $(this).data("img");
  let count_debt = $(this).data("count");
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `คุณยืนยันที่จะลบประวัติจ่ายหนี้ของ ${name} จำนวน ${count_debt} บ. นี้ ใช่ไหม`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#65696cff",
    cancelButtonColor: "#d33",
    cancelButtonText: "ยกเลิก",
    confirmButtonText: "ยืนยัน",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const responseapi = await fetch(
          `http://localhost/smokker24hours/system/backend/api/customer_api.php`,
          {
            method: "DELETE",
            credentials: "include",
            body: JSON.stringify({
              id: ID,
              name: name,
              image: img,
            }),
          }
        );
        const responsedata = await responseapi.json();
        if (responsedata.status === 201) {
          Swal.fire({
            title: "เรียบร้อย",
            text: responsedata.message,
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
