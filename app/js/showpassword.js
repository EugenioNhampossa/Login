function showPass() {
  var textBox = document.getElementById("password");
  if (textBox.type === "password") {
    textBox.type = "text";
  } else {
    textBox.type = "password";
  }
}
