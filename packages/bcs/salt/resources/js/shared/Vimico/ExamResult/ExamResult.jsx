import moment from "moment";
import React, { Suspense, useEffect, useState } from "react";

import { Button, Loading } from "../../components";
import { Container } from "../../Container";
import mockAPI from "./ExamResult.constants";
import { CandidateInfo } from "../components";
import clsx from "clsx";
import { usePage } from "@inertiajs/react";
import dayjs from "dayjs";

import { TableResult } from "../components";
import { AnswerChooseIcon, AnswerIcon, AnswerTickIcon } from "../../../icon";
import {Radio} from "antd";

export default function ExamResult({ background, indexItem }) {
    const {
        id,
        exam_name,
        is_started_at,
        is_ended_at,
        user_info,
        exam_result,
        examination,
        start_time,
        end_time,
    } = usePage().props.components[indexItem];

    const listLabelAnswer = ['A', 'B', 'C', 'D', 'E', 'F', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
    const startTime = dayjs(start_time);
    const endTime = dayjs(end_time);
    const time = endTime.diff(startTime);
    const examinationData = examination ?? [];
    const days = Math.floor(time / (1000 * 60 * 60 * 24));
    const hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((time % (1000 * 60)) / 1000);

    const [showDetail, setShowDetail] = useState(false);
    const config_button = {
        text: !showDetail ? "XEM CHI TIẾT" : "ẨN CHI TIẾT",
        button_type: "button",
        url: null,
        color_background: "#324376",
        detail_button_color_text: "#ffffff",
        icon: {
            data: null,
        },
        color_text: "#ffffff",
    };

    const formatDate = (date) => {
        return moment(
            new Date(date).toLocaleString("en-US", {
                timeZone: "Asia/Singapore",
            })
        ).format("DD/MM/YYYY");
    };

    const data = [...Array(Math.floor(examinationData.length / 5)).keys()];
    const itemDta =
        examinationData.length - Math.floor(examinationData.length / 5) * 5;

    return (
        <Suspense fallback={<Loading />}>
            <div
                id={id}
                className="py-10 flex-1"
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    {exam_result ? (
                        <div className="exam__container">
                            <div className="exam__info">
                                <h2 className="exam__title">
                                    Kỳ Sát hạch Huấn luyện AT-VSLĐ
                                </h2>
                                <h3 className="exam__name">{exam_name}</h3>
                                <p className="exam__date">
                                    Thời gian: {formatDate(is_started_at)} -{" "}
                                    {formatDate(is_ended_at)}
                                </p>
                            </div>
                            <div className="flex flex-col xl:flex-row justify-end gap-5 xl:gap-0 w-full">
                                <CandidateInfo
                                    avatar={user_info?.avatar}
                                    full_name={user_info?.full_name}
                                    identification_number={
                                        user_info?.identification_number
                                    }
                                    date_of_birth={user_info?.date_of_birth}
                                    coaching_team={user_info?.coaching_team}
                                    className="candidate__info"
                                    classNameInfo="custom_grid_result"
                                    classNamePaddingResult="pt-[39px]"
                                />
                                <div className="exam__result">
                                    <h2 className="title">Kết quả thi</h2>
                                    <div className="content flex flex-col gap-[30px]">
                                        <p
                                            className={clsx(
                                                "result",
                                                exam_result?.is_passed
                                                    ? "result--passed"
                                                    : "result--failed"
                                            )}
                                        >
                                            {exam_result?.is_passed
                                                ? "ĐẠT"
                                                : "KHÔNG ĐẠT"}
                                        </p>
                                        <div className="flex flex-col gap-5">
                                            <p className="result__item">
                                                <span className="text">
                                                    Kết quả:
                                                </span>
                                                <span className="score number">
                                                    {exam_result?.score}/10
                                                </span>
                                            </p>

                                            <p className="result__item">
                                                <span className="text">
                                                    Số câu trả lời ĐÚNG:
                                                </span>
                                                <span className="number">
                                                    {exam_result?.right_answers}
                                                </span>
                                            </p>
                                            <p className="result__item">
                                                <span className="text">
                                                    Số câu trả lời SAI:
                                                </span>
                                                <span className="number">
                                                    {exam_result?.wrong_answers}
                                                </span>
                                            </p>
                                            <p className="result__item">
                                                <span className="text">
                                                    Số câu CHƯA TRẢ LỜI:
                                                </span>
                                                <span className="number">
                                                    {exam_result?.unanswered}
                                                </span>
                                            </p>

                                            <p className="result__item">
                                                <span className="text">
                                                    Thời gian:
                                                </span>
                                                <span className="number">
                                                    {days > 0 && (
                                                        <span className="number">
                                                            {days}{" "}
                                                            <span className="text">
                                                                ngày{" "}
                                                            </span>
                                                        </span>
                                                    )}
                                                    {hours > 0 && (
                                                        <span className="number">
                                                            {hours}{" "}
                                                            <span className="text">
                                                                giờ{" "}
                                                            </span>
                                                        </span>
                                                    )}
                                                    {minutes > 0 && (
                                                        <span className="number">
                                                            {minutes}{" "}
                                                            <span className="text">
                                                                phút{" "}
                                                            </span>
                                                        </span>
                                                    )}
                                                    {seconds > 0 && (
                                                        <span className="number">
                                                            {seconds}{" "}
                                                            <span className="text">
                                                                giây
                                                            </span>
                                                        </span>
                                                    )}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="flex flex-col gap-5 w-full">
                                <div
                                    className={clsx(
                                        showDetail
                                            ? "flex justify-between"
                                            : "flex justify-end"
                                    )}
                                >
                                    {showDetail && (
                                        <span className="text-[30px] font-medium text-black">
                                            Kết quả chi tiết
                                        </span>
                                    )}
                                    <Button
                                        config={config_button}
                                        handleClickButton={() =>
                                            setShowDetail((prev) => !prev)
                                        }
                                    />
                                </div>

                                {showDetail && (
                                    <div>
                                        <table>
                                            <tbody>
                                            {data.map((item, index) => {
                                                return (
                                                    <TableResult
                                                        numberItem={item}
                                                        examination={
                                                            examinationData
                                                        }
                                                        key={index + item}
                                                    />
                                                );
                                            })}
                                            <tr>
                                                {examinationData
                                                    .slice(
                                                        examinationData.length -
                                                        itemDta,
                                                        examinationData.length
                                                    )
                                                    .map(
                                                        (
                                                            itemQuestion,
                                                            index
                                                        ) => {
                                                            return (
                                                                <td
                                                                    key={
                                                                        index +
                                                                        itemQuestion
                                                                    }
                                                                    className="max-w-[205px] align-top"
                                                                >
                                                                    <div className="custom_table_grid">
                                                                        <div
                                                                            className={clsx(
                                                                                itemQuestion.is_correct
                                                                                    ? "text-[#23A538]"
                                                                                    : "text-[#E40613]"
                                                                            )}
                                                                        >
                                                                            Câu{" "}
                                                                            {
                                                                                itemQuestion.order
                                                                            }
                                                                        </div>
                                                                        {itemQuestion.is_correct && (
                                                                            <div className="w-[140px]">
                                                                                {itemQuestion.answers.map(
                                                                                    (
                                                                                        itemAnswer,
                                                                                        index
                                                                                    ) => {
                                                                                        if (
                                                                                            itemAnswer.is_choose
                                                                                        ) {
                                                                                            return (
                                                                                                <AnswerChooseIcon
                                                                                                    color="#23A538"
                                                                                                    key={
                                                                                                        index +
                                                                                                        itemAnswer
                                                                                                    }
                                                                                                    className="w-[25px] h-[24px] inline mr-[5px] mb-[5px]"
                                                                                                />
                                                                                            );
                                                                                        }
                                                                                        return (
                                                                                            <AnswerIcon
                                                                                                color="#23A538"
                                                                                                key={
                                                                                                    index +
                                                                                                    itemAnswer
                                                                                                }
                                                                                                className="w-[25px] h-[24px] inline mr-[5px] mb-[5px]"
                                                                                            />
                                                                                        );
                                                                                    }
                                                                                )}
                                                                            </div>
                                                                        )}
                                                                        {!itemQuestion.is_correct && (
                                                                            <div className="w-[140px]">
                                                                                {itemQuestion.answers.map(
                                                                                    (
                                                                                        itemAnswer,
                                                                                        index
                                                                                    ) => {
                                                                                        if (
                                                                                            itemAnswer.is_choose
                                                                                        ) {
                                                                                            return (
                                                                                                <AnswerChooseIcon
                                                                                                    color="#E40613"
                                                                                                    key={
                                                                                                        index +
                                                                                                        itemAnswer
                                                                                                    }
                                                                                                    className="w-[25px] h-[24px] inline mr-[5px] mb-[5px]"
                                                                                                />
                                                                                            );
                                                                                        }
                                                                                        if (
                                                                                            itemAnswer.is_correct
                                                                                        ) {
                                                                                            return (
                                                                                                <AnswerTickIcon
                                                                                                    color="#E40613"
                                                                                                    key={
                                                                                                        index +
                                                                                                        itemAnswer
                                                                                                    }
                                                                                                    className="w-[25px] h-[24px] inline mr-[5px] mb-[5px]"
                                                                                                />
                                                                                            );
                                                                                        }
                                                                                        return (
                                                                                            <AnswerIcon
                                                                                                color="#E40613"
                                                                                                key={
                                                                                                    index +
                                                                                                    itemAnswer
                                                                                                }
                                                                                                className="w-[25px] h-[24px] inline mr-[5px] mb-[5px]"
                                                                                            />
                                                                                        );
                                                                                    }
                                                                                )}
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </td>
                                                            );
                                                        }
                                                    )}
                                            </tr>
                                            </tbody>
                                        </table>

                                        <div className="mt-10">
                                            <span className="text-[30px] font-medium text-black mt-10">Chi tiết bài làm </span>
                                        </div>
                                        {examination.map((item, index) => (
                                            <div className="question-block" key={index}>
                                                <div style={{marginTop: '20px', display: 'flex', alignItems: 'center'}}>
                                                    <strong style={{
                                                        display: 'inline',
                                                        marginRight: '5px',
                                                        width: '80px'
                                                    }}>Câu {item.order}:</strong>
                                                    <span style={{display: 'inline'}}
                                                          dangerouslySetInnerHTML={{__html: item.question_content}}/>
                                                </div>
                                                <table className="borderless-table">
                                                    {item.answers.map((answerQuestion, keyAnswer) => (
                                                        <tr key={keyAnswer}>
                                                        <td style={{ width: '25px', paddingTop: '2px' }}>
                                                                {answerQuestion.is_correct ? <span className="checkmark">✓</span> : null}
                                                            </td>
                                                            <td style={{ width: '25px' }}>
                                                                <span
                                                                    className={clsx(answerQuestion.is_choose ? 'answered' : 'answer')}
                                                                    style={{ display: 'inline' }}
                                                                >
                                                                  {listLabelAnswer[keyAnswer]}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <p style={{ display: 'inline' }}><span dangerouslySetInnerHTML={{__html: answerQuestion.data}}/></p>
                                                            </td>
                                                        </tr>
                                                    ))}
                                                </table>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </div>
                    ) : null}
                </Container>
            </div>
        </Suspense>
    );
}
