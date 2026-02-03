import clsx from "clsx";
import { Checkbox } from "antd";
import { useState, Suspense, useEffect } from "react";

import { Container } from "../../Container";
import { moockApi } from "./ExamRules.contants";
import { Button, Loading } from "../../components";
import { usePage } from "@inertiajs/react";

export default function ExamRules({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const [isAllowed, setIsAllowed] = useState({
        value: true,
        message: "",
    });
    const [isDisable, setIsDisable] = useState(true);

    // Validate user has the right to do exam
    useEffect(() => {
        if (!config.description) {
            setIsAllowed({
                ...isAllowed,
                value: false,
                message: "Không có bài thi nào!",
            });
        } else if (config.is_completed) {
            setIsAllowed({
                ...isAllowed,
                value: false,
                message: "Thí sinh đã hoàn thành bài thi này!",
            });
        }
    }, [config.description, config.is_completed]);

    const onChange = (e) => {
        if (e.target.checked) {
            setIsDisable((prev) => !prev);
        }
        if (!e.target.checked) {
            setIsDisable((prev) => !prev);
        }
    };

    return (
        <Suspense fallback={<Loading />}>
            <div
                id={config.id}
                className="py-10 examRules__container"
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <div className="flex flex-col gap-[40px]">
                        {isAllowed.value ? (
                            <>
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: config.description,
                                    }}
                                    className="custom-scroll examRules__content"
                                />

                                <Checkbox
                                    onChange={onChange}
                                    className="custom-checkbox"
                                >
                                    {config.agree}
                                </Checkbox>
                            </>
                        ) : (
                            <div className="flex pt-[60px] pb-[160px] justify-center items-center">
                                <h2 className="text-black text-4xl">
                                    {isAllowed.message}
                                </h2>
                            </div>
                        )}

                        <div className="flex flex-row justify-between">
                            <Button config={config.config_button_one} />
                            <Button
                                config={config.config_button_two}
                                className={clsx(
                                    isDisable || !isAllowed.value
                                        ? "bg-[#A4A4A4] pointer-events-none"
                                        : null,
                                    "flex-row-reverse"
                                )}
                                handleClickLocalStorage={() =>
                                    isAllowed.value
                                        ? localStorage.setItem(
                                              "appDesktop",
                                              "123"
                                          )
                                        : null
                                }
                                id_policy_vimico={config.quiz_id}
                            />
                        </div>
                    </div>
                </Container>
            </div>
        </Suspense>
    );
}
