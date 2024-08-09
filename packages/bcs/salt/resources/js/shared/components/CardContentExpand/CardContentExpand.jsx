import clsx from "clsx";
import { Col, Row, Typography } from "antd";
import React, {useState, Suspense, useRef, useEffect} from "react";

import { Loading } from "../Loading";
import { Container } from "../../Container";
import { DropUpWhiteIcon, DropIcon } from "../../../icon";
import { Button } from "../Button";

const { Paragraph } = Typography;

export default function CardContentExpand({ config }) {
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
        if (layoutKey[0] !== "image") return "cardContentExpand--renderOrder";
    };

    const handleExpan = () => {
        setIsShow((prev) => !prev);
    };

    return (
        <Suspense fallback={<Loading />}>
            <div id={config.id} className="cardContentExpand backgroundImg">
                <Container>
                    <Row
                        gutter={{
                            xxl: 80,
                            xl: 60,
                            lg: 40,
                            md: 30,
                            sm: 20,
                            xs: 20,
                        }}
                        className={clsx(renderOrder())}
                    >
                        <Col xxl={12} xl={12} lg={12} md={12} sm={12} xs={12}>
                            {img && (
                                <div className="cardContentExpand__img">
                                    <img
                                        src={img}
                                        alt="CardContentExpand img"
                                    />
                                </div>
                            )}
                        </Col>
                        <Col xxl={12} xl={12} lg={12} md={12} sm={12} xs={12}>
                            <div className="cardContentExpand__info">
                                {title && (
                                    <div className="cardSlide--info--title">
                                        {title}
                                    </div>
                                )}
                                {description && (
                                    <>
                                        <Paragraph
                                            ref={descriptionRef}
                                            className={clsx("cardContentExpand--info--description")}
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
                </Container>
            </div>
        </Suspense>
    );
}
