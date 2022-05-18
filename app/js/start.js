$("#btReset").on("click", function (event) {
  $(".ui.mini.modal.pwdreset")
    .modal("setting", {
      closable: false,
      onApprove: function () {
        $("#formPwdReset").submit();
      },
    })
    .modal("show");
});

$("button").mouseenter(function () {
  $(this).transition("pulse");
});
