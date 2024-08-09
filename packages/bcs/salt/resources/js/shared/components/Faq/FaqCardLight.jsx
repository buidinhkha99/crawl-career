import { useState } from "react";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";
import { usePage } from "@inertiajs/react";

import { DropUpIcon, DropDownIcon } from "../../../icon";

export default function FaqCardLight({ question, answer }) {
    const [isShow, setIsShow] = useState(false);
    const handleClick = () => {
        setIsShow(!isShow);
    };

    return (
        <div className="faqCardLight">
            <div className="faqCardLight__div" onClick={handleClick}>
                <span className="faqCardLight__div--question">{question}</span>
                {isShow ? <DropDownIcon /> : <DropUpIcon />}
            </div>
            {isShow && (
                <div className="faqCardLight--markdown">
                    <ReactMarkdown
                        children={answer}
                        remarkPlugins={[remarkGfm]}
                        // TODO: check access again css
                        className="cssMarkdownFaqLight markdown"
                    />
                </div>
            )}
        </div>
    );
}
