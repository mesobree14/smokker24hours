"use strict";

$(document).on("click", "#selct_datestock", function (e) {
  console.log("click");
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      
      <form id="popupForm" class="mt-4 row text-center" method="POST" action="backend/PDF_resultfinanedate.php" enctype="multipart/form-data" target="_blank">
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">เริ่มตั้งแต่วันที่</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">จนถึงวันที่</label>
          <input type="datetime-local"name="end_date" class="form-control" placeholder="End Date" required>
        </div>
        <div class="col-12">
        <button type="submit" class="hidden-swall btn btn-success btn-block">ตกลง</button>
        </div>
      </form>
     
      
    `,
    didOpen: () => {
      // เมื่อ popup เปิด ให้ผูก event ให้ปุ่ม submit
      document
        .getElementById("popupForm")
        .addEventListener("submit", function (e) {
          e.preventDefault(); // กันไม่ให้ submit ก่อนเวลา
          Swal.close(); // 🔥 ปิด SweetAlert ก่อน

          // ส่งฟอร์มจริง (เปิดหน้าใหม่เพราะ target="_blank")
          this.submit();
        });
    },
  });
});

$(document).on("click", "#select_stcokdate", function (e) {
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      
      <form id="isPopupForm" class="mt-4 row text-center" method="POST" action="backend/PDF_financestockdate.php" enctype="multipart/form-data" target="_blank">
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">เริ่มตั้งแต่วันที่</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">จนถึงวันที่</label>
          <input type="datetime-local"name="end_date" class="form-control" placeholder="End Date" required>
        </div>
        <div class="col-12">
        <button type="submit" class="hidden-swall btn btn-success btn-block">ตกลง</button>
        </div>
      </form>
      
    `,
    didOpen: () => {
      // เมื่อ popup เปิด ให้ผูก event ให้ปุ่ม submit
      document
        .getElementById("isPopupForm")
        .addEventListener("submit", function (e) {
          e.preventDefault(); // กันไม่ให้ submit ก่อนเวลา
          Swal.close(); // 🔥 ปิด SweetAlert ก่อน

          // ส่งฟอร์มจริง (เปิดหน้าใหม่เพราะ target="_blank")
          this.submit();
        });
    },
  });
});

$(document).on("click", "#select_detailStcokDate", function (e) {
  let lot_number = $(this).data("lotnumber");
  console.log("xxx");
  console.log({ lot_number });
  $("#lot_number").val(lot_number);
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      <span class="font-bold">${lot_number}</span>
      <form id="soIsPopupForm" class="mt-4 row text-center" method="POST" action="../backend/PDF_date_financeinlot.php" enctype="multipart/form-data" target="_blank">
        <input type="text" style="display:none;" name="lot_number" id="lot_number" value='${lot_number}' />
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">เริ่มตั้งแต่วันที่</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">จนถึงวันที่</label>
          <input type="datetime-local"name="end_date" class="form-control" placeholder="End Date" required>
        </div>
        <div class="col-12">
        <button type="submit" class="hidden-swall btn btn-success btn-block">ตกลง</button>
        </div>
      </form>
      
    `,
    didOpen: () => {
      // เมื่อ popup เปิด ให้ผูก event ให้ปุ่ม submit
      document
        .getElementById("soIsPopupForm")
        .addEventListener("submit", function (e) {
          e.preventDefault(); // กันไม่ให้ submit ก่อนเวลา
          Swal.close(); // 🔥 ปิด SweetAlert ก่อน

          // ส่งฟอร์มจริง (เปิดหน้าใหม่เพราะ target="_blank")
          this.submit();
        });
    },
  });
});
