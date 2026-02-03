import { toast } from "react-toastify";
import qs from "qs";

import { SuccessIcon, ErrorIcon } from "../icon";

export const message = {
    success: (msg, title) =>
        toast.success(
            <div>
                <p className="text-white font-bold text-xl font-inter">
                    {title ? title : "Complete"}
                </p>
                <p className="text-white text-sm font-medium font-inter">
                    {msg}
                </p>
            </div>,
            {
                theme: "colored",
                autoClose: 3000,
                icon: ({ theme, type }) => <SuccessIcon />,
            }
        ),
    error: (msg, title) =>
        toast.error(
            <div>
                <p className="text-white font-bold text-xl font-inter">
                    {title ? title : "Error"}
                </p>
                <p className="text-white text-sm font-medium font-inter">
                    {msg}
                </p>
            </div>,
            {
                theme: "colored",
                autoClose: 3000,
                icon: ({ theme, type }) => <ErrorIcon />,
            }
        ),
};
export const filterNonNull = (obj) =>
    Object.fromEntries(Object.entries(obj).filter(([k, v]) => v));

export const paramSerializer = (param) =>
    qs.stringify(filterNonNull({ ...param }));
