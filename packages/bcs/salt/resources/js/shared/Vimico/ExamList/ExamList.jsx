
import { Suspense, useState } from "react";
import { Radio } from "antd";
import clsx from "clsx";
import { router, usePage } from "@inertiajs/react";

import { Container } from "../../Container";
import { Button, Loading } from "../../components";

export default function ExamList({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const [defaultExam, setDefaultExam] = useState(config.exams[0]?.exam_id)
    const onChangeExam = (e) => {
        setDefaultExam(e.target.value)
        setDefaultValue(config.exams.find((exam) => exam.exam_id === e.target.value).quizzes[0].id || null);
    };

    const [defaultValue, setDefaultValue] = useState(config.exams.find((exam) => exam.exam_id === defaultExam)?.quizzes[0]?.id || null)
    const onChangeRadio = (e) => {
        setDefaultValue(e.target.value)
    };

    const handleClickButton = () => {
        isAllowed.value
            ? localStorage.setItem(
                "appDesktop",
                "123"
            )
            : null
        router.post(
            "",
            defaultValue,
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                },
                onError: (err) => {
                },
            }
        );
    }

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="pb-10 backgroundImg flex flex-col gap-10 flex-1 justify-center items-center"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <div className="flex flex-col gap-10 justify-center w-[80%] mx-auto">
                        {config.exams.length > 0 ?
                            <div className="flex flex-col gap-10">
                                <p className="font-normal text-[20px] text-black">
                                    Chọn đợt thi:
                                </p>
                                <Radio.Group
                                    className="salt__radio-list"
                                    onChange={onChangeExam}
                                    value={defaultExam}
                                    defaultValue={defaultExam}
                                >
                                    <div className="grid grid-cols-1 gap-5">
                                        {config.exams.map((item, index) => (
                                            <Radio
                                                key={item + index}
                                                value={item.exam_id}
                                                className="items-start salt__radio-item text-black"
                                            >
                                                <div className="text-base font-light">
                                                    {
                                                        <div
                                                            dangerouslySetInnerHTML={{
                                                                __html: item.exam_name,
                                                            }}
                                                        ></div>
                                                    }
                                                </div>
                                            </Radio>
                                        ))}

                                        <Radio.Group
                                            className="salt__radio-list"
                                            onChange={onChangeRadio}
                                            value={defaultValue}
                                            defaultValue={defaultValue}
                                        >
                                            <p className="font-normal text-[20px] text-black mt-5 mb-10">
                                                Chọn đề thi:
                                            </p>
                                            <div className="grid grid-cols-1 gap-5">
                                                {config.exams.find((exam) => exam.exam_id === defaultExam).quizzes?.map((item, index) => (
                                                        <Radio
                                                            key={item + index}
                                                            value={item.id}
                                                            className="items-start salt__radio-item text-black"
                                                        >
                                                            <div className="text-base font-light">
                                                                <div
                                                                    dangerouslySetInnerHTML={{
                                                                        __html: item.name,
                                                                    }}
                                                                ></div>
                                                            </div>
                                                        </Radio>
                                                    ))}
                                            </div>
                                        </Radio.Group>
                                    </div>
                                </Radio.Group>

                            </div> : <div className="flex pt-[60px] pb-[160px] justify-center items-center">
                                <h2 className="text-black text-4xl">
                                    Không có đề thi nào!
                                </h2>
                            </div>
                        }

                        <div className="flex flex-row justify-between">
                            <Button config={config.config_button_one} />
                            <Button
                                config={config.config_button_two}
                                className={clsx(
                                    config.quizzes.length > 0
                                        ? null
                                        : "bg-[#A4A4A4] pointer-events-none",
                                    "flex-row-reverse"
                                )}
                                handleClickLocalStorage={handleClickButton}
                                id_policy_vimico={defaultValue}
                            />
                        </div>
                    </div>
                </Container>
            </div>

        </Suspense>
    );
}
