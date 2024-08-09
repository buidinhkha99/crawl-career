import clsx from "clsx";
import { useState, useCallback, useEffect } from "react";

export default function Time({ date, postDetail, ...props }) {
    const [timePost, setTimePost] = useState({
        days: null,
        hours: null,
        minutes: null,
        secs: null,
    });
    const getTime = (date) => {
        const time = Date.parse(new Date().toISOString()) - Date.parse(date);
        if (time / 1000 < 60) {
            setTimePost({ ...timePost, secs: Math.floor((time / 1000) % 60) });
        }
        if (
            Math.floor(time / 1000) >= 60 &&
            Math.floor(time / 1000 / 60) < 60
        ) {
            setTimePost({
                ...timePost,
                minutes: Math.floor((time / 1000 / 60) % 60),
            });
        }
        if (
            Math.floor(time / 1000 / 60) >= 60 &&
            Math.floor(time / (1000 * 60 * 60)) < 24
        ) {
            setTimePost({
                ...timePost,
                hours: Math.floor((time / (1000 * 60 * 60)) % 24),
            });
        }
        if (Math.floor(time / (1000 * 60 * 60)) >= 24) {
            setTimePost({
                ...timePost,
                days: Math.floor(time / (1000 * 60 * 60 * 24)),
            });
        }
    };

    useEffect(() => {
        const interval = setInterval(() => getTime(date), 1000);
        return () => clearInterval(interval);
    }, [getTime]);

    const renderClass = () => {
        if (postDetail)
            return "time__detailPost--day ";
        return "time__listPost--day";
    };

    const renderTimePost = () => {
        if (timePost.secs) {
            return <span className="time">{timePost.secs} secs ago</span>;
        }
        if (timePost.minutes) {
            return <span className="time">{timePost.minutes} minutes ago</span>;
        }
        if (timePost.hours) {
            return <span className="time">{timePost.hours} hours ago</span>;
        }
        if (timePost.days) {
            return (
                <span className={clsx(renderClass())}>
                    {timePost.days} days ago
                </span>
            );
        }
    };

    return renderTimePost();
}
