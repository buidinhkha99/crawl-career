export const mockApi = [
    // {
    //     type: "text",
    //     name: "company",
    //     rounded_full: false,
    //     disabled: false,
    //     size: "medium", //large
    //     layout: "icon", //icon, button, icon-button
    //     height: "40px",
    //     border_color: "#FFC401",
    //     text_color: "#FFC401",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
    //             <path
    //                 fill-rule="evenodd"
    //                 d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
    //                 clip-rule="evenodd"
    //             ></path>
    //         </svg>`,
    //     suffix: {
    //         type: "icon",
    //         data: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
    //                 <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
    //             </svg>`,
    //     },
    //     placeholder: "Company",
    //     rules: [
    //         {
    //             required: true,
    //             message: "Please enter your company name",
    //         },
    //     ],
    // },
    {
        type: "email",
        name: "address",
        rounded_full: false,
        disabled: false,
        size: "medium", //large
        layout: "icon-button",
        height: "40px",
        border_color: "#FFC401",
        text_color: "#FFC401",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                    clip-rule="evenodd"
                ></path>
            </svg>`,
        suffix: null,
        placeholder: "Company",
        rules: [
            {
                required: true,
                message: "Please enter your company name",
            },
        ],
    },
    {
        type: "email",
        name: "address",
        rounded_full: false,
        disabled: false,
        size: "medium", //large
        layout: "icon-button",
        height: "40px",
        border_color: "#FFC401",
        text_color: "#FFC401",
        icon: null,
        suffix: {
            type: "icon",
            data: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>`,
        },
        placeholder: "Address",
        rules: [
            {
                required: true,
                message: "Please enter your address",
            },
            {
                type: "email",
                message: "Please enter valid email",
            },
        ],
    },
    {
        type: "text",
        name: "password",
        rounded_full: false,
        disabled: false,
        size: "medium", //large
        layout: "icon-button",
        height: "40px",
        border_color: "#FFC401",
        text_color: "#FFC401",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                    clip-rule="evenodd"
                ></path>
            </svg>`,
        suffix: {
            type: "text",
            data: "Submit",
        },
        placeholder: "Password",
        rules: [
            {
                required: true,
                message: "Please enter your company name",
            },
        ],
    },
    {
        type: "textarea",
        name: "message",
        rounded_full: false,
        disabled: false,
        rows: 5,
        placeholder: "Message",
        border_color: "#FFC401",
        text_color: "#FFC401",
        rules: [
            {
                required: true,
                message: "Please enter your message",
            },
        ],
        suffix: null,
        preffix: null,
    },
];
