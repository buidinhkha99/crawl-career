export default function CloseSearchIcon({ ...props }) {
    return (
        <svg
            width="30"
            height="30"
            viewBox="0 0 48 48"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...props}
        >
            <path
                d="M8 40L40 8"
                stroke="#000000"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
            <path
                d="M40 40L8 8"
                stroke="#000000"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>
    );
}
