//Validanting login form
$(".ui.form.loginForm").form({
  fields: {
    username: {
      identifier: "username",
      rules: [
        {
          type: "empty",
          prompt: "Please enter a username",
        },
        {
          type: "minLength[4]",
          prompt: "Your username must be at least {ruleValue} characters",
        },
      ],
    },
    password: {
      identifier: "password",
      rules: [
        {
          type: "empty",
          prompt: "Please enter a password",
        },
        {
          type: "minLength[6]",
          prompt: "Your password must be at least {ruleValue} characters",
        },
      ],
    },
  },
});

//Validating Register form
