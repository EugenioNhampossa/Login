//Validating Register form
$(".ui.form").form({
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
    email: {
      identifier: "email",
      rules: [
        {
          type: "empty",
          prompt: "Please enter a email",
        },
        {
          type: "email",
          prompt: "Please enter a valid e-mail",
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
    passwordR: {
      identifier: "passwordRetyped",
      rules: [
        {
          type: "empty",
          prompt: "Please retype your password",
        },
        {
          type: "minLength[6]",
          prompt: "Your retyped pwd must be at least {ruleValue} characters",
        },
      ],
    },
    match: {
      identifier: "passwordRetyped",
      rules: [
        {
          type: "match[password]",
          prompt: "Please put the same value in both fields",
        },
      ],
    },
    terms: {
      identifier: "terms",
      rules: [
        {
          type: "checked",
          prompt: "You must agree to the terms and conditions",
        },
      ],
    },
    newPassword: {
      identifier: "newPassword",
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
    number: {
      identifier: "typedCode",
      rules: [
        {
          type: "integer",
          prompt: "Please enter a valid number",
        },
      ],
    },
  },
});
