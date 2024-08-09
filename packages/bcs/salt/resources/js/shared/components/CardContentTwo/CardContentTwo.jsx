import { Row, Col } from "antd";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";

export default function CardContentTwo({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Content Two...</h1>}>
            <div
                className="cardContentTwo backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <SubHeading>{config.title}</SubHeading>
                    <Row
                        type="flex"
                        gutter={[
                            {
                                xxl: 120,
                                xl: 90,
                                lg: 60,
                                md: 30,
                                sm: 36,
                                xs: 28,
                            },
                            { xs: 20, sm: 20, md: 40, lg: 60, xl: 40 },
                        ]}
                        className="cardContentTwo--row"
                    >
                        {config.components?.length > 0 &&
                            config.components.map((item, index) => (
                                <Col
                                    xxl={12}
                                    xl={12}
                                    sm={12}
                                    xs={12}
                                    key={`${item.title} + ${
                                        item.img
                                    } + ${index} + ${Math.random()}`}
                                    className="custom-card-col"
                                >
                                    <Card
                                        ellipsis={6}
                                        config={config.components[index]}
                                    />
                                </Col>
                            ))}
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
