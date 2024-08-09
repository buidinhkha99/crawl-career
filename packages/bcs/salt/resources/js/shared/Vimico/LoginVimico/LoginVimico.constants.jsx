export const moockApi = {
    type: "Form",
    size: "large",
    form_id: "1",
    title: "đăng nhập",
    inputs: [
        {
            type: "text",
            layout: "icon",
            name: "password",
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">\n  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>\n</svg>\n',
            disabled: false,
            placeholder: "Name",
            default: null,
            rules: [
                {
                    required: false,
                    message: null,
                },
                null,
            ],
            suffix: {
                text: null,
                button_type: "button",
                url: "#",
                color_background: null,
                detail_button_color_text: null,
                icon: {
                    data: null,
                },
            },
        },
        {
            type: "password",
            layout: "icon",
            name: "username",
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">\n  <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>\n</svg>\n',
            disabled: false,
            placeholder: "password",
            default: null,
            rules: [
                {
                    required: false,
                    message: null,
                },
                null,
            ],
            suffix: {
                text: null,
                button_type: "button",
                url: "#",
                color_background: null,
                detail_button_color_text: null,
                icon: {
                    data: null,
                },
            },
        },
    ],
    config_button: {
        text: "Đăng nhập",
        button_type: "submit",
        url: null,
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0_136_6495" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24"><rect width="24" height="24" fill="#D9D9D9"/></mask><g mask="url(#mask0_136_6495)"><path d="M12 21V19H19V5H12V3H19C19.55 3 20.0208 3.19583 20.4125 3.5875C20.8042 3.97917 21 4.45 21 5V19C21 19.55 20.8042 20.0208 20.4125 20.4125C20.0208 20.8042 19.55 21 19 21H12ZM10 17L8.625 15.55L11.175 13H3V11H11.175L8.625 8.45L10 7L15 12L10 17Z" fill="white"/></g></svg>',
        },
        color_text: "#ffffff",
    },
};
