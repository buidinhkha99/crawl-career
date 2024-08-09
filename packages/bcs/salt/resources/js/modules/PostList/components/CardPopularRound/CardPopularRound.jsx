import { Row, Col, Typography } from "antd";

const { Paragraph } = Typography;

export default function CardPopularRound({ img, title, description }) {
    return (
        <div className="mt-5">
            <Row
                gutter={[
                    { xs: 8, lg: 4, xl: 8 },
                    { xs: 8, lg: 30, xl: 20 },
                ]}
            >
                <Col span={7}>
                    <div>
                        <img
                            src={img}
                            alt="img card round popular"
                            className="cardPopularRound__img"
                        />
                    </div>
                </Col>
                <Col span={17}>
                    <Paragraph
                        ellipsis={{ rows: 2 }}
                        className="cardPopularRound--title"
                    >
                        {title}
                    </Paragraph>
                    <Paragraph
                        ellipsis={{ rows: 4 }}
                        className="cardPopularRound--description"
                    >
                        {description}
                    </Paragraph>
                </Col>
            </Row>
        </div>
    );
}
