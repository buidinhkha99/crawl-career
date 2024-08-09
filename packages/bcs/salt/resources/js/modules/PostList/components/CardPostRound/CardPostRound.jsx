import { Row, Col, Typography } from "antd";
import { Link } from "@inertiajs/react";

import { AlarmIcon } from "../../../../icon";
import { Tag } from "../Tag";
import { Time } from "../../../../shared";

const { Paragraph } = Typography;

export default function CardPostRound({
    img,
    title,
    description,
    tags,
    date,
    url,
}) {
    return (
        <div>
            <Row gutter={20}>
                <Col xs={6} sm={6} md={6} lg={6} xl={6} xxl={5}>
                    { img &&
                        <Link href={url}>
                            <img
                                src={img}
                                alt="img card round post"
                                className="cardPostRound__img"
                            />
                        </Link>
                    }
                </Col>
                <Col xs={18} sm={18} md={18} lg={18} xl={18} xxl={19}>
                    <Link href={url} className="cardPostRound--title">
                        {title}
                    </Link>
                    <div className="cardPostRound--time">
                        <AlarmIcon className="cardPostRound--timeIcon" />
                        <Time date={date} />
                    </div>
                    <Paragraph
                        ellipsis={{ rows: 3 }}
                        className="cardPostRound--description"
                    >
                        {description}
                    </Paragraph>
                    {tags.map((tag, index) => {
                        return (
                            <Link href={tag.url} key={tag.title + index}>
                                <Tag
                                    color={tag.color || "#4C94AF"}
                                    title={tag.title}
                                />
                            </Link>
                        );
                    })}
                </Col>
            </Row>
        </div>
    );
}
