class WithdrawProfit extends HTMLElement {
  constructor() {
    super();
  }
  // get usableProfit() {
  //   return this.getAttribute("usableprofit") || "0";
  // }

  connectedCallback() {
    this.renderHTML();
    //this.scripts();
  }

  // scripts() {
  //   let input_price = document.getElementById("count_withdraw");
  //   let res_value = document.getElementById("res-value");
  //   input_price.addEventListener("input", () => {
  //     let result =
  //       Number(this.usableProfit.replace(/,/g, "").trim()) -
  //       Number(input_price.value.replace(/,/g, "").trim());
  //     res_value.textContent = Math.floor(result * 100) / 100;

  //     if (
  //       Number(input_price.value.replace(/,/g, "").trim()) >
  //       Number(this.usableProfit.replace(/,/g, "").trim())
  //     ) {
  //       input_price.style.border = "3px solid red";
  //       res_value.style.color = "red";
  //     } else {
  //       res_value.style.color = "green";
  //       input_price.style.border = "3px solid green";
  //     }
  //   });
  // }
  renderHTML() {
    this.innerHTML = `
      <div class="modal fade bd-example-modal-xl" id="modalFormWithdrawProfit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content" id="">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มจำนวนเบิกถอน <span id="productname"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="POST" action="backend/financeProfit.php" id="is_form" enctype="multipart/form-data">
              <input type="hidden" name="status_form" value="withdraw"/>
              <input type="hidden" name="withdraw_id" id="withdraw_id"/>
              <input type="hidden" name="withdraw_img" id="withdraw_img"/>
              <input type="hidden" name="withdraw_balance" id="withdraw_balance"/>
              <div class="modal-body">
                <div class="modal-body">
                    <div class="col-md-12 row mb-3">
                      <div class="col-md-12 row py-2">
                        <p class="ml-auto text-primary font-weight-bold">จำนวนเงินที่สามารถเบิกถอนได้ : <span id="res_withdraw"></span></p>
                      </div>
                      
                      
                      <div class="col-md-7">s
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

customElements.define("main-profit-withdraw", WithdrawProfit);

$(document).on("click", "#modal_formwithdraw_profit", function (e) {
  let withdraw_balance = $(this).data("balance");
  console.log({ withdraw_balance });
  $("#withdraw_balance").val(withdraw_balance);
  $("#res_withdraw").html(`${withdraw_balance.toLocaleString()} บาท`);
});
