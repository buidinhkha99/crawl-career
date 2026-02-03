import { Row, Col, Typography } from "antd";

import { AlarmIcon } from "../../../../icon";
import { Tag } from "../Tag";
import { Time } from "../../../../shared";

const { Paragraph } = Typography;

export default function CardPostSquare({
    img,
    title,
    description,
    tags,
    date,
    url,
}) {
    return (
        <div>
            <Row
                gutter={[
                    { xs: 0, sm: 0, md: 20, lg: 20, xl: 20, xxl: 20 },
                    { xs: 10, sm: 10, md: 0, lg: 0, xl: 0, xxl: 0 },
                ]}
            >
                <Col xs={24} sm={24} md={8} lg={8} xl={8} xxl={8}>
                    { img &&
                        <Link href={url}>
                            <img
                                src={img}
                                alt="img card square post"
                                className="cardPostSquare--img"
                            />
                        </Link>
                    }
                </Col>
                <Col xs={24} sm={24} md={16} lg={16} xl={16} xxl={16}>
                    <Link href={url} className="cardPostSquare--title">
                        {title}
                    </Link>
                    <Paragraph
                        ellipsis={{ rows: 4 }}
                        className="cardPostSquare--description"
                    >
                        {description}
                    </Paragraph>
                    <div className="cardPostSquare--timeTag">
                        <div className="cardPostSquare--time">
                            <AlarmIcon className="cardPostSquare--timeIcon" />
                            <Time date={date} />
                        </div>
                        <div>
                            {tags.map((tag, index) => {
                                return (
                                    <Link
                                        href={tag.url}
                                        key={tag.title + index}
                                    >
                                        <Tag
                                            color={tag.color || "#4C94AF"}
                                            title={tag.title}
                                        />
                                    </Link>
                                );
                            })}
                        </div>
                    </div>
                </Col>
            </Row>
        </div>
    );
}
