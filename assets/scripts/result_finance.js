"use strict";
console.log("1");

$(document).on("click", "#selct_datestock", function (e) {
  console.log("click");
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      
      <form id="popupForm" class="mt-4 row text-center" method="POST" action="backend/PDF_resultfinanedate.php" enctype="multipart/form-data" target="_blank">
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">Start Date</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">End Date</label>
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

$(document).on("click", "#select_dateinstock", function (e) {
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      
      <form id="isPopupForm" class="mt-4 row text-center" method="POST" action="backend/PDF_resultfinanedate.php" enctype="multipart/form-data" target="_blank">
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">Start Date</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">End Date</label>
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
