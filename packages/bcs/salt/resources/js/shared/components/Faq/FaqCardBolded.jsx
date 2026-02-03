import React, { useState } from "react";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";

import { DropUpIcon, DropDownIcon } from "../../../icon";

export default function FaqCardBolded({ question, answer }) {
    const [isShow, setIsShow] = useState(false);
    const handleClick = () => {
        setIsShow(!isShow);
    };

    return (
        <div className="faqCardBolded">
            <div className="faqCardBolded__div" onClick={handleClick}>
                <span className="faqCardBolded__div--question">{question}</span>
                {isShow ? <DropDownIcon /> : <DropUpIcon />}
            </div>
            {isShow && (
                <div className="faqCardBolded--markdown">
                    <ReactMarkdown
                        children={answer}
                        remarkPlugins={[remarkGfm]}
                        className="cssMarkdownFaqBolded markdown"
                    />
                </div>
            )}
        </div>
    );
}
