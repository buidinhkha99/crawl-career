import { Row, Col } from "antd";
import clsx from "clsx";

export default function CardInfoIcon({ ellipsis, config }) {
    const span = config.layout.split("-").pop().split(":");
    const spanOne =
        (24 / (Number(span[0]) + Number(span[1]))) * Number(span[0]);
    const spanTwo =
        (24 / (Number(span[0]) + Number(span[1]))) * Number(span[1]);
    const renderClassName = () => {
        if (config.layout === "image-info_icon-1:1") return "cardInfoIcon";
        return "cardInfoIcon--reverse";
    };

    return (
        <Row className={clsx(renderClassName())}>
            <Col span={spanOne}>
                {config.img && (
                    <img
                        src={config.img}
                        alt="img card info icon"
                        className="cardInfoIcon__img"
                    />
                )}
            </Col>
            <Col span={spanTwo}>
                <div className="cardInfoIcon__info">
                    <h3 className="cardInfoIcon__info--title">
                        {config.title}
                    </h3>
                    {config.description_icon.length > 0 && (
                        <div className="cardInfoIcon__info--desctiption">
                            {config.description_icon.map(
                                (itemDesription, index) => {
                                    return (
                                        <div
                                            key={itemDesription + index}
                                            className="cardInfoIcon--description"
                                        >
                                            {itemDesription.icon && (
                                                <div
                                                    className="cardInfoIcon--description--icon"
                                                    dangerouslySetInnerHTML={{
                                                        __html: itemDesription.icon,
                                                    }}
                                                />
                                            )}
                                            {itemDesription.title && (
                                                <p className="cardInfoIcon--description--title">
                                                    {itemDesription.title}
                                                </p>
                                            )}
                                        </div>
                                    );
                                }
                            )}
                        </div>
                    )}
                </div>
            </Col>
        </Row>
    );
}
