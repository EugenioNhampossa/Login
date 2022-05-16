function showPass() {
  let textBoxes = document.getElementsByClassName("password");
  for (let textBox of textBoxes) {
    if (textBox.type === "password") {
      textBox.type = "text";
    } else {
      textBox.type = "password";
    }
  }
}
