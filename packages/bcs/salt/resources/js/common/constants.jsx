export const formLogin = [
    {
        type: "text",
        name: "email",
        rounded_full: "20px",
        rules: [
            {
                required: true,
                message: "Please enter your email",
            },
        ],
        placeholder: "Email",
    },
    {
        type: "password",
        name: "password",
        rounded_full: "20px",
        rules: [
            {
                required: true,
                message: "Please enter your password ",
            },
        ],
        placeholder: "Password",
    },
];

export const formRegister = [
    {
        type: "text",
        name: "email",
        rounded_full: "20px",
        rules: [
            {
                required: true,
                message: "Please enter your email",
            },
        ],
        placeholder: "Email",
    },
    {
        type: "password",
        name: "password",
        rounded_full: "20px",
        rules: [
            {
                required: true,
                message: "Please enter your password ",
            },
        ],
        placeholder: "Password",
    },
    {
        type: "password",
        name: "confirm_password",
        rounded_full: "20px",
        rules: [
            {
                required: true,
                message: "Please re-enter your password",
            },
        ],
        placeholder: "Re - password",
    },
];
