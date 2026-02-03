import { usePage } from "@inertiajs/react";
import clsx from "clsx";

import { moockApi } from "./Faq.constants";
import FaqCardBolded from "./FaqCardBolded";
import FaqCardLight from "./FaqCardLight";

export default function Faq({ background, indexItem }) {
    const config = usePage().props.components[indexItem];
    return (
        <div
            className="faq backgroundImg"
            style={{
                backgroundImage: `url(${background?.data})` || null,
                backgroundColor: background?.data || "transparent",
            }}
        >
            <div className="faq--title" id={config?.id}>
                {config.title}
            </div>
            {/* {config.components?.length > 0 &&
                config.components?.map((faq, index) => {
                    return config.theme === "light" ? (
                        <FaqCardLight
                            question={faq.question}
                            answer={faq.answer}
                            key={index + faq.question}
                        />
                    ) : (
                        <FaqCardBolded
                            question={faq.question}
                            answer={faq.answer}
                            key={index + faq.question}
                        />
                    );
                })} */}
            {config.components?.length > 0 &&
                config.components?.map((faq, index) => {
                    return (
                        <FaqCardLight
                            question={faq.question}
                            answer={faq.answer}
                            key={index + faq.question}
                        />
                    );
                })}
        </div>
    );
}
