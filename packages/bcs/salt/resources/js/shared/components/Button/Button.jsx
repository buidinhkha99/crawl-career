import clsx from "clsx";
import { Link, usePage } from "@inertiajs/react";

export default function Button({ config, className, handleClickButton, handleClickLocalStorage, id_policy_vimico }) {
    const { button_text_color, button_color_background, button_icon_color } =
        usePage().props.setting;
    config.color_text = button_text_color;
    config.color_background = button_color_background;
    const renderSvgIcon = () => {
        if (config.icon.data && button_icon_color) {
            const fill = `fill="${button_icon_color}"`;
            const fisrt = config.icon.data.indexOf("fill=");
            const after = config.icon.data.indexOf(">");
            return `${config.icon.data.slice(
                0,
                fisrt
            )}${fill}${config.icon.data.slice(after, -1)}`;
        }
        if (config.icon.data && !button_icon_color) {
            return config.icon.data;
        }
    };

    const handleClick = () => {
        handleClickButton();
    };

    const handleClickLocal =() =>{
        handleClickLocalStorage()
    }

    const renderButton = () => {
        if (config.url && config.url.startsWith("https")) {
            return (
                <a
                    href={config.url}
                    target="_blank"
                    rel="nofollow"
                    style={{
                        backgroundColor: config.color_background || "#40A0A0",
                    }}
                    className={clsx("buttonCard", className)}
                >
                    {config.icon.data && (
                        <div
                            className="buttonCard--icon"
                            dangerouslySetInnerHTML={{
                                __html: renderSvgIcon(),
                            }}
                        />
                    )}
                    {config.text && (
                        <span
                            className="buttonCard--text"
                            style={{ color: config.color_text }}
                        >
                            {config.text}
                        </span>
                    )}
                </a>
            );
        }

        if (config.url && config.url.startsWith("/")) {

            if(id_policy_vimico) {
                return (
                    <Link
                        href={`${config.url}/${id_policy_vimico}`}
                        style={{
                            backgroundColor: config.color_background || "#40A0A0",
                        }}
                        className={clsx("buttonCard", className)}
                        onClick={handleClickLocal}
                    >
                        {config.icon.data && (
                            <div
                                className="buttonCard--icon"
                                dangerouslySetInnerHTML={{
                                    __html: renderSvgIcon(),
                                }}
                            />
                        )}
                        {config.text && (
                            <span
                                className="buttonCard--text"
                                style={{ color: config.color_text }}
                            >
                            {config.text}
                        </span>
                        )}
                    </Link>
                )
            }
            return (
                <Link
                    href={config.url}
                    style={{
                        backgroundColor: config.color_background || "#40A0A0",
                    }}
                    className={clsx("buttonCard", className)}
                    onClick={handleClickLocal}
                >
                    {config.icon.data && (
                        <div
                            className="buttonCard--icon"
                            dangerouslySetInnerHTML={{
                                __html: renderSvgIcon(),
                            }}
                        />
                    )}
                    {config.text && (
                        <span
                            className="buttonCard--text"
                            style={{ color: config.color_text }}
                        >
                            {config.text}
                        </span>
                    )}
                </Link>
            );
        }

        if (config.url && config.url.startsWith("#")) {
            return (
                <a
                    href={config.url}
                    rel="nofollow"
                    style={{
                        backgroundColor: config.color_background || "#40A0A0",
                    }}
                    className={clsx("buttonCard", className)}
                >
                    {config.icon.data && (
                        <div
                            className="buttonCard--icon"
                            dangerouslySetInnerHTML={{
                                __html: renderSvgIcon(),
                            }}
                        />
                    )}
                    {config.text && (
                        <span
                            className="buttonCard--text"
                            style={{ color: config.color_text }}
                        >
                            {config.text}
                        </span>
                    )}
                </a>
            );
        }

        if (config.url === null) {
            return (
                <div
                    onClick={() => handleClick()}
                    style={{
                        backgroundColor: config.color_background || "#40A0A0",
                    }}
                    className={clsx("buttonCard", className)}
                >
                    {config.icon.data && config.button_type !== "submit" && (
                        <div
                            className="buttonCard--icon"
                            dangerouslySetInnerHTML={{
                                __html: renderSvgIcon(),
                            }}
                        />
                    )}
                    {config.text && config.button_type !== "submit" && (
                        <span
                            className="buttonCard--text"
                            style={{ color: config.color_text }}
                        >
                            {config.text}
                        </span>
                    )}

                    {config.icon.data && config.button_type === "submit" && (
                        <button
                            /* Disable alert of dynamic assigning for button's type */
                            /* eslint-disable react/button-has-type */
                            type={config.button_type || "button"}
                        >
                            <div
                                className="buttonCard--icon"
                                dangerouslySetInnerHTML={{
                                    __html: renderSvgIcon(),
                                }}
                            />
                        </button>
                    )}
                    {config.text && config.button_type === "submit" && (
                        <button
                            /* Disable alert of dynamic assigning for button's type */
                            /* eslint-disable react/button-has-type */
                            type={config.button_type || "button"}
                        >
                            <span
                                className="buttonCard--text"
                                style={{ color: config.color_text }}
                            >
                                {config.text}
                            </span>
                        </button>
                    )}
                </div>
            );
        }
    };

    return (
        <>{renderButton()}</>
        // <button
        //     /* Disable alert of dynamic assigning for button's type */
        //     /* eslint-disable react/button-has-type */
        //     type={config.button_type || "button"}
        //     /* eslint-enable react/button-has-type */
        //     className={clsx("buttonCard", className)}
        //     style={{ backgroundColor: config.color_background || "#40A0A0" }}
        // >
        //     {renderButton()}
        // </button>
    );
}
