import clsx from "clsx";
import { Radio, Checkbox } from "antd";

import Button from "../../../../components/Button/Button";

export default function AnswerSheet({
    answers,
    handleClick,
    handleChange,
    questions,
    currentQuestion,
    config_submit_button,
    handleClickSubmit,
}) {
    const onChangeRadio = (e, question_id, question_type) => {
        handleChange(e.target.value, question_id, question_type);
    };

    const onChangeCheckBox = (value, question_id, question_type) => {
        handleChange(value, question_id, question_type);
    };

    const checkActive = (currentID) =>
        currentID === currentQuestion.question_id && "active";

    const checkAnswered = (currentID) =>
        answers.find((item) => item.question_id === currentID) && "answered";

    return (
        <>
            <div className="h-[680px] w-full px-5 border-l border-[#324376] flex-col flex justify-between">
                <div className="h-full custom-scroll answerSheet__content overflow-y-scroll">
                    <div className="answerSheet__list grid grid-cols-2 gap-x-[10px] max-h-[600px] w-full ">
                        {questions.map((question, index) => {
                            const findIndexQuestion = answers.findIndex(
                                (item) =>
                                    item.question_id === question.question_id
                            );

                            const defaultSelected =
                                findIndexQuestion !== -1
                                    ? answers[findIndexQuestion].answer
                                    : [];

                            return (
                                <div
                                    key={question.question_content + index}
                                    onClick={() => handleClick(question)}
                                    className={clsx(
                                        "answerSheet__item w-full cursor-pointer flex min-h-[60px] gap-[10px] p-[10px]",
                                        checkAnswered(question.question_id),
                                        checkActive(question.question_id)
                                    )}
                                >
                                    <h3 className="answerSheet__title">
                                        Câu {index + 1}
                                    </h3>
                                    {question.question_type ===
                                        "One Answer" && (
                                        <Radio.Group
                                            className={
                                                "salt__radio-list salt__radio-list--sheet flex-1"
                                            }
                                            value={defaultSelected}
                                            defaultValue={defaultSelected}
                                            onChange={() => handleClick(question)}
                                            // onChange={(e) =>
                                            //     onChangeRadio(
                                            //         e,
                                            //         question.question_id,
                                            //         question.question_type
                                            //     )
                                            // }
                                        >
                                            {question.answers.map(
                                                (answer, index) => (
                                                    <Radio
                                                        key={
                                                            answer.data + index
                                                        }
                                                        value={answer.id}
                                                        className="items-start salt__radio-item text-black"
                                                    ></Radio>
                                                )
                                            )}
                                        </Radio.Group>
                                    )}

                                    {question.question_type ===
                                        "Multiple Answer" && (
                                        <Checkbox.Group
                                            className="salt__checkbox-list salt__checkbox-list--sheet"
                                            options={question.answers.map(
                                                (item) => ({
                                                    value: item.id,
                                                })
                                            )}
                                            value={defaultSelected}
                                            onChange={() => handleClick(question)}
                                            // onChange={(e) =>
                                            //     onChangeCheckBox(
                                            //         e,
                                            //         question.question_id,
                                            //         question.question_type
                                            //     )
                                            // }
                                        />
                                    )}
                                </div>
                            );
                        })}
                    </div>
                </div>

                <Button
                    className="cursor-pointer"
                    config={config_submit_button}
                    handleClickButton={handleClickSubmit}
                />
            </div>
        </>
    );
}
