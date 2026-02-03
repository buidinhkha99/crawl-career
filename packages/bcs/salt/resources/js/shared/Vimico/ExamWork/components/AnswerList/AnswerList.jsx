import { Radio, Checkbox } from "antd";

export default function AnswerList({
    question,
    defaultSelected,
    handleChange,
}) {
    const { question_id, question_type, answers } = question;

    const onChangeRadio = (e) => {
        handleChange(e.target.value, question_id, question_type);
    };

    const onChangeCheckBox = (value) => {
        handleChange(value, question_id, question_type);
    };

    return (
        <div className="mt-5">
            {question_type === "One Answer" && (
                <Radio.Group
                    className="salt__radio-list"
                    onChange={onChangeRadio}
                    value={defaultSelected}
                    defaultValue={defaultSelected}
                >
                    <div className="grid grid-cols-1 gap-5">
                        {answers.map((item, index) => (
                            <Radio
                                key={item + index}
                                value={item.id}
                                className="items-start salt__radio-item text-black"
                            >
                                <div className="text-base font-light">
                                    {
                                        <div
                                            dangerouslySetInnerHTML={{
                                                __html: item.data,
                                            }}
                                        ></div>
                                    }
                                </div>
                            </Radio>
                        ))}
                    </div>
                </Radio.Group>
            )}

            {question_type === "Multiple Answer" && (
                <Checkbox.Group
                    value={defaultSelected}
                    className="salt__checkbox-list"
                    options={answers.map((item) => ({
                        label: item.data,
                        value: item.id,
                    }))}
                    onChange={(value) => onChangeCheckBox(value)}
                />
            )}
        </div>
    );
}
