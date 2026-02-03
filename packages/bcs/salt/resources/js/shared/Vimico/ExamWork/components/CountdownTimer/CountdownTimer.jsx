import clsx from "clsx";
import React, { useState } from "react";
import { renderIcon } from "../../../../../helpers/renderIcon";
import { useCountdown } from "../../../../../hooks/useCountdown";

const CountdownTimer = React.memo(({ targetDate, onExpired }) => {
    const [expired, setExpired] = useState(false);
    const [days, hours, minutes, seconds] = useCountdown(targetDate);

    if (days + hours + minutes + seconds <= 0 && !expired) {
        onExpired();
        setExpired(true);
    }

    return (
        <ShowCounter
            days={days}
            hours={hours}
            minutes={minutes}
            seconds={seconds}
        />
    );
});

const ShowCounter = ({ days, hours, minutes, seconds }) => {
    return (
        <div className="flex justify-center h-[70px] items-center w-screen gap-2 bg-[#FAEEC7]">
            {renderIcon("alarmIcon", "#000", {
                className: "w-[42px] h-[42px]",
            })}

            <span className="text-black font-light text-4xl">
                Thời gian còn lại:
            </span>
            {days > 0 && (
                <>
                    <DateTimeDisplay
                        value={days}
                        type={"Days"}
                        isDanger={days <= 3}
                    />
                    <span className="text-5xl text-black font-black">:</span>
                </>
            )}
            {hours > 0 && (
                <>
                    <DateTimeDisplay
                        value={hours}
                        type={"Hours"}
                        isDanger={false}
                    />
                    <span className="text-5xl text-black font-black">:</span>
                </>
            )}
            <DateTimeDisplay
                value={minutes}
                type={"Mins"}
                isDanger={hours <= 0 && minutes <= 10}
            />
            <span
                className={clsx(
                    "text-5xl font-black",
                    hours <= 0 && minutes <= 10
                        ? "text-[#F76C5E]"
                        : "text-black"
                )}
            >
                :
            </span>
            <DateTimeDisplay
                value={seconds}
                type={"Seconds"}
                isDanger={hours <= 0 && minutes <= 10}
            />
        </div>
    );
};

const DateTimeDisplay = ({ value, type, isDanger }) => {
    return (
        <span className={isDanger ? "text-[#F76C5E]" : "countdown text-black"}>
            <span className="text-5xl font-black">
                {value <10 ? `0${value}` : value}
            </span>

            {/* To display text of type */}
            {/* <span>{type}</span> */}
        </span>
    );
};

export default CountdownTimer;
