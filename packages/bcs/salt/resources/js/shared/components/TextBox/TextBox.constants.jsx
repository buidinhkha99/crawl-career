export const mockApi = [
    {
        type: "email",
        size: "medium", //large
        layout: "icon-button",
        border_color: "#FFC401",
        text_color: "#FFC401",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                    clip-rule="evenodd"
                ></path>
              </svg>`,
        placeholder: "Company",
        rules: [
            {
                required: true,
                message: "Please enter your company",
            },
            {
                type: "email",
                message: "Your email is not valid",
            },
        ],
    },
    // {
    //     type: "email",
    //     border_color: "#FFC401",
    //     text_color: "#FFC401",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
    //             <path
    //                 fill-rule="evenodd"
    //                 d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
    //                 clip-rule="evenodd"
    //             ></path>
    //           </svg>`,
    //     placeholder: "Email address",
    //     required: true,
    //     message: "Please enter your email",
    // },
    // {
    //     type: "password",
    //     border_color: "#FFC401",
    //     text_color: "#FFC401",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
    //             <path
    //                 fill-rule="evenodd"
    //                 d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
    //                 clip-rule="evenodd"
    //             ></path>
    //           </svg>`,
    //     placeholder: "Email password",
    //     required: true,
    //     message: "Please enter your password",
    // },
];
