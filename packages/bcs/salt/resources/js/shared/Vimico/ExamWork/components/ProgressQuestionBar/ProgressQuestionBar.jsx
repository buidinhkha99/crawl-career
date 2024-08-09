import React from "react";

export default function ProgressQuestionBar({ counted, total }) {
    const calculateWidthCounter = () => {
        return `${((counted / total) * 100).toFixed()}%`;
    };

    return (
        <div className="progressBar__container py-8">
            <div className="text-[#324376] font-extrabold flex items-center gap-[10px]">
                <span className="text-xl">Đã làm:</span>
                <span className="text-[32px]">
                    <span>{counted}</span>
                    <span>/</span>
                    <span>{total}</span>
                </span>
            </div>

            <div className="progressBar__content h-5 w-full bg-[#D6D9E4] rounded-full overflow-hidden">
                <div
                    className="progressBar__counted bg-[#324376] h-full rounded-r-full"
                    style={{ width: calculateWidthCounter() }}
                ></div>
            </div>
        </div>
    );
}

ProgressQuestionBar.defaultProps = {
    counted: 0,
    total: 20,
};
