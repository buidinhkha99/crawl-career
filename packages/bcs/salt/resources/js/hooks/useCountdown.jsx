import { useEffect, useState } from "react";

const useCountdown = (targetDate) => {
    const [countDown, setCountDown] = useState(targetDate * 60 * 1000);

    useEffect(() => {
        const interval = setInterval(() => {
            // Cancel update calculate component when out of time
            if (countDown <= 0) {
                return clearInterval(interval);
            }
            setCountDown(countDown - 1000);
        }, 1000);

        return () => {
            clearInterval(interval);
        };
    }, [countDown]);

    return getReturnValues(countDown);
};

const getReturnValues = (countDown) => {
    // calculate time left
    const days = Math.floor(countDown / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
        (countDown % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((countDown % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((countDown % (1000 * 60)) / 1000);

    return [days, hours, minutes, seconds];
};

export { useCountdown };
