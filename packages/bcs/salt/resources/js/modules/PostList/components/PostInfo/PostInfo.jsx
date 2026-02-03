import { Tag } from "../Tag";
import { CardPopularSquare } from "../CardPopularSquare";
import { Col, Row } from "antd";
import { CardPopularRound } from "../CardPopularRound";

const mockApi={
    tags: [
        {
            title: "Lorem",
            color:"red",
            url:""
        },
        {
            title: "eros con",
            color:"green",
            url:""
        },
        {
            title: "et Sed",
            color: "orange",
            url:""
        },
    ],
}

export default function PostInfo({ topic, popular }) {
    return (
        <div>
            <Row
                gutter={[
                    { xs: 0, md: 20 },
                    { xs: 20, md: 40 },
                ]}
            >
                <Col xs={24} md={12} lg={24}>
                    <div className="postInfoTopic">
                        <div className="postInfoTopic--title">
                            {topic.title}
                        </div>
                        <div className="postInfoTopic__div--tag">
                            {/* {topic.data?.map((tagTopic, index) => { */}
                            {mockApi.tags?.map((tagTopic, index) => {
                                return (
                                    <Tag
                                        key={tagTopic.title + index}
                                        color={tagTopic.color}
                                        title={tagTopic.title}
                                        className="mb-5"
                                    />
                                );
                            })}
                        </div>
                    </div>
                </Col>
                <Col xs={24} md={12} lg={24}>
                    <div className="postInfoPopular">
                        <div className="postInfoPopular--title">
                            {popular.title}
                        </div>
                        <div className="postInfoPopular--card">
                            {popular.data.map((item, index) =>
                                popular.type_card === "img_square" ? (
                                    <CardPopularSquare
                                        key={item.title + index}
                                        img={item.img}
                                        title={item.title}
                                        description={item.description}
                                    />
                                ) : (
                                    <CardPopularRound
                                        key={item.title + index}
                                        img={item.img}
                                        title={item.title}
                                        description={item.description}
                                    />
                                )
                            )}
                        </div>
                    </div>
                </Col>
            </Row>
        </div>
    );
}
