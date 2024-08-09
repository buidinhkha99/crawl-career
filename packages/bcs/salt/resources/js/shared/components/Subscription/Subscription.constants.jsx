export const mockApi = {
    id: "subscription",
    size: "large",
    type: "Subscription",
    title: "Sign up for our New letter",
    background: {
        type: "url", // color, url
        data: "black",
    },
    content:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.",
    input: {
        type: "email",
        name: "email",
        layout: "icon-button",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                </svg>`,
        rules: [
            {
                type: "email",
                message: "Please enter valid email",
            },
        ],
        suffix: {
            type: "text",
            data: "Submit",
        },
        disabled: false,
        rounded_full: true,
        placeholder: "Enter your email",
        config_button: {
            icon: {
                data: null,
                url: null,
            },
            text: "Submit",
            button_type: "submit",
            url: null,
            type: "submit",
            size: "medium",
            color_background: "#fff",
            color_text: "#227C9D",
        },
    },
};
