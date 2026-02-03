import clsx from "clsx";

import { AnswerChooseIcon, AnswerTickIcon, AnswerIcon } from "../../../../icon";

export default function TableResult({ numberItem, examination }) {
    return (
        <>
            <tr>
                {examination
                    .slice(numberItem * 5, numberItem * 5 + 5)
                    .map((itemQuestion, index) => {
                        return (
                            <td
                                key={index + itemQuestion}
                                className="max-w-[205px] align-top"
                            >
                                {/* <div className="flex items-center gap-2"> */}
                                <div className="custom_table_grid">
                                    <div
                                        className={clsx(
                                            itemQuestion.is_correct
                                                ? "text-[#23A538]"
                                                : "text-[#E40613]"
                                        )}
                                    >
                                        Câu {itemQuestion.order}
                                    </div>
                                    {itemQuestion.is_correct && (
                                        <div className="w-[140px]">
                                            {itemQuestion.answers.map(
                                                (itemAnswer, index) => {
                                                    if (itemAnswer.is_choose) {
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
                                                (itemAnswer, index) => {
                                                    if (itemAnswer.is_choose) {
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
                                                    if (itemAnswer.is_correct) {
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
                    })}
            </tr>
        </>
    );
}
