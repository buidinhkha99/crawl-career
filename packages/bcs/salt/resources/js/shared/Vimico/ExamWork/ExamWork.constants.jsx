const mockApi = {
    type: "ExamWork",
    exam_id: 1,
    quiz_id: 10,
    question_list: [
        {
            question_id: 1,
            question_type: "multiple",
            question_content: `<h2>Định kỳ bảo dưỡng, sửa chữa c&aacute;c m&aacute;y m&oacute;c hỏng h&oacute;c bất thường th&igrave; c&ocirc;ng việc sửa chữa cần được tiến h&agrave;nh dựa tr&ecirc;n c&aacute;c y&ecirc;u cầu an to&agrave;n n&agrave;o sau đ&acirc;y:</h2>
            <p><img src="https://i.chzbgr.com/original/9088391680/h6F058149/cat-meme-driving-test-9088391680" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "11",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "12",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "13",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "14",
                    data: "Một câu trả lời khác mà không liên quan tới câu hỏi.",
                },
            ],
        },
        {
            question_id: 2,
            question_type: "one",
            question_content: `<h2>Phần đường xe chạy: được sử dụng cho các phương tiện giao thông qua lại.</h2>
            <p><img src="https://www.pumpkin.care/wp-content/uploads/2020/08/Cat-Memes-2020.jpg" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "15",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "16",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "17",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "18",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 3,
            question_type: "one",
            question_content: `<h2>Làn đường xe chạy: được chia theo chiều dọc và có bề rộng đủ cho xe chạy an toàn?</h2>
            <p><img src="https://i.imgflip.com/6txnl1.jpg" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "19",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "20",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "21",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "22",
                    data: "Tất cả đều đúng.",
                },
                {
                    id: "23",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "24",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "25",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "26",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 4,
            question_type: "multiple",
            question_content: `<h2>Trong các khái niệm dưới đây khái niệm “dừng xe” được hiểu như thế nào là đúng?</h2>
            <p><img src="https://bestlifeonline.com/wp-content/uploads/sites/3/2018/06/cat-meme-54-0.jpg?quality=82&strip=all" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "27",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "28",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "29",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "30",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 5,
            question_type: "multiple",
            question_content: `<h2>Định kỳ bảo dưỡng, sửa chữa c&aacute;c m&aacute;y m&oacute;c hỏng h&oacute;c bất thường th&igrave; c&ocirc;ng việc sửa chữa cần được tiến h&agrave;nh dựa tr&ecirc;n c&aacute;c y&ecirc;u cầu an to&agrave;n n&agrave;o sau đ&acirc;y:</h2>
            <p><img src="https://i.chzbgr.com/original/9088391680/h6F058149/cat-meme-driving-test-9088391680" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "31",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "32",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "33",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "34",
                    data: "Một câu trả lời khác mà không liên quan tới câu hỏi.",
                },
            ],
        },
        {
            question_id: 6,
            question_type: "one",
            question_content: `<h2>Phần đường xe chạy: được sử dụng cho các phương tiện giao thông qua lại.</h2>
            <p><img src="https://www.pumpkin.care/wp-content/uploads/2020/08/Cat-Memes-2020.jpg" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "35",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "36",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "37",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "38",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 7,
            question_type: "one",
            question_content: `<h2>Làn đường xe chạy: được chia theo chiều dọc và có bề rộng đủ cho xe chạy an toàn?</h2>
            <p><img src="https://i.imgflip.com/6txnl1.jpg" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "39",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "40",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "41",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "42",
                    data: "Tất cả đều đúng.",
                },
                {
                    id: "43",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "44",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "45",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "46",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 8,
            question_type: "multiple",
            question_content: `<h2>Trong các khái niệm dưới đây khái niệm “dừng xe” được hiểu như thế nào là đúng?</h2>
            <p><img src="https://bestlifeonline.com/wp-content/uploads/sites/3/2018/06/cat-meme-54-0.jpg?quality=82&strip=all" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "47",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "48",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "49",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "50",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 9,
            question_type: "multiple",
            question_content: `<h2>Trong các khái niệm dưới đây khái niệm “dừng xe” được hiểu như thế nào là đúng?</h2>
            <p><img src="https://bestlifeonline.com/wp-content/uploads/sites/3/2018/06/cat-meme-54-0.jpg?quality=82&strip=all" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "51",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "52",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "53",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "54",
                    data: "Tất cả đều đúng.",
                },
            ],
        },
        {
            question_id: 10,
            question_type: "multiple",
            question_content: `<h2>Định kỳ bảo dưỡng, sửa chữa c&aacute;c m&aacute;y m&oacute;c hỏng h&oacute;c bất thường th&igrave; c&ocirc;ng việc sửa chữa cần được tiến h&agrave;nh dựa tr&ecirc;n c&aacute;c y&ecirc;u cầu an to&agrave;n n&agrave;o sau đ&acirc;y:</h2>
            <p><img src="https://i.chzbgr.com/original/9088391680/h6F058149/cat-meme-driving-test-9088391680" width="567" height="319"></p>`, // HTML
            answers: [
                {
                    id: "55",
                    data: "Phải có lệnh sửa chữa của quản đốc phân xưởng cơ điện.",
                },
                {
                    id: "56",
                    data: "Phải ghi rõ nội dung sửa chữa.",
                },
                {
                    id: "57",
                    data: "Phải giao cho những công nhân chuyên nghiệp hoặc đã qua đào tạo hướng dẫn.",
                },
                {
                    id: "58",
                    data: "Một câu trả lời khác mà không liên quan tới câu hỏi.",
                },
            ],
        },
    ],
    duration: 30,
    config_button_next: {
        text: "TIẾP THEO",
        button_type: "button",
        url: null,
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>`,
        },
        color_text: "#ffffff",
    },
    config_button_prev: {
        text: "TRƯỚC",
        button_type: "button",
        url: null,
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>`,
        },
        color_text: "#ffffff",
    },
    config_button_submit: {
        text: "NỘP BÀI",
        button_type: "button",
        url: null,
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: "",
        color_text: "#ffffff",
    },
};

export default mockApi;
