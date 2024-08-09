import avater from "../../../../img/avater.png";

export const moockApi = {
    user_info: {
        avatar: avater,
        full_name: "BÙI HOÀNG LONG",
        identification_number: "23567",
        date_of_birth: "23/11/1980",
        coaching_team: "Nhóm 1",
        work_unit: "Phòng CNTT",
        working_position: "Trưởng phòng",
    },
    agree: "Xác nhận đúng không tin cá nhân",
    config_button_one: {
        text: "trước",
        button_type: "button",
        url: "/",
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: '<svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="white"/></svg>',
        },
        color_text: "#ffffff",
    },
    config_button_two: {
        text: "tiếp theo",
        button_type: "button",
        url: "/",
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: '<svg width="13" height="20" viewBox="0 0 13 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.025 20L0.25 18.225L8.475 10L0.25 1.775L2.025 0L12.025 10L2.025 20Z" fill="white"/></svg>',
        },
        color_text: "#ffffff",
    },
};
