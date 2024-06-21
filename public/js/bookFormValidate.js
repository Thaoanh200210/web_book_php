

$("form").validate({
  rules: {
    name: {
      required: true,
    },
    "author-1": {
      required: true,
    },
    "type-1": {
      required: true,
    },
    quantity: {
      required: true,
      min: 1,
    },
  },
  messages: {
    name: "Bạn chưa nhập tên sách",
    "author-1": "Phải có ít nhất 1 tác giả",
    "type-1": "Phải có ít nhất 1 thể loại",
    quantity: {
      required: "Bạn chưa nhập số lượng",
      min: "Số lượng phải ít nhất bằng 1",
    },
  },
  errorPlacement: function (error, element) {
    error.addClass("invalid-feedback");
    if (element.prop("type") === "checkbox")
      error.insertAfter(element.siblings("label"));
    else error.insertAfter(element);
  },
  highlight: function (element, errorClass, validClass) {
    $(element).addClass("is-invalid").removeClass("is-valid");
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).addClass("is-valid").removeClass("is-invalid");
  },
});
