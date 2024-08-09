import { Suspense } from "react";
import { usePage } from "@inertiajs/react";
import clsx from "clsx";

import { Container } from "../../Container";
import { moockApi } from "./InfoFooter.constants";
import { Loading } from "../../components";
import { render } from "react-dom";

export default function InfoFooter({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    // const renderPositon = () => {
    //     if (indexItem === usePage().props.components.length - 1)
    //         return "absolute w-full bottom-0 ";
    // };

    return (
        <Suspense fallback={<Loading />}>
            <div
                className={clsx("py-5 backgroundImg")}
                style={{
                    backgroundImage: `url(${config?.background?.data})` || null,
                    backgroundColor: config?.background?.data || "transparent",
                }}
                id={config?.id}
            >
                <Container>
                    <div className="flex items-center justify-center gap-2">
                        <div className="flex flex-col gap-[10px]">
                            <span className="text-[14px] text-[#324376] font-bold">
                                {config.title}
                            </span>
                            <span className="font-normal text-[14px] text-black">
                                Địa chỉ: {config.address}
                            </span>
                            <span className="font-normal text-[14px] text-black">
                                Điện thoại: {config.phone}
                            </span>
                            <span className="font-normal text-[14px] text-black">
                                Website:{" "}
                                <a
                                    href={config.website}
                                    target="_blank"
                                    className="text-[#586BA4] font-bold"
                                >
                                    {config.website}
                                </a>
                            </span>
                        </div>
                        <img
                            src={config.img}
                            alt="img logo"
                            className="max-w-[165px] max-h-[120px]"
                        />
                    </div>
                </Container>
            </div>
        </Suspense>
    );
}
