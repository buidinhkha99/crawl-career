import clsx from "clsx";
import { Col, Row } from "antd";
import React, { Suspense, useState, useEffect } from "react";
import { router, usePage } from "@inertiajs/react";

import { Container } from "../../Container";
import { message } from "../../../common/utils";
import { ModalNotification } from "../components";
import Button from "../../components/Button/Button";
import Loading from "../../components/Loading/Loading";
import AnswerList from "./components/AnswerList/AnswerList";
import { CountdownTimer } from "./components";
import AnswerSheet from "./components/AnswerSheet/AnswerSheet";
import ProgressQuestionBar from "./components/ProgressQuestionBar/ProgressQuestionBar";

export default function ExamWork({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const {
        quiz_id,
        exam_id,
        duration,
        id,
        config_button_prev,
        config_button_next,
        config_button_submit,
        result_link,
    } = config;

    const [isAllowed, setIsAllowed] = useState({
        value: true,
        message: "",
    });
    const [questionList, setQuestionList] = useState(
        config.question_list ?? []
    );
    const [isStartAt, setIsStartAt] = useState(false);
    const [cloneQuestionList, setCloneQuestionList] = useState([]);
    const [shuffledQuestion, setShuffledQuestion] = useState(null);
    const [isOpenModal, setIsOpenModal] = useState(false);
    const [selectedAnswer, setSelectedAnswer] = useState([]);
    const [selectedQuestion, setSelectedQuestion] = useState(null);
    const [dataModal, setDataModal] = useState({
        title: "Bạn đã có chắc chắn nộp bài?",
        agree: "XÁC NHẬN NỘP BÀI",
        cancel: "HUỶ",
    });
    const [isTimeEnd, setIsTimeEnd] = useState(false);
    const [isSubmit, setIsSubmit] = useState(false);
    const [isSuccess, setIsSuccess] = useState(null);
    const [isError, setIsError] = useState(null);

    // Randomly shuffle questions and their answers
    useEffect(() => {
        const shuffle = (arr) => {
            return arr.sort(() => Math.random() - 0.5);
        };

        if (questionList && questionList.length > 0) {
            let cloneQuestionList = shuffle(questionList);

            cloneQuestionList = cloneQuestionList.map((question) => {
                question.answers = shuffle(question.answers);
                return question;
            });

            setShuffledQuestion(cloneQuestionList);
            setSelectedQuestion(cloneQuestionList[0]);

            return;
        }
    }, [questionList]);

    // Get the starting time
    useEffect(() => {
        if (shuffledQuestion && Object.keys(shuffledQuestion).length > 0) {
            setIsStartAt(new Date().toISOString());
        }
    }, [shuffledQuestion]);

    // Clone the shuffle answer such the selected answer has the same length
    useEffect(() => {
        if (shuffledQuestion && Object.keys(shuffledQuestion).length > 0) {
            setCloneQuestionList(
                shuffledQuestion.map((question) => {
                    question.answered = null;
                    return question;
                })
            );
        }
    }, [shuffledQuestion]);

    // Validate user has the right to do exam
    useEffect(() => {
        if (questionList.length === 0 && !config.question_list) {
            setIsAllowed({
                ...isAllowed,
                value: false,
                message: "Bài Thi không có sẵn!",
            });
        }
    }, [questionList, config.question_list]);

    useEffect(() => {
        if (isSubmit) {
            const timeEnd = new Date(
                Date.parse(new Date(isStartAt).toISOString()) +
                    duration * 60 * 1000
            ).toISOString();
            const data = {
                exam_id,
                quiz_id,
                data: cloneQuestionList.map((question, index) => {
                    const result = selectedAnswer.find(
                        (item) => item.question_id === question.question_id
                    );
                    question.answered = result ? result.answer : null;
                    question.order = index + 1;
                    return question;
                }),
                is_started_at: isStartAt,
                is_finished_at: isTimeEnd ? timeEnd : new Date().toISOString(),
            };

            router.post("/answer", data, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    setIsSuccess(res.props.flash.message);
                },
                onError: (err) => {
                    setIsError(err.error);
                },
            });
        }
    }, [isSubmit]);

    const handleOnChangeAnswer = (answer, question_id, question_type) => {
        let objIndex;
        let cloneState = [...selectedAnswer];

        if (selectedAnswer.length > 0) {
            let currentLength;
            objIndex = selectedAnswer.findIndex(
                (item) => item.question_id === question_id
            );

            // If the array of currentAnswer does not include the selecting question
            if (objIndex < 0) {
                return setSelectedAnswer([
                    ...selectedAnswer,
                    { question_id, question_type, answer },
                ]);
            } else {
                currentLength = cloneState[objIndex].answer.length;
            }

            // Remove the object stored answer if the answer include an empty array for checkbox question
            if (currentLength && currentLength === 1 && answer.length === 0) {
                return setSelectedAnswer(
                    cloneState.filter(
                        (item) => item.question_id !== question_id
                    )
                );
            }

            cloneState[objIndex].answer = answer;
            setSelectedAnswer(cloneState);
        } else {
            setSelectedAnswer([
                {
                    question_id,
                    question_type,
                    answer,
                },
            ]);
        }
    };

    const findIndexQuestion = () => {
        return shuffledQuestion
            .map((question) => question.question_id)
            .indexOf(selectedQuestion.question_id);
    };

    const nextQuestion = () => {
        setSelectedQuestion(shuffledQuestion[findIndexQuestion() + 1]);
    };

    const prevQuestion = () => {
        setSelectedQuestion(shuffledQuestion[findIndexQuestion() - 1]);
    };

    const handleOnClickAnswerSheet = (question) => {
        setSelectedQuestion(question);
    };

    const handleSubmitExam = () => {
        if (!isSubmit) {
            const timeEnd = new Date(
                Date.parse(new Date(isStartAt).toISOString()) +
                    duration * 60 * 1000
            ).toISOString();
            const data = {
                exam_id,
                quiz_id,
                data: cloneQuestionList.map((question, index) => {
                    const result = selectedAnswer.find(
                        (item) => item.question_id === question.question_id
                    );
                    question.answered = result ? result.answer : null;
                    question.order = index + 1;
                    return question;
                }),
                is_started_at: isStartAt,
                is_finished_at: isTimeEnd ? timeEnd : new Date().toISOString(),
            };

            router.post("/answer", data, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    message.success(res.props.flash.message, "Hoàn thành");
                    setTimeout(() => {
                        router.visit(result_link);
                    }, 1000);
                },
                onError: (err) => {
                    message.error(err.error, "Lỗi");
                },
            });
        }
        if (isSubmit) {
            if (isSuccess) {
                message.success(isSuccess, "Hoàn thành");
            }
            if (isError) {
                message.error(isError, "Lỗi");
            }
            setTimeout(() => {
                router.visit(result_link);
            }, 1000);
        }
    };

    const handleExpired = () => {
        setIsSubmit(true);
        setDataModal({
            title: "Đã hết giờ làm bài",
            agree: "NỘP BÀI VÀ XEM KẾT QUẢ",
        });
        setIsOpenModal(true);
        setIsTimeEnd(true);
    };

    return (
        <Suspense fallback={<Loading />}>
            {isAllowed.value ? (
                <>
                    {selectedQuestion && shuffledQuestion && isStartAt && (
                        <div
                            className="examWork__container"
                            id={id}
                            style={{
                                backgroundImage:
                                    `url(${background?.data})` || null,
                                backgroundColor:
                                    background?.data || "transparent",
                            }}
                        >
                            <CountdownTimer
                                targetDate={duration}
                                onExpired={handleExpired}
                            />

                            <Container>
                                <Row>
                                    <Col xxl={24} xl={24} lg={24}>
                                        <ProgressQuestionBar
                                            counted={selectedAnswer.length}
                                            total={shuffledQuestion.length}
                                        />
                                    </Col>

                                    <Col xxl={24} xl={24} lg={24}>
                                        <Row
                                            gutter={{
                                                xxl: 60,
                                                xl: 60,
                                            }}
                                        >
                                            <Col
                                                xxl={16}
                                                xl={16}
                                                lg={24}
                                                md={24}
                                            >
                                                <div className="h-[680px] flex-1 w-full flex flex-col justify-between">
                                                    <div className="overflow-y-scroll w-full examWork__content custom-scroll flex-1">
                                                        {/* {isAllowed.value ? (
                                                    <> */}
                                                        <div
                                                            className="question__content"
                                                            dangerouslySetInnerHTML={{
                                                                __html: selectedQuestion.question_content,
                                                            }}
                                                        />

                                                        <AnswerList
                                                            question={
                                                                selectedQuestion
                                                            }
                                                            defaultSelected={
                                                                selectedAnswer.length >
                                                                    0 &&
                                                                selectedAnswer.find(
                                                                    (item) =>
                                                                        item.question_id ===
                                                                        selectedQuestion.question_id
                                                                )?.answer
                                                            }
                                                            handleChange={
                                                                handleOnChangeAnswer
                                                            }
                                                        />
                                                    </div>

                                                    <div className="flex justify-between mt-10">
                                                        <Button
                                                            config={
                                                                config_button_prev
                                                            }
                                                            handleClickButton={
                                                                prevQuestion
                                                            }
                                                            className={clsx(
                                                                "prev__btn",
                                                                selectedQuestion ===
                                                                    cloneQuestionList[0] &&
                                                                    "disabled"
                                                            )}
                                                        />

                                                        <Button
                                                            config={
                                                                config_button_next
                                                            }
                                                            handleClickButton={
                                                                nextQuestion
                                                            }
                                                            className={clsx(
                                                                "next__btn",
                                                                selectedQuestion ===
                                                                    cloneQuestionList[
                                                                        cloneQuestionList.length -
                                                                            1
                                                                    ] &&
                                                                    "disabled"
                                                            )}
                                                        />
                                                    </div>
                                                </div>
                                            </Col>

                                            <Col xxl={8} xl={8} lg={24} md={24}>
                                                <AnswerSheet
                                                    answers={selectedAnswer}
                                                    handleClick={
                                                        handleOnClickAnswerSheet
                                                    }
                                                    handleChange={
                                                        handleOnChangeAnswer
                                                    }
                                                    handleClickSubmit={() =>
                                                        setIsOpenModal(true)
                                                    }
                                                    questions={shuffledQuestion}
                                                    currentQuestion={
                                                        selectedQuestion
                                                    }
                                                    config_submit_button={
                                                        config_button_submit
                                                    }
                                                />
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                            </Container>
                            <ModalNotification
                                open={isOpenModal}
                                handleCancel={() => setIsOpenModal(false)}
                                handleAgree={handleSubmitExam}
                                dataModal={dataModal}
                                className={clsx(
                                    "flex",
                                    dataModal.cancel
                                        ? "justify-between"
                                        : "justify-end"
                                )}
                            />
                        </div>
                    )}
                </>
            ) : (
                <div
                    className="examWork__container"
                    id={id}
                    style={{
                        backgroundImage: `url(${background?.data})` || null,
                        backgroundColor: background?.data || "transparent",
                    }}
                >
                    <h2 className="text-black text-4xl">{isAllowed.message}</h2>
                </div>
            )}
        </Suspense>
    );
}
