import React from "react";

export default function DeleteIcon({ ...props }) {
    return (
        <svg
            width="24"
            height="28"
            viewBox="0 0 24 28"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...props}
        >
            <path
                d="M4.5 27.5C3.675 27.5 2.96875 27.2063 2.38125 26.6188C1.79375 26.0312 1.5 25.325 1.5 24.5V5H0V2H7.5V0.5H16.5V2H24V5H22.5V24.5C22.5 25.325 22.2063 26.0312 21.6188 26.6188C21.0312 27.2063 20.325 27.5 19.5 27.5H4.5ZM19.5 5H4.5V24.5H19.5V5ZM7.5 21.5H10.5V8H7.5V21.5ZM13.5 21.5H16.5V8H13.5V21.5Z"
                fill="white"
            />
        </svg>
    );
}
