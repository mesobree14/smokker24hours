"use strict";
console.log("1");

$(document).on("click", "#selct_datestock", function (e) {
  console.log("click");
  Swal.fire({
    showConfirmButton: false,
    html: `
      
      
      <form class="mt-4 row text-center" method="POST" action="backend/PDF_resultfinanedate.php" enctype="multipart/form-data" target="_blank">
        <div class="form-group col-6">
          <label for="" class="mr-auto str_date">Start Date</label>
          <input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date" required>
        </div>
        <div class="form-group col-6">
          <label for="" class="mr-auto">End Date</label>
          <input type="datetime-local"name="end_date" class="form-control" placeholder="End Date" required>
        </div>
        <div class="col-12">
        <button type="submit" class="btn btn-success btn-block">ตกลง</button>
        </div>
      </form>
      <a class="" href="details/PDF/PDF_financelot.php" target="_blank">
           <u> <i class="fas fa-file-code px-2"></i> PDF สรุปสต็อกสินค้า แสดงทั้งหมด </u>
      </a>
      
    `,
  });
});
