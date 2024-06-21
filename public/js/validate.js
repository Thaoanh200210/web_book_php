jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
})

jQuery.validator.addMethod("phoneRegexp", function(value, element, param) {
  return value.trim().match(/^(08||07||09)\d{8}$/);
})

jQuery.validator.addMethod("idCardRegexp", function(value, element, param) {
  return value.trim().match(/\d{10}$/);
})

$("form").validate({
  rules: {
    username: {
      required: true,
      minlength: 2,
    },
    password: {
      required: true,
      minlength: 5,
    },
    password_new: {
      required: true,
      minlength: 5,
      notEqual: "#password",
    },
    confirm_password: {
      required: true,
      minlength: 5,
      equalTo: "#password",
    },
    phone: {
      required: true,
      phoneRegexp: true
    },
    address: {
      required: true
    },
    idCard: {
      required: true,
      idCardRegexp: true
    }
  },

  messages: {
    username: {
      required: "Bạn chưa nhập tên đăng nhập",
      minlength: "Tên đăng nhập phải có ít nhất 2 ký tự	",
    },
    password: {
      required: "Bạn chưa nhập mật khẩu",
      minlength: "Mật khẩu phải có ít nhất 5 ký tự",
    },
    password_new: {
      required: "Bạn chưa nhập mật khẩu mới",
      minlength: "Mật khẩu mới phải có ít nhất 5 ký tự",
      notEqual: "Mật khẩu mới phải khác mật khẩu cũ"
    },
    confirm_password: {
      required: "Bạn chưa nhập vào mật khẩu",
      minlength: "Mật khẩu phải có ít nhât 5 ký tự",
      equalTo: "Mật khẩu nhập lại không trùng khớp với mật khẩu đã nhập",
    },
    phone: {
      required: "Bạn chưa nhập số điện thoại",
      phoneRegexp: "Số điện thoại không đúng"
    },
    address: {
      required: "Bạn chưa nhập địa chỉ",
    },
    idCard: {
      required: "Bạn chưa nhập số căn cước",
      idCardRegexp: "Số căn cước không đúng"
    }
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
})

const buttonSubmit = $("form").children("#submitButton");
buttonSubmit.onclick = (e) => {
  e.preventDefault();
};
