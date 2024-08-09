
import { Suspense, useState } from "react";
import { Radio } from "antd";
import clsx from "clsx";
import { router, usePage } from "@inertiajs/react";

import { Container } from "../../Container";
import { Button, Loading } from "../../components";

export default function ExamList({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const [defaulValue, setDefaultValue] = useState(config.quizzes[0]?.id)
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
            defaulValue,
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
                        {config.quizzes.length > 0 ?

                            <div className="flex flex-col gap-10">
                                <h2 className="font-bold text-[36px] text-black">{config.exam_name}</h2>
                                <p className="font-normal text-[20px] text-black">
                                    Vui lòng chọn 1 trong các đề dưới đây:
                                </p>
                                <Radio.Group
                                    className="salt__radio-list"
                                    onChange={onChangeRadio}
                                    value={defaulValue}
                                    defaultValue={defaulValue}
                                >
                                    <div className="grid grid-cols-1 gap-5">
                                        {config.quizzes.map((item, index) => (
                                            <Radio
                                                key={item + index}
                                                value={item.id}
                                                className="items-start salt__radio-item text-black"
                                            >
                                                <div className="text-base font-light">
                                                    {
                                                        <div
                                                            dangerouslySetInnerHTML={{
                                                                __html: item.name,
                                                            }}
                                                        ></div>
                                                    }
                                                </div>
                                            </Radio>
                                        ))}
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
                                id_policy_vimico={defaulValue}
                            />
                        </div>
                    </div>
                </Container>
            </div>

        </Suspense>
    );
}
