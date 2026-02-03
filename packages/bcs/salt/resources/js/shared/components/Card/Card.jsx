import clsx from "clsx";
import { Typography } from "antd";
import {useEffect, useRef, useState} from "react";

import { Button } from "../Button";
import { Link } from "@inertiajs/react";
import { DropUpWhiteIcon, DropIcon } from "../../../icon";

const { Paragraph } = Typography;

export default function Card({ config, background, ellipsis, className }) {
    const { img, title, description, description_ellipsis } = config;

    const [isExpandable, setExpandable] = useState(false);
    const [isShow, setIsShow] = useState(true);
    const descriptionRef = useRef(null);

    useEffect(() => {
        if (!descriptionRef.current) {
            return;
        }

        descriptionRef.current.setAttribute("style", `overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: ${isShow ? description_ellipsis : 'unset'}`);

        if (descriptionRef.current.clientHeight < descriptionRef.current.scrollHeight) {
            setExpandable(true);
        }
    });

    const layoutKey = config.layout ? config.layout.split("-") : [];

    const renderOrder = () => {
        if (layoutKey[0] !== "image") return "card--renderOrder";
    };

    const handleExpan = () => {
        setIsShow((prev) => !prev);
    };

    return (
        <div className={clsx(renderOrder(), "card", className)}>
            {img && layoutKey.includes("image") && !config.config_direct && (
                <div className="card__img-link">
                    <img src={img} alt="card" className="card__img" />
                </div>
            )}
            {img && layoutKey.includes("image") && config.config_direct && (
                <Link
                    href={config.config_direct.url}
                    className="card__img-link"
                >
                    <img src={img} alt="card" className="card__img" />
                </Link>
            )}
            <div className="card-bottom p-10 flex-col flex flex-grow">
                <div className="card-title">
                    {title && !config.config_direct && (
                        <h2 className={clsx("card--info--title")}>{title}</h2>
                    )}
                    {title && config.config_direct && (
                        <Link
                            href={config.config_direct.url}
                            className={clsx("card--info--title")}
                        >
                            {title}
                        </Link>
                    )}
                </div>
                <div className="card-des flex-grow">
                    {description && (
                        <>
                            <Paragraph
                                ref={descriptionRef}
                                className={clsx("card--info--description")}
                            >
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: description,
                                    }}
                                />
                            </Paragraph>

                            {isExpandable && (
                                <div className="card-custom-show-text">
                                    {isShow && <DropIcon onClick={handleExpan} />}
                                    {!isShow && <DropUpWhiteIcon onClick={handleExpan} />}
                                </div>
                            )}
                        </>
                    )}

                    {config.config_button &&
                        (config.config_button.icon?.data ||
                            config.config_button.text) && (
                            <Button
                                config={config.config_button}
                                className="cardSlide--info--button"
                            />
                        )}
                </div>
            </div>
        </div>
    );
}
