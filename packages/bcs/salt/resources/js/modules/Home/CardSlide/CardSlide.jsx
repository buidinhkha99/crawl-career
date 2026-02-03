import clsx from "clsx";
import { Row, Col, Typography } from "antd";
import { Link } from "@inertiajs/react";
import { useState, useRef, useEffect } from "react";

import { Button } from "../../../shared";
import { DropUpWhiteIcon, DropIcon } from "../../../icon";

const { Paragraph } = Typography;

export default function CardSlide({ config, ellipsis }) {
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

    const layoutKey = config.layout.split("-");

    const renderOrder = () => {
        if (layoutKey[0] !== "image") return "cardSlide--renderOrder";
    };

    const handleExpan = () => {
        setIsShow((prev) => !prev);
    };

    return (
        <div id={config.id} className="cardSlide">
            <Row
                gutter={[{ xs: 0 }, { xs: 20, sm: 0 }]}
                className={clsx(renderOrder())}
            >
                <Col xs={24} sm={8}>
                    {img && !config.config_direct && (
                        <div className="cardSlide__img">
                            <img
                                src={img}
                                className="cardSlide__img--width"
                                alt="card slide"
                            />
                        </div>
                    )}
                    {img && config.config_direct && (
                        <Link
                            href={config.config_direct.url}
                            className="cardSlide__img"
                        >
                            <img
                                src={img}
                                className="cardSlide__img--width"
                                alt="card slide"
                            />
                        </Link>
                    )}
                </Col>
                <Col xs={24} sm={16}>
                    <div className="cardSlide--info">
                        {title && !config.config_direct && (
                            <div className={clsx("cardSlide--info--title")}>
                                {title}
                            </div>
                        )}
                        {title && config.config_direct && (
                            <Link href={config.config_direct.url}>
                                <div className={clsx("cardSlide--info--title")}>
                                    {title}
                                </div>
                            </Link>
                        )}
                        {description && (
                            <>
                                <Paragraph
                                    ref={descriptionRef}
                                    className={clsx("cardSlide--info--description")}
                                >
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: description,
                                        }}
                                    />
                                </Paragraph>

                                {isExpandable && (
                                    <div className="cardSlide-custom-show-text">
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
                </Col>
            </Row>
        </div>
    );
}
