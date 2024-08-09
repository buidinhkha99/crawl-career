import avatar from "../../../../img/avater.png";

const mockAPI = {
    exam_name: "Đợt 2 - 2023",
    is_started_at: "2023-04-11T06:48:07.450Z",
    is_ended_at: "2023-04-12T06:48:07.450Z",
    user_info: {
        avatar: avatar,
        full_name: "BÙI HOÀNG LONG",
        identification_number: "23567",
        date_of_birth: "23/11/1980",
        coaching_team: "Nhóm 1",
        work_unit: "Phòng CNTT",
        working_position: "Trưởng phòng",
    },
    exam_result: {
        right_answers: 16,
        wrong_answers: 3,
        unanswered: 1,
        score: 8,
        is_passed: true,
    },
};

export default mockAPI;
