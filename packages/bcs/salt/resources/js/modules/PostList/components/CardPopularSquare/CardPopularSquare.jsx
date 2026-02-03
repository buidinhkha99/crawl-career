import { Row, Col, Typography } from "antd";

const { Paragraph } = Typography;

export default function CardPopularSquare({ img, title, description }) {
    return (
        <div className="cardPopularSquare">
            <Row
                gutter={[
                    { xs: 8, md:8, lg: 4, xl: 8 },
                    { xs: 20, md:20, lg: 30, xl: 20 },
                ]}
            >
                <Col span={10}>
                    <div>
                        <img
                            src={img}
                            alt="img card round popular"
                            className="cardPopularSquare__img"
                        />
                    </div>
                </Col>
                <Col span={14}>
                    <Paragraph
                        ellipsis={{ rows: 2 }}
                        className="cardPopularSquare--title"
                    >
                        {title}
                    </Paragraph>
                    <Paragraph
                        ellipsis={{ rows: 4 }}
                        className="cardPopularSquare--description"
                    >
                        {description}
                    </Paragraph>
                </Col>
            </Row>
        </div>
    );
}
