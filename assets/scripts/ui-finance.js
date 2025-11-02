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

class Capital extends HTMLElement {
  constructor() {
    super();
  }
  get availableCapital() {
    return this.getAttribute("availablecapital");
  }
  connectedCallback() {
    this.renderHTML();
    //this.scripts();
  }
  // scripts() {
  //   let count_capital = document.getElementById("count_capital");
  //   count_capital.addEventListener("input", () => {
  //     if (
  //       Number(count_capital.value.replace(/,/g, "").trim()) >
  //       Number(this.availableCapital.replace(/,/g, "").trim())
  //     ) {
  //       count_capital.style.border = "3px solid red";
  //     } else {
  //       count_capital.style.border = "3px solid green";
  //     }
  //   });
  // }
  renderHTML() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalFormCapital" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มจำนวนทุน <span id="productname"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="POST" action="backend/finance.php" enctype="multipart/form-data">
              <input type="hidden" name="status_form" value="capital"/>
              <input type="hidden" name="capital_id" id="capital_id" />
              <input type="hidden" name="capital_img" id="capital_img" />
              <input type="hidden" name="capital_balance" id="capital_balance" />
              <div class="modal-body">
                <div class="modal-body">
                    <div class="col-md-12 row mb-3">
                      <div class="col-md-12 row py-2">
                        <span class="ml-auto text-primary font-weight-bold">จำนวนทุนที่สามารถใช้ได้ : <span class="border" id="res_capital"></span></span>
                      </div>
                      <div class="col-md-7">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนทุน / .บ</label>
                          <input type="text" class="form-control" name="count_capital" id="count_capital" placeholder="ชื่อสินค้า" required>
                        </div>  
                      </div>
                      <div class="col-md-5">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">เวลา</label>
                            <input type="datetime-local" class="form-control" name="date_time_capital" id="date_time_capital" placeholder="วันที่และเวลา" required>
                          </div>
                      </div>
                      <div class="col-md-7">
                        
                      </div>
                      <div class="col-md-5">
                          <mian-add-image id="slip_capital" count="capital_slip" wrapper="ux-wrap" filenames="uimgname" cancles="ux-cancle"
                            names="รูปโปรไฟล์" custom="btn_custom" setdefault="setDefaultImgCapital"></mian-add-image>
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

customElements.define("main-create-capital", Capital);

$(document).on("click", "#modal_formcapital", function (e) {
  let capital_balance = $(this).data("balance");
  console.log({ capital_balance });
  $("#capital_balance").val(capital_balance);
  $("#res_capital").html(`${capital_balance.toLocaleString()} บาท`);
});

$(document).on("click", "#update_capital", function (e) {
  let id_capital = $(this).data("id");
  let img_capital = $(this).data("img");
  let count_capital = $(this).data("count");
  let date_capital = $(this).data("date");
  let balance_capital = $(this).data("balance");
  console.log({ img_capital });

  $("#capital_id").val(id_capital);
  $("#count_capital").val(count_capital);
  $("#date_time_capital").val(date_capital);
  $("#capital_balance").val();

  e.preventDefault();
  $("#capital_img").val(img_capital);
  $("#slip_capital").val(img_capital);
  $(".slip_capital").attr("src", `../db/slip-finance/${img_capital}`);
  $(".ux-wrap").last().addClass("active");
  $(".uimgname").html(img_capital);
  console.log({ id_capital, img_capital, count_capital, date_capital });
});

$(document).on("click", "#confirmTrashCapital", function (e) {
  let ID = $(this).data("id");
  let name = $(this).data("name");
  let img = $(this).data("img");
  console.log({ img });
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `ทุน ${name} นี้ จะถูกลบ จะไม่สามารถย้อนกลับได้`,
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
          `http://localhost/smokker24hours/system/backend/api/finances.php`,
          {
            method: "DELETE",
            credentials: "include",
            body: JSON.stringify({
              id: ID,
              table_name: "capital",
              image: img,
            }),
          }
        );
        const responsedata = await responseapi.json();
        console.log("s=", responsedata.status);
        if (responsedata.status === 201) {
          console.log(responsedata);
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

class Withdraw extends HTMLElement {
  constructor() {
    super();
  }
  get usableProfit() {
    return this.getAttribute("usableprofit") || "0";
  }

  connectedCallback() {
    this.renderHTML();
    this.scripts();
  }

  scripts() {
    let input_price = document.getElementById("count_withdraw");
    let res_value = document.getElementById("res-value");
    input_price.addEventListener("input", () => {
      let result =
        Number(this.usableProfit.replace(/,/g, "").trim()) -
        Number(input_price.value.replace(/,/g, "").trim());
      res_value.textContent = Math.floor(result * 100) / 100;

      if (
        Number(input_price.value.replace(/,/g, "").trim()) >
        Number(this.usableProfit.replace(/,/g, "").trim())
      ) {
        input_price.style.border = "3px solid red";
        res_value.style.color = "red";
      } else {
        res_value.style.color = "green";
        input_price.style.border = "3px solid green";
      }
    });
  }
  renderHTML() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalFormWithdraw" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มจำนวนเบิกถอน <span id="productname"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="POST" action="backend/finance.php" id="is_form" enctype="multipart/form-data">
              <input type="hidden" name="status_form" value="withdraw"/>
              <input type="hidden" name="withdraw_id" id="withdraw_id"/>
              <input type="hidden" name="withdraw_img" id="withdraw_img"/>
              <input type="text" name="withdraw_balance" id="withdraw_balance"/>
              <div class="modal-body">
                <div class="modal-body">
                    <div class="col-md-12 row mb-3">
                      <div class="col-md-12 row py-2">
                        <p class="ml-auto text-primary font-weight-bold">จำนวนเงินที่สามารถเบิกถอนได้ : <span id="res_withdraw"></span></p>
                      </div>
                      
                      
                      <div class="col-md-7">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">จำนวนเงินที่เบิกถอน / .บ <span id="res-value"></span></label>
                          <input type="text" class="form-control" name="count_withdraw" id="count_withdraw" placeholder="จำนวนเงิน" required>
                        </div>  
                      </div>
                      <div class="col-md-5">
                          <div class="form-group mb-2">
                            <label class="mt-0 mb-0 font-weight-bold text-dark">เวลา</label>
                            <input type="datetime-local" class="form-control" name="date_time_withdraw" id="date_time_withdraw" placeholder="วันที่และเวลา" required>
                          </div>
                      </div>
                      <div class="col-md-7">
                        <div class="form-group mb-2">
                          <label class="mt-0 mb-0 font-weight-bold text-dark">เหตุผลในการเบิกถอน</label>
                          <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                        </div>
                      </div>
                      <div class="col-md-5">
                          <mian-add-image id="slip_withdraw" count="withdraw_slip" wrapper="ux-wrap" filenames="uimgname-withdraw" cancles="ux-cancle"
                            names="รูปโปรไฟล์" custom="btn_custom_withdraw" setdefault="setDefaultImgWithDraw"></mian-add-image>
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

customElements.define("main-create-withdraw", Withdraw);

$(document).on("click", "#modal_formwithdraw", function (e) {
  let withdraw_balance = $(this).data("balance");
  console.log({ withdraw_balance });
  $("#withdraw_balance").val(withdraw_balance);
  $("#res_withdraw").html(`${withdraw_balance.toLocaleString()} บาท`);
});

$(document).on("click", "#update_withraw", function (e) {
  //document.getElementById("is_form").id = "is_update_form";
  let withdraw_id = $(this).data("id");
  let count_withdraw = $(this).data("count");
  let date_withraw = $(this).data("date");
  let img_withdraw = $(this).data("img");
  let reason = $(this).data("reason");
  let withdraw_balance = $(this).data("balance");

  $("#withdraw_id").val(withdraw_id);
  $("#withdraw_balance").val(withdraw_balance);
  $("#count_withdraw").val(count_withdraw);
  $("#date_time_withdraw").val(date_withraw);
  $("#withdraw_img").val(img_withdraw);
  $("#reason").val(reason);

  $("#slip_withdraw").val(img_withdraw);
  $(".slip_withdraw").attr("src", `../db/slip-finance/${img_withdraw}`);
  $(".ux-wrap").last().addClass("active");
  $(".uimgname-withdraw").html(img_withdraw);
});

// เช็กว่า jQuery มีหรือยัง
console.log(
  "has jQuery?",
  !!window.jQuery,
  "version:",
  window.jQuery && jQuery.fn && jQuery.fn.jquery
);

// เช็กว่า bootstrap modal plugin โหลดไหม
console.log(
  "has bootstrap modal plugin?",
  !!(window.jQuery && jQuery.fn && jQuery.fn.modal)
);

// เช็กว่ามี element id ที่ตรงไหม
console.log(
  "#modalFormWithdraw exists?",
  document.getElementById("modalFormWithdraw") ? true : false
);

// เช็กว่ามี error ใน console (ดู red errors) — ถ้ามี ให้แก้ก่อน

// $(document).ready(function () {
//   $("#modalFormWithdraw").on("hidden.bs.modal", function (e) {
//     console.log(e);
//     console.log("fl");
//     $(this).find("form").trigger("reset");
//     //$(this).find("#is_update_form").removeAttr("id");
//   });
// });

$(document).on("click", "#confirmTrashWithroaw", function (e) {
  let ID = $(this).data("id");
  let name = $(this).data("name");
  let img = $(this).data("img");
  console.log({ img });
  Swal.fire({
    title: "คุณแน่ใจไหม ?",
    text: `คุณยืนยันที่จะลบข้อมูลเบิกถอน ${name} นี้ ใช่ไหม`,
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
          `http://localhost/smokker24hours/system/backend/api/finances.php`,
          {
            method: "DELETE",
            credentials: "include",
            body: JSON.stringify({
              id: ID,
              table_name: "withdraw",
              image: img,
            }),
          }
        );
        const responsedata = await responseapi.json();
        console.log("s=", responsedata.status);
        if (responsedata.status === 201) {
          console.log(responsedata);
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
