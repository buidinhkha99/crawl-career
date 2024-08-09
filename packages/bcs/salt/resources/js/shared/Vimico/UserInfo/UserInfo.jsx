import { Suspense, useState } from "react";
import { Checkbox } from "antd";

import { Container } from "../../Container";
import { Button, Loading } from "../../components";
import clsx from "clsx";
import { CandidateInfo } from "../components";
import { usePage } from "@inertiajs/react";

export default function UserInfo({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    const [isDisable, setIsDisable] = useState(true);
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
                className="py-10 backgroundImg userInfo__container"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <div className="flex justify-center items-center">
                        <div className="flex flex-col gap-10">
                            <CandidateInfo
                                avatar={config?.avatar}
                                full_name={config?.full_name}
                                identification_number={
                                    config?.identification_number
                                }
                                date_of_birth={config?.date_of_birth}
                                coaching_team={config?.coaching_team}
                                work_unit={config?.work_unit}
                                working_position={config?.working_position}
                                classNameInfo="custom_grid_info"
                            />

                            <Checkbox
                                onChange={onChange}
                                className="custom-checkbox"
                            >
                                {config?.agree}
                            </Checkbox>
                            <div className="flex flex-row justify-center">
                                <Button
                                    config={config?.config_button}
                                    handleClickButton={() => {}}
                                    className={clsx(
                                        isDisable
                                            ? "bg-[#A4A4A4] pointer-events-none"
                                            : null,
                                        "flex-row-reverse"
                                    )}
                                />
                            </div>
                        </div>
                    </div>
                </Container>
            </div>
        </Suspense>
    );
}
